<?php
session_start();
session_destroy();

// Set a logout message
$_SESSION['logout_message'] = "You have been successfully logged out.";

// Redirect to login page
header("Location: login.php");
exit();
?>
