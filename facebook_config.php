<?php
// facebook_config.php

// Make sure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Minimal .env loader (similar to tremendous_helper.php, but self-contained)
 */
function fb_load_env(string $envPath): void
{
    if (!file_exists($envPath)) {
        return;
    }

    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        // Skip comments
        if ($line === '' || $line[0] === '#') {
            continue;
        }

        if (strpos($line, '=') === false) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name  = trim($name);
        $value = trim($value);

        // Remove wrapping quotes if present
        $value = trim($value, " \t\n\r\0\x0B\"'");

        $_ENV[$name] = $value;
        putenv("$name=$value");
    }
}

// Load .env (same directory as this file)
fb_load_env(__DIR__ . '/.env');

// Read from environment
$fbAppId     = $_ENV['FACEBOOK_APP_ID']     ?? getenv('FACEBOOK_APP_ID');
$fbAppSecret = $_ENV['FACEBOOK_APP_SECRET'] ?? getenv('FACEBOOK_APP_SECRET');

if (empty($fbAppId) || empty($fbAppSecret)) {
    throw new Exception('FACEBOOK_APP_ID or FACEBOOK_APP_SECRET is not set in .env');
}

// Define constants used by the other FB files
define('FB_APP_ID', $fbAppId);
define('FB_APP_SECRET', $fbAppSecret);

// Build redirect URI dynamically so it works for:
//   - prod:  https://.../facebook-callback.php
//   - test:  https://.../test/facebook-callback.php
//   - local: http://localhost/opinionelite_panel/facebook-callback.php
// NOTE: Make sure you add BOTH prod and test redirect URIs in Facebook App settings.
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
$dir    = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\'); // '' or '/test' or '/opinionelite_panel'
define('FB_REDIRECT_URI', $scheme . '://' . $host . $dir . '/facebook-callback.php');

// Use the graph version your app is on (check in the Facebook app dashboard)
define('FB_GRAPH_VERSION', 'v21.0');
