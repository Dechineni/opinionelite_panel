<?php
// facebook-callback.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/facebook_config.php';
require __DIR__ . '/UI/config.php'; // $db

/**
 * ✅ Determine base prefix for redirects (prod/test/local)
 * Prefer what was computed in facebook_login.php, else fallback to detection.
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
 * ✅ Build redirect_uri dynamically so it matches what we sent to Facebook in facebook_login.php
 * IMPORTANT: this must match exactly for token exchange.
 */
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'] ?? 'localhost';

// This becomes:
// - prod:  https://opinionelite.com/facebook-callback.php
// - test:  https://opinionelite.com/test/facebook-callback.php
// - local: http://localhost/opinionelite_panel/facebook-callback.php
$computedRedirectUri = $scheme . '://' . $host . withBasePrefix($basePrefix, 'facebook-callback.php');

// Use config constant only if we are on prod root; otherwise force computed
$redirectUriForToken = (defined('FB_REDIRECT_URI') && FB_REDIRECT_URI) ? FB_REDIRECT_URI : $computedRedirectUri;
if ($basePrefix !== '') {
    $redirectUriForToken = $computedRedirectUri;
}

// 1) Basic error checks
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

// 2) Exchange code for access token
$tokenUrl = 'https://graph.facebook.com/' . FB_GRAPH_VERSION . '/oauth/access_token';

$tokenParams = [
    'client_id'     => FB_APP_ID,
    'redirect_uri'  => $redirectUriForToken,
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

// 3) Fetch user profile (id, name, email)
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
 * 4) Existing flow logic
 *
 * - If email exists in `signup` → auto-login → UI/index.php
 * - If not:
 *     - If flow=signup → join.php
 *     - If flow=signin → alert + back
 */
$flow = $_SESSION['facebook_flow'] ?? 'signup';

// ✅ Check if email already exists (prepared statement)
$stmt = $db->prepare("SELECT id, username FROM signup WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
$user = $res ? $res->fetch_assoc() : null;
$stmt->close();

if ($user) {
    // Existing user -> log in (no alerts)
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['username']  = $user['username'];
    $_SESSION['login_via'] = 'facebook';

    unset($_SESSION['facebook_flow']);

    $homeUrl = withBasePrefix($basePrefix, 'UI/index.php');
    echo "<script>
        localStorage.setItem('passwordVerified', 'true');
        localStorage.setItem('username', " . json_encode($user['username']) . ");
        window.location.href = '{$homeUrl}';
    </script>";
    exit;
}

// New user
if ($flow === 'signup') {
    // New Facebook user -> send to join.php with prefilled data
    $_SESSION['facebook_new_user']   = true;
    $_SESSION['facebook_email']      = $email;
    $_SESSION['facebook_first_name'] = $firstName;
    $_SESSION['facebook_last_name']  = $lastName;
    $_SESSION['facebook_id']         = $facebookId;

    unset($_SESSION['facebook_flow']);

    $joinUrl = withBasePrefix($basePrefix, 'join.php?from=facebook');
    header('Location: ' . $joinUrl);
    exit;
}

// Signin flow but user doesn't exist -> show message and go back
unset($_SESSION['facebook_flow']);
$goBack = withBasePrefix($basePrefix, 'index.php');

echo "<script>
  alert('Account doesn\\'t exist, please Sign up.');
  window.location.href='{$goBack}';
</script>";
exit;
