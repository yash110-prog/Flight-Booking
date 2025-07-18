<?php
session_start();
require_once 'database.php';

// Security check - only allow this in test mode
if (!isset($_SESSION['test_mode']) || $_SESSION['test_mode'] !== true) {
    die("Access denied");
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $flight_id = isset($_POST['flight_id']) ? intval($_POST['flight_id']) : 0;
    $booking_reference = isset($_POST['booking_reference']) ? $_POST['booking_reference'] : '';
    $total_passengers = isset($_POST['total_passengers']) ? intval($_POST['total_passengers']) : 1;
    $total_price = isset($_POST['total_price']) ? floatval($_POST['total_price']) : 0;
    
    // Validation
    $errors = [];
    
    if ($user_id <= 0) {
        $errors[] = "Invalid user ID";
    }
    
    if ($flight_id <= 0) {
        $errors[] = "Invalid flight ID";
    }
    
    if (empty($booking_reference)) {
        $errors[] = "Booking reference is required";
    }
    
    if ($total_passengers <= 0) {
        $errors[] = "At least 1 passenger is required";
    }
    
    if ($total_price <= 0) {
        $errors[] = "Price must be greater than 0";
    }
    
    // If validation passes, insert the booking
    if (empty($errors)) {
        try {
            // First, verify the flight exists
            $flight_check = "SELECT id FROM flights WHERE id = ?";
            $stmt = $conn->prepare($flight_check);
            $stmt->bind_param("i", $flight_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                $errors[] = "Flight ID $flight_id not found in the database";
            } else {
                // Insert the booking
                $insert_query = "INSERT INTO bookings (user_id, flight_id, booking_reference, total_passengers, total_price) 
                                VALUES (?, ?, ?, ?, ?)";
                
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("iisid", $user_id, $flight_id, $booking_reference, $total_passengers, $total_price);
                
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Test booking created successfully!";
                } else {
                    $errors[] = "Failed to create booking: " . $stmt->error;
                }
            }
        } catch (Exception $e) {
            $errors[] = "Error: " . $e->getMessage();
        }
    }
    
    // Store errors in session if any
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
    }
    
    // Redirect back to dashboard
    header("Location: dashboard.php");
    exit();
} else {
    // Not a POST request
    header("Location: dashboard.php");
    exit();
}
?> 