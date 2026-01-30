<?php
// UI/get_profile_answers.php
// Public endpoint (token protected) that returns OP Panel profile answers for a given user.

include_once __DIR__ . '/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

/**
 * Auth (server-to-server)
 * - Expect a shared secret in either:
 *   - Authorization: Bearer <token>
 *   - X-Api-Key: <token>
 *
 * Configure the expected token in public_html/.env as:
 *   OP_PANEL_PROFILE_API_KEY=...
 */
$EXPECTED_KEY = oe_env('OP_PANEL_PROFILE_API_KEY', '');

$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
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
 * OP Panel DB tables:
 * - user_answers(user_id, question_id, answer)
 * - question_answers(id, name, question)
 *
 * We return answers keyed by question_answers.name
 * (e.g. AGE, SALARY, STANDARD_TRAVEL_COUNTRIES).
 */
$sql = "
  SELECT qa.name AS question_key, ua.answer AS answer_value
  FROM user_answers ua
  INNER JOIN question_answers qa ON qa.id = ua.question_id
  WHERE ua.user_id = ?
";

$stmt = $con->prepare($sql);
if (!$stmt) {
  http_response_code(500);
  echo json_encode(['error' => 'DB prepare failed']);
  exit;
}

$stmt->bind_param('s', $userId);
$stmt->execute();
$res = $stmt->get_result();

$answers = [];
while ($row = $res->fetch_assoc()) {
  $k = (string)($row['question_key'] ?? '');
  $v = (string)($row['answer_value'] ?? '');
  if ($k !== '') {
    $answers[$k] = $v;
  }
}

$stmt->close();

echo json_encode([
  'userId' => $userId,
  'answers' => $answers,
]);
