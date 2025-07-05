<?php
require_once 'includes/db_connect.php';

echo "<h2>Testing Database Connection and Experience Data</h2>";

if ($pdo) {
    echo "<p style='color: green;'>✓ Database connection successful!</p>";
    
    try {
        $stmt = $pdo->query('SELECT * FROM Experiences ORDER BY experience_id DESC LIMIT 5');
        $experiences = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<p>Found " . count($experiences) . " experiences in database:</p>";
        
        foreach ($experiences as $exp) {
            echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
            echo "<strong>ID:</strong> " . $exp['experience_id'] . "<br>";
            echo "<strong>Title:</strong> " . $exp['title'] . "<br>";
            echo "<strong>Location:</strong> " . $exp['location'] . "<br>";
            echo "<strong>Price:</strong> ₱" . number_format($exp['price'], 2) . "<br>";
            echo "<strong>Category:</strong> " . $exp['category'] . "<br>";
            echo "<strong>Image:</strong> " . $exp['image_url'] . "<br>";
            echo "<a href='pages/view-test.php?id=" . $exp['experience_id'] . "'>View Details</a>";
            echo "</div>";
        }
        
    } catch (PDOException $e) {
        echo "<p style='color: red;'>✗ Database error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Database connection failed!</p>";
}
?> 