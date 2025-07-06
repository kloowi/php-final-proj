<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Connection Test</h1>";

// Test the hostinger database connection
echo "<h2>Testing Hostinger Database Connection</h2>";
require_once 'includes/db_connect-hostinger.php';

if ($pdo) {
    echo "<p style='color: green;'>✓ Database connection successful!</p>";
    
    // Test a simple query
    try {
        $stmt = $pdo->query('SELECT COUNT(*) as count FROM Experiences');
        $result = $stmt->fetch();
        echo "<p>✓ Found " . $result['count'] . " experiences in the database.</p>";
        
        // Test if the Experiences table exists and show its structure
        $stmt = $pdo->query('DESCRIBE Experiences');
        $columns = $stmt->fetchAll();
        echo "<h3>Experiences Table Structure:</h3>";
        echo "<ul>";
        foreach ($columns as $column) {
            echo "<li><strong>" . $column['Field'] . "</strong> - " . $column['Type'] . "</li>";
        }
        echo "</ul>";
        
    } catch (PDOException $e) {
        echo "<p style='color: red;'>✗ Query error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Database connection failed!</p>";
    echo "<p>Check your database credentials and make sure the database exists.</p>";
}

// Test the local database connection
echo "<h2>Testing Local Database Connection</h2>";
require_once 'includes/db_connect.php';

if ($pdo) {
    echo "<p style='color: green;'>✓ Local database connection successful!</p>";
} else {
    echo "<p style='color: red;'>✗ Local database connection failed!</p>";
}

echo "<h2>PHP Information</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>PDO Available: " . (extension_loaded('pdo') ? 'Yes' : 'No') . "</p>";
echo "<p>PDO MySQL Available: " . (extension_loaded('pdo_mysql') ? 'Yes' : 'No') . "</p>";

echo "<h2>Server Information</h2>";
echo "<p>Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script Name: " . $_SERVER['SCRIPT_NAME'] . "</p>";
?> 