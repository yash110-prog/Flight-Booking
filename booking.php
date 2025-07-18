<?php
require_once 'database.php';

// Get flight and passenger details
$flight_id = $_GET['flight_id'] ?? null;
$passengers = $_GET['passengers'] ?? 1;

if (!$flight_id) {
    header('Location: search_flights.php');
    exit;
}

// Get flight details
$query = "
    SELECT f.*, a.name as airline_name 
    FROM flights f 
    JOIN airlines a ON f.airline_id = a.id 
    WHERE f.id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $flight_id);
$stmt->execute();
$result = $stmt->get_result();
$flight = $result->fetch_assoc();

if (!$flight) {
    header('Location: search_flights.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->begin_transaction();
    try {
        // Insert booking
        $insertBooking = $conn->prepare("
            INSERT INTO bookings (flight_id, booking_date, total_passengers, total_price) 
            VALUES (?, NOW(), ?, ?)
        ");
        $total_price = $flight['price'] * $passengers;
        $insertBooking->bind_param("iid", $flight_id, $passengers, $total_price);
        $insertBooking->execute();
        $booking_id = $conn->insert_id;

        // Insert passengers
        $insertPassenger = $conn->prepare("
            INSERT INTO passengers (booking_id, first_name, last_name, email, phone) 
            VALUES (?, ?, ?, ?, ?)
        ");
        for ($i = 1; $i <= $passengers; $i++) {
            $first_name = $_POST["first_name_$i"];
            $last_name = $_POST["last_name_$i"];
            $email = $_POST["email_$i"];
            $phone = $_POST["phone_$i"];
            $insertPassenger->bind_param("issss", $booking_id, $first_name, $last_name, $email, $phone);
            $insertPassenger->execute();
        }

        // Update available seats
        $updateSeats = $conn->prepare("
            UPDATE flights 
            SET available_seats = available_seats - ? 
            WHERE id = ?
        ");
        $updateSeats->bind_param("ii", $passengers, $flight_id);
        $updateSeats->execute();

        $conn->commit();
        header("Location: booking_confirmation.php?booking_id=$booking_id");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        $error = "An error occurred while processing your booking. Please try again.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Flight - Airline Reservation System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1a73e8;
            --secondary-color: #34a853;
            --accent-color: #fbbc05;
            --dark-color: #202124;
            --light-color: #f8f9fa;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--light-color) !important;
        }

        .nav-link {
            color: var(--light-color) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            opacity: 0.9;
        }

        .nav-link:hover {
            color: var(--light-color) !important;
            opacity: 1;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .nav-link.active {
            color: var(--light-color) !important;
            font-weight: 600;
            background-color: var(--accent-color);
            border-radius: 4px;
        }

        .navbar-toggler {
            border-color: var(--light-color);
            opacity: 0.8;
        }

        .navbar-toggler:hover {
            opacity: 1;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(248, 249, 250, 0.9)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #1557b0;
            border-color: #1557b0;
        }

        .card {
            border: none;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .card-header {
            background-color: var(--light-color);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">SkyWings</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search_flights.php">Search Flights</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="my_bookings.php">My Bookings</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Flight Details</h2>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong>Airline:</strong> <?php echo htmlspecialchars($flight['airline_name']); ?></p>
                                <p><strong>Flight Number:</strong> <?php echo htmlspecialchars($flight['flight_number']); ?></p>
                                <p><strong>From:</strong> <?php echo htmlspecialchars($flight['departure_city']); ?></p>
                                <p><strong>To:</strong> <?php echo htmlspecialchars($flight['arrival_city']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Departure Time:</strong> <?php echo date('Y-m-d H:i', strtotime($flight['departure_time'])); ?></p>
                                <p><strong>Arrival Time:</strong> <?php echo date('Y-m-d H:i', strtotime($flight['arrival_time'])); ?></p>
                                <p><strong>Price per Passenger:</strong> ₹<?php echo number_format($flight['price'], 2); ?></p>
                                <p><strong>Total Price:</strong> ₹<?php echo number_format($flight['price'] * $passengers, 2); ?></p>
                            </div>
                        </div>

                        <h3 class="card-title">Passenger Details</h3>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <?php for ($i = 1; $i <= $passengers; $i++): ?>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h4>Passenger <?php echo $i; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="first_name_<?php echo $i; ?>" class="form-label">First Name</label>
                                                    <input type="text" class="form-control" id="first_name_<?php echo $i; ?>" 
                                                           name="first_name_<?php echo $i; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="last_name_<?php echo $i; ?>" class="form-label">Last Name</label>
                                                    <input type="text" class="form-control" id="last_name_<?php echo $i; ?>" 
                                                           name="last_name_<?php echo $i; ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="email_<?php echo $i; ?>" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email_<?php echo $i; ?>" 
                                                           name="email_<?php echo $i; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="phone_<?php echo $i; ?>" class="form-label">Phone</label>
                                                    <input type="tel" class="form-control" id="phone_<?php echo $i; ?>" 
                                                           name="phone_<?php echo $i; ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>

                            <button type="submit" class="btn btn-primary">Confirm Booking</button>
                            <a href="search_results.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html> 