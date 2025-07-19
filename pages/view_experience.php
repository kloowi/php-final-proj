<?php include '../includes/header.php'; ?>

<?php 
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../includes/db_config.php';

// Get experience ID from URL parameter with proper validation
$experience_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Validate experience_id
if ($experience_id <= 0) {
    // Log the invalid ID
    error_log("Invalid experience ID: " . $_GET['id'] ?? 'not set');
    
    // Redirect to explore page
    header('Location: explore.php');
    exit;
}

$experience = null;
$error_message = '';

if ($pdo) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM Experiences WHERE experience_id = ?');
        $stmt->execute([$experience_id]);
        $experience = $stmt->fetch();
        
        if (!$experience) {
            error_log("Experience not found with ID: " . $experience_id);
            $error_message = "Experience not found.";
        }
    } catch (PDOException $e) {
        error_log("Database error in view_experience.php: " . $e->getMessage());
        $error_message = "Database error occurred.";
        
        // For debugging, show the error if debug parameter is set
        if (isset($_GET['debug']) && $_GET['debug'] === '1') {
            echo "Database error: " . $e->getMessage();
            exit;
        }
    }
} else {
    $error_message = "Database connection failed.";
    error_log("Database connection is null in view_experience.php");
}

// If no experience found or database error, redirect to explore page
if (!$experience) {
    header('Location: explore.php');
    exit;
}

// Fetch reviews for this experience's category
$reviews = [];
if (!empty($experience['category']) && $pdo) {
    try {
        // Debug: Log the experience category
        error_log("Experience category: " . $experience['category']);
        
        // Create a mapping of experience categories to review categories
        $category_mapping = [
            // Historical categories
            'history' => ['history', 'historical', 'historical & cultural sites', 'cultural', 'heritage'],
            'historical' => ['history', 'historical', 'historical & cultural sites', 'cultural', 'heritage'],
            'heritage' => ['history', 'historical', 'historical & cultural sites', 'cultural', 'heritage'],
            'cultural' => ['history', 'historical', 'historical & cultural sites', 'cultural', 'heritage'],
            
            // Food categories
            'food' => ['food', 'food & market experiences', 'cuisine', 'dining', 'market'],
            'cuisine' => ['food', 'food & market experiences', 'cuisine', 'dining', 'market'],
            'dining' => ['food', 'food & market experiences', 'cuisine', 'dining', 'market'],
            'market' => ['food', 'food & market experiences', 'cuisine', 'dining', 'market'],
            
            // Nature categories
            'nature' => ['nature', 'nature & scenic spots', 'scenic', 'park', 'outdoor'],
            'scenic' => ['nature', 'nature & scenic spots', 'scenic', 'park', 'outdoor'],
            'park' => ['nature', 'nature & scenic spots', 'scenic', 'park', 'outdoor'],
            'outdoor' => ['nature', 'nature & scenic spots', 'scenic', 'park', 'outdoor']
        ];
        
        $exp_category = strtolower(trim($experience['category']));
        $matching_categories = [];
        
        // Find matching categories
        foreach ($category_mapping as $key => $values) {
            if (stripos($exp_category, $key) !== false) {
                $matching_categories = array_merge($matching_categories, $values);
            }
        }
        
        // If no specific mapping found, try the original category and common variations
        if (empty($matching_categories)) {
            $matching_categories = [$exp_category, $experience['category']];
            
            // Add common variations
            if (stripos($exp_category, 'history') !== false) {
                $matching_categories = array_merge($matching_categories, ['history', 'historical', 'historical & cultural sites']);
            }
            if (stripos($exp_category, 'food') !== false) {
                $matching_categories = array_merge($matching_categories, ['food', 'food & market experiences']);
            }
            if (stripos($exp_category, 'nature') !== false) {
                $matching_categories = array_merge($matching_categories, ['nature', 'nature & scenic spots']);
            }
        }
        
        // Remove duplicates and create placeholders for SQL
        $matching_categories = array_unique($matching_categories);
        $placeholders = str_repeat('?,', count($matching_categories) - 1) . '?';
        
        // Query with multiple category matches
        $stmt = $pdo->prepare("SELECT * FROM Reviews WHERE LOWER(category) IN ($placeholders) ORDER BY rating DESC LIMIT 10");
        $stmt->execute($matching_categories);
        $reviews = $stmt->fetchAll();
        
        // If still no reviews found, show some general reviews as fallback
        if (empty($reviews)) {
            $stmt = $pdo->prepare("SELECT * FROM Reviews ORDER BY rating DESC LIMIT 5");
            $stmt->execute();
            $reviews = $stmt->fetchAll();
        }
        
        // Debug: Log the matching process
        error_log("Experience category: " . $experience['category']);
        error_log("Matching categories: " . implode(', ', $matching_categories));
        error_log("Found " . count($reviews) . " reviews");
        
    } catch (PDOException $e) {
        error_log("Error fetching reviews: " . $e->getMessage());
        // Continue without reviews rather than failing completely
    }
}

