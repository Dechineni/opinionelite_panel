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
$redirect_uri  = 'https://lightsteelblue-chimpanzee-746078.hostingersite.com/linkedin-callback.php';

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

// Generate a random password (for DB only – not emailed)
$plain_password  = substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789'), 0, 10);
$hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);

// Mark this as a LinkedIn user
$user_type = 'linkedin';

/**
 * 3) Check if user already exists
 *    - If yes: auto-login (no email)
 *    - If no:  insert, send confirmation email (no password), auto-login
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

// New user -> insert into DB with user_type = 'linkedin'
$stmt = $db->prepare("
    INSERT INTO signup (firstname, lastname, email, username, password, user_type)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("ssssss", $first_name, $last_name, $email, $username, $hashed_password, $user_type);

if ($stmt->execute()) {

    // Send simple confirmation email (no username/password)
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.office365.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'noreply@opinionelite.com';  // Microsoft 365 email
        $mail->Password   = 'kkzzxtbjxmhpkjvm';          // App password / real password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('noreply@opinionelite.com', 'Opinion Elite');
        $mail->addAddress($email, $first_name);
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to Opinion Elite!';

        $safeFirst = htmlspecialchars($first_name ?: 'there');

        $mail->Body = '
        <div style="max-width: 600px; margin: auto; padding: 20px; font-family: Arial, sans-serif; background-color: #f4f4f4;">
          <div style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); padding: 30px;">
            <h2 style="color: #333333; text-align: center;">Welcome to <span style="color: #f9a825;">Opinion Elite</span>!</h2>
            <p style="font-size: 16px; color: #555555;">
              Hi ' . $safeFirst . ',
            </p>
            <p style="font-size: 16px; color: #555555; line-height: 1.6;">
              Thanks for joining <strong>Opinion Elite</strong> with your LinkedIn account.
              Your profile has been created and you can now access your dashboard and start
              participating in exclusive surveys.
            </p>
            <p style="font-size: 14px; color: #777777; line-height: 1.6%;">
              Next time, simply click <strong>Sign in with LinkedIn</strong> on our website
              to access your account quickly and securely.
            </p>
            <div style="text-align: center; margin: 30px 0;">
              <a href="https://lightsteelblue-chimpanzee-746078.hostingersite.com/"
                 style="background-color: #f9a825; color: #000; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">
                Go to Opinion Elite
              </a>
            </div>
          </div>
        </div>';

        $mail->send();
    } catch (Exception $e) {
        // If email fails we still log the user in
        // error_log("LinkedIn signup email failed: " . $mail->ErrorInfo);
    }

    $stmt->close();

    // Auto-login new user
    autoLoginAndRedirect($username);

} else {
    $stmt->close();
    echo "<script>alert('Database insert failed.'); window.location.href='index.php';</script>";
    exit();
}
?>
