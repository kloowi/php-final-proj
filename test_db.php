<?php
$host = 'localhost';
$dbname = 'manila_experiences';
$username = 'root';
$password = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    echo 'Connection successful!';
} catch(PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
} 