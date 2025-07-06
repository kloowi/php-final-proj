<?php 
require_once '../includes/db_connect.php';

// Get experience ID from URL parameter
$experience_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$experience = null;
if ($pdo && $experience_id > 0) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM Experiences WHERE experience_id = ?');
        $stmt->execute([$experience_id]);
        $experience = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}

// If no experience found, redirect to explore page or show error
if (!$experience) {
    header('Location: explore-test.php');
    exit;
}

// Fetch reviews for this experience's category
$reviews = [];
if (!empty($experience['category'])) {
    $stmt = $pdo->prepare('SELECT * FROM Reviews WHERE category = ? ORDER BY rating DESC');
    $stmt->execute([$experience['category']]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

include '../includes/header.php'; 
?>

<link rel="stylesheet" href="../assets/css/view_experience.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="page-wrapper">
    <div class="experience-card">


        <!-- Back Button -->
        <a href="explore-testlocal.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Explore
        </a>

        <!-- Header Image -->
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
        <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($experience['title']); ?>">

        <!-- Title and Price -->
        <div class="title-price">
            <h1><?php echo htmlspecialchars($experience['title']); ?></h1>
            <div class="price-box-inline">₱<?php echo number_format($experience['price'], 2); ?> <span>/guest</span></div>
        </div>
        <p class="subheading"><?php echo htmlspecialchars($experience['location']); ?></p>

        <!-- Side-by-side Layout -->
        <div class="details-row">
            <div class="col-70">
                <div class="card">
                    <p><?php echo nl2br(htmlspecialchars($experience['description'])); ?></p>
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
                    <div class="reviews-list">
                    <?php if ($reviews): ?>
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
                                                <span class="review-username"><?php echo htmlspecialchars($review['username']); ?></span>
                                                <?php if (!empty($review['location'])): ?>
                                                    <span class="review-location"><?php echo htmlspecialchars($review['location']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="review-rating-row">
                                                <span class="review-rating">
                                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                                        <i class="fa-star<?php echo $i < $review['rating'] ? ' fa-solid' : ' fa-regular'; ?>" style="color:#f5b50a;"></i>
                                                    <?php endfor; ?>
                                                </span>
                                            </div>
                                            <div class="review-body">
                                                <p><?php echo htmlspecialchars($review['description']); ?></p>
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



                <a href="guest_details.php?experience_id=<?php echo $experience_id; ?>&title=<?php echo urlencode($experience['title']); ?>&price=<?php echo $experience['price']; ?>" class="book-btn">Book Now!</a>
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

<?php include '../includes/footer.php'; ?>
