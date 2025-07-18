<?php
require_once 'database.php';

// Fetch all bookings
$sql = "
    SELECT b.*, f.*, a.name as airline_name 
    FROM bookings b 
    LEFT JOIN flights f ON b.flight_id = f.id 
    LEFT JOIN airlines a ON f.airline_id = a.id 
    ORDER BY b.booking_date DESC
";

$result = $conn->query($sql);
$bookings = [];

if ($result && $result->num_rows > 0) {
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Airline Reservation System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a73e8;
            --secondary-color: #34a853;
            --accent-color: #fbbc05;
            --dark-color: #202124;
            --light-color: #f8f9fa;
        }

        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            background-color: var(--light-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-wrapper {
            flex: 1 0 auto;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            color: white !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
        }

        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }

        .nav-link.active {
            color: white !important;
            font-weight: 600;
            border-bottom: 2px solid white;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .card-title {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--dark-color);
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #1557b0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 115, 232, 0.3);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .alert-info {
            background-color: rgba(26, 115, 232, 0.1);
            color: var(--primary-color);
        }

        footer {
            background: var(--dark-color);
            color: white;
            padding: 1rem 0;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .table-responsive {
                border-radius: 15px;
            }
            
            .btn-sm {
                width: 100%;
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
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
                            <a class="nav-link active" href="my_bookings.php">My Bookings</a>
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
                            <h2 class="card-title">My Bookings</h2>

                            <?php if (count($bookings) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Booking Reference</th>
                                                <th>Airline</th>
                                                <th>Flight Number</th>
                                                <th>From</th>
                                                <th>To</th>
                                                <th>Departure</th>
                                                <th>Passengers</th>
                                                <th>Total Price</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($bookings as $booking): ?>
                                                <tr>
                                                    <td><?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                                    <td><?php echo htmlspecialchars($booking['airline_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($booking['flight_number']); ?></td>
                                                    <td><?php echo htmlspecialchars($booking['departure_city']); ?></td>
                                                    <td><?php echo htmlspecialchars($booking['arrival_city']); ?></td>
                                                    <td><?php echo date('Y-m-d H:i', strtotime($booking['departure_time'])); ?></td>
                                                    <td><?php echo $booking['total_passengers']; ?></td>
                                                    <td>₹<?php echo number_format($booking['total_price'], 2); ?></td>
                                                    <td>
                                                        <?php
                                                        $departure_time = strtotime($booking['departure_time']);
                                                        $current_time = time();
                                                        if ($departure_time > $current_time) {
                                                            echo '<span class="badge bg-success">Upcoming</span>';
                                                        } else {
                                                            echo '<span class="badge bg-secondary">Completed</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <a href="booking_confirmation.php?booking_id=<?php echo $booking['id']; ?>" 
                                                           class="btn btn-primary btn-sm">View Details</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    You don't have any bookings yet. <a href="search_flights.php">Search for flights</a> to make a booking.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white mt-5">
        <div class="container py-2">
            <p class="text-center mb-0">&copy; 2024 Airline Reservation System. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
