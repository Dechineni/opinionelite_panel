<?php
require __DIR__ . '/vendor/autoload.php';
session_start();

use Dotenv\Dotenv;

// Load .env (safe if it doesn’t exist, e.g. on prod)
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// ✅ Determine flow: signup or signin (default signup)
$flow = isset($_GET['flow']) ? strtolower(trim($_GET['flow'])) : '';
if (!in_array($flow, ['signup', 'signin'], true)) {
    $flow = 'signup';
}
$_SESSION['linkedin_flow'] = $flow;

// LinkedIn app credentials
$client_id     = $_ENV['LINKEDIN_CLIENT_ID'] ?? getenv('LINKEDIN_CLIENT_ID');
$client_secret = $_ENV['LINKEDIN_CLIENT_SECRET'] ?? getenv('LINKEDIN_CLIENT_SECRET');

if (!$client_id) {
    echo "LinkedIn client ID is not configured on the server. Please contact support.";
    exit();
}

$redirect_uri = $_ENV['LINKEDIN_REDIRECT_URI'] ?? getenv('LINKEDIN_REDIRECT_URI');
if (!$redirect_uri) {
    // fallback
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $redirect_uri = $protocol . $_SERVER['HTTP_HOST'] . "/linkedin-callback.php";
}

// CSRF state
$state = bin2hex(random_bytes(16));
$_SESSION['linkedin_state'] = $state;

// Build LinkedIn authorization URL
$params = [
    'response_type' => 'code',
    'client_id'     => $client_id,
    'redirect_uri'  => $redirect_uri,
    'state'         => $state,
    'scope'         => 'openid profile email',
];

$auth_url = 'https://www.linkedin.com/oauth/v2/authorization?' . http_build_query($params);

// Send user to LinkedIn
header("Location: $auth_url");
exit();
