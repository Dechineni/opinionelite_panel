<?php
// UI/get_profile_answers.php
// Token-protected endpoint that returns OP Panel profile answers for a given user.

header('Content-Type: application/json');

// Log errors to file (safe); do not display in production
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../php-error.log');
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

require_once __DIR__ . '/config.php';

/**
 * Auth (server-to-server)
 * Accept either:
 *  - Authorization: Bearer <token>
 *  - X-Api-Key: <token>
 *
 * Token expected in .env:
 *  OP_PANEL_PROFILE_API_KEY=...
 */
function read_env_value($key, $default = '') {
  // Prefer your project helper if it exists
  if (function_exists('oe_env')) return oe_env($key, $default);

  // Fall back to getenv / $_ENV / $_SERVER
  $v = getenv($key);
  if ($v !== false && $v !== null && $v !== '') return $v;

  if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];
  if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') return $_SERVER[$key];

  return $default;
}

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
$userId = trim((string)($_GET['user_id'] ?? ''));
if ($userId === '') {
  http_response_code(400);
  echo json_encode(['error' => 'Missing user_id']);
  exit;
}

/**
 * IMPORTANT:
 * config.php may define DB connection as $db OR $con.
 * Support both.
 */
$conn = null;
if (isset($db) && $db instanceof mysqli) $conn = $db;
if (!$conn && isset($con) && $con instanceof mysqli) $conn = $con;

if (!$conn) {
  http_response_code(500);
  echo json_encode(['error' => 'DB connection not available (expected $db or $con from UI/config.php)']);
  exit;
}

/**
 * ✅ Correct join:
 * question_id repeats per profile, so join must include profile.
 *
 * Also order by submitted_at desc so the latest answer wins if duplicates exist.
 */
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

$stmt->bind_param('s', $userId);

if (!$stmt->execute()) {
  http_response_code(500);
  echo json_encode(['error' => 'DB execute failed']);
  $stmt->close();
  exit;
}

// Bind results (works without mysqlnd/get_result)
$stmt->bind_result($questionKey, $answerValue);

$answers = [];
while ($stmt->fetch()) {
  $k = trim((string)$questionKey);
  $v = (string)$answerValue;

  // because we ORDER BY newest first, only set if not already set
  if ($k !== '' && !array_key_exists($k, $answers)) {
    $answers[$k] = $v;
  }
}

$stmt->close();

// ---- Signup context (country/birthday/zipcode etc.) ----
// Used by OpinionElite UI for additional eligibility checks.
$signup = [
  'country'  => null,
  'birthday' => null,
];

// NOTE: signup.username stores the user's "username" used across OP Panel.
$sql2 = "SELECT country, birthday FROM signup WHERE username = ? LIMIT 1";
$stmt2 = $conn->prepare($sql2);
if ($stmt2) {
  $stmt2->bind_param('s', $userId);
  if ($stmt2->execute()) {
    $stmt2->bind_result($country, $birthday);
    if ($stmt2->fetch()) {
      $signup['country']  = $country !== null ? (string)$country : null;
      $signup['birthday'] = $birthday !== null ? (string)$birthday : null;
    }
  }
  $stmt2->close();
}

echo json_encode([
  'userId'  => $userId,
  'signup'  => $signup,
  'answers' => $answers,
]);
