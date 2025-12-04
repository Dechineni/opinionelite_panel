

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
 
?>