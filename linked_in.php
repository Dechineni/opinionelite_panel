<?php
session_start();

// LinkedIn OIDC App Info
$client_id = '86ekr2plzfbfun';
$redirect_uri = 'https://lightsteelblue-chimpanzee-746078.hostingersite.com/linkedin-callback.php';
$scope = 'openid profile email';
$state = bin2hex(random_bytes(16));
$nonce = bin2hex(random_bytes(16)); // for ID token validation

$_SESSION['linkedin_state'] = $state;
$_SESSION['linkedin_nonce'] = $nonce;

// Build LinkedIn login URL
$params = http_build_query([
    'response_type' => 'code',
    'client_id' => $client_id,
    'redirect_uri' => $redirect_uri,
    'scope' => $scope,
    'state' => $state,
    'nonce' => $nonce,
]);

header("Location: https://www.linkedin.com/oauth/v2/authorization?$params");
exit;
?>
