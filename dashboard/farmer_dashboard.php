
<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: ../users/login.php");
    exit();
}

// For role-specific dashboards, add:
if ($_SESSION['role'] !== 'farmer') { // Change for each dashboard
    header("Location: ../users/login.php");
    exit();
}

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'farmer') {
    header("Location: login.php");
    exit();
}

// Get farmer's name from database
require_once '../db.php';
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($farmer_name);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #28a745;
            --secondary-color: #f8f9fa;
            --accent-color: #ffc107;
        }
        
        body {
            background-color: #f5f5f5;
        }
        
        .sidebar {
            background-color: var(--primary-color);
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 5px;
            border-radius: 5px;
        }
        
        .sidebar .nav-link:hover, 
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            font-weight: 600;
        }
        
        .profile-card {
            background: linear-gradient(135deg, var(--primary-color), #5cb85c);
            color: white;
            border-radius: 10px;
        }
        
        .stat-card {
            border-left: 4px solid var(--primary-color);
        }
        
        .stat-card i {
            font-size: 2rem;
            color: var(--primary-color);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar.active {
                margin-left: 0;
            }
            
        }
        .main-content {
        background: url('../assets/images/backgrounds/farmer.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    .sidebar {
        background-color: rgba(40, 167, 69, 0.9) !important;
        backdrop-filter: blur(5px);
    }
    .card {
        background-color: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(2px);
    }
    
        
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="p-4">
            <h4 class="text-center mb-4">
                <i class="fas fa-leaf"></i> FASTS
            </h4>
            <div class="text-center mb-4">
                <img src="../assets/images/user-avatar.png" alt="User" class="rounded-circle" width="80">
                <h5 class="mt-2 mb-0"><?= htmlspecialchars($farmer_name) ?></h5>
                <small class="text-white-50">Farmer</small>
            </div>
            <hr class="bg-white">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#crop-suggestion">
                        <i class="fas fa-seedling"></i> Crop Suggestion
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-calendar-alt"></i> Seasonal Calendar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-bell"></i> Notifications
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <a class="nav-link text-danger" href="../users/logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button class="btn btn-primary d-md-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h3 class="mb-0">Farmer Dashboard</h3>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars(explode(' ', $farmer_name)[0]) ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../users/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Crop Suggestions</h6>
                                <h3>12</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 text-success rounded p-3">
                                <i class="fas fa-seedling"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Active Seasons</h6>
                                <h3>2</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 text-success rounded p-3">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Alerts</h6>
                                <h3>3</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 text-success rounded p-3">
                                <i class="fas fa-bell"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Crop Suggestion Form -->
        <div class="card" id="crop-suggestion">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-seedling me-2"></i>Crop Suggestion Based on Farmer Input</h5>
            </div>
            <div class="card-body">
                <form action="process_weather.php" method="POST">
                    <div class="row">
                        <!-- Rainfall -->
                        <div class="col-md-6 mb-3">
                            <label for="rainfall" class="form-label">Rainfall</label>
                            <select class="form-select" id="rainfall" name="rainfall" required>
                                <option value="" disabled selected>Select Rainfall Level</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                        <!-- Temperature -->
                        <div class="col-md-6 mb-3">
                            <label for="temperature" class="form-label">Temperature</label>
                            <select class="form-select" id="temperature" name="temperature" required>
                                <option value="" disabled selected>Select Temperature Level</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Season -->
                        <div class="col-md-6 mb-3">
                            <label for="season" class="form-label">Season</label>
                            <select class="form-select" id="season" name="season" required>
                                <option value="" disabled selected>Select Season</option>
                                <option value="january">January</option>
                                <option value="february">February</option>
                                <option value="march">March</option>
                                <option value="april">April</option>
                                <option value="may">May</option>
                                <option value="october">October</option>
                                <option value="november">November</option>
                                <option value="december">December</option>
                            </select>
                        </div>
                        <!-- Soil Type -->
                        <div class="col-md-6 mb-3">
                            <label for="soil_type" class="form-label">Soil Type</label>
                            <select class="form-select" id="soil_type" name="soil_type" required>
                                <option value="" disabled selected>Select Soil Type</option>
                                <option value="sandy">Sandy</option>
                                <option value="clay">Clay</option>
                                <option value="loamy">Loamy</option>
                                <option value="silty">Silty</option>
                            </select>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-lightbulb me-2"></i>Get Crop Suggestions
                    </button>
                </form>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Activity</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Crop Suggestion</h6>
                            <small>3 days ago</small>
                        </div>
                        <p class="mb-1">Received suggestions for maize and beans</p>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Weather Alert</h6>
                            <small>1 week ago</small>
                        </div>
                        <p class="mb-1">Heavy rainfall expected in your region</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
        
        // Auto-fetch weather data (example)
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                // This is where you would call a weather API with the coordinates
                console.log("Latitude: " + position.coords.latitude + ", Longitude: " + position.coords.longitude);
                // You can use this to fetch real weather data for the farmer's location
            });
        }
    </script>
</body>
</html>