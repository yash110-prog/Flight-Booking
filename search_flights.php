<?php
require_once 'database.php';

// Define available airports (you can modify this list as needed)
$availableAirports = ['Delhi', 'Mumbai', 'Bangalore', 'Kolkata', 'Chennai'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Flights - Airline Reservation System</title>
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
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .navbar-brand {
      font-weight: 700;
      color: white !important;
    }
    
    .nav-link {
      color: rgba(255, 255, 255, 0.9) !important;
      font-weight: 500;
    }
    
    .nav-link:hover {
      color: white !important;
    }
    
    .nav-link.active {
      color: white !important;
      font-weight: 600;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: var(--light-color);
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(26, 115, 232, 0.25);
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
      padding-bottom: 60px;
    }

    /* Footer Styles */
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
      text-align: center;
    }

    footer .container {
      display: flex;
      justify-content: center;
      align-items: center;
    }
  </style>
</head>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('searchForm').addEventListener('submit', function (e) {
      const from = document.getElementById('from').value.trim();
      const to = document.getElementById('to').value.trim();
      const date = document.getElementById('departure').value;

      if (from === '' || to === '') {
        alert('Please fill in both From and To fields.');
        e.preventDefault(); // Stop form submission
        return;
      }

      const today = new Date().toISOString().split('T')[0];
      if (date < today) {
        alert('Please select a valid (future) departure date.');
        e.preventDefault();
      }
    });
  });
</script>

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
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link active" href="search_flights.php">Search Flights</a></li>
          <li class="nav-item"><a class="nav-link" href="my_bookings.php">My Bookings</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="content-wrapper">
    <div class="container mt-4">
      <div class="row">
        <div class="col-md-12">
          <div class="card shadow-sm">
            <div class="card-body p-5">
              <h2 class="text-center mb-4">Find Your Perfect Flight</h2>
              <form id="searchForm" action="search_results.php" method="GET" class="row g-3">
                <div class="col-md-3">
                  <label class="form-label">From</label>
                  <select class="form-select" id="from" name="from" required>
                    <option value="" selected disabled>Select Departure City</option>
                    <option value="DEL">Delhi</option>
                    <option value="BOM">Mumbai</option>
                    <option value="BLR">Bangalore</option>
                    <option value="MAA">Chennai</option>
                    <option value="HYD">Hyderabad</option>
                    <option value="CCU">Kolkata</option>
                    <option value="DXB">Dubai</option>
                    <option value="SIN">Singapore</option>
                    <option value="LHR">London</option>
                    <option value="JFK">New York</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label">To</label>
                  <select class="form-select" id="to" name="to" required>
                    <option value="" selected disabled>Select Arrival City</option>
                    <option value="DEL">Delhi</option>
                    <option value="BOM">Mumbai</option>
                    <option value="BLR">Bangalore</option>
                    <option value="MAA">Chennai</option>
                    <option value="HYD">Hyderabad</option>
                    <option value="CCU">Kolkata</option>
                    <option value="DXB">Dubai</option>
                    <option value="SIN">Singapore</option>
                    <option value="LHR">London</option>
                    <option value="JFK">New York</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label class="form-label">Departure</label>
                  <input type="date" class="form-control" id="departure" name="departure" required>
                </div>
                <div class="col-md-2">
                  <label class="form-label">Passengers</label>
                  <select class="form-select" id="passengers" name="passengers" required>
                    <option value="1">1 Passenger</option>
                    <option value="2">2 Passengers</option>
                    <option value="3">3 Passengers</option>
                    <option value="4">4 Passengers</option>
                    <option value="5">5 Passengers</option>
                    <option value="6">6 Passengers</option>
                  </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                  <button type="submit" class="btn btn-primary w-100 py-3">
                    <i class="fas fa-search fa-lg"></i> Search
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer>
    <div class="container">
      <p class="text-center">&copy; <?php echo date("Y"); ?> SkyWings. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>
