
<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: ../users/login.php");
    exit();
}

// For role-specific dashboards, add:
if ($_SESSION['role'] !== 'officer') { // Change for each dashboard
    header("Location: ../users/login.php");
    exit();
}
require_once '../db.php';

// Check if user is logged in and is an officer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'officer') {
    header("Location: ../users/login.php");
    exit();
}

// Get officer's name from database
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($officer_name);
$stmt->fetch();
$stmt->close();

// Get all farmers for SMS dropdown
$farmers = $conn->query("SELECT id, name, username FROM users WHERE role = 'farmer'");

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['send_sms'])) {
        $farmer_id = $_POST['farmer_id'];
        $message = $_POST['message'];
        
        // In a real implementation, you would integrate with an SMS gateway here
        // This is just a simulation
        $sms_sent = true; // Assume SMS was sent successfully
        
        if ($sms_sent) {
            $sms_status = "SMS sent successfully to farmer ID: $farmer_id";
        } else {
            $sms_status = "Failed to send SMS";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Dashboard - FASTS</title>
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
        background: url('../assets/images/backgrounds/officer.png');
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
                <i class="fas fa-user-shield"></i> FASTS Officer
            </h4>
            <div class="text-center mb-4">
                <img src="../assets/images/officer-avatar.png" alt="User" class="rounded-circle" width="80">
                <h5 class="mt-2 mb-0"><?= htmlspecialchars($officer_name) ?></h5>
                <small class="text-white-50">Agricultural Officer</small>
            </div>
            <hr class="bg-white">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#crop-suggestions">
                        <i class="fas fa-seedling"></i> Crop Suggestions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sms-section">
                        <i class="fas fa-sms"></i> SMS Notifications
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
            <h3><i class="fas fa-user-shield me-2"></i> Officer Dashboard</h3>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars(explode(' ', $officer_name)[0]) ?>
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
                                <h6 class="text-muted">Farmers</h6>
                                <h3><?= $conn->query("SELECT COUNT(*) FROM users WHERE role = 'farmer'")->fetch_row()[0] ?></h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 text-primary rounded p-3">
                                <i class="fas fa-users fa-2x"></i>
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
                                <h6 class="text-muted">Crops</h6>
                                <h3><?= $conn->query("SELECT COUNT(*) FROM crops")->fetch_row()[0] ?></h3>
                            </div>
                            <div class="bg-success bg-opacity-10 text-success rounded p-3">
                                <i class="fas fa-seedling fa-2x"></i>
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
                                <h6 class="text-muted">Seasons</h6>
                                <h3><?= $conn->query("SELECT COUNT(*) FROM seasons")->fetch_row()[0] ?></h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 text-warning rounded p-3">
                                <i class="fas fa-calendar-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Crop Suggestions Section -->
        <div class="card mb-4" id="crop-suggestions">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-seedling me-2"></i> Crop Suggestions</h5>
                <div>
                    <button class="btn btn-sm btn-success me-2" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Print Section (only visible when printing) -->
                <div class="print-section">
                    <h4 class="text-center mb-4">Crop Suggestions Report</h4>
                    <p class="text-center mb-4">Generated by: <?= htmlspecialchars($officer_name) ?></p>
                    <p class="text-center mb-4">Date: <?= date('Y-m-d') ?></p>
                    <hr>
                </div>
                
                <!-- Display crop suggestions (same as in process_weather.php) -->
                <?php
                // This would normally come from a database query or form submission
                // For demonstration, we'll use sample data
                $sample_suggestions = [
                    'Maize' => 'High suitability for current conditions',
                    'Beans' => 'Good yield expected',
                    'Sunflower' => 'Moderate suitability'
                ];
                ?>
                
                <div class="row">
                    <?php foreach ($sample_suggestions as $crop => $description): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card crop-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 text-success">
                                            <i class="fas fa-leaf fa-2x"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0"><?= htmlspecialchars($crop) ?></h5>
                                            <small class="text-muted"><?= htmlspecialchars($description) ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="mt-4">
                    <h5>Recommendations:</h5>
                    <ul>
                        <li>Plant maize in well-drained fields</li>
                        <li>Apply fertilizer 2 weeks after planting</li>
                        <li>Expected rainfall: 50-70mm this season</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- SMS Notification Section -->
        <div class="card" id="sms-section">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-sms me-2"></i> Send SMS to Farmers</h5>
            </div>
            <div class="card-body">
                <?php if (isset($sms_status)): ?>
                    <div class="alert alert-<?= strpos($sms_status, 'successfully') !== false ? 'success' : 'danger' ?>">
                        <?= htmlspecialchars($sms_status) ?>
                    </div>
                <?php endif; ?>
                
                <form action="officer.php" method="POST">
                    <div class="mb-3">
                        <label for="farmer_id" class="form-label">Select Farmer</label>
                        <select class="form-select" id="farmer_id" name="farmer_id" required>
                            <option value="" selected disabled>Select a farmer</option>
                            <?php while ($farmer = $farmers->fetch_assoc()): ?>
                                <option value="<?= $farmer['id'] ?>">
                                    <?= htmlspecialchars($farmer['name']) ?> (<?= htmlspecialchars($farmer['username']) ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required placeholder="Enter your message here..."></textarea>
                    </div>
                    <button type="submit" name="send_sms" class="btn btn-success">
                        <i class="fas fa-paper-plane me-2"></i> Send SMS
                    </button>
                </form>
                
                <div class="mt-4">
                    <h5>Sample SMS Templates:</h5>
                    <div class="list-group">
                        <button class="list-group-item list-group-item-action template-btn" data-template="Plant maize this season. Expected rainfall is good for high yield.">
                            Crop Suggestion
                        </button>
                        <button class="list-group-item list-group-item-action template-btn" data-template="Heavy rainfall expected next week. Prepare your fields accordingly.">
                            Weather Alert
                        </button>
                        <button class="list-group-item list-group-item-action template-btn" data-template="Visit the agricultural office for free fertilizer samples this week.">
                            Announcement
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
        
        // Template buttons for SMS
        document.querySelectorAll('.template-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('message').value = this.dataset.template;
            });
        });
        
        // Print functionality
        function printContent() {
            window.print();
        }
    </script>
</body>
</html>