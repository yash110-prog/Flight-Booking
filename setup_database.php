<?php
// Simple script to initialize database tables

// Include database connection
require_once 'database.php';

// Function to run SQL file
function runSQLFile($conn, $filename) {
    if (file_exists($filename)) {
        $sql = file_get_contents($filename);
        
        // Split SQL file into multiple statements
        $statements = explode(';', $sql);
        
        $success = true;
        $error_messages = [];
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                if (!$conn->query($statement)) {
                    $success = false;
                    $error_messages[] = "Error executing statement: " . $conn->error;
                }
            }
        }
        
        return [
            'success' => $success,
            'errors' => $error_messages
        ];
    } else {
        return [
            'success' => false,
            'errors' => ["File $filename not found"]
        ];
    }
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Run the SQL files in the correct order
$results = [];

// First run flights table setup
$results['flights'] = runSQLFile($conn, 'setup_flights.sql');

// Then run bookings setup
$results['bookings'] = runSQLFile($conn, 'setup_bookings.sql');

// Output results
echo "<h1>Database Setup Results</h1>";

foreach ($results as $file => $result) {
    echo "<h2>$file</h2>";
    if ($result['success']) {
        echo "<p style='color:green'>Success!</p>";
    } else {
        echo "<p style='color:red'>Failed</p>";
        echo "<ul>";
        foreach ($result['errors'] as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }
}

echo "<p><a href='dashboard.php'>Go to Dashboard</a></p>";

$conn->close();
?> 