<?php
require_once './authentication/auth.php';
requireAdminLogin();
$admin = getCurrentAdmin();

// Handle experience deletion
if (isset($_POST['delete_experience'])) {
    $experience_id = $_POST['experience_id'];
    $stmt = $pdo->prepare("DELETE FROM Experiences WHERE experience_id = ?");
    $stmt->execute([$experience_id]);
    header('Location: dashboard.php');
    exit();
}

// Fetch all experiences
$stmt = $pdo->query("SELECT * FROM Experiences ORDER BY experience_id DESC");
$experiences = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .admin-header h1 {
            margin: 0;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-warning {
            background: #ffc107;
            color: black;
        }
        .experiences-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .experience-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background: white;
        }
        .experience-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .experience-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }
        .logout-btn {
            background: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <div class="admin-sidebar">
            <div class="sidebar-header">
                <h3>Admin Panel</h3>
            </div>
            <ul class="sidebar-nav">
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="experiences.php">Experiences</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="admin-content">
            <div class="admin-header">
                <h1>Welcome, <?php echo htmlspecialchars($admin['username']); ?></h1>
            </div>
            <?php displayFlashMessage(); ?>
            <div class="dashboard-cards">
                <div class="dashboard-card">
                    <div class="card-title">Total Experiences</div>
                    <div class="card-value">
                        <?php
                        require_once '../includes/db_connect.php';
                        $count = $pdo->query('SELECT COUNT(*) FROM Experiences')->fetchColumn();
                        echo $count;
                        ?>
                    </div>
                </div>
            </div>
            <div class="admin-container">
                <div class="admin-header">
                    <h1>Manage Experiences</h1>
                    <div>
                        <a href="add_experience.php" class="btn btn-primary">Add New Experience</a>
                    </div>
                </div>

                <div class="experiences-grid">
                    <?php foreach ($experiences as $experience): ?>
                        <div class="experience-card">
                            <img src="<?php echo htmlspecialchars(
                                !empty($experience['image_url']) ? '../' . $experience['image_url'] : '../assets/images/experience.jpg'
                            ); ?>" 
                                 alt="<?php echo htmlspecialchars($experience['title']); ?>">
                            <h3><?php echo htmlspecialchars($experience['title']); ?></h3>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($experience['location']); ?></p>
                            <p><strong>Price:</strong> $<?php echo number_format($experience['price'], 2); ?></p>
                            <p><strong>Duration:</strong> <?php echo htmlspecialchars($experience['duration']); ?></p>
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($experience['category']); ?></p>
                            <p><?php echo htmlspecialchars(substr($experience['description'], 0, 100)) . '...'; ?></p>
                            
                            <div class="experience-actions">
                                <a href="experiences.php?edit=1<?php echo $experience['experience_id']; ?>" 
                                   class="btn btn-warning">Edit</a>
                                <form method="POST" style="display: inline;" 
                                      onsubmit="return confirm('Are you sure you want to delete this experience?')">
                                    <input type="hidden" name="experience_id" value="<?php echo $experience['experience_id']; ?>">
                                    <button type="submit" name="delete_experience" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (empty($experiences)): ?>
                    <p>No experiences found. <a href="add_experience.php">Add your first experience</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html> 