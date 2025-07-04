<?php
require_once './authentication/auth.php';
requireAdminLogin();
$admin = getCurrentAdmin();
require_once '../includes/db_connect.php';


// Handle add/edit/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    // Handle file upload if present
    $image_path = '';
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/images/experiences/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_tmp = $_FILES['image_file']['tmp_name'];
        $file_name = basename($_FILES['image_file']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_ext, $allowed_ext)) {
            $new_name = uniqid('exp_', true) . '.' . $file_ext;
            $dest_path = $upload_dir . $new_name;
            if (move_uploaded_file($file_tmp, $dest_path)) {
                $image_path = 'assets/images/experiences/' . $new_name;
            }
        }
    }
    if ($action === 'add') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $duration = trim($_POST['duration'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $available_slots = intval($_POST['available_slots'] ?? 0);
        // Use uploaded image or fallback to text input if no file uploaded
        $image_url = $image_path ?: trim($_POST['image_url'] ?? '');
        if ($title && $description) {
            $stmt = $pdo->prepare('INSERT INTO Experiences (title, description, location, price, duration, category, available_slots, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$title, $description, $location, $price, $duration, $category, $available_slots, $image_url]);
            setFlashMessage('success', 'Experience added successfully.');
        } else {
            setFlashMessage('danger', 'Title and description are required.');
        }
        header('Location: experiences.php');
        exit;
    }
    if ($action === 'edit') {
        require_once 'edit_experience.php';
    }
    if ($action === 'delete') {
        $id = intval($_POST['id']);
        $stmt = $pdo->prepare('DELETE FROM Experiences WHERE experience_id=?');
        $stmt->execute([$id]);
        setFlashMessage('success', 'Experience deleted.');
        header('Location: experiences.php');
        exit;
    }
}

// Get experiences
$experiences = $pdo->query('SELECT * FROM Experiences ORDER BY experience_id DESC')->fetchAll(PDO::FETCH_ASSOC);
// Get experience for editing
$editing = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $pdo->prepare('SELECT * FROM Experiences WHERE experience_id=?');
    $stmt->execute([$id]);
    $editing = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Edit action moved to separate file
require_once 'edit_experience.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Experiences - Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-layout">
        <div class="admin-sidebar">
            <div class="sidebar-header">
                <h3>Admin Panel</h3>
            </div>
            <ul class="sidebar-nav">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="experiences.php" class="active">Experiences</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="admin-content">
            <div class="admin-header">
                <h1>Manage Experiences</h1>
            </div>
            <?php displayFlashMessage(); ?>
            <div class="form-container">
                <h2><?php echo $editing ? 'Edit Experience' : 'Add New Experience'; ?></h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="<?php echo $editing ? 'edit' : 'add'; ?>">
                    <?php if ($editing): ?>
                        <input type="hidden" name="id" value="<?php echo $editing['experience_id']; ?>">
                        <input type="hidden" name="existing_image_url" value="<?php echo htmlspecialchars($editing['image_url'] ?? ''); ?>">
                    <?php endif; ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Title *</label>
                            <input type="text" name="title" value="<?php echo htmlspecialchars($editing['title'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location" value="<?php echo htmlspecialchars($editing['location'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($editing['price'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Duration</label>
                            <input type="text" name="duration" value="<?php echo htmlspecialchars($editing['duration'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" name="category" value="<?php echo htmlspecialchars($editing['category'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Available Slots</label>
                            <input type="number" name="available_slots" value="<?php echo htmlspecialchars($editing['available_slots'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Image File</label>
                            <input type="file" name="image_file" accept="image/*">
                            <?php if ($editing && !empty($editing['image_url'])): ?>
                                <br><img src="../<?php echo htmlspecialchars($editing['image_url']); ?>" alt="Current Image" style="max-width:100px;max-height:100px;">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description *</label>
                        <textarea name="description" required><?php echo htmlspecialchars($editing['description'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-success"><?php echo $editing ? 'Update' : 'Add'; ?> Experience</button>
                    <?php if ($editing): ?>
                        <a href="experiences.php" class="btn btn-secondary">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="table-container">
                <h2>All Experiences</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Category</th>
                            <th>Slots</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($experiences as $exp): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($exp['title']); ?></td>
                                <td><?php echo htmlspecialchars($exp['location']); ?></td>
                                <td><?php echo htmlspecialchars($exp['price']); ?></td>
                                <td><?php echo htmlspecialchars($exp['duration']); ?></td>
                                <td><?php echo htmlspecialchars($exp['category']); ?></td>
                                <td><?php echo htmlspecialchars($exp['available_slots']); ?></td>
                                <td>
                                    <a href="?edit=<?php echo $exp['experience_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this experience?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $exp['experience_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html> 