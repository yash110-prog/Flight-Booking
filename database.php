<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = "localhost";
$db_user = "root";         // Change as per your config
$db_pass = "";             // Change as per your config
$db_name = "airline_db";   // Change as per your config

// Create connection with error handling
try {
    $conn = new mysqli($host, $db_user, $db_pass, $db_name);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to ensure proper encoding
    $conn->set_charset("utf8mb4");
    
    // Connection successful - you can uncomment this for debugging
    // echo "Database connection established successfully!";
    
} catch (Exception $e) {
    // Log the error
    error_log("Database connection error: " . $e->getMessage());
    
    // Display a user-friendly error message
    echo '<div style="color: red; padding: 10px; margin: 10px; border: 1px solid red; background-color: #ffebee;">
            <strong>Database Connection Error:</strong><br>
            We are experiencing technical difficulties. Please try again later.
            <br><small>Error details have been logged.</small>
          </div>';
    exit();
}

// Don't close the connection here - it will be closed at the end of the script that includes this file
?>
