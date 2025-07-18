<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "airline_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fetch all flights
    $stmt = $conn->prepare("SELECT * FROM flights ORDER BY departure_time ASC");
    $stmt->execute();
    $flights = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Flights - SkyWings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
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
                        <a class="nav-link active" href="flights.php">Flights</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary ms-2" href="register.php">Sign Up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flights Section -->
    <div class="container" style="margin-top: 100px;">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="display-6">Available Flights</h2>
                <p class="text-muted">Find your perfect flight from our extensive selection</p>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary" id="sortPrice">
                        <i class="fas fa-sort-amount-down me-2"></i>Sort by Price
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="sortTime">
                        <i class="fas fa-clock me-2"></i>Sort by Time
                    </button>
                </div>
            </div>
        </div>

        <!-- Flight Cards -->
        <div class="row" id="flightContainer">
            <?php if (!empty($flights)): ?>
                <?php foreach ($flights as $flight): ?>
                    <div class="col-md-4 mb-4 flight-card" 
                         data-price="<?php echo $flight['price']; ?>"
                         data-time="<?php echo strtotime($flight['departure_time']); ?>">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">
                                        <?php echo $flight['flight_number']; ?>
                                    </h5>
                                    <span class="badge bg-success">
                                        <?php echo $flight['available_seats']; ?> Seats Left
                                    </span>
                                </div>
                                
                                <div class="flight-details mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0"><?php echo $flight['departure_city']; ?></h6>
                                            <small class="text-muted">
                                                <?php 
                                                $departureDateTime = new DateTime($flight['departure_time']);
                                                echo $departureDateTime->format('d M Y, h:i A');
                                                ?>
                                            </small>
                                        </div>
                                        <div class="text-center">
                                            <i class="fas fa-plane text-primary"></i>
                                            <div class="small text-muted">
                                                <?php 
                                                $duration = strtotime($flight['arrival_time']) - strtotime($flight['departure_time']);
                                                echo floor($duration/3600) . 'h ' . floor(($duration%3600)/60) . 'm';
                                                ?>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-end"><?php echo $flight['arrival_city']; ?></h6>
                                            <small class="text-muted text-end d-block">
                                                <?php 
                                                $arrivalDateTime = new DateTime($flight['arrival_time']);
                                                echo $arrivalDateTime->format('d M Y, h:i A');
                                                ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">Price per person</small>
                                        <h5 class="mb-0 text-primary">₹<?php echo number_format($flight['price']); ?></h5>
                                    </div>
                                    <a href="search_results.php?from=<?php echo $flight['departure_city']; ?>&to=<?php echo $flight['arrival_city']; ?>&departure=<?php echo date('Y-m-d', strtotime($flight['departure_time'])); ?>&passengers=1" class="btn btn-primary">
                                        Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        No flights available at the moment. Please check back later.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>SkyWings</h5>
                    <p>Making your travel dreams come true since 2024</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">About Us</a></li>
                        <li><a href="#" class="text-white">Contact</a></li>
                        <li><a href="#" class="text-white">Careers</a></li>
                        <li><a href="#" class="text-white">Blog</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <p><i class="fas fa-envelope me-2"></i> support@skywings.com</p>
                    <p><i class="fas fa-phone me-2"></i> +1 (555) 123-4567</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i> 123 Aviation Way, New York, NY</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; <?php echo date("Y"); ?> SkyWings. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sorting functionality
        $(document).ready(function() {
            let sortOrder = 1; // 1 for ascending, -1 for descending

            $('#sortPrice').click(function() {
                sortFlights('price');
            });

            $('#sortTime').click(function() {
                sortFlights('time');
            });

            function sortFlights(type) {
                const container = $('#flightContainer');
                const cards = container.find('.flight-card').get();

                cards.sort(function(a, b) {
                    const aValue = $(a).data(type);
                    const bValue = $(b).data(type);
                    return (aValue - bValue) * sortOrder;
                });

                sortOrder *= -1; // Toggle sort order
                container.empty().append(cards);
            }
        });
    </script>
</body>
</html> 