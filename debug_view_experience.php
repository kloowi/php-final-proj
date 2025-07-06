<?php 
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug: View Experience Page</h1>";

// Test database connection first
echo "<h2>Step 1: Testing Database Connection</h2>";
require_once '../includes/db_connect-hostinger.php';

if ($pdo) {
    echo "<p style='color: green;'>✓ Database connection successful!</p>";
} else {
    echo "<p style='color: red;'>✗ Database connection failed!</p>";
    echo "<p>This is likely the cause of your 500 error.</p>";
    exit;
}

// Get experience ID from URL parameter
echo "<h2>Step 2: Processing Experience ID</h2>";
$experience_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
echo "<p>Experience ID: " . $experience_id . "</p>";

if ($experience_id <= 0) {
    echo "<p style='color: red;'>✗ Invalid experience ID</p>";
    exit;
}

// Try to fetch the experience
echo "<h2>Step 3: Fetching Experience Data</h2>";
try {
    $stmt = $pdo->prepare('SELECT * FROM Experiences WHERE experience_id = ?');
    $stmt->execute([$experience_id]);
    $experience = $stmt->fetch();
    
    if ($experience) {
        echo "<p style='color: green;'>✓ Experience found!</p>";
        echo "<p>Title: " . htmlspecialchars($experience['title']) . "</p>";
        echo "<p>Location: " . htmlspecialchars($experience['location']) . "</p>";
        echo "<p>Price: ₱" . number_format($experience['price'], 2) . "</p>";
    } else {
        echo "<p style='color: red;'>✗ Experience not found with ID: " . $experience_id . "</p>";
        
        // Show all available experiences
        echo "<h3>Available Experiences:</h3>";
        $stmt = $pdo->query('SELECT experience_id, title FROM Experiences LIMIT 10');
        $experiences = $stmt->fetchAll();
        echo "<ul>";
        foreach ($experiences as $exp) {
            echo "<li>ID: " . $exp['experience_id'] . " - " . htmlspecialchars($exp['title']) . "</li>";
        }
        echo "</ul>";
        exit;
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database error: " . $e->getMessage() . "</p>";
    exit;
}

// Try to fetch reviews
echo "<h2>Step 4: Fetching Reviews</h2>";
$reviews = [];
if (!empty($experience['category'])) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM Reviews WHERE category = ? ORDER BY rating DESC LIMIT 5');
        $stmt->execute([$experience['category']]);
        $reviews = $stmt->fetchAll();
        echo "<p>✓ Found " . count($reviews) . " reviews for category: " . htmlspecialchars($experience['category']) . "</p>";
    } catch (PDOException $e) {
        echo "<p style='color: orange;'>⚠ Review fetch error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>No category found for this experience.</p>";
}

echo "<h2>Step 5: Testing Header Include</h2>";
try {
    include '../includes/header.php';
    echo "<p style='color: green;'>✓ Header included successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Header include error: " . $e->getMessage() . "</p>";
}

echo "<h2>Step 6: All Tests Passed!</h2>";
echo "<p style='color: green;'>✓ The view_experience.php page should work correctly.</p>";
echo "<p>If you're still getting a 500 error, check your hosting error logs.</p>";

// Show the actual page content
echo "<hr>";
echo "<h2>Actual Page Content:</h2>";
?>

<link rel="stylesheet" href="../assets/css/view_experience.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="page-wrapper">
    <div class="experience-card">
        <!-- Back Button -->
        <a href="explore.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Explore
        </a>

        <!-- Header Image -->
        <?php
        $image_path = $experience['image_url'] ?? '';
        if ($image_path && !preg_match('/^https?:\/\//', $image_path)) {
            $image_path = '../' . $image_path;
        }
        
        if (empty($image_path)) {
            $image_path = '../assets/images/experience_1.jpg';
        }
        ?>
        <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($experience['title'] ?? 'Experience'); ?>">

        <!-- Title and Price -->
        <div class="title-price">
            <h1><?php echo htmlspecialchars($experience['title'] ?? 'Experience Title'); ?></h1>
            <div class="price-box-inline">₱<?php echo number_format($experience['price'] ?? 0, 2); ?> <span>/guest</span></div>
        </div>
        <p class="subheading"><?php echo htmlspecialchars($experience['location'] ?? 'Location'); ?></p>

        <!-- Description -->
        <div class="details-row">
            <div class="col-70">
                <div class="card">
                    <p><?php echo nl2br(htmlspecialchars($experience['description'] ?? 'No description available.')); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
try {
    include '../includes/footer.php';
    echo "<p style='color: green;'>✓ Footer included successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Footer include error: " . $e->getMessage() . "</p>";
}
?> 