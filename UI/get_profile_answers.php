<?php
// UI/get_profile_answers.php
// Token-protected endpoint that returns OP Panel profile answers for a given user.

header('Content-Type: application/json');

// OPTIONAL: write PHP errors to a local log file for debugging (safe on prod)
// After things work, you can keep this or remove it.
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../php-error.log');
error_reporting(E_ALL);
// Do NOT display errors to the client in production:
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

require_once __DIR__ . '/config.php';

/**
 * Auth (server-to-server)
 * Accept either:
 *  - Authorization: Bearer <token>
 *  - X-Api-Key: <token>
 *
 * Token expected in public_html/.env:
 *  OP_PANEL_PROFILE_API_KEY=...
 */
$EXPECTED_KEY = function_exists('oe_env') ? oe_env('OP_PANEL_PROFILE_API_KEY', '') : '';

$authHeader   = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$apiKeyHeader = $_SERVER['HTTP_X_API_KEY'] ?? '';

$token = '';
if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $m)) {
  $token = trim($m[1]);
} elseif (is_string($apiKeyHeader) && trim($apiKeyHeader) !== '') {
  $token = trim($apiKeyHeader);
}

if (!$EXPECTED_KEY || $token !== $EXPECTED_KEY) {
  http_response_code(401);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

// ---- input ----
$userId = trim((string)($_GET['user_id'] ?? ''));
if ($userId === '') {
  http_response_code(400);
  echo json_encode(['error' => 'Missing user_id']);
  exit;
}

/**
 * IMPORTANT:
 * Your config might define DB connection as $db OR $con.
 * We support both to avoid redeploy mismatches.
 */
$conn = null;
if (isset($db) && $db instanceof mysqli) $conn = $db;
if (!$conn && isset($con) && $con instanceof mysqli) $conn = $con;

if (!$conn) {
  http_response_code(500);
  echo json_encode(['error' => 'DB connection not available (expected $db or $con from UI/config.php)']);
  exit;
}

$sql = "
  SELECT qa.name AS question_key, ua.answer AS answer_value
  FROM user_answers ua
  INNER JOIN question_answers qa ON qa.question_id = ua.question_id
  WHERE ua.user_id = ?
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
  http_response_code(500);
  echo json_encode(['error' => 'DB prepare failed']);
  exit;
}

$stmt->bind_param('s', $userId);

if (!$stmt->execute()) {
  http_response_code(500);
  echo json_encode(['error' => 'DB execute failed']);
  $stmt->close();
  exit;
}

/**
 * Use bind_result + fetch (works even if mysqlnd/get_result is not available)
 */
$stmt->bind_result($questionKey, $answerValue);

$answers = [];
while ($stmt->fetch()) {
  $k = trim((string)$questionKey);
  $v = (string)$answerValue;
  if ($k !== '') $answers[$k] = $v;
}

$stmt->close();

echo json_encode([
  'userId'  => $userId,
  'answers' => $answers,
]);
