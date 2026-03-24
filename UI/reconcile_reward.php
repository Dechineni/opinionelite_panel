<?php
header('Content-Type: application/json');

ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../php-error.log');
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

require_once __DIR__ . '/config.php';

function rr_env_value($key, $default = '') {
    if (function_exists('oe_env')) return oe_env($key, $default);
    $v = getenv($key);
    if ($v !== false && $v !== null && $v !== '') return $v;
    if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];
    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') return $_SERVER[$key];
    return $default;
}

function rr_json($payload, $status = 200) {
    http_response_code($status);
    echo json_encode($payload);
    exit;
}

$expectedKey = trim((string) rr_env_value('OP_PANEL_PROFILE_API_KEY', ''));
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$apiKeyHeader = $_SERVER['HTTP_X_API_KEY'] ?? '';
$token = '';
if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $m)) {
    $token = trim($m[1]);
} elseif (is_string($apiKeyHeader) && trim($apiKeyHeader) !== '') {
    $token = trim($apiKeyHeader);
}

if ($expectedKey === '' || !hash_equals($expectedKey, $token)) {
    rr_json(['error' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    rr_json(['error' => 'Method not allowed'], 405);
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
    rr_json(['error' => 'Invalid JSON body'], 400);
}

$signupId = trim((string)($data['signup_id'] ?? ''));
$pid = trim((string)($data['pid'] ?? ''));
$projectCode = trim((string)($data['project_code'] ?? ''));
$projectName = trim((string)($data['project_name'] ?? ''));
$supplierCode = trim((string)($data['supplier_code'] ?? ''));
$supplierName = trim((string)($data['supplier_name'] ?? ''));
$rewardAmount = isset($data['reward_amount']) ? (float)$data['reward_amount'] : 0.0;

if ($signupId === '' || !preg_match('/^\d+$/', $signupId)) {
    rr_json(['error' => 'Missing or invalid signup_id'], 400);
}
if ($pid === '') {
    rr_json(['error' => 'Missing pid'], 400);
}
if ($rewardAmount <= 0) {
    rr_json(['error' => 'Invalid reward_amount'], 400);
}

$conn = null;
if (isset($db) && $db instanceof mysqli) $conn = $db;
if (!$conn && isset($con) && $con instanceof mysqli) $conn = $con;
if (!$conn) {
    rr_json(['error' => 'DB connection not available'], 500);
}

$username = '';
$stmtUser = $conn->prepare('SELECT username FROM signup WHERE id = ? LIMIT 1');
if (!$stmtUser) {
    rr_json(['error' => 'DB prepare failed (signup lookup)'], 500);
}
$signupIdInt = (int)$signupId;
$stmtUser->bind_param('i', $signupIdInt);
if (!$stmtUser->execute()) {
    $stmtUser->close();
    rr_json(['error' => 'DB execute failed (signup lookup)'], 500);
}
$stmtUser->bind_result($resolvedUsername);
if ($stmtUser->fetch()) {
    $username = trim((string)$resolvedUsername);
}
$stmtUser->close();

if ($username === '') {
    rr_json(['error' => 'User not found for signup_id'], 404);
}

$profileKey = 'survey_reward:' . $pid;
$descriptionParts = array_filter([
    $projectCode !== '' ? $projectCode : null,
    $projectName !== '' ? $projectName : null,
    $supplierCode !== '' ? $supplierCode : null,
    $supplierName !== '' ? $supplierName : null,
]);
$profileLabel = implode(' | ', $descriptionParts);
if ($profileLabel === '') {
    $profileLabel = 'Survey Completion Reward';
}
$profileValue = $profileKey . ' | ' . $profileLabel;

$profileLike = $profileKey . '%';
$check = $conn->prepare('SELECT id, reward, profile FROM rewards WHERE user = ? AND profile LIKE ? LIMIT 1');
if (!$check) {
    rr_json(['error' => 'DB prepare failed (reward check)'], 500);
}
$check->bind_param('ss', $username, $profileLike);
if (!$check->execute()) {
    $check->close();
    rr_json(['error' => 'DB execute failed (reward check)'], 500);
}
$check->bind_result($existingId, $existingReward, $existingProfile);
if ($check->fetch()) {
    $check->close();
    rr_json([
        'ok' => true,
        'credited' => false,
        'duplicate' => true,
        'username' => $username,
        'profile' => (string)$existingProfile,
        'existing_reward' => (float)$existingReward,
    ]);
}
$check->close();

$insert = $conn->prepare('INSERT INTO rewards (user, reward, profile) VALUES (?, ?, ?)');
if (!$insert) {
    rr_json(['error' => 'DB prepare failed (reward insert)'], 500);
}
$rewardAmountRounded = round($rewardAmount, 2);
$insert->bind_param('sds', $username, $rewardAmountRounded, $profileValue);
if (!$insert->execute()) {
    $msg = $insert->error;
    $insert->close();
    rr_json(['error' => 'DB execute failed (reward insert)', 'detail' => $msg], 500);
}
$insertId = $insert->insert_id;
$insert->close();

$total = 0.0;
$totalStmt = $conn->prepare('SELECT COALESCE(SUM(reward), 0) AS total FROM rewards WHERE user = ?');
if ($totalStmt) {
    $totalStmt->bind_param('s', $username);
    if ($totalStmt->execute()) {
        $totalStmt->bind_result($sumReward);
        if ($totalStmt->fetch()) {
            $total = (float)$sumReward;
        }
    }
    $totalStmt->close();
}

rr_json([
    'ok' => true,
    'credited' => true,
    'duplicate' => false,
    'username' => $username,
    'profile' => $profileValue,
    'reward_amount' => $rewardAmountRounded,
    'reward_row_id' => $insertId,
    'new_total_reward' => round($total, 2),
]);
