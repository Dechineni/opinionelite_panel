<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

session_start();                     // ✅ start session

include 'admin/config.php';          // Ensure $db is defined here

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Validate input
if (empty($username) || empty($password)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please enter both username and password.'
    ]);
    exit;
}

// Use prepared statements for security
$query = "SELECT * FROM signup WHERE username = ? LIMIT 1";
$stmt  = $db->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check result
if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {

        // ✅ put login info into PHP session
        $_SESSION['username']  = $user['username'];
        $_SESSION['user_type'] = $user['user_type'] ?? 'direct';

        echo json_encode([
            'success'  => true,
            'username' => $user['username'],
            'userType' => $_SESSION['user_type'],
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid password.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Username not found.'
    ]);
}
?>
