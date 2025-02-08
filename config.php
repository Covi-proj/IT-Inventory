<?php
// Database configuration
$host = 'localhost';
$dbname = 'license_db';
$username = 'root';
$password = '';

// Create and return a PDO connection
function getDatabaseConnection()
{
    try {
        $conn = new PDO("mysql:host=$GLOBALS[host];dbname=$GLOBALS[dbname]", $GLOBALS['username'], $GLOBALS['password']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("<div class='error-message'>Connection failed: " . $e->getMessage() . "</div>");
    }
}
?>