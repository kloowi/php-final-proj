<?php
require_once '../includes/db_connect-hostinger.php';

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $rating = (int)($_POST['rating'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');

    if ($username && $rating && $description && $category) {
        $stmt = $pdo->prepare('INSERT INTO Reviews (username, rating, description, category) VALUES (?, ?, ?, ?)');
        if ($stmt->execute([$username, $rating, $description, $category])) {
            $message = 'Review added successfully!';
        } else {
            $message = 'Failed to add review.';
        }
    } else {
        $message = 'Please fill in all fields.';
    }
}

// Fetch reviews for selected category
$selected_category = $_POST['category'] ?? '';
$reviews = [];
if ($selected_category) {
    $stmt = $pdo->prepare('SELECT * FROM Reviews WHERE category = ? ORDER BY rating DESC');
    $stmt->execute([$selected_category]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Review</title>
    <link rel="stylesheet" href="assets/css/view_experience.css">
    <style>
        .review-form { max-width: 400px; margin: 30px auto; padding: 24px; border: 1px solid #e0e0e0; border-radius: 10px; background: #fff; }
        .review-form input, .review-form textarea, .review-form select { width: 100%; margin-bottom: 12px; padding: 8px; border-radius: 6px; border: 1px solid #ccc; }
        .review-form button { background: #4aa3ff; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; }
        .review-form button:hover { background: #1a73e8; }
        .reviews-list { max-width: 600px; margin: 30px auto; }
        .review { border-bottom: 1px solid #e0e0e0; padding: 12px 0; }
        .review strong { font-size: 16px; }
        .review span { color: #f5b50a; margin-left: 8px; }
        .review p { margin: 4px 0 0 0; }
    </style>
</head>
<body>
    <div class="review-form">
        <h2>Add a Review</h2>
        <?php if ($message): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Your Name" required>
            <select name="rating" required>
                <option value="">Rating</option>
                <option value="5">5 - Excellent</option>
                <option value="4">4 - Good</option>
                <option value="3">3 - Average</option>
                <option value="2">2 - Poor</option>
                <option value="1">1 - Terrible</option>
            </select>
            <textarea name="description" placeholder="Write your review..." required></textarea>
            <input type="text" name="category" placeholder="Category (e.g. Historical, Food, Nature)" value="<?php echo htmlspecialchars($selected_category); ?>" required>
            <button type="submit">Submit Review</button>
        </form>
    </div>

    <?php if ($selected_category): ?>
        <div class="reviews-list">
            <h3>Reviews for category: <?php echo htmlspecialchars($selected_category); ?></h3>
            <?php if ($reviews): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review">
                        <strong><?php echo htmlspecialchars($review['username']); ?></strong>
                        <span><?php echo str_repeat('★', (int)$review['rating']); ?></span>
                        <p><?php echo htmlspecialchars($review['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No reviews for this category yet.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
</html> 