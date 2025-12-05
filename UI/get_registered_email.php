<?php
// get_registered_email.php
include('config.php');

header('Content-Type: application/json');

$username = isset($_POST['username']) ? trim($_POST['username']) : '';

if ($username === '') {
    echo json_encode(['success' => false, 'email' => null, 'message' => 'Missing username']);
    exit;
}

// signup table columns: id, firstname, lastname, email, ..., username, ...
if ($stmt = $db->prepare("SELECT email, firstname, lastname FROM signup WHERE username = ? LIMIT 1")) {
    $stmt->bind_param('s', $username);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            echo json_encode([
                'success' => true,
                'email'   => $row['email'],
                'name'    => trim(($row['firstname'] ?? '') . ' ' . ($row['lastname'] ?? ''))
            ]);
        } else {
            echo json_encode(['success' => false, 'email' => null, 'message' => 'User not found']);
        }
    } else {
        echo json_encode(['success' => false, 'email' => null, 'message' => 'Query failed']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'email' => null, 'message' => 'Prepare failed']);
}