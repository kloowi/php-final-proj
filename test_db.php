<?php
require_once 'includes/db_connect.php';

echo "<h2>Database Connection Test</h2>";

try {
    // Test connection
    echo "<p>✅ Database connection successful</p>";
    
    // Check if Users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'Users'");
    if ($stmt->rowCount() > 0) {
        echo "<p>✅ Users table exists</p>";
        
        // Show table structure
        $stmt = $pdo->query("DESCRIBE Users");
        echo "<h3>Users Table Structure:</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Check if table has any data
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM Users");
        $result = $stmt->fetch();
        echo "<p>Users in database: " . $result['count'] . "</p>";
        
    } else {
        echo "<p>❌ Users table does not exist</p>";
    }
    
    // Check if Admin table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'Admin'");
    if ($stmt->rowCount() > 0) {
        echo "<p>✅ Admin table exists</p>";
    } else {
        echo "<p>❌ Admin table does not exist</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
}
?> 