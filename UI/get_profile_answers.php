<?php
// UI/get_profile_answers.php
// Token-protected endpoint: returns OP Panel profile answers for a user.
// Used server-to-server by OpinionElite UI.

require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

function unauthorized($msg = "Unauthorized") {
  http_response_code(401);
  echo json_encode(["error" => $msg]);
  exit;
}

function getBearerToken() {
  // Works in most environments
  $hdr = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

  // Some hosts may provide it differently
  if (!$hdr && function_exists('apache_request_headers')) {
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) $hdr = $headers['Authorization'];
    if (isset($headers['authorization'])) $hdr = $headers['authorization'];
  }

  if (!$hdr) return null;

  if (stripos($hdr, 'Bearer ') === 0) {
    return trim(substr($hdr, 7));
  }
  return null;
}

$expected = oe_env('OP_PANEL_PROFILE_API_KEY', '');
if (!$expected) {
  http_response_code(500);
  echo json_encode(["error" => "Server misconfigured: OP_PANEL_PROFILE_API_KEY not set"]);
  exit;
}

$token = getBearerToken();
if (!$token || !hash_equals($expected, $token)) {
  unauthorized();
}

$userId = trim((string)($_GET['user_id'] ?? ''));
if ($userId === '') {
  http_response_code(400);
  echo json_encode(["error" => "Missing user_id"]);
  exit;
}

// NOTE: in your config.php DB connection variable is $db (mysqli)
if (!isset($db) || !$db) {
  http_response_code(500);
  echo json_encode(["error" => "DB not initialized"]);
  exit;
}

/**
 * Table assumption:
 * user_answers(user_id, question, answer)
 * If your columns are different, tell me and I’ll adjust the query.
 */
$sql = "SELECT question, answer FROM user_answers WHERE user_id = ?";

$stmt = $db->prepare($sql);
if (!$stmt) {
  http_response_code(500);
  echo json_encode(["error" => "DB prepare failed"]);
  exit;
}

$stmt->bind_param("s", $userId);
$stmt->execute();
$res = $stmt->get_result();

$answers = [];
while ($row = $res->fetch_assoc()) {
  $q = trim((string)($row['question'] ?? ''));
  $a = (string)($row['answer'] ?? '');
  if ($q !== '') $answers[$q] = $a;
}
$stmt->close();

// Force answers to be JSON object {} even when empty
echo json_encode([
  "userId" => $userId,
  "answers" => (object)$answers
]);
