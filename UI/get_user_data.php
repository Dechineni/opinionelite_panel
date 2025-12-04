<?php
include 'config.php';

header('Content-Type: application/json');

$username = $_GET['username'] ?? '';

if (!$username) {
    echo json_encode(['success' => false, 'message' => 'Username is required.']);
    exit;
}

$username = mysqli_real_escape_string($db, $username);

$query = "SELECT firstname,lastname,email, birthday FROM signup WHERE username = '$username' LIMIT 1";
$result = mysqli_query($db, $query);

if ($result && mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
    echo json_encode(['success' => true, 'user' => $user]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
}
?>