<?php
session_start();

// For testing - set default user if none exists
if (!isset($_SESSION['user_id'])) {
    // Check if any users exist in the database
    require_once 'database.php';
    $check_users = "SELECT id FROM users LIMIT 1";
    $user_result = $conn->query($check_users);
    
    if ($user_result && $user_result->num_rows > 0) {
        $user_row = $user_result->fetch_assoc();
        $_SESSION['user_id'] = $user_row['id'];
        $_SESSION['test_mode'] = true; // Flag that we're in test mode
    }
}

require_once 'database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login to access your dashboard";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user information
$user_query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Debug variable to track issues
$debug_info = [];

// Fetch user's bookings
try {
    // First check if bookings table exists
    $check_table = "SHOW TABLES LIKE 'bookings'";
    $table_result = $conn->query($check_table);
    $debug_info[] = "Checking for bookings table: " . ($table_result && $table_result->num_rows > 0 ? "Found" : "Not found");
    
    if ($table_result && $table_result->num_rows > 0) {
        // Check user ID value
        $debug_info[] = "User ID: " . $user_id;
        
        // First try a simple count query to verify we can get bookings
        $count_query = "SELECT COUNT(*) as total FROM bookings WHERE user_id = ?";
        $count_stmt = $conn->prepare($count_query);
        
        if ($count_stmt) {
            $count_stmt->bind_param("i", $user_id);
            $count_stmt->execute();
            $count_result = $count_stmt->get_result();
            $count_data = $count_result->fetch_assoc();
            $total_bookings = $count_data['total'];
            $debug_info[] = "Total bookings from count query: " . $total_bookings;
            $count_stmt->close();
            
            // Simplified query to eliminate join issues
            if ($total_bookings > 0) {
                $booking_query = "SELECT b.*, f.flight_number, f.departure_city, f.arrival_city, 
                                f.departure_time, f.arrival_time, f.price 
    FROM bookings b 
                                LEFT JOIN flights f ON b.flight_id = f.id 
                                WHERE b.user_id = ?";
            } else {
                // No bookings found, skip the more complex query
                $booking_query = "SELECT * FROM bookings WHERE user_id = ?";
                $debug_info[] = "No bookings found for this user";
            }
            
            $stmt = $conn->prepare($booking_query);
            if ($stmt) {
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $booking_result = $stmt->get_result();
                $bookings = [];
                while ($row = $booking_result->fetch_assoc()) {
                    $bookings[] = $row;
                }
                $debug_info[] = "Found " . count($bookings) . " bookings in main query";
                $stmt->close();
            } else {
                // Prepare statement failed
                $bookings = [];
                $debug_info[] = "Error preparing booking query: " . $conn->error;
            }
        } else {
            $bookings = [];
            $debug_info[] = "Error preparing count query: " . $conn->error;
        }
    } else {
        // Bookings table doesn't exist yet
        $bookings = [];
        $debug_info[] = "Bookings table doesn't exist";
    }
} catch (Exception $e) {
    // Handle any exceptions
$bookings = [];
    $debug_info[] = "Exception: " . $e->getMessage();
}

// Store debug info for diagnostic purposes
$_SESSION['debug_info'] = $debug_info;

