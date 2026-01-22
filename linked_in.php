<?php
require __DIR__ . '/vendor/autoload.php';
session_start();

use Dotenv\Dotenv;

// Load .env (safe if it doesn’t exist, e.g. on prod)
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

/**
 * ✅ Determine flow: signup or signin (default signup)
 * index.php should call:
 *  - linked_in.php?flow=signup
 *  - linked_in.php?flow=signin
 */
$flow = isset($_GET['flow']) ? strtolower(trim($_GET['flow'])) : '';
if (!in_array($flow, ['signup', 'signin'], true)) {
    $flow = 'signup';
}
$_SESSION['linkedin_flow'] = $flow;

/**
 * ✅ Build base prefix dynamically so it works for:
 *   - prod:  https://.../linkedin-callback.php
 *   - test:  https://.../test/linkedin-callback.php
 *   - local: http://localhost/opinionelite_panel/linkedin-callback.php
 *
 * Strategy:
 *  - If request URI starts with /test -> basePrefix = /test
 *  - Else if script is running in a subfolder (e.g. /opinionelite_panel) -> basePrefix = that folder
 *  - Else basePrefix = ""
 */
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? ''; // e.g. /test/linked_in.php or /opinionelite_panel/linked_in.php

$basePrefix = '';
if (preg_match('#^/test(/|$)#', $requestUri)) {
    $basePrefix = '/test';
} else {
    // Detect local subfolder (e.g. /opinionelite_panel)
    $dir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
    // dirname("/linked_in.php") => "/" (root)
    if ($dir !== '' && $dir !== '/' && $dir !== '\\') {
        $basePrefix = $dir;
    }
}

// Save for callbacks (linkedin-callback.php should use this for redirects)
$_SESSION['base_prefix'] = $basePrefix;

// LinkedIn app credentials
$client_id     = $_ENV['LINKEDIN_CLIENT_ID'] ?? getenv('LINKEDIN_CLIENT_ID');
$client_secret = $_ENV['LINKEDIN_CLIENT_SECRET'] ?? getenv('LINKEDIN_CLIENT_SECRET'); // (kept for completeness)

if (!$client_id) {
    echo "LinkedIn client ID is not configured on the server. Please contact support.";
    exit();
}

// Prefer explicit env redirect, else build dynamically
$redirect_uri = $_ENV['LINKEDIN_REDIRECT_URI'] ?? getenv('LINKEDIN_REDIRECT_URI');
if (!$redirect_uri) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $redirect_uri = $protocol . $_SERVER['HTTP_HOST'] . $basePrefix . "/linkedin-callback.php";
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
