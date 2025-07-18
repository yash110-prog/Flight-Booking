<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "airline_db"); // Update credentials if needed

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data safely
$firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
$lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

// Password match check
if ($password !== $confirmPassword) {
    $_SESSION['error'] = "Passwords do not match!";
    header("Location: register.php");
    exit();
}

// Password hashing for security
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$sql = "INSERT INTO users (first_name, last_name, email, phone, password) 
        VALUES ('$firstName', '$lastName', '$email', '$phone', '$hashedPassword')";

if ($conn->query($sql) === TRUE) {
    header("Location: login.php"); // Redirect to login on success
} else {
    $_SESSION['error'] = "Registration failed: " . $conn->error;
    header("Location: register.php");
}

$conn->close();
?>
