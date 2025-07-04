<?php
$host = 'localhost';
$dbname = 'stepintomanila';
$username = 'root';
$password = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    echo 'Connection successful!';
} catch(PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
} 