<?php
// UI/get_profile_answers.php
header('Content-Type: application/json');

// Log errors to file (safe); do not display in production
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../php-error.log');
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

require_once __DIR__ . '/config.php';

function read_env_value($key, $default = '') {
  if (function_exists('oe_env')) return oe_env($key, $default);

  $v = getenv($key);
  if ($v !== false && $v !== null && $v !== '') return $v;

  if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];
  if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') return $_SERVER[$key];

  return $default;
}

// ---- Auth ----
$EXPECTED_KEY = trim((string) read_env_value('OP_PANEL_PROFILE_API_KEY', ''));

$authHeader   = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$apiKeyHeader = $_SERVER['HTTP_X_API_KEY'] ?? '';

$token = '';
if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $m)) {
  $token = trim($m[1]);
} elseif (is_string($apiKeyHeader) && trim($apiKeyHeader) !== '') {
  $token = trim($apiKeyHeader);
}

if ($EXPECTED_KEY === '' || !hash_equals($EXPECTED_KEY, $token)) {
  http_response_code(401);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

// ---- input ----
$userIdParam = trim((string)($_GET['user_id'] ?? ''));
if ($userIdParam === '') {
  http_response_code(400);
  echo json_encode(['error' => 'Missing user_id']);
  exit;
}

// ---- DB conn ----
$conn = null;
if (isset($db) && $db instanceof mysqli) $conn = $db;
if (!$conn && isset($con) && $con instanceof mysqli) $conn = $con;

if (!$conn) {
  http_response_code(500);
  echo json_encode(['error' => 'DB connection not available (expected $db or $con from UI/config.php)']);
  exit;
}

// ---- Resolve identifier: if numeric, treat it as signup.id and map to username ----
$username = $userIdParam;
$signupId = null;

if (preg_match('/^\d+$/', $userIdParam)) {
  $signupId = (int)$userIdParam;
  $sqlU = "SELECT username FROM signup WHERE id = ? LIMIT 1";
  $stmtU = $conn->prepare($sqlU);
  if (!$stmtU) {
    http_response_code(500);
    echo json_encode(['error' => 'DB prepare failed (resolve user by signup id)']);
    exit;
  }
  $stmtU->bind_param('i', $signupId);
  if (!$stmtU->execute()) {
    http_response_code(500);
    echo json_encode(['error' => 'DB execute failed (resolve user by signup id)']);
    $stmtU->close();
    exit;
  }
  $stmtU->bind_result($uname);
  if ($stmtU->fetch()) {
    $username = trim((string)$uname);
  } else {
    $username = '';
  }
  $stmtU->close();

  if ($username === '') {
    http_response_code(404);
    echo json_encode(['error' => 'User not found for the given signup id']);
    exit;
  }
}

// ---- Load signup context (id/country/birthday) ----
$signup = [
  'id'       => null,
  'country'  => null,
  'birthday' => null,
];

$sql2 = "SELECT id, country, birthday FROM signup WHERE username = ? LIMIT 1";
$stmt2 = $conn->prepare($sql2);
if ($stmt2) {
  $stmt2->bind_param('s', $username);
  if ($stmt2->execute()) {
    $stmt2->bind_result($sid, $country, $birthday);
    if ($stmt2->fetch()) {
      $signup['id']       = $sid !== null ? (int)$sid : null;
      $signup['country']  = $country !== null ? (string)$country : null;
      $signup['birthday'] = $birthday !== null ? (string)$birthday : null;
    }
  }
  $stmt2->close();
}

// ---- Fetch profile answers ----
$sql = "
  SELECT
    qa.name AS question_key,
    ua.answer AS answer_value
  FROM user_answers ua
  INNER JOIN question_answers qa
    ON TRIM(qa.question_id) = TRIM(ua.question_id)
   AND TRIM(qa.profile)     = TRIM(ua.profile)
  WHERE ua.user_id = ?
  ORDER BY ua.submitted_at DESC, ua.id DESC
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
  http_response_code(500);
  echo json_encode(['error' => 'DB prepare failed']);
  exit;
}

$stmt->bind_param('s', $username);

if (!$stmt->execute()) {
  http_response_code(500);
  echo json_encode(['error' => 'DB execute failed']);
  $stmt->close();
  exit;
}

$stmt->bind_result($questionKey, $answerValue);

$answers = [];
while ($stmt->fetch()) {
  $k = trim((string)$questionKey);
  $v = (string)$answerValue;

  // newest first, keep first occurrence only
  if ($k !== '' && !array_key_exists($k, $answers)) {
    $answers[$k] = $v;
  }
}

$stmt->close();

echo json_encode([
  // keep original input echoed back (could be username or numeric)
  'userId'   => $userIdParam,
  // include resolved username so server-side consumers can use it if needed
  'username' => $username,
  'signup'   => $signup,
  'answers'  => $answers,
]);
