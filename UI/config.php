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

// Ensure the connection uses utf8mb4 (matches your table collations)
if (!$db->set_charset('utf8mb4')) {
    // Not fatal for the user, but log it so you can see if something is wrong
    error_log('Error loading character set utf8mb4: ' . $db->error);
}

?>
