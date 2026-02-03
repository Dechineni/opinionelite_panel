<?php
// UI/get_surveys.php
// Browser (OP Panel UI) calls this endpoint.
// This endpoint calls OpinionElite UI (server-to-server) to get eligible surveys.

require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

// Accept BOTH styles:
// - GET  ?user_id=ravulasbalu6   (your Postman style)
// - POST username=ravulasbalu6   (your existing surveys.php JS style)
$user =
  $_GET['user_id'] ?? $_GET['userId'] ?? $_GET['username'] ??
  $_POST['username'] ?? $_POST['user_id'] ?? '';

$user = trim((string)$user);

if ($user === '') {
  echo json_encode(['userId' => '', 'items' => []]);
  exit;
}

// ENV (loaded by UI/config.php via oe_env)
$OE_API_BASE =
  oe_env('OP_ELITE_UI_BASE', '') ?:
  oe_env('OE_API_BASE', '') ?:
  'https://opinion-elite.com';

$CALLER_KEY = oe_env('OP_PANEL_CALLER_KEY', '');

if (!$CALLER_KEY) {
  http_response_code(500);
  echo json_encode(['error' => 'Server misconfigured: OP_PANEL_CALLER_KEY not set']);
  exit;
}

$url = rtrim($OE_API_BASE, '/') . '/api/integrations/op-panel/surveys?userId=' . urlencode($user);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 25);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'x-op-panel-key: ' . $CALLER_KEY,
  'Accept: application/json',
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);
curl_close($ch);

if ($response === false) {
  http_response_code(502);
  echo json_encode(['error' => 'Failed calling OpinionElite UI', 'detail' => $err]);
  exit;
}

// If OpinionElite UI returns non-JSON, pass it back with status
http_response_code($httpCode ?: 200);
echo $response;
