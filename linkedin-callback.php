<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';
include('UI/config.php');

session_start();

use Dotenv\Dotenv;

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// LinkedIn OIDC credentials
$client_id     = $_ENV['LINKEDIN_CLIENT_ID']    ?? getenv('LINKEDIN_CLIENT_ID')    ?? null;
$client_secret = $_ENV['LINKEDIN_CLIENT_SECRET']?? getenv('LINKEDIN_CLIENT_SECRET')?? null;
$redirect_uri  = $_ENV['LINKEDIN_REDIRECT_URI'] ?? getenv('LINKEDIN_REDIRECT_URI')
                 ?? 'https://lightsteelblue-chimpanzee-746078.hostingersite.com/linkedin-callback.php';

if (!$client_id || !$client_secret) {
    echo "<script>alert('LinkedIn configuration missing on server.'); window.location.href='index.php';</script>";
    exit();
}

// CSRF state check
if (!isset($_GET['state']) || $_GET['state'] !== ($_SESSION['linkedin_state'] ?? '')) {
    echo "<script>alert('CSRF token mismatch.'); window.location.href='index.php';</script>";
    exit();
}

// Check for authorization code
if (!isset($_GET['code'])) {
    echo "<script>alert('Authorization code not received.'); window.location.href='index.php';</script>";
    exit();
}

$code = $_GET['code'];

// Exchange code for access token
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
    // Optional: log $response for debugging
    echo "<script>alert('Failed to get access token.'); window.location.href='index.php';</script>";
    exit();
}

$access_token = $tokens['access_token'];

// Fetch user info via OIDC userinfo endpoint
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
$username   = $email; // Use email as username

// Generate random password and hash it
$plain_password  = substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789'), 0, 10);
$hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);

// Check if user already exists
$check = $db->prepare("SELECT id FROM signup WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $check->close();
    echo "<script>alert('Email already registered!'); window.location.href='index.php';</script>";
    exit();
}
$check->close();

// Insert new user
$stmt = $db->prepare("INSERT INTO signup (firstname, lastname, email, username, password) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $first_name, $last_name, $email, $username, $hashed_password);

if ($stmt->execute()) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.office365.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'noreply@opinionelite.com';
        $mail->Password   = 'kkzzxtbjxmhpkjvm';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('noreply@opinionelite.com', 'Opinion Elite');
        $mail->addAddress($email, $first_name);
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to Opinion Elite!';
        $mail->Body = '
        <div style="max-width: 600px; margin: auto; padding: 20px; font-family: Arial, sans-serif; background-color: #f4f4f4;">
          <div style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); padding: 30px;">
            <h2 style="color: #333333; text-align: center;">Welcome to <span style="color: #0078d4;">Opinion Elite</span>!</h2>
            <p style="font-size: 16px; color: #555555;">
              Hi '.$first_name.'
            </p>
            <p style="font-size: 16px; color: #555555; line-height: 1.6;">
              Thanks for signing up with <strong>Opinion Elite</strong>!<br>
              Your Username is: '.$username.'<br>
              Your Password is: '.$plain_password.'<br>
              We\'re glad to have you on board. You can now participate in exclusive surveys and share your valuable opinions.
            </p>
            <div style="text-align: center; margin: 30px 0;">
              <a href="https://opinionelite.com" style="background-color: #0078d4; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Get Started
              </a>
            </div>
          </div>
        </div>';
        $mail->send();

        echo "<script>
          alert('Signup Successfully Completed');
          window.location.href='index.php';
        </script>";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "<script>alert('Database insert failed.'); window.location.href='index.php';</script>";
}
$stmt->close();
