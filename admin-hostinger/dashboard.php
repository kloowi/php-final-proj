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
        .admin-content {
            max-width: none;
            margin: 0;
        }
        .experiences-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 48px;
            width: 100%;
            margin: 32px 0 48px 0;
        }
        .experience-card {
            width: 100%;
            min-width: 310px;
            margin: 0;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(74,163,255,0.08);
            border: 1px solid #e5e7eb;
            padding: 18px;
            margin-bottom: 18px;
        }
        .experience-card img {
            border-radius: 12px;
            display: block;
            margin: 0 auto 12px auto;
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .experience-card h3, .experience-card a {
            color: #0080ff;
            font-size: 1.1em;
            margin: 0 0 8px 0;
            font-weight: 700;
            text-decoration: none;
        }
        .experience-card p {
            margin: 0 0 6px 0;
            color: #222;
            font-size: 1em;
        }
        .experience-card .label {
            color: #4aa3ff;
            font-weight: 600;
        }
        .experience-actions {
            margin-top: 14px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            align-items: center;
        }
        .experience-actions .btn-edit, .experience-actions .btn-delete {
            padding: 8px 24px;
            font-size: 1em;
            font-weight: 600;
            text-align: center;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
            display: inline-block;
        }
        .experience-actions .btn-edit {
            background: #4aa3ff;
            color: #fff;
            margin-right: 10px;
        }
        .experience-actions .btn-edit:hover {
            background: #0080ff;
        }
        .experience-actions .btn-delete {
            background: #dc3545;
            color: #fff;
        }
        .experience-actions .btn-delete:hover {
            background: #b52a37;
        }
        .experience-actions .btn-edit.btn-sm, .experience-actions .btn-delete.btn-sm {
            width: 100px;
            padding: 8px 10px;
            font-size: 1em;
            font-weight: 600;
            text-align: center;
            box-sizing: border-box;
        }
        .logout-btn {
            background: #6c757d;
            color: white;
        }
        @media (max-width: 1400px) {
            .experiences-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        @media (max-width: 1100px) {
            .experiences-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 700px) {
            .experiences-grid {
                grid-template-columns: 1fr;
            }
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
                <li><a href="add_review.php">Manage Reviews</a></li>
                <li><a href="/php-final-proj-main/admin/authentication/logout.php">Logout</a></li>
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
                        require_once '../includes/db_connect-hostinger.php';
                        $count = $pdo->query('SELECT COUNT(*) FROM Experiences')->fetchColumn();
                        echo $count;
                        ?>
                    </div>
                </div>
            </div>
            <div class="admin-container">
                <div style="position: relative; margin-bottom: 0; width: 130%;">
                    <h2 style="margin-top: 0; margin-bottom: 0;">Manage Experiences</h2>
                    <a href="experiences.php" class="btn btn-primary" style="background: #0883f7; color: #fff; border-radius: 8px; font-size: 1em; font-weight: 400; padding: 10px 24px; border: none; box-shadow: none; position: absolute; right: 0; top: 0; display: inline-block;">Add New Experience</a>
                </div>
                <hr style="border: none; border-top: 2px solid #e5e7eb; width: 130%; margin: 28px 0 0 0;">

                <div class="experiences-grid">
                    <?php foreach ($experiences as $experience): ?>
                        <div class="experience-card">
                            <div class="experience-category" style="color: #4aa3ff; font-weight: 700; font-size: 1.05em; margin-bottom: 8px;">
                                <?php echo htmlspecialchars($experience['category']); ?>
                            </div>
                            <img src="<?php echo htmlspecialchars(
                                !empty($experience['image_url']) ? '../' . $experience['image_url'] : '../assets/images/experience.jpg'
                            ); ?>" 
                                 alt="<?php echo htmlspecialchars($experience['title']); ?>">
                            <h3 style="color: #222; font-size: 1.15em; margin: 0 0 8px 0; font-weight: 700;">
                                <?php echo htmlspecialchars($experience['title']); ?>
                            </h3>
                            <div style="color: #555; font-size: 1em; margin-bottom: 4px;">
                                <?php echo htmlspecialchars($experience['location']); ?>
                            </div>
                            <div style="color: #888; font-size: 0.98em; margin-bottom: 4px;">
                                Price: $<?php echo number_format($experience['price'], 2); ?> | Duration: <?php echo htmlspecialchars($experience['duration']); ?>
                            </div>
                            <div style="color: #222; font-size: 0.98em; margin-bottom: 8px;">
                                <?php echo htmlspecialchars(substr($experience['description'], 0, 100)) . '...'; ?>
                            </div>
                            
                            <div class="experience-actions">
                                <a href="experiences.php?edit=1<?php echo $experience['experience_id']; ?>" 
                                   class="btn btn-edit btn-sm">Edit</a>
                                <form method="POST" style="display: inline; margin: 0;" 
                                      onsubmit="return confirm('Are you sure you want to delete this experience?')">
                                    <input type="hidden" name="experience_id" value="<?php echo $experience['experience_id']; ?>">
                                    <button type="submit" name="delete_experience" class="btn btn-delete btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (empty($experiences)): ?>
                    <p>No experiences found. <a href="experiences.php">Add your first experience</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html> 