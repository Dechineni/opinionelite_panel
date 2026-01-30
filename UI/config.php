<?php 

/**
 * UI/config.php
 *
 * Shared config for OP Panel.
 *
 * - OP Panel is deployed under public_html/ on Hostinger.
 * - We load environment variables from public_html/.env using vlucas/phpdotenv.
 * - Use oe_env('KEY') to read env values reliably.
 */

// 1) Load composer autoloader (Dotenv, PHPMailer, etc.)
$autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

// 2) Load .env from the project root (public_html/)
$root = realpath(__DIR__ . '/..');
if ($root && file_exists($root . '/.env') && class_exists(\Dotenv\Dotenv::class)) {
    try {
        // safeLoad() won't throw if the file is missing/empty and doesn't overwrite existing vars
        \Dotenv\Dotenv::createImmutable($root)->safeLoad();
    } catch (Throwable $e) {
        // ignore dotenv errors on shared hosting
    }
}

/**
 * Read an env var from $_ENV/$_SERVER/getenv with a default.
 */
function oe_env(string $key, $default = null)
{
    if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];
    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') return $_SERVER[$key];
    $v = getenv($key);
    if ($v !== false && $v !== '') return $v;
    return $default;
}

// Database configuration 
$dbHost     = "localhost"; 
$dbUsername = "u300474982_opinion"; 
$dbPassword = "^l/XV[cE6"; 
$dbName     = "u300474982_opinion"; 

// Create database connection 
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 

// Check connection 
if ($db->connect_error) { 
    die("Connection failed: " . $db->connect_error); 
} 

// Ensure the connection uses utf8mb4 (matches your table collations)
if (!$db->set_charset('utf8mb4')) {
    // Not fatal for the user, but log it so you can see if something is wrong
    error_log('Error loading character set utf8mb4: ' . $db->error);
}

?>
