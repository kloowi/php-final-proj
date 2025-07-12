<!DOCTYPE html>
<?php include '../includes/header-index.php'; ?>
<?php
require_once '../includes/db_connect-hostinger.php';

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

// Group experiences by category
$categorized_experiences = [
    'Historical & Cultural Sites' => [],
    'Food & Market Experiences' => [],
    'Nature & Scenic Spots' => []
];

foreach ($experiences as $experience) {
    $category = $experience['category'] ?? 'Historical & Cultural Sites'; // Default category
    
    // Map database categories to display categories
    if (stripos($category, 'food') !== false || stripos($category, 'market') !== false || stripos($category, 'chinatown') !== false) {
        $categorized_experiences['Food & Market Experiences'][] = $experience;
    } elseif (stripos($category, 'nature') !== false || stripos($category, 'park') !== false || stripos($category, 'scenic') !== false || stripos($category, 'bay') !== false) {
        $categorized_experiences['Nature & Scenic Spots'][] = $experience;
    } else {
        $categorized_experiences['Historical & Cultural Sites'][] = $experience;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Explore Manila</title>
    <link rel="stylesheet" href="../assets/css/cards.css">
</head>
<body>
    <div class="container">
        <?php foreach ($categorized_experiences as $category_title => $category_experiences): ?>
            <?php if (!empty($category_experiences)): ?>
                <h2 class="section-title" style="margin-top: 40px; margin-bottom: 20px;"><?php echo htmlspecialchars($category_title); ?></h2>
                <div class="card-grid">
                    <?php foreach ($category_experiences as $experience): ?>
                        <?php
                        // Handle image path - if it's a relative path, add the parent directory
                        $image_path = $experience['image_url'];
                        if ($image_path && !preg_match('/^https?:\/\//', $image_path)) {
                            $image_path = '../' . $image_path;
                        }
                        
                        // Fallback image if no image is set
                        if (empty($image_path)) {
                            $image_path = '../assets/images/experience_1.jpg';
                        }
                        ?>
                        <a href="view_experience.php?id=<?php echo $experience['experience_id']; ?>" style="text-decoration: none; color: inherit;">
                            <div class="card">
                                <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($experience['title']); ?>" class="card-img">
                                <div class="card-content">
                                    <div class="card-text-group">
                                        <div>
                                            <h3 class="card-title"><?php echo htmlspecialchars($experience['title']); ?></h3>
                                            <p class="card-subtitle"><?php echo htmlspecialchars($experience['location']); ?> ★4.5</p>
                                        </div>
                                        <div class="card-price">₱<?php echo number_format($experience['price'], 2); ?></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

<?php include '../includes/footer.php'; ?>
</body>
</html>



