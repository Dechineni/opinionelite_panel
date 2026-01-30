<?php
// UI/get_surveys.php
// Called by the OP Panel Surveys UI (browser -> OP Panel), then OP Panel calls OpinionElite UI server-to-server.

include_once __DIR__ . '/config.php';

header('Content-Type: application/json');

$username = $_POST['username'] ?? '';
$username = trim((string)$username);

if ($username === '') {
  echo json_encode(['items' => []]);
  exit;
}

// These must be configured on Hostinger (.env loaded by UI/config.php)
$OE_API_BASE = oe_env('OE_API_BASE', 'https://opinion-elite.com');
$CALLER_KEY  = oe_env('OP_PANEL_CALLER_KEY', '');

if (!$CALLER_KEY) {
  http_response_code(500);
  echo json_encode(['error' => 'Server misconfigured: OP_PANEL_CALLER_KEY not set']);
  exit;
}

$url = rtrim($OE_API_BASE, '/') . '/api/integrations/op-panel/surveys?userId=' . urlencode($username);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'x-op-panel-key: ' . $CALLER_KEY,
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

http_response_code($httpCode ?: 200);
echo $response;
