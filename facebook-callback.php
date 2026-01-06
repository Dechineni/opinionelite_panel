<?php
// facebook-callback.php
require __DIR__ . '/facebook_config.php';
require __DIR__ . '/UI/config.php'; // for $db, etc. – same as other pages

// 1. Basic error checks
if (!isset($_GET['code'])) {
    echo 'Facebook login failed: missing "code" parameter.';
    exit;
}

if (!isset($_GET['state']) || !isset($_SESSION['fb_oauth_state']) ||
    !hash_equals($_SESSION['fb_oauth_state'], $_GET['state'])) {
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
    'fields'        => 'id,first_name,last_name,name,email',
    'access_token'  => $accessToken,
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
 * You can mirror what you already do for LinkedIn:
 *   - If email exists in `signup` → log them in and redirect to UI/index.php
 *   - If not → store details in session and redirect to join.php
 */

// Example: check if email already exists
$sql  = "SELECT id, username FROM signup WHERE email = '" . mysqli_real_escape_string($db, $email) . "'";
$rs   = mysqli_query($db, $sql);
$user = $rs ? mysqli_fetch_assoc($rs) : null;

if ($user) {
    // Existing user -> log in
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['username']  = $user['username'];
    $_SESSION['login_via'] = 'facebook';

    echo "<script>
        localStorage.setItem('passwordVerified', 'true');
        localStorage.setItem('username', " . json_encode($user['username']) . ");
        window.location.href = 'UI/index.php';
    </script>";
    exit;
} else {
    // New Facebook user -> send to join.php with prefilled data (similar to LinkedIn flow)
    $_SESSION['facebook_new_user']   = true;
    $_SESSION['facebook_email']      = $email;
    $_SESSION['facebook_first_name'] = $firstName;
    $_SESSION['facebook_last_name']  = $lastName;

    header('Location: join.php');
    exit;
}