?>

<head>
    <meta charset="UTF-8">
    <title>StepIntoManila</title>
    <link rel="stylesheet" href="../assets/css/view_experience.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../assets/images/logo/blue-logo.png">
</head>

<div class="page-wrapper">
    <div class="experience-card">

        <!-- Back Button -->
        <a href="explore.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Explore
        </a>

        <!-- Header Image -->
        <?php
        // Handle image path - if it's a relative path, add the parent directory
        $image_path = $experience['image_url'] ?? '';
        if ($image_path && !preg_match('/^https?:\/\//', $image_path)) {
            $image_path = '../' . $image_path;
        }
        
        // Fallback image if no image is set
        if (empty($image_path)) {
            $image_path = '../assets/images/experience_1.jpg';
        }
        ?>
        <div class="image-container">
            <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($experience['title'] ?? 'Experience'); ?>" id="experience-image">
            <button class="enlarge-btn" onclick="openImageModal()">
                <i class="fas fa-expand"></i>
            </button>
        </div>

        <!-- Title and Price -->
        <div class="title-price">
            <h1><?php echo htmlspecialchars($experience['title'] ?? 'Experience Title'); ?></h1>
            <div class="price-box-inline">₱<?php echo number_format($experience['price'] ?? 0, 2); ?> <span>/guest</span></div>
        </div>
        <p class="subheading"><?php echo htmlspecialchars($experience['location'] ?? 'Location'); ?></p>

        <!-- Side-by-side Layout -->
        <div class="details-row">
            <div class="col-70">
                <div class="card">
                    <p><?php echo nl2br(htmlspecialchars($experience['description'] ?? 'No description available.')); ?></p>
                </div>

                <!-- Accordion Section -->
                <button class="accordion-toggle">
                    What to Expect <i class="fas fa-chevron-right arrow"></i>
                </button>
                <div class="accordion-content">
                    <p>Expect a guided walking tour filled with historical insights, engaging stories, and photo-worthy views.</p>
                </div>

                <button class="accordion-toggle">
                    What's Included <i class="fas fa-chevron-right arrow"></i>
                </button>
                <div class="accordion-content">
                    <p>Admission, guided walk, museum access, and complimentary bottled water.</p>
                </div>

                <button class="accordion-toggle">
                    Before You Book <i class="fas fa-chevron-right arrow"></i>
                </button>
                <div class="accordion-content">
                    <p>Please wear comfortable walking shoes and bring sun protection. Tours run rain or shine.</p>
                </div>

                <button class="accordion-toggle">
                    Terms & Conditions <i class="fas fa-chevron-right arrow"></i>
                </button>
                <div class="accordion-content">
                    <p>Non-refundable. Rescheduling allowed 24 hours before start. Late arrivals may forfeit the slot.</p>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-30">
                <div class="reviews modern-reviews">
                    <h3 class="reviews-title">Guest Reviews</h3>
                    <?php if (isset($_GET['debug']) && $_GET['debug'] === '1'): ?>
                        <div style="background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 5px;">
                            <strong>Debug Info:</strong><br>
                            Experience Category: <?php echo htmlspecialchars($experience['category'] ?? 'NULL'); ?><br>
                            Experience ID: <?php echo $experience_id; ?><br>
                            Reviews Found: <?php echo count($reviews); ?><br>
                            <?php if (!empty($reviews)): ?>
                                Review Categories: <?php echo implode(', ', array_unique(array_column($reviews, 'category'))); ?><br>
                                Total Reviews in DB: <?php 
                                    $stmt = $pdo->query('SELECT COUNT(*) FROM Reviews');
                                    echo $stmt->fetchColumn();
                                ?>
                            <?php else: ?>
                                <br>All Review Categories in DB: <?php 
                                    $stmt = $pdo->query('SELECT DISTINCT category FROM Reviews');
                                    $all_categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
                                    echo implode(', ', $all_categories);
                                ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="reviews-list">
                    <?php if (!empty($reviews)): ?>
                        <?php 
                        // Check if these are fallback reviews (not matching the experience category)
                        $is_fallback = false;
                        if (!empty($experience['category'])) {
                            $exp_category_lower = strtolower($experience['category']);
                            $review_categories = array_unique(array_column($reviews, 'category'));
                            $has_matching_category = false;
                            foreach ($review_categories as $rev_cat) {
                                if (stripos($exp_category_lower, strtolower($rev_cat)) !== false || 
                                    stripos(strtolower($rev_cat), $exp_category_lower) !== false) {
                                    $has_matching_category = true;
                                    break;
                                }
                            }
                            $is_fallback = !$has_matching_category;
                        }
                        
                        if ($is_fallback): ?>
                            <p class="no-reviews" style="color: #666; font-style: italic; margin-bottom: 15px;">
                                Showing general reviews while we gather more feedback for this experience.
                            </p>
                        <?php endif; ?>
                        
                        <?php 
                        // Group reviews into pairs for 2-column layout
                        $review_pairs = array_chunk($reviews, 2);
                        foreach ($review_pairs as $pair): ?>
                            <div class="review-row">
                                <?php foreach ($pair as $review): ?>
                                    <div class="review-card">
                                        <div class="review-avatar">
                                            <img src="../assets/images/review-icon.png" alt="Reviewer avatar">
                                        </div>
                                        <div class="review-main">
                                            <div class="review-header">
                                                <span class="review-username"><?php echo htmlspecialchars($review['username'] ?? 'Anonymous'); ?></span>
                                                <?php if (!empty($review['location'])): ?>
                                                    <span class="review-location"><?php echo htmlspecialchars($review['location']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="review-rating-row">
                                                <span class="review-rating">
                                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                                        <i class="fa-star<?php echo $i < ($review['rating'] ?? 0) ? ' fa-solid' : ' fa-regular'; ?>" style="color:#f5b50a;"></i>
                                                    <?php endfor; ?>
                                                </span>
                                            </div>
                                            <div class="review-body">
                                                <p><?php echo htmlspecialchars($review['description'] ?? 'No review text.'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-reviews">No reviews for this category yet.</p>
                    <?php endif; ?>
                    </div>
                </div>

                <?php if ($isLoggedIn): ?>
                    <a href="guest_details.php?experience_id=<?php echo $experience_id; ?>&title=<?php echo urlencode($experience['title'] ?? ''); ?>&price=<?php echo $experience['price'] ?? 0; ?>" class="book-btn">Book Now!</a>
                <?php else: ?>
                    <a href="login.php?redirect=<?php echo urlencode('guest_details.php?experience_id=' . $experience_id . '&title=' . urlencode($experience['title'] ?? '') . '&price=' . ($experience['price'] ?? 0)); ?>" class="book-btn">Book Now!</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Accordion JS -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll(".accordion-toggle");

    buttons.forEach(button => {
        button.addEventListener("click", () => {
            const content = button.nextElementSibling;
            const isOpen = content.style.display === "block";

            // Close all
            document.querySelectorAll(".accordion-content").forEach(c => c.style.display = "none");
            buttons.forEach(b => b.classList.remove("open"));

            if (!isOpen) {
                content.style.display = "block";
                button.classList.add("open");
            }
        });
    });
});
</script>

<!-- Image Modal -->
<div id="imageModal" class="image-modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeImageModal()">&times;</span>
        <img id="modalImage" src="" alt="Enlarged image">
    </div>
</div>

<!-- Image Modal JavaScript -->
<script>
function openImageModal() {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const experienceImg = document.getElementById('experience-image');
    
    modalImg.src = experienceImg.src;
    modalImg.alt = experienceImg.alt;
    modal.style.display = 'flex';
    
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside the image
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>

<?php include '../includes/footer.php'; ?>
