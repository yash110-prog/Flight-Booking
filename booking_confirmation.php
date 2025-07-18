<?php
require_once 'database.php';

if (!isset($_GET['booking_id'])) {
    die("Booking ID is required.");
}

$booking_id = (int) $_GET['booking_id'];

// Fetch booking and flight info
$sql = "
    SELECT b.*, f.*, a.name AS airline_name
    FROM bookings b
    LEFT JOIN flights f ON b.flight_id = f.id
    LEFT JOIN airlines a ON f.airline_id = a.id
    WHERE b.id = $booking_id
";

$result = $conn->query($sql);
if (!$result || $result->num_rows === 0) {
    die("Booking not found.");
}
$booking = $result->fetch_assoc();

// Fetch passenger info
$passengers = [];
$passenger_sql = "SELECT * FROM passengers WHERE booking_id = $booking_id";
$passenger_result = $conn->query($passenger_sql);
if ($passenger_result && $passenger_result->num_rows > 0) {
    $passengers = $passenger_result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1a73e8;
            --secondary-color: #34a853;
            --accent-color: #fbbc05;
            --dark-color: #202124;
            --light-color: #f8f9fa;
        }
        body {
            background-color: var(--light-color);
            font-family: 'Segoe UI', sans-serif;
            color: var(--dark-color);
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }
        .card-title {
            color: var(--primary-color);
        }
        .lead {
            color: var(--secondary-color);
        }
        .section-title {
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        .booking-reference {
            background-color: var(--accent-color);
            color: var(--dark-color);
            display: inline-block;
            padding: 0.3rem 1rem;
            border-radius: 10px;
            font-weight: bold;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }
        .btn-secondary {
            background-color: var(--secondary-color);
            border: none;
        }
        .table th {
            background-color: var(--primary-color);
            color: white;
        }
        .table td {
            background-color: white;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="card p-4">
        <div class="text-center mb-4">
            <h1 class="card-title">🎉 Booking Confirmed!</h1>
            <p class="lead">Your booking has been successfully processed.</p>
            <p>Booking Reference: <span class="booking-reference"><?= str_pad($booking_id, 6, '0', STR_PAD_LEFT); ?></span></p>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="section-title">✈️ Flight Info</h4>
                <ul class="list-unstyled">
                    <li><strong>Airline:</strong> <?= htmlspecialchars($booking['airline_name']); ?></li>
                    <li><strong>Flight Number:</strong> <?= htmlspecialchars($booking['flight_number']); ?></li>
                    <li><strong>From:</strong> <?= htmlspecialchars($booking['departure_city']); ?></li>
                    <li><strong>To:</strong> <?= htmlspecialchars($booking['arrival_city']); ?></li>
                </ul>
            </div>
            <div class="col-md-6">
                <h4 class="section-title">🕒 Timing & Price</h4>
                <ul class="list-unstyled">
                    <li><strong>Departure:</strong> <?= date('d M Y, H:i', strtotime($booking['departure_time'])); ?></li>
                    <li><strong>Arrival:</strong> <?= date('d M Y, H:i', strtotime($booking['arrival_time'])); ?></li>
                    <li><strong>Passengers:</strong> <?= $booking['total_passengers']; ?></li>
                    <li><strong>Total Price:</strong> ₹<?= number_format($booking['total_price'], 2); ?></li>
                </ul>
            </div>
        </div>

        <h4 class="section-title">👥 Passenger Details</h4>
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($passengers as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']); ?></td>
                            <td><?= htmlspecialchars($p['email']); ?></td>
                            <td><?= htmlspecialchars($p['phone']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center">
            <a href="my_bookings.php" class="btn btn-primary me-2">View All Bookings</a>
            <a href="index.php" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
