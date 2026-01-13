<?php
require __DIR__ . '/vendor/autoload.php';
session_start();

use Dotenv\Dotenv;

// Load .env (safe if it doesn’t exist, e.g. on prod)
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// LinkedIn app credentials
$client_id = $_ENV['LINKEDIN_CLIENT_ID'] ?? getenv('LINKEDIN_CLIENT_ID');
$client_secret = $_ENV['LINKEDIN_CLIENT_SECRET'] ?? getenv('LINKEDIN_CLIENT_SECRET');

if (!$client_id) {
    echo "LinkedIn client ID is not configured on the server. Please contact support.";
    exit();
}

/**
 * Build redirect_uri dynamically so it works for:
 *   - prod:  https://.../linkedin-callback.php
 *   - test:  https://.../test/linkedin-callback.php
 *   - local: http://localhost/opinionelite_panel/linkedin-callback.php
 */
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'];
$dir    = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); // '' or '/test' or '/opinionelite_panel'

$redirect_uri = $scheme . '://' . $host . $dir . '/linkedin-callback.php';

// CSRF protection state
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
