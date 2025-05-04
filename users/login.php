<?php
session_start();
require_once '../db.php';

// Initialize variables
$error = '';
$success = '';

// Check for brute force attempts
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_login_attempt'] = 0;
}

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for brute force
    if ($_SESSION['login_attempts'] >= 5 && (time() - $_SESSION['last_login_attempt']) < 300) {
        $error = "Too many login attempts. Please try again in 5 minutes.";
    } else {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $error = "Invalid form submission";
        } else {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Basic validation
            if (empty($username) || empty($password)) {
                $error = 'Both username and password are required';
                $_SESSION['login_attempts']++;
                $_SESSION['last_login_attempt'] = time();
            } else {
                try {
                    // Get user from database using prepared statement
                    $stmt = $conn->prepare("SELECT id, username, password, role, name FROM users WHERE username = ? LIMIT 1");
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows === 1) {
                        $user = $result->fetch_assoc();
                        
                        // Verify password with constant time comparison
                        if (password_verify($password, $user['password'])) {
                            // Regenerate session ID
                            session_regenerate_id(true);
                            
                            // Set session variables
                            $_SESSION = [
                                'user_id' => $user['id'],
                                'username' => $user['username'],
                                'role' => $user['role'],
                                'name' => $user['name'],
                                'logged_in' => true,
                                'last_activity' => time()
                            ];
                            
                            // Reset login attempts
                            $_SESSION['login_attempts'] = 0;
                            
                            // Redirect based on role
                            $redirect = match($user['role']) {
                                'admin' => '../dashboard/admin_dashboard.php',
                                'officer' => '../dashboard/officer_dashboard.php',
                                'farmer' => '../dashboard/farmer_dashboard.php',
                            };
                            
                            header("Location: $redirect");
                            exit();
                        } else {
                            $error = 'Invalid username or password';
                            $_SESSION['login_attempts']++;
                            $_SESSION['last_login_attempt'] = time();
                        }
                    } else {
                        $error = 'Invalid username or password';
                        $_SESSION['login_attempts']++;
                        $_SESSION['last_login_attempt'] = time();
                    }
                    
                    $stmt->close();
                } catch (Exception $e) {
                    error_log("Login error: " . $e->getMessage());
                    $error = 'A system error occurred. Please try again.';
                }
            }
        }
    }
}

// Generate CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Check for registration success
if (isset($_GET['success'])) {
    if ($_GET['success'] === 'registered') {
        $success = 'Registration successful! Please log in.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FASTS</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/bootstrap/css/style.css">
    <style>
        .password-toggle { cursor: pointer; }
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                        url('../assets/images/backgrounds/login.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
        }
        .alert { transition: opacity 0.5s ease; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center text-success mb-4">
                            <i class="fas fa-leaf me-2"></i>FASTS Login
                        </h2>
                        
                        <!-- Success Message -->
                        <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= htmlspecialchars($success) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Error Message -->
                        <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <form id="loginForm" action="login.php" method="POST" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                            <div class="mb-3">
                                <label for="username" class="form-label fw-bold">Username</label>
                                <input type="text" class="form-control form-control-lg" id="username" 
                                       name="username" required placeholder="Enter your username"
                                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                                <div class="invalid-feedback">Please enter your username</div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg" id="password" 
                                           name="password" required placeholder="Enter your password">
                                    <button class="btn btn-outline-secondary password-toggle" type="button">
                                        <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">Please enter your password</div>
                            </div>
                            
                            <button type="submit" class="btn btn-success btn-lg w-100 py-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                            
                            <div class="mt-3 text-center">
                                <a href="register.php" class="text-decoration-none">
                                    Don't have an account? Register here
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password visibility toggle
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');
            
            document.querySelector('.password-toggle').addEventListener('click', function() {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                toggleIcon.classList.toggle('bi-eye');
                toggleIcon.classList.toggle('bi-eye-slash');
            });
            
            // Form validation
            const form = document.getElementById('loginForm');
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
            
            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</body>
</html>