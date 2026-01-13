<?php
// facebook-callback.php

// Make sure session is available before using $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/facebook_config.php';
require __DIR__ . '/UI/config.php'; // for $db, etc. – same as other pages

// Determine the requested auth flow (set in facebook_login.php)
$authFlow = strtolower($_SESSION['facebook_flow'] ?? 'signin');
if (!in_array($authFlow, ['signup', 'signin'], true)) {
    $authFlow = 'signin';
}

// 1. Basic error checks
if (!isset($_GET['code'])) {
    echo 'Facebook login failed: missing "code" parameter.';
    exit;
}

if (
    !isset($_GET['state']) ||
    !isset($_SESSION['fb_oauth_state']) ||
    !hash_equals($_SESSION['fb_oauth_state'], $_GET['state'])
) {
    echo 'Facebook login failed: invalid state.';
    exit;
}

// We don’t need state anymore
unset($_SESSION['fb_oauth_state']);

// 2. Exchange code for access token
$tokenUrl = 'https://graph.facebook.com/' . FB_GRAPH_VERSION . '/oauth/access_token';

$tokenParams = [
    'client_id'     => FB_APP_ID,
    'redirect_uri'  => FB_REDIRECT_URI,
    'client_secret' => FB_APP_SECRET,
    'code'          => $_GET['code'],
];

$tokenResponse = file_get_contents($tokenUrl . '?' . http_build_query($tokenParams));
if ($tokenResponse === false) {
    echo 'Failed to contact Facebook token endpoint.';
    exit;
}

$tokenData = json_decode($tokenResponse, true);
if (!isset($tokenData['access_token'])) {
    echo 'Facebook login failed: no access token returned.<br>';
    echo htmlspecialchars($tokenResponse);
    exit;
}

$accessToken = $tokenData['access_token'];

// 3. Fetch user profile (id, name, email)
$profileUrl = 'https://graph.facebook.com/' . FB_GRAPH_VERSION . '/me';

$profileParams = [
    'fields'       => 'id,first_name,last_name,name,email',
    'access_token' => $accessToken,
];

$profileResponse = file_get_contents($profileUrl . '?' . http_build_query($profileParams));
if ($profileResponse === false) {
    echo 'Failed to fetch Facebook profile.';
    exit;
}

$profile = json_decode($profileResponse, true);

// Basic safety checks
$email      = $profile['email']      ?? null;
$firstName  = $profile['first_name'] ?? '';
$lastName   = $profile['last_name']  ?? '';
$facebookId = $profile['id']         ?? null;

if (!$email) {
    echo 'Facebook did not return an email address. Please use another login method.';
    exit;
}

/**
 * 4. Plug into your existing flow.
 *
 *   - If email exists in `signup` → log them in and redirect to UI/index.php
 *   - If not → store details in session and redirect to join.php (Facebook path)
 */

// Check if email already exists (prepared statement)
$stmt = $db->prepare('SELECT id, username FROM signup WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result ? $result->fetch_assoc() : null;
$stmt->close();

if ($user) {
    // If user clicked SIGN UP but account already exists -> show dialog and send back
    if ($authFlow === 'signup') {
        unset($_SESSION['facebook_flow']);
        echo "<script>alert('Account already exists, please Sign in.'); window.location.href='index.php';</script>";
        exit;
    }

    // SIGN IN flow -> log in
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['username']  = $user['username'];
    $_SESSION['login_via'] = 'facebook';

    unset($_SESSION['facebook_flow']);
    echo "<script>
        localStorage.setItem('passwordVerified', 'true');
        localStorage.setItem('username', " . json_encode($user['username']) . ");
        window.location.href = 'UI/index.php';
    </script>";
    exit;
} else {
    // No user found
    if ($authFlow === 'signin') {
        unset($_SESSION['facebook_flow']);
        echo "<script>alert('Account doesn\\'t exist, please Sign up.'); window.location.href='index.php';</script>";
        exit;
    }

    // New Facebook user -> send to join.php with prefilled data (similar to LinkedIn flow)
    $_SESSION['facebook_new_user']   = true;
    $_SESSION['facebook_email']      = $email;
    $_SESSION['facebook_first_name'] = $firstName;
    $_SESSION['facebook_last_name']  = $lastName;
    $_SESSION['facebook_id']         = $facebookId;

    // Explicitly tag the source so the URL matches expectations
    header('Location: join.php?from=facebook');
    exit;
}
