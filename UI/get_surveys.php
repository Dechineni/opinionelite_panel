<?php
// UI/get_surveys.php
// Called by the OP Panel Surveys UI (browser -> OP Panel), then OP Panel calls OpinionElite UI server-to-server.

require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

// Accept username from:
// 1) Browser UI (surveys.php) sends POST form field "username"
// 2) Postman/curl can send GET query param "user_id" or "userId"
$username =
  $_POST['username']
  ?? $_GET['user_id']
  ?? $_GET['userId']
  ?? '';

$username = trim((string)$username);

if ($username === '') {
  http_response_code(400);
  echo json_encode([
    'error' => 'Missing username (send POST username=... OR GET ?user_id=... / ?userId=...)',
    'items' => [],
  ]);
  exit;
}

// These must be configured on Hostinger (.env loaded by UI/config.php)
$OE_API_BASE = trim((string)oe_env('OP_ELITE_UI_BASE', 'https://opinion-elite.com'));
$CALLER_KEY  = trim((string)oe_env('OP_PANEL_CALLER_KEY', ''));

if ($CALLER_KEY === '') {
  http_response_code(500);
  echo json_encode([
    'error' => 'Server misconfigured: OP_PANEL_CALLER_KEY not set in OP Panel (.env)',
    'items' => [],
  ]);
  exit;
}

$url = rtrim($OE_API_BASE, '/') . '/api/integrations/op-panel/surveys?userId=' . urlencode($username);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 25);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'x-op-panel-key: ' . $CALLER_KEY,
  'Accept: application/json',
]);
curl_setopt($ch, CURLOPT_USERAGENT, 'OpinionElite-OPPanel/1.0');

$response = curl_exec($ch);
$httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err      = curl_error($ch);
curl_close($ch);

if ($response === false) {
  http_response_code(502);
  echo json_encode([
    'error'  => 'Failed calling OpinionElite UI',
    'detail' => $err,
    'items'  => [],
  ]);
  exit;
}

// If OpinionElite UI returns non-200, bubble it with detail (helps debugging)
if ($httpCode && $httpCode >= 400) {
  http_response_code($httpCode);
  echo json_encode([
    'error'  => 'OpinionElite UI returned an error',
    'status' => $httpCode,
    'detail' => $response,
    'items'  => [],
  ]);
  exit;
}

// Pass through the response as-is (should already be JSON with { userId, items })
http_response_code($httpCode ?: 200);
echo $response;
