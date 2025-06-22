<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SkyWings - Your Journey Begins Here</title>
  
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Custom Styles -->
  <style>
    :root {
      --primary-color: #1a73e8;
      --secondary-color: #34a853;
      --accent-color: #fbbc05;
      --dark-color: #202124;
      --light-color: #f8f9fa;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background: var(--light-color);
      color: var(--dark-color);
    }
    
    .navbar {
      background: rgba(255, 255, 255, 0.95) !important;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .navbar-brand {
      font-weight: 700;
      color: var(--primary-color) !important;
    }
    
    .hero {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 100px 0;
      position: relative;
      overflow: hidden;
    }
    
    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('https://images.unsplash.com/photo-1436491865332-7a61a109cc38?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80') center/cover;
      opacity: 0.1;
    }
    
    .search-section {
      background: white;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      margin-top: -50px;
      position: relative;
      z-index: 1;
    }
    
    .flight-card {
      background: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s ease;
    }
    
    .flight-card:hover {
      transform: translateY(-5px);
    }
    
    .btn-primary {
      background: var(--primary-color);
      border: none;
      padding: 12px 30px;
      border-radius: 8px;
    }
    
    .btn-primary:hover {
      background: #1557b0;
    }
    
    .feature-icon {
      font-size: 2.5rem;
      color: var(--primary-color);
      margin-bottom: 1rem;
    }
    
    .announcement-section {
      background: var(--light-color);
      padding: 40px 0;
    }
    
    .announcement-card {
      background: white;
      border-radius: 10px;
      padding: 20px;
      margin: 10px;
      box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
    }
    
    .footer {
      background: var(--dark-color);
      color: white;
      padding: 40px 0;
    }
    
    .social-icons a {
      color: white;
      font-size: 1.5rem;
      margin: 0 10px;
      transition: color 0.3s ease;
    }
    
    .social-icons a:hover {
      color: var(--accent-color);
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">
        <i class="fas fa-plane-departure me-2"></i>SkyWings
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="flights.php">Flights</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="my_bookings.php">My Bookings</a>
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

  <!-- Hero Section -->
  <div class="hero text-center">
    <div class="container">
      <h1 class="display-4 fw-bold mb-4">Discover the World with SkyWings</h1>
      <p class="lead mb-5">Experience seamless travel with our premium services</p>
    </div>
  </div>

  <!-- Search Section -->
  <div class="container">
    <div class="search-section text-center">
      <h3 class="text-center mb-4">Find Your Perfect Flight</h3>
      <a href="search_flights.php" class="btn btn-primary btn-lg">
        <i class="fas fa-search me-2"></i>Search Flights
      </a>
    </div>
  </div>

  <!-- Features Section -->
  <div class="container py-5">
    <div class="row text-center">
      <div class="col-md-4">
        <i class="fas fa-shield-alt feature-icon"></i>
        <h4>Safe Travel</h4>
        <p>Your safety is our top priority with enhanced cleaning protocols</p>
      </div>
      <div class="col-md-4">
        <i class="fas fa-percentage feature-icon"></i>
        <h4>Best Prices</h4>
        <p>Guaranteed lowest fares with our price match policy</p>
      </div>
      <div class="col-md-4">
        <i class="fas fa-headset feature-icon"></i>
        <h4>24/7 Support</h4>
        <p>Round-the-clock customer service for all your needs</p>
      </div>
    </div>
  </div>
  <!-- Announcements -->
  <div class="announcement-section">
    <div class="container">
      <h3 class="text-center mb-4">Latest Updates</h3>
      <div class="row">
        <div class="col-md-4">
          <div class="announcement-card">
            <i class="fas fa-tag text-warning mb-2"></i>
            <h5>Summer Sale</h5>
            <p>20% off on all bookings till April 30th!</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="announcement-card">
            <i class="fas fa-route text-primary mb-2"></i>
            <h5>New Routes</h5>
            <p>Delhi to Dubai now available daily!</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="announcement-card">
            <i class="fas fa-clock text-success mb-2"></i>
            <h5>Early Bird Offers</h5>
            <p>Book now to avoid last-minute fare hikes</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
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

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  
  <!-- Custom Script -->
  <script>
    $(document).ready(function() {
      // Form validation
      $('#searchForm').on('submit', function(e) {
        const from = $('#from').val();
        const to = $('#to').val();
        const departure = $('#departure').val();
        
        if (!from || !to) {
          alert('Please select both departure and arrival cities.');
          e.preventDefault();
        }
        
        if (!departure) {
          alert('Please select a departure date.');
          e.preventDefault();
        }
        
        if (from === to) {
          alert('Departure and arrival cities cannot be the same.');
          e.preventDefault();
        }
      });
      
      // Smooth scroll for navigation links
      $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $(this.hash);
        if (target.length) {
          $('html, body').animate({
            scrollTop: target.offset().top - 70
          }, 1000);
        }
      });
    });
  </script>
</body>
</html>
