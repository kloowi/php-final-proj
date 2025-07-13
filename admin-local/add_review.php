<?php
require_once '../includes/db_connect.php';

// Check if database connection is available
if (!$pdo) {
    die("Database connection failed. Please try again later.");
}

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $rating = (int)($_POST['rating'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');

    if ($username && $rating && $description && $category) {
        try {
            $stmt = $pdo->prepare('INSERT INTO Reviews (username, rating, description, category) VALUES (?, ?, ?, ?)');
            if ($stmt->execute([$username, $rating, $description, $category])) {
                $message = 'Review added successfully!';
            } else {
                $message = 'Failed to add review.';
            }
        } catch (PDOException $e) {
            $message = 'Database error occurred. Please try again.';
            error_log("Review insert error: " . $e->getMessage());
        }
    } else {
        $message = 'Please fill in all fields.';
    }
}

// Fetch reviews for selected category
$selected_category = $_POST['category'] ?? '';
$reviews = [];
if ($selected_category) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM Reviews WHERE category = ? ORDER BY rating DESC');
        $stmt->execute([$selected_category]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Review fetch error: " . $e->getMessage());
        $message = 'Error loading reviews. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Reviews - Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        .review-form, .reviews-list, .admin-header h2, .reviews-list h3 {
            font-family: Arial, sans-serif !important;
        }
        .admin-header h2, .reviews-list h3 {
            font-weight: 700;
            font-size: 1.4em;
            margin-bottom: 24px;
        }
        .form-container {
            max-width: 600px;
            width: 100%;
            margin: 40px auto;
            padding: 32px;
            border: 1px solid #e0e0e0;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(74,163,255,0.08);
        }
        @media (max-width: 700px) {
            .form-container {
                max-width: 100%;
                padding: 18px;
            }
        }
        .form-container input[type="text"],
        .form-container textarea,
        .form-container select {
            font-size: 1.1em;
            height: 48px;
            box-sizing: border-box;
            background: #f8fbff;
            color: #222;
            margin-bottom: 12px;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            width: 100%;
        }
        .form-container textarea {
            height: 80px;
        }
        .form-container button {
            background: #4aa3ff;
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.2s;
        }
        .form-container button:hover {
            background: #0080ff;
        }
        .table-container {
            max-width: 900px;
            margin: 40px auto 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(74,163,255,0.08);
            border: 1px solid #e5e7eb;
            padding: 32px;
        }
        .review-card {
            border-bottom: 1px solid #e0e0e0;
            padding: 18px 0;
        }
        .review-card:last-child {
            border-bottom: none;
        }
        .reviewer-name {
            font-weight: bold;
            color: #0080ff;
            font-size: 1.1em;
        }
        .review-rating {
            color: #f5b50a;
            margin-left: 8px;
        }
        .review-experience {
            font-style: italic;
            color: #888;
            margin-left: 12px;
        }
        .review-desc {
            margin: 8px 0 0 0;
            color: #222;
        }
        .error { color: #d32f2f; background: #ffebee; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .success { color: #2e7d32; background: #e8f5e8; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <div class="admin-sidebar">
            <div class="sidebar-header">
                <h3>Admin Panel</h3>
            </div>
            <ul class="sidebar-nav">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="experiences.php">Experiences</a></li>
                <li><a href="add_review.php" class="active">Manage Reviews</a></li>
                <li><a href="/php-final-proj-main/admin/authentication/logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="admin-content">
            <div class="admin-header">
                <h2 style="margin-top: 0; margin-bottom: 0;">Manage Reviews</h2>
            </div>
            <div class="form-container">
                <h2>Add a Review</h2>
                <?php if (isset($message) && $message): ?>
                    <p class="<?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>"><?php echo htmlspecialchars($message); ?></p>
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
                    <input type="text" name="category" placeholder="Category (e.g. Historical, Food, Nature)" value="<?php echo htmlspecialchars($selected_category ?? ''); ?>" required>
                    <button type="submit">Submit Review</button>
                </form>
            </div>

            <?php if (isset($selected_category) && $selected_category): ?>
                <div class="table-container">
                    <h3>Reviews for category: <?php echo htmlspecialchars($selected_category); ?></h3>
                    <?php if (isset($reviews) && $reviews): ?>
                        <?php foreach ($reviews as $review): ?>
                            <div class="review-card">
                                <span class="reviewer-name"><?php echo htmlspecialchars($review['username']); ?></span>
                                <span class="review-rating"><?php echo str_repeat('★', (int)$review['rating']); ?></span>
                                <span class="review-experience"><?php echo htmlspecialchars($review['category']); ?></span>
                                <div class="review-desc"><?php echo htmlspecialchars($review['description']); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No reviews for this category yet.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 