// Count upcoming flights
$upcoming_flights = 0;
if (!empty($bookings)) {
    foreach ($bookings as $booking) {
        if (isset($booking['departure_time']) && strtotime($booking['departure_time']) > time()) {
            $upcoming_flights++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - SkyWings</title>
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
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: white !important;
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
            margin-bottom: 1.5rem;
        }

        .card-title {
            color: var(--dark-color);
            font-weight: 600;
        }

        .dash-card {
            background: white;
            border-radius: 15px;
            transition: transform 0.3s;
            height: 100%;
        }

        .dash-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .icon-box {
            font-size: 2rem;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
        }

        .bg-primary-light {
            background-color: rgba(26, 115, 232, 0.1);
            color: var(--primary-color);
        }

        .bg-success-light {
            background-color: rgba(52, 168, 83, 0.1);
            color: var(--secondary-color);
        }

        .bg-warning-light {
            background-color: rgba(251, 188, 5, 0.1);
            color: var(--accent-color);
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0;
        }

        footer {
            background: var(--dark-color);
            color: white;
            padding: 1rem 0;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #1557b0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 115, 232, 0.3);
        }

        .recent-booking {
            border-left: 4px solid var(--primary-color);
            padding-left: 1rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .stats-value {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
                <a class="navbar-brand" href="index.php">
                    <i class="fas fa-plane-departure me-2"></i>SkyWings
                </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                            <a class="nav-link" href="flights.php">Flights</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="my_bookings.php">My Bookings</a>
                    </li>
                    <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

        <div class="container mt-5">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['errors'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5>Errors:</h5>
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <li><?= htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-md-8">
                    <h2>Welcome, <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
                    <p class="text-muted">Dashboard overview of your activity and upcoming flights</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="search_flights.php" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Search Flights
                    </a>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="dash-card p-4 text-center">
                        <div class="icon-box bg-primary-light">
                            <i class="fas fa-plane"></i>
                        </div>
                        <h5 class="mt-3">Total Bookings</h5>
                        <p class="stats-value"><?= count($bookings); ?></p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="dash-card p-4 text-center">
                        <div class="icon-box bg-success-light">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h5 class="mt-3">Upcoming Flights</h5>
                        <p class="stats-value"><?= $upcoming_flights; ?></p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="dash-card p-4 text-center">
                        <div class="icon-box bg-warning-light">
                            <i class="fas fa-tag"></i>
                        </div>
                        <h5 class="mt-3">Rewards Points</h5>
                        <p class="stats-value"><?= isset($user['reward_points']) ? $user['reward_points'] : 0; ?></p>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings and User Info -->
            <div class="row">
                <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="card-title">Recent Bookings</h5>
                                <a href="my_bookings.php" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>

                            <?php if (empty($bookings)): ?>
                            <div class="alert alert-info">
                                You don't have any bookings yet. <a href="search_flights.php">Search for flights</a> to make a booking.
                            </div>
                            <?php else: ?>
                                <?php foreach (array_slice($bookings, 0, 3) as $booking): ?>
                                    <div class="recent-booking">
                                        <h6><?= htmlspecialchars($booking['flight_number']); ?></h6>
                                        <p class="mb-0">
                                            <span class="text-muted"><?= htmlspecialchars(getCityName($booking['departure_city'])); ?></span>
                                            <i class="fas fa-arrow-right mx-2"></i>
                                            <span class="text-muted"><?= htmlspecialchars(getCityName($booking['arrival_city'])); ?></span>
                                        </p>
                                        <p class="mb-0">
                                            <small class="text-muted">
                                                <?= date('d M Y, h:i A', strtotime($booking['departure_time'])); ?>
                                            </small>
                                        </p>
                                    </div>
                                <?php endforeach; ?>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Profile Information</h5>
                            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
                            <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Not provided'); ?></p>
                            <p><strong>Member Since:</strong> <?= date('M Y', strtotime($user['created_at'])); ?></p>
                            <a href="profile.php" class="btn btn-outline-primary w-100 mt-3">Edit Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white mt-5">
        <div class="container py-2">
            <p class="text-center mb-0">&copy; <?= date('Y'); ?> SkyWings. All rights reserved.</p>
        </div>
    </footer>

    <?php if (isset($_SESSION['test_mode']) && $_SESSION['test_mode']): ?>
    <!-- Debug information (only shown in test mode) -->
    <div class="container mt-3 mb-5">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Debug Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="create_test_booking.php" method="post" class="border p-3 rounded">
                            <h6>Create Test Booking:</h6>
                            <input type="hidden" name="user_id" value="<?= $user_id ?>">
                            <div class="mb-2">
                                <label for="flight_id" class="form-label">Flight ID:</label>
                                <input type="number" name="flight_id" id="flight_id" class="form-control" value="1" required>
                            </div>
                            <div class="mb-2">
                                <label for="booking_reference" class="form-label">Booking Reference:</label>
                                <input type="text" name="booking_reference" id="booking_reference" class="form-control" value="TEST<?= rand(1000, 9999) ?>" required>
                            </div>
                            <div class="mb-2">
                                <label for="total_passengers" class="form-label">Passengers:</label>
                                <input type="number" name="total_passengers" id="total_passengers" class="form-control" value="1" required>
                            </div>
                            <div class="mb-2">
                                <label for="total_price" class="form-label">Price:</label>
                                <input type="number" name="total_price" id="total_price" class="form-control" value="5999" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Create Test Booking</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h6>Database Query Debug:</h6>
                        <ul>
                            <?php foreach ($_SESSION['debug_info'] as $info): ?>
                                <li><?= htmlspecialchars($info) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <h6>Session Data:</h6>
                        <pre class="small"><?= htmlspecialchars(print_r($_SESSION, true)) ?></pre>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6>User Data:</h6>
                        <pre class="small"><?= htmlspecialchars(print_r($user, true)) ?></pre>
                    </div>
                    <div class="col-md-6">
                        <h6>First Booking (if any):</h6>
                        <pre class="small"><?= !empty($bookings) ? htmlspecialchars(print_r($bookings[0], true)) : 'No bookings found' ?></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Helper function for city names -->
    <?php
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
    ?>
</body>
</html>
