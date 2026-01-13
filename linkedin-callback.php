<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';
include('UI/config.php');
session_start();

use Dotenv\Dotenv;

// Load .env from project root
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// LinkedIn OIDC credentials from .env
$client_id     = $_ENV['LINKEDIN_CLIENT_ID']    ?? null;
$client_secret = $_ENV['LINKEDIN_CLIENT_SECRET'] ?? null;

// MUST match LinkedIn app redirect URL exactly
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'];
$dir    = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); // '' or '/test' or '/opinionelite_panel'

$redirect_uri = $scheme . '://' . $host . $dir . '/linkedin-callback.php';


// Basic safety check
if (!$client_id || !$client_secret) {
    echo "LinkedIn credentials are not configured on the server. Please contact support.";
    exit();
}

// CSRF state check
if (!isset($_GET['state']) || !isset($_SESSION['linkedin_state']) || $_GET['state'] !== $_SESSION['linkedin_state']) {
    echo "<script>alert('CSRF token mismatch.'); window.location.href='index.php';</script>";
    exit();
}

// Check for authorization code
if (!isset($_GET['code'])) {
    echo "<script>alert('Authorization code not received.'); window.location.href='index.php';</script>";
    exit();
}

$code = $_GET['code'];

/**
 * Helper: auto-login and redirect to UI home
 */
function autoLoginAndRedirect(string $usernameForLogin): void {
    $usernameJs = json_encode($usernameForLogin);
    echo "<script>
        localStorage.setItem('passwordVerified', 'true');
        localStorage.setItem('username', {$usernameJs});
        window.location.href = 'UI/index.php';
    </script>";
    exit();
}

/**
 * 1) Exchange code for access token
 */
$token_url = 'https://www.linkedin.com/oauth/v2/accessToken';
$data = http_build_query([
    'grant_type'    => 'authorization_code',
    'code'          => $code,
    'redirect_uri'  => $redirect_uri,
    'client_id'     => $client_id,
    'client_secret' => $client_secret,
]);

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$tokens = json_decode($response, true);

if (!isset($tokens['access_token'])) {
    echo "<script>alert('Failed to get access token.'); window.location.href='index.php';</script>";
    exit();
}

$access_token = $tokens['access_token'];

/**
 * 2) Fetch user info from LinkedIn (OIDC userinfo endpoint)
 */
$ch = curl_init('https://api.linkedin.com/v2/userinfo');
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $access_token"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$userinfo_response = curl_exec($ch);
curl_close($ch);

$userinfo = json_decode($userinfo_response, true);

if (!isset($userinfo['email'])) {
    echo "<script>alert('Failed to fetch email.'); window.location.href='index.php';</script>";
    exit();
}

// Extract user info
$first_name = $userinfo['given_name']  ?? '';
$last_name  = $userinfo['family_name'] ?? '';
$email      = $userinfo['email'];
$username   = $email; // using email as username for LinkedIn signups

/**
 * 3) Check if user already exists
 *    - Existing user: auto-login → home
 *    - New user: store info in session → Join the Elite form
 */
$check = $db->prepare("SELECT id, username FROM signup WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    // Existing user -> login directly
    $check->bind_result($existingId, $existingUsername);
    $check->fetch();
    $check->close();

    $usernameForLogin = $existingUsername ?: $username;
    autoLoginAndRedirect($usernameForLogin);
}

$check->close();

// New user: save LinkedIn info in session and send to Join the Elite page
$_SESSION['linkedin_new_user']   = true;
$_SESSION['linkedin_email']      = $email;
$_SESSION['linkedin_first_name'] = $first_name;
$_SESSION['linkedin_last_name']  = $last_name;

// Redirect to Join the Elite (index.php)
header("Location: join.php?from=linkedin");
exit();
?>