<?php
if (!isset($action)) {
    $action = $_POST['action'] ?? '';
}
if ($action === 'edit') {
    $id = intval($_POST['id']);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $duration = trim($_POST['duration'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $rating = floatval($_POST['rating'] ?? 0);
    // Use uploaded image or fallback to previous image if no new file uploaded
    $image_url = $image_path ?: trim($_POST['existing_image_url'] ?? '');
    if ($title && $description) {
        $stmt = $pdo->prepare('UPDATE Experiences SET title=?, description=?, location=?, price=?, duration=?, category=?, rating=?, image_url=? WHERE experience_id=?');
        $stmt->execute([$title, $description, $location, $price, $duration, $category, $rating, $image_url, $id]);
        setFlashMessage('success', 'Experience updated successfully.');
    } else {
        setFlashMessage('danger', 'Title and description are required.');
    }
    header('Location: experiences.php');
    exit;
}
?> 