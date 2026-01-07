<?php 

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

// Ensure we use utf8mb4 for full Unicode support (accents, emoji, etc.)
if (!$db->set_charset('utf8mb4')) {
    // Log but don't crash the page
    error_log('Error loading character set utf8mb4 in admin/config.php: ' . $db->error);
}

?>
