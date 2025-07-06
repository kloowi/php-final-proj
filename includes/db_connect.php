<?php
$host = 'localhost';
$dbname = 'stepintomanila';
$username = 'root';
$password = '';

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