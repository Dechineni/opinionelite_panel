<?php
include_once 'config.php';

header('Content-Type: application/json');

$username = $_POST['username'] ?? '';

if (!empty($username)) {
    $stmt = $db->prepare("SELECT SUM(reward) AS total FROM rewards WHERE user = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();

    echo json_encode(['total_reward' => $total ?? 0]);
} else {
    echo json_encode(['total_reward' => 0]);
}
?>
