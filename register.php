<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - SkyWings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    :root {
      --primary-color: #1a73e8;
      --secondary-color: #34a853;
      --accent-color: #fbbc05;
      --dark-color: #202124;
      --light-color: #f8f9fa;
    }
    
    .navbar {
      background: rgba(255, 255, 255, 0.95) !important;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .navbar-brand {
      font-weight: 700;
      color: var(--primary-color) !important;
    }
    
    .nav-link {
      color: var(--dark-color) !important;
      font-weight: 500;
    }
    
    .nav-link:hover {
      color: var(--primary-color) !important;
    }
    
    .nav-link.active {
      color: var(--primary-color) !important;
    }
    
    .btn-primary {
      background: var(--primary-color);
      border: none;
      padding: 8px 20px;
      border-radius: 8px;
    }
    
    .btn-primary:hover {
      background: #1557b0;
    }
  </style>
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
            <a class="nav-link" href="flights.php">Flights</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-primary ms-2 active" href="register.php">Sign Up</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Register Section -->
  <div class="container" style="margin-top: 100px;">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow-sm">
          <div class="card-body p-5">
            <h2 class="text-center mb-4">Create Your Account</h2>
            <p class="text-center text-muted mb-4">Join SkyWings and start your journey</p>
            
            <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            
            <form action="register_process.php" method="POST">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="firstName" class="form-label">First Name</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter your first name" required>
                  </div>
                </div>
                
                <div class="col-md-6 mb-3">
                  <label for="lastName" class="form-label">Last Name</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter your last name" required>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
              </div>

              <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-phone"></i></span>
                  <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
                </div>
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
                </div>
                <div class="form-text">Password must be at least 8 characters long</div>
              </div>

              <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                  <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
                </div>
              </div>

              <div class="mb-4">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="terms" required>
                  <label class="form-check-label" for="terms">
                    I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
                  </label>
                </div>
              </div>
              
              <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="fas fa-user-plus me-2"></i>Create Account
              </button>
              
              <div class="text-center">
                <p class="mb-0">Already have an account? 
                  <a href="login.php" class="text-decoration-none">Sign in</a>
                </p>
              </div>
            </form>
          </div>
        </div>
      </div>
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
    // Password confirmation validation
    document.querySelector('form').addEventListener('submit', function(e) {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      
      if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
      }
    });
  </script>
</body>
</html>
