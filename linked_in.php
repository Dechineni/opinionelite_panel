<?php
session_start();

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Read from env
$client_id     = $_ENV['LINKEDIN_CLIENT_ID']     ?? getenv('LINKEDIN_CLIENT_ID')     ?? null;
$redirect_uri  = $_ENV['LINKEDIN_REDIRECT_URI']  ?? getenv('LINKEDIN_REDIRECT_URI')  ?? 'https://lightsteelblue-chimpanzee-746078.hostingersite.com/linkedin-callback.php';

if (!$client_id) {
    echo "LinkedIn client ID is not configured on the server. Please contact support.";
    exit;
}

// OIDC scopes
$scope = 'openid profile email';

// CSRF + nonce
$state = bin2hex(random_bytes(16));
$nonce = bin2hex(random_bytes(16));

$_SESSION['linkedin_state'] = $state;
$_SESSION['linkedin_nonce'] = $nonce;

// Build LinkedIn login URL
$params = http_build_query([
    'response_type' => 'code',
    'client_id'     => $client_id,
    'redirect_uri'  => $redirect_uri,
    'scope'         => $scope,
    'state'         => $state,
    'nonce'         => $nonce,
]);

header("Location: https://www.linkedin.com/oauth/v2/authorization?$params");
exit;
