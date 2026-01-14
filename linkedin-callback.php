<?php
require __DIR__ . '/vendor/autoload.php';
include('UI/config.php');
session_start();

use Dotenv\Dotenv;

// Load .env from project root
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// LinkedIn OIDC credentials from .env / env vars
$client_id     = $_ENV['LINKEDIN_CLIENT_ID'] ?? getenv('LINKEDIN_CLIENT_ID');
$client_secret = $_ENV['LINKEDIN_CLIENT_SECRET'] ?? getenv('LINKEDIN_CLIENT_SECRET');

// Basic safety check
if (!$client_id || !$client_secret) {
    echo "LinkedIn credentials are not configured on the server. Please contact support.";
    exit();
}

/**
 * ✅ Determine base prefix for redirects (prod/test/local)
 * Prefer what was computed in linked_in.php, else fallback to detection.
 */
$basePrefix = $_SESSION['base_prefix'] ?? '';
if (!is_string($basePrefix)) {
    $basePrefix = '';
}
$basePrefix = rtrim($basePrefix, '/');

// Fallback detection if session not present
if ($basePrefix === '') {
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';

    if (preg_match('#^/test(/|$)#', $requestUri)) {
        $basePrefix = '/test';
    } else {
        $dir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        if ($dir !== '' && $dir !== '/' && $dir !== '\\') {
            $basePrefix = $dir; // e.g. /opinionelite_panel
        }
    }
}

/**
 * Helper: Build a URL with basePrefix safely
 */
function withBasePrefix(string $basePrefix, string $path): string {
    $path = '/' . ltrim($path, '/'); // ensure single leading slash
    if ($basePrefix === '') return $path;
    return $basePrefix . $path;
}

/**
 * MUST match the redirect_uri used in linked_in.php for token exchange.
 * If LINKEDIN_REDIRECT_URI is set, use it.
 * Else build dynamically with basePrefix.
 */
$redirect_uri = $_ENV['LINKEDIN_REDIRECT_URI'] ?? getenv('LINKEDIN_REDIRECT_URI');
if (!$redirect_uri) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'];
    $redirect_uri = $scheme . '://' . $host . withBasePrefix($basePrefix, 'linkedin-callback.php');
}

// CSRF state check
if (
    !isset($_GET['state']) ||
    !isset($_SESSION['linkedin_state']) ||
    $_GET['state'] !== $_SESSION['linkedin_state']
) {
    $goBack = withBasePrefix($basePrefix, 'index.php');
    echo "<script>alert('CSRF token mismatch.'); window.location.href='{$goBack}';</script>";
    exit();
}

// Check for authorization code
if (!isset($_GET['code'])) {
    $goBack = withBasePrefix($basePrefix, 'index.php');
    echo "<script>alert('Authorization code not received.'); window.location.href='{$goBack}';</script>";
    exit();
}

$code = $_GET['code'];

/**
 * Helper: auto-login and redirect to UI home
 */
function autoLoginAndRedirect(string $usernameForLogin, string $basePrefix): void {
    $usernameJs = json_encode($usernameForLogin);
    $homeUrl = withBasePrefix($basePrefix, 'UI/index.php');

    echo "<script>
        localStorage.setItem('passwordVerified', 'true');
        localStorage.setItem('username', {$usernameJs});
        window.location.href = '{$homeUrl}';
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
    $goBack = withBasePrefix($basePrefix, 'index.php');
    echo "<script>alert('Failed to get access token.'); window.location.href='{$goBack}';</script>";
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
    $goBack = withBasePrefix($basePrefix, 'index.php');
    echo "<script>alert('Failed to fetch email.'); window.location.href='{$goBack}';</script>";
    exit();
}

// Extract user info
$first_name = $userinfo['given_name']  ?? '';
$last_name  = $userinfo['family_name'] ?? '';
$email      = $userinfo['email'];
$username   = $email; // using email as username for LinkedIn signups

/**
 * 3) Check if this email already exists
 *
 * - Existing user: auto-login → home (regardless of signup/signin click)
 * - New user:
 *     - If flow=signup → store info in session → join.php
 *     - If flow=signin → show message → back to index.php
 */
$check = $db->prepare("SELECT id, username FROM signup WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

// ✅ What did the user click on the launch page?
$flow = $_SESSION['linkedin_flow'] ?? 'signup';

if ($check->num_rows > 0) {
    // Existing user -> login directly (no alerts)
    $check->bind_result($existingId, $existingUsername);
    $check->fetch();
    $check->close();

    $usernameForLogin = $existingUsername ?: $username;

    // cleanup
    unset($_SESSION['linkedin_flow']);

    autoLoginAndRedirect($usernameForLogin, $basePrefix);
}

$check->close();

// New user
if ($flow === 'signup') {
    // Signup flow -> send to join.php with prefilled data
    $_SESSION['linkedin_new_user']   = true;
    $_SESSION['linkedin_email']      = $email;
    $_SESSION['linkedin_first_name'] = $first_name;
    $_SESSION['linkedin_last_name']  = $last_name;

    // cleanup
    unset($_SESSION['linkedin_flow']);

    $joinUrl = withBasePrefix($basePrefix, 'join.php?from=linkedin');
    header("Location: {$joinUrl}");
    exit();
}

// Signin flow but user doesn't exist -> show message and go back
unset($_SESSION['linkedin_flow']);
$goBack = withBasePrefix($basePrefix, 'index.php');
echo "<script>
  alert('Account doesn\\'t exist, please Sign up.');
  window.location.href='{$goBack}';
</script>";
exit();
