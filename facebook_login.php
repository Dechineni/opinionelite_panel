<?php
// facebook_login.php
require __DIR__ . '/facebook_config.php';

// ✅ Determine flow: signup or signin (default signup)
$flow = isset($_GET['flow']) ? strtolower(trim($_GET['flow'])) : '';
if (!in_array($flow, ['signup', 'signin'], true)) {
    $flow = 'signup';
}
$_SESSION['facebook_flow'] = $flow;

// CSRF protection: random state stored in session
$state = bin2hex(random_bytes(16));
$_SESSION['fb_oauth_state'] = $state;

$params = [
    'client_id'     => FB_APP_ID,
    'redirect_uri'  => FB_REDIRECT_URI,
    'state'         => $state,
    'response_type' => 'code',
    'scope'         => 'email,public_profile',
];

$authUrl = 'https://www.facebook.com/' . FB_GRAPH_VERSION . '/dialog/oauth?' . http_build_query($params);

// Redirect user to Facebook
header('Location: ' . $authUrl);
exit;
