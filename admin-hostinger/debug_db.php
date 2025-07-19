<?php
require_once '../includes/db_config.php';

echo "<h2>Database Debug Information</h2>";

// Check database connection
if (!$pdo) {
    echo "<p style='color: red;'>❌ Database connection failed!</p>";
    exit;
} else {
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
}

// Check what tables exist
try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Available Tables:</h3>";
    if (empty($tables)) {
        echo "<p style='color: red;'>❌ No tables found in database!</p>";
    } else {
        echo "<ul>";
        foreach ($tables as $table) {
            $status = ($table === 'Reviews') ? "✅" : "📋";
            echo "<li>$status $table</li>";
        }
        echo "</ul>";
    }
    
    // Check Reviews table specifically
    if (in_array('Reviews', $tables)) {
        echo "<h3>Reviews Table Structure:</h3>";
        $stmt = $pdo->query("DESCRIBE Reviews");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>{$column['Field']}</td>";
            echo "<td>{$column['Type']}</td>";
            echo "<td>{$column['Null']}</td>";
            echo "<td>{$column['Key']}</td>";
            echo "<td>{$column['Default']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Check if there are any reviews
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM Reviews");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "<p>Total reviews in database: $count</p>";
    } else {
        echo "<p style='color: red;'>❌ Reviews table does not exist!</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error checking tables: " . $e->getMessage() . "</p>";
}
?> 