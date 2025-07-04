
<?php
require_once '../includes/db_connect.php';


$experiences = [];
if ($pdo) {
    try {
        $stmt = $pdo->query('SELECT * FROM Experiences ORDER BY experience_id DESC');
        $experiences = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle error silently or log it
        error_log("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Historical & Cultural Sites</title>
    <link rel="stylesheet" href="../assets/css/cards.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container">
        <h2 class="section-title">&gt; Historical & Cultural Sites</h2>
        <div class="card-grid">
            <?php
                if (!empty($experiences)) {
                    foreach ($experiences as $experience) {
                        // Handle image path - if it's a relative path, add the parent directory
                        $image_path = $experience['image_url'];
                        if ($image_path && !preg_match('/^https?:\/\//', $image_path)) {
                            $image_path = '../' . $image_path;
                        }
                        
                        // Fallback image if no image is set
                        if (empty($image_path)) {
                            $image_path = '../assets/images/experience_1.jpg';
                        }
                        
                        echo '
                        <div class="card">
                            <img src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($experience['title']) . '" class="card-img">
                            <div class="card-content">
                                <div class="card-text-group">
                                    <div>
                                        <h3 class="card-title">' . htmlspecialchars($experience['title']) . '</h3>
                                        <p class="card-subtitle">' . htmlspecialchars($experience['location']) . ' ★4.5</p>
                                    </div>
                                    <div class="card-price">₱' . number_format($experience['price'], 2) . '</div>
                                </div>
                            </div>
                        </div>';

                    }
                } 
            ?>
        </div>
    </div>
</body>
</html>



