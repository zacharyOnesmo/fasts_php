<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FASTS - Home</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/bootstrap/css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #28a745;
            --secondary-color: #f8f9fa;
            --accent-color: #ffc107;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('../assets/images/farm-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            position: relative;
        }
        
        .feature-card {
            transition: transform 0.3s;
            border-radius: 10px;
            overflow: hidden;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .btn-get-started {
            padding: 12px 30px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .hero-section {
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                    url('../assets/images/backgrounds/home.jpg');
        background-size: cover;
        background-position: center;
    }
    /* Add this to your existing styles */
    
   
    
    /* Features section with background */
    .features-section {
        background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), 
                    url('../assets/images/backgrounds/feature.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        padding: 80px 0;
        position: relative;
        overflow: hidden;
    }
    
    /* How it works section */
    .how-it-works {
        padding: 80px 0;
        background-color: #f8f9fa;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .feature-card {
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
    }
    
    /* Delay animations for each card */
    .feature-card:nth-child(1) { animation-delay: 0.2s; }
    .feature-card:nth-child(2) { animation-delay: 0.4s; }
    .feature-card:nth-child(3) { animation-delay: 0.6s; }
    
    /* Hover effect */
    .feature-card:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
    }
    
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow sticky-top">
        <div class="container">
        <a class="navbar-brand" href="#">
    <img src="../assets/images/ui/logo.png" height="30" class="d-inline-block align-top me-2">
    <span class="fw-bold">FASTS</span>
</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#"><i class="fas fa-home me-1"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../users/login.php"><i class="fas fa-sign-in-alt me-1"></i> Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="../users/register.php"><i class="fas fa-user-plus me-1"></i> Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Automated Farmers Seasonal Tracking System</h1>
            <p class="lead mb-5">Empowering farmers with AI-driven insights and real-time weather-based crop recommendations</p>
            <a href="../users/register.php" class="btn btn-success btn-lg btn-get-started">
                <i class="fas fa-play-circle me-2"></i>Get Started
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-success">Our Key Features</h2>
                <p class="text-muted">Designed to revolutionize modern farming practices</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <h5 class="card-title fw-bold">Smart Crop Suggestions</h5>
                            <p class="card-text">AI-powered recommendations based on real-time weather data and soil conditions.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-sms"></i>
                            </div>
                            <h5 class="card-title fw-bold">SMS Alerts</h5>
                            <p class="card-text">Critical updates delivered via SMS for farmers without smartphones.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h5 class="card-title fw-bold">Seasonal Analytics</h5>
                            <p class="card-text">Track and analyze seasonal patterns for better planning.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-success">How It Works</h2>
                <p class="text-muted">Simple steps to get the most out of FASTS</p>
            </div>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card border-0 text-center p-3 h-100">
                        <div class="card-body">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 mb-3 mx-auto" style="width: 70px; height: 70px;">
                                <i class="fas fa-user-plus fs-3"></i>
                            </div>
                            <h5>1. Register</h5>
                            <p class="text-muted">Create your account as a farmer or officer</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 text-center p-3 h-100">
                        <div class="card-body">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 mb-3 mx-auto" style="width: 70px; height: 70px;">
                                <i class="fas fa-sign-in-alt fs-3"></i>
                            </div>
                            <h5>2. Login</h5>
                            <p class="text-muted">Access your personalized dashboard</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 text-center p-3 h-100">
                        <div class="card-body">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 mb-3 mx-auto" style="width: 70px; height: 70px;">
                                <i class="fas fa-cloud-sun fs-3"></i>
                            </div>
                            <h5>3. Input Data</h5>
                            <p class="text-muted">Enter weather and soil conditions</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 text-center p-3 h-100">
                        <div class="card-body">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 mb-3 mx-auto" style="width: 70px; height: 70px;">
                                <i class="fas fa-lightbulb fs-3"></i>
                            </div>
                            <h5>4. Get Insights</h5>
                            <p class="text-muted">Receive AI-powered recommendations</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-success text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-leaf me-2"></i>FASTS</h5>
                    <p>Empowering farmers with technology for better yields and sustainable agriculture.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">Home</a></li>
                        <li><a href="../users/login.php" class="text-white">Login</a></li>
                        <li><a href="../users/register.php" class="text-white">Register</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i> zikrykamwela.com</li>
                        <li><i class="fas fa-phone me-2"></i> +255 627 118 307</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-white">
            <div class="text-center">
                <p class="mb-0">&copy; 2025 FASTS | Developed by Zikry</p>
            </div>
        </div>
    </footer>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>