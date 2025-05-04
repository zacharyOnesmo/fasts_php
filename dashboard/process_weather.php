<?php
require_once '../db.php'; // Include database connection

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to get weather data from API
function getWeatherData($latitude, $longitude) {
    $apiKey = 'YOUR_OPENWEATHERMAP_API_KEY'; // Replace with your actual API key
    $url = "https://api.openweathermap.org/data/2.5/weather?lat=$latitude&lon=$longitude&appid=$apiKey&units=metric";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Function to categorize rainfall
function categorizeRainfall($rainfall) {
    if ($rainfall > 50) return 'high';
    if ($rainfall > 20) return 'medium';
    return 'low';
}

// Function to categorize temperature
function categorizeTemperature($temp) {
    if ($temp > 30) return 'high';
    if ($temp > 20) return 'medium';
    return 'low';
}

$suggested_crops = [];
$weather_data = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $rainfall = strtolower($_POST['rainfall'] ?? '');
    $temperature = strtolower($_POST['temperature'] ?? '');
    $season = strtolower($_POST['season'] ?? '');
    $soil_type = strtolower($_POST['soil_type'] ?? '');

    // Try to get real weather data if available
    if (isset($_POST['use_current_weather']) && $_POST['use_current_weather'] === 'on') {
        if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
            $weather_data = getWeatherData($_POST['latitude'], $_POST['longitude']);
            
            if ($weather_data && isset($weather_data['rain']['1h'])) {
                $rainfall = categorizeRainfall($weather_data['rain']['1h']);
            }
            
            if ($weather_data && isset($weather_data['main']['temp'])) {
                $temperature = categorizeTemperature($weather_data['main']['temp']);
            }
        }
    }

    // Query database for crop suggestions based on parameters
    $stmt = $conn->prepare("
        SELECT c.crop_name 
        FROM crops c
        JOIN seasons s ON c.season_id = s.id
        WHERE 
            (s.season_name LIKE ? OR ? = '')
            AND (c.rainfall_level = ? OR ? = '')
            AND (c.temperature_level = ? OR ? = '')
            AND (c.soil_type = ? OR ? = '')
    ");
    
    $season_param = "%$season%";
    $stmt->bind_param("ssssssss", 
        $season_param, $season,
        $rainfall, $rainfall,
        $temperature, $temperature,
        $soil_type, $soil_type
    );
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $suggested_crops[] = $row['crop_name'];
    }
    
    $stmt->close();

    // Fallback suggestions if no crops match
    if (empty($suggested_crops)) {
        // Basic AI-based fallback logic
        if ($rainfall === 'high' && $temperature === 'medium') {
            $suggested_crops[] = 'Rice';
            $suggested_crops[] = 'Maize';
        } elseif ($rainfall === 'low' && $temperature === 'high') {
            $suggested_crops[] = 'Millet';
            $suggested_crops[] = 'Sorghum';
        } elseif ($season === 'january' || $season === 'february') {
            $suggested_crops[] = 'Wheat';
            $suggested_crops[] = 'Barley';
        } else {
            $suggested_crops[] = 'No specific crops found. Consider consulting an agricultural expert.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crop Suggestions</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .crop-card {
            border-left: 4px solid #28a745;
            transition: transform 0.3s;
        }
        .crop-card:hover {
            transform: translateY(-5px);
        }
        .weather-icon {
            font-size: 3rem;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <h1 class="text-success mb-4">
                            <i class="fas fa-seedling"></i> Crop Suggestions
                        </h1>
                        
                        <?php if ($weather_data): ?>
                            <div class="alert alert-info">
                                <h5><i class="fas fa-cloud-sun"></i> Current Weather Data Used</h5>
                                <p>
                                    Temperature: <?= $weather_data['main']['temp'] ?>°C | 
                                    Conditions: <?= $weather_data['weather'][0]['description'] ?> | 
                                    Rainfall: <?= $weather_data['rain']['1h'] ?? '0' ?>mm
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card mb-3">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Based on Your Input</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Rainfall:</strong> <?= ucfirst($rainfall) ?></p>
                                        <p><strong>Temperature:</strong> <?= ucfirst($temperature) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Season:</strong> <?= ucfirst($season) ?></p>
                                        <p><strong>Soil Type:</strong> <?= ucfirst($soil_type) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($suggested_crops)): ?>
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0">Recommended Crops</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php foreach ($suggested_crops as $crop): ?>
                                            <div class="col-md-6 mb-3">
                                                <div class="card crop-card h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-3 text-success">
                                                                <i class="fas fa-leaf fa-2x"></i>
                                                            </div>
                                                            <div>
                                                                <h5 class="mb-0"><?= htmlspecialchars($crop) ?></h5>
                                                                <small class="text-muted">High suitability for your conditions</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <h5><i class="fas fa-exclamation-triangle"></i> No Suitable Crops Found</h5>
                                <p>We couldn't find crops matching your exact conditions. Please try adjusting your parameters or consult with an agricultural officer.</p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-4">
                            <a href="farmer_dashboard.php" class="btn btn-success">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>