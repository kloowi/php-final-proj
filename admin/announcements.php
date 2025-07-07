<?php
require_once 'auth.php';
requireAdminLogin();

$admin = getCurrentAdmin();
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Handle form submissions
if ($_POST) {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $announcement_date = $_POST['announcement_date'] ?? '';
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;

        if (empty($title) || empty($content) || empty($announcement_date)) {
            setFlashMessage('danger', 'Please fill in all required fields.');
        } else {
            $result = createAnnouncement($title, $content, $announcement_date, $is_featured);
            setFlashMessage($result['success'] ? 'success' : 'danger', $result['message']);
        }

        header('Location: announcements.php');
        exit;
    }

    if ($action === 'edit') {
        $id = (int)$_POST['id'];
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $announcement_date = $_POST['announcement_date'] ?? '';
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (empty($title) || empty($content) || empty($announcement_date)) {
            setFlashMessage('danger', 'Please fill in all required fields.');
        } else {
            $result = updateAnnouncement($id, $title, $content, $announcement_date, $is_featured, $is_active);
            setFlashMessage($result['success'] ? 'success' : 'danger', $result['message']);
        }

        header('Location: announcements.php');
        exit;
    }

    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        $result = deleteAnnouncement($id);
        setFlashMessage($result['success'] ? 'success' : 'danger', $result['message']);

        header('Location: announcements.php');
        exit;
    }
}

// Get announcement for editing
$editing_announcement = null;
if (isset($_GET['edit'])) {
    $editing_announcement = getAnnouncementById((int)$_GET['edit']);
}

// Get announcements
$announcements = getAnnouncementsAdmin($limit, $offset);

// Handle logout
if (isset($_GET['logout'])) {
    adminLogout();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements - Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="sidebar-header">
                <h3>SIM Admin</h3>
                <small>Management Panel</small>
            </div>
            <ul class="sidebar-nav">
                <?php foreach (getAdminNavigation() as $nav_item): ?>
                    <li>
                        <a href="<?php echo $nav_item['url']; ?>"
                            class="<?php echo basename($_SERVER['PHP_SELF']) === $nav_item['url'] ? 'active' : ''; ?>">
                            <i class="<?php echo $nav_item['icon']; ?>"></i>
                            <?php echo $nav_item['title']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li>
                    <a href="?logout=1" onclick="return confirm('Are you sure you want to logout?')">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="admin-content">
            <div class="admin-header">
                <h1><i class="fas fa-bullhorn"></i> Manage Announcements</h1>
                <div class="admin-user">
                    <span>Welcome, <?php echo htmlspecialchars($admin['full_name']); ?></span>
                </div>
            </div>

            <?php displayFlashMessage(); ?>

            <!-- Add/Edit Announcement Form -->
            <div class="form-container">
                <h2><i class="fas fa-<?php echo $editing_announcement ? 'edit' : 'plus'; ?>"></i>
                    <?php echo $editing_announcement ? 'Edit Announcement' : 'Add New Announcement'; ?></h2>

                <form method="POST" action="">
                    <input type="hidden" name="action" value="<?php echo $editing_announcement ? 'edit' : 'create'; ?>">
                    <?php if ($editing_announcement): ?>
                        <input type="hidden" name="id" value="<?php echo $editing_announcement['id']; ?>">
                    <?php endif; ?>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="title">Announcement Title *</label>
                            <input
                                type="text"
                                id="title"
                                name="title"
                                class="form-control"
                                value="<?php echo htmlspecialchars($editing_announcement['title'] ?? ''); ?>"
                                required
                                placeholder="Enter announcement title">
                        </div>

                        <div class="form-group">
                            <label for="announcement_date">Announcement Date *</label>
                            <input
                                type="date"
                                id="announcement_date"
                                name="announcement_date"
                                class="form-control"
                                value="<?php echo $editing_announcement['announcement_date'] ?? date('Y-m-d'); ?>"
                                required>
                        </div>
                    </div>

                    <div class="form-row single">
                        <div class="form-group">
                            <label for="content">Content *</label>
                            <textarea
                                id="content"
                                name="content"
                                class="form-control"
                                rows="4"
                                required
                                placeholder="Enter announcement content..."><?php echo htmlspecialchars($editing_announcement['content'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="checkbox-group">
                            <input
                                type="checkbox"
                                id="is_featured"
                                name="is_featured"
                                <?php echo ($editing_announcement['is_featured'] ?? false) ? 'checked' : ''; ?>>
                            <label for="is_featured">Featured Announcement (will appear on homepage)</label>
                        </div>

                        <?php if ($editing_announcement): ?>
                            <div class="checkbox-group">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    name="is_active"
                                    <?php echo ($editing_announcement['is_active'] ?? true) ? 'checked' : ''; ?>>
                                <label for="is_active">Active (visible to public)</label>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-row single">
                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i>
                                <?php echo $editing_announcement ? 'Update Announcement' : 'Create Announcement'; ?>
                            </button>

                            <?php if ($editing_announcement): ?>
                                <a href="announcements.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel Edit
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Announcements List -->
            <div class="table-container">
                <div class="table-header">
                    <h2><i class="fas fa-list"></i> All Announcements</h2>
                    <div style="color: #666; font-size: 14px;">
                        Total: <?php echo count($announcements); ?> announcements
                    </div>
                </div>

                <?php if (!empty($announcements)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Featured</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($announcements as $announcement): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($announcement['title']); ?></strong>
                                        <div style="font-size: 12px; color: #666; margin-top: 5px;">
                                            <?php echo strlen($announcement['content']) > 100 ?
                                                substr(htmlspecialchars($announcement['content']), 0, 100) . '...' :
                                                htmlspecialchars($announcement['content']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($announcement['announcement_date'])); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $announcement['is_featured'] ? 'confirmed' : 'pending'; ?>">
                                            <?php echo $announcement['is_featured'] ? 'Yes' : 'No'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo $announcement['is_active'] ? 'active' : 'inactive'; ?>">
                                            <?php echo $announcement['is_active'] ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M j, Y g:i A', strtotime($announcement['created_at'])); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="?edit=<?php echo $announcement['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $announcement['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="padding: 40px; text-align: center; color: #666;">
                        <i class="fas fa-megaphone" style="font-size: 64px; margin-bottom: 20px; opacity: 0.3;"></i>
                        <h3>No Announcements Found</h3>
                        <p>Create your first announcement using the form above.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Auto-focus on title field when page loads (if not editing)
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!$editing_announcement): ?>
                document.getElementById('title').focus();
            <?php endif; ?>
        });

        // Character counter for content
        const contentField = document.getElementById('content');
        const titleField = document.getElementById('title');

        function addCharacterCounter(field, maxLength) {
            const counter = document.createElement('div');
            counter.style.cssText = 'font-size: 12px; color: #666; margin-top: 5px; text-align: right;';
            field.parentNode.appendChild(counter);

            function updateCounter() {
                const remaining = maxLength - field.value.length;
                counter.textContent = `${field.value.length}/${maxLength} characters`;
                counter.style.color = remaining < 50 ? '#dc3545' : '#666';
            }

            field.addEventListener('input', updateCounter);
            updateCounter();
        }

        addCharacterCounter(titleField, 255);
        addCharacterCounter(contentField, 1000);

        // Confirm navigation away from unsaved changes
        let formChanged = false;
        const form = document.querySelector('form');
        const formInputs = form.querySelectorAll('input, textarea, select');

        formInputs.forEach(input => {
            input.addEventListener('change', () => formChanged = true);
        });

        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        form.addEventListener('submit', () => formChanged = false);
    </script>
</body>

</html> 