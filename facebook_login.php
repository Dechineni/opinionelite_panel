<?php
// facebook_login.php
require __DIR__ . '/facebook_config.php';
session_start();

/**
 * ✅ Determine flow: signup or signin (default signup)
 * index.php should call:
 *  - facebook_login.php?flow=signup
 *  - facebook_login.php?flow=signin
 */
$flow = isset($_GET['flow']) ? strtolower(trim($_GET['flow'])) : '';
if (!in_array($flow, ['signup', 'signin'], true)) {
    $flow = 'signup';
}
$_SESSION['facebook_flow'] = $flow;

/**
 * ✅ Build base prefix dynamically so it works for:
 *   - prod:  https://.../facebook-callback.php
 *   - test:  https://.../test/facebook-callback.php
 *   - local: http://localhost/opinionelite_panel/facebook-callback.php
 */
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';

$basePrefix = '';
if (preg_match('#^/test(/|$)#', $requestUri)) {
    $basePrefix = '/test';
} else {
    $dir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
    if ($dir !== '' && $dir !== '/' && $dir !== '\\') {
        $basePrefix = $dir; // e.g. /opinionelite_panel
    }
}

// Save for callbacks (facebook-callback.php should use this for redirects)
$_SESSION['base_prefix'] = $basePrefix;

/**
 * ✅ Build redirect_uri dynamically unless FB_REDIRECT_URI already includes /test/local
 * (We intentionally compute it here to avoid hardcoding prod callback)
 */
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Default computed redirect uri
$computedRedirectUri = $scheme . '://' . $host . rtrim($basePrefix, '/') . '/facebook-callback.php';

// Use config constant if present, but prefer computed when running under /test or subfolder
$redirectUri = defined('FB_REDIRECT_URI') && FB_REDIRECT_URI ? FB_REDIRECT_URI : $computedRedirectUri;

// If we are on /test or local subfolder, force the computed redirect
if ($basePrefix !== '') {
    $redirectUri = $computedRedirectUri;
}

// CSRF protection: random state stored in session
$state = bin2hex(random_bytes(16));
$_SESSION['fb_oauth_state'] = $state;

$params = [
    'client_id'     => FB_APP_ID,
    'redirect_uri'  => $redirectUri,
    'state'         => $state,
    'response_type' => 'code',
    'scope'         => 'email,public_profile',
];

$authUrl = 'https://www.facebook.com/' . FB_GRAPH_VERSION . '/dialog/oauth?' . http_build_query($params);

// Redirect user to Facebook
header('Location: ' . $authUrl);
exit;
