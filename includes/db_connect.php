<?php
$host = 'localhost';
$dbname = 'stepintomanila';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // If the database does not exist, set $pdo to null and allow the app to continue
    $pdo = null;
    // Optionally, you can log the error or set a flag
    // error_log("Database connection failed: " . $e->getMessage());
} 