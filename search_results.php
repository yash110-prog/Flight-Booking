<?php
session_start();
require_once 'database.php';

// Convert airport code to city name
function getCityName($code) {
    $cities = [
        'DEL' => 'Delhi',
        'BOM' => 'Mumbai',
        'BLR' => 'Bangalore',
        'MAA' => 'Chennai',
        'HYD' => 'Hyderabad',
        'CCU' => 'Kolkata',
        'DXB' => 'Dubai',
        'SIN' => 'Singapore',
        'LHR' => 'London',
        'JFK' => 'New York'
    ];
    return $cities[$code] ?? $code;
}

// Get and convert search parameters
$fromCode = $_GET['from'] ?? '';
$toCode = $_GET['to'] ?? '';
$from = getCityName($fromCode);
$to = getCityName($toCode);
$departure = $_GET['departure'] ?? '';
$passengers = $_GET['passengers'] ?? 1;

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validate input
if (empty($from) || empty($to) || empty($departure)) {
    $_SESSION['error'] = "Please provide valid search criteria.";
    header("Location: search_flights.php");
    exit();
}

$departure_date = date('Y-m-d', strtotime($departure));

// SQL query
$query = "SELECT * FROM flights 
          WHERE departure_city = ? 
          AND arrival_city = ? 
          AND DATE(departure_time) = ? 
          AND available_seats >= ?";

try {
    // Connection check moved here
    if (!isset($conn)) {
        throw new Exception("Database connection not established");
    }

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssi", $from, $to, $departure_date, $passengers);
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $flights = $result->fetch_all(MYSQLI_ASSOC);
    
    // Close the statement after use
    $stmt->close();

} catch (Exception $e) {
    $_SESSION['error'] = "Error searching flights: " . $e->getMessage();
    header("Location: search_flights.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flight Search Results - SkyWings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            padding-bottom: 60px; /* Add padding to prevent content from being hidden by footer */
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

        .flight-card {
            background: white;
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        .price {
            font-size: 1.5rem;
            color: var(--primary-color);
            font-weight: bold;
        }
        .btn-book {
            background: var(--primary-color);
            color: white;
            border-radius: 10px;
            padding: 0.5rem 1.2rem;
        }
        footer {
            background: #000000;
            color: white;
            padding: 1.5rem 0;
            position: relative;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        footer p {
            margin: 0;
            font-size: 1rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        footer .container {
            display: flex;
            justify-content: center;
            align-items: center;
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
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="search_flights.php">Search Flights</a></li>
          <li class="nav-item"><a class="nav-link" href="my_bookings.php">My Bookings</a></li>
        </ul>
      </div>
    </div>
  </nav>

        <div class="container my-5">
            <!-- Search Summary -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title">Search Results</h3>
                    <div class="row">
                        <div class="col-md-3"><strong>From:</strong> <?= htmlspecialchars($from); ?></div>
                        <div class="col-md-3"><strong>To:</strong> <?= htmlspecialchars($to); ?></div>
                        <div class="col-md-3"><strong>Date:</strong> <?= date('d M Y', strtotime($departure_date)); ?></div>
                        <div class="col-md-3"><strong>Passengers:</strong> <?= htmlspecialchars($passengers); ?></div>
                    </div>
                </div>
            </div>

            <!-- Flight Results -->
            <?php if (empty($flights)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>No flights found matching your criteria.
                </div>
                <a href="search_flights.php" class="btn btn-primary">New Search</a>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($flights as $flight): ?>
                        <div class="col-md-6">
                            <div class="flight-card">
                                <h5><?= htmlspecialchars($flight['flight_number']) ?> (<?= htmlspecialchars($flight['departure_city']) ?> ➜ <?= htmlspecialchars($flight['arrival_city']) ?>)</h5>
                                <p><strong>Departure:</strong> <?= date('d M Y, H:i', strtotime($flight['departure_time'])) ?></p>
                                <p><strong>Arrival:</strong> <?= date('d M Y, H:i', strtotime($flight['arrival_time'])) ?></p>
                                <p class="price">₹<?= number_format($flight['price'], 2) ?></p>
                                <p><small><?= $flight['available_seats'] ?> seats available</small></p>
                                <a href="booking.php?flight_id=<?= $flight['id'] ?>&passengers=<?= $passengers ?>" class="btn btn-book">Book Now</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <div class="container">
            <p class="text-center">&copy; <?php echo date("Y"); ?> SkyWings. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the database connection at the end of the script
if (isset($conn)) {
    $conn->close();
}
?>
