<?php
// Detect if we're on localhost or production
$is_localhost = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1' || strpos($_SERVER['HTTP_HOST'], 'localhost:') === 0);

if ($is_localhost) {
    // Local database configuration
    $host = 'localhost';
    $dbname = 'stepintomanila';
    $username = 'root';
    $password = '';
} else {
    // Hostinger database configuration
    $host = 'localhost';
    $dbname = 'u134427490_StepIntoManila';
    $username = 'u134427490_girlies';
    $password = 'Girlies%_123';
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Log the error for debugging
    error_log("Database connection failed: " . $e->getMessage());
    
    // Set $pdo to null to allow graceful degradation
    $pdo = null;
    
    // For development, you might want to show the error
    if (isset($_GET['debug']) && $_GET['debug'] === '1') {
        echo "Database connection error: " . $e->getMessage();
    }
} 