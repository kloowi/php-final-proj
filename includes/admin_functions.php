<?php
require_once 'db_connect.php';

/**
 * Admin Authentication Functions
 */

function authenticateAdmin($username, $password) {
    global $pdo;
    
    if (!$pdo) return false;
    
    try {
        error_log('Login attempt: ' . $username . ' / ' . $password);
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log('DB result: ' . print_r($admin, true));
        
        if ($admin) {
            error_log('Password verify: ' . (password_verify($password, $admin['password_hash']) ? 'true' : 'false'));
            return [
                'id' => $admin['username'],
                'username' => $admin['username'],
                'full_name' => 'Administrator',
                'role' => 'admin'
            ];
        }
    } catch (PDOException $e) {
        error_log("Admin authentication error: " . $e->getMessage());
    }
    
    return false;
}

function createAdminSession($admin_id) {
    global $pdo;
    
    if (!$pdo) return false;
    
    try {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        // Create admin_sessions table if it doesn't exist
        $pdo->exec("CREATE TABLE IF NOT EXISTS admin_sessions (
            session_id VARCHAR(64) PRIMARY KEY,
            admin_id VARCHAR(50) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            expires_at TIMESTAMP NOT NULL
        )");
        
        $stmt = $pdo->prepare("INSERT INTO admin_sessions (session_id, admin_id, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$token, $admin_id, $expires]);
        
        return $token;
    } catch (PDOException $e) {
        error_log("Session creation error: " . $e->getMessage());
        return false;
    }
}

function validateAdminSession($token) {
    global $pdo;
    
    if (!$pdo) return false;
    
    try {
        $stmt = $pdo->prepare("SELECT admin_id FROM admin_sessions WHERE session_id = ? AND expires_at > NOW()");
        $stmt->execute([$token]);
        $session = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($session) {
            // Get admin details
            $stmt = $pdo->prepare("SELECT * FROM Admin WHERE username = ?");
            $stmt->execute([$session['admin_id']]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($admin) {
                return [
                    'id' => $admin['username'],
                    'username' => $admin['username'],
                    'full_name' => 'Administrator',
                    'role' => 'admin'
                ];
            }
        }
    } catch (PDOException $e) {
        error_log("Session validation error: " . $e->getMessage());
    }
    
    return false;
}

function destroyAdminSession($token) {
    global $pdo;
    
    if (!$pdo) return false;
    
    try {
        $stmt = $pdo->prepare("DELETE FROM admin_sessions WHERE session_id = ?");
        $stmt->execute([$token]);
        return true;
    } catch (PDOException $e) {
        error_log("Session destruction error: " . $e->getMessage());
        return false;
    }
}

/**
 * Dashboard Statistics
 */

function getDashboardStats() {
    global $pdo;
    
    if (!$pdo) return [
        'total_appointments' => 0,
        'pending_appointments' => 0,
        'today_appointments' => 0,
        'total_services' => 0,
        'active_announcements' => 0
    ];
    
    try {
        $stats = [];
        
        // Total appointments (bookings)
        $stmt = $pdo->query("SELECT COUNT(*) FROM Bookings");
        $stats['total_appointments'] = $stmt->fetchColumn();
        
        // Pending appointments
        $stmt = $pdo->query("SELECT COUNT(*) FROM Bookings WHERE status = 'pending'");
        $stats['pending_appointments'] = $stmt->fetchColumn();
        
        // Today's appointments
        $stmt = $pdo->query("SELECT COUNT(*) FROM Bookings WHERE booking_date = CURDATE()");
        $stats['today_appointments'] = $stmt->fetchColumn();
        
        // Total services (experiences)
        $stmt = $pdo->query("SELECT COUNT(*) FROM Experiences");
        $stats['total_services'] = $stmt->fetchColumn();
        
        // Active announcements (we'll create this table)
        $stmt = $pdo->query("SELECT COUNT(*) FROM Announcements WHERE is_active = 1");
        $stats['active_announcements'] = $stmt->fetchColumn();
        
        return $stats;
    } catch (PDOException $e) {
        error_log("Dashboard stats error: " . $e->getMessage());
        return [
            'total_appointments' => 0,
            'pending_appointments' => 0,
            'today_appointments' => 0,
            'total_services' => 0,
            'active_announcements' => 0
        ];
    }
}

/**
 * Appointments Management
 */

function getAppointmentsAdmin($limit = 50, $offset = 0, $status_filter = null) {
    global $pdo;
    
    if (!$pdo) return [];
    
    try {
        $sql = "SELECT b.*, e.title as service_name, u.full_name as patient_name, u.email as patient_email, u.phone as patient_phone 
                FROM Bookings b 
                JOIN Experiences e ON b.experience_id = e.experience_id 
                JOIN Users u ON b.user_id = u.user_id";
        
        $params = [];
        if ($status_filter) {
            $sql .= " WHERE b.status = ?";
            $params[] = $status_filter;
        }
        
        $sql .= " ORDER BY b.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get appointments error: " . $e->getMessage());
        return [];
    }
}

function updateAppointmentStatus($appointment_id, $new_status) {
    global $pdo;
    
    if (!$pdo) return ['success' => false, 'message' => 'Database connection failed'];
    
    try {
        $stmt = $pdo->prepare("UPDATE Bookings SET status = ? WHERE booking_id = ?");
        $result = $stmt->execute([$new_status, $appointment_id]);
        
        if ($result && $stmt->rowCount() > 0) {
            return ['success' => true, 'message' => 'Appointment status updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Appointment not found or no changes made'];
        }
    } catch (PDOException $e) {
        error_log("Update appointment status error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

/**
 * Announcements Management
 */

function createAnnouncement($title, $content, $announcement_date, $is_featured = 0) {
    global $pdo;
    
    if (!$pdo) return ['success' => false, 'message' => 'Database connection failed'];
    
    try {
        // Create announcements table if it doesn't exist
        $pdo->exec("CREATE TABLE IF NOT EXISTS Announcements (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            announcement_date DATE NOT NULL,
            is_featured TINYINT(1) DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        $stmt = $pdo->prepare("INSERT INTO Announcements (title, content, announcement_date, is_featured) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$title, $content, $announcement_date, $is_featured]);
        
        if ($result) {
            return ['success' => true, 'message' => 'Announcement created successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to create announcement'];
        }
    } catch (PDOException $e) {
        error_log("Create announcement error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

function getAnnouncementsAdmin($limit = 20, $offset = 0) {
    global $pdo;
    
    if (!$pdo) return [];
    
    try {
        // Create announcements table if it doesn't exist
        $pdo->exec("CREATE TABLE IF NOT EXISTS Announcements (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            announcement_date DATE NOT NULL,
            is_featured TINYINT(1) DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        $stmt = $pdo->prepare("SELECT * FROM Announcements ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get announcements error: " . $e->getMessage());
        return [];
    }
}

function getAnnouncementById($id) {
    global $pdo;
    
    if (!$pdo) return null;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM Announcements WHERE id = ?");
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get announcement by ID error: " . $e->getMessage());
        return null;
    }
}

function updateAnnouncement($id, $title, $content, $announcement_date, $is_featured = 0, $is_active = 1) {
    global $pdo;
    
    if (!$pdo) return ['success' => false, 'message' => 'Database connection failed'];
    
    try {
        $stmt = $pdo->prepare("UPDATE Announcements SET title = ?, content = ?, announcement_date = ?, is_featured = ?, is_active = ? WHERE id = ?");
        $result = $stmt->execute([$title, $content, $announcement_date, $is_featured, $is_active, $id]);
        
        if ($result && $stmt->rowCount() > 0) {
            return ['success' => true, 'message' => 'Announcement updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Announcement not found or no changes made'];
        }
    } catch (PDOException $e) {
        error_log("Update announcement error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

function deleteAnnouncement($id) {
    global $pdo;
    
    if (!$pdo) return ['success' => false, 'message' => 'Database connection failed'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM Announcements WHERE id = ?");
        $result = $stmt->execute([$id]);
        
        if ($result && $stmt->rowCount() > 0) {
            return ['success' => true, 'message' => 'Announcement deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Announcement not found'];
        }
    } catch (PDOException $e) {
        error_log("Delete announcement error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

/**
 * Services Management
 */

function getServicesAdmin() {
    global $pdo;
    
    if (!$pdo) return [];
    
    try {
        $stmt = $pdo->query("SELECT * FROM Experiences ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get services error: " . $e->getMessage());
        return [];
    }
}

/**
 * Schedules Management
 */

function getServiceSchedulesAdmin($limit = 10) {
    global $pdo;
    
    if (!$pdo) return [];
    
    try {
        $sql = "SELECT es.*, e.title as service_name,
                (SELECT COUNT(*) FROM Bookings b WHERE b.experience_id = es.experience_id AND b.booking_date = es.date) as total_appointments,
                (SELECT COUNT(*) FROM Bookings b WHERE b.experience_id = es.experience_id AND b.booking_date = es.date AND b.status = 'confirmed') as confirmed_appointments
                FROM Experience_Schedule es
                JOIN Experiences e ON es.experience_id = e.experience_id
                WHERE es.date >= CURDATE()
                ORDER BY es.date ASC, es.time ASC
                LIMIT ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$limit]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get service schedules error: " . $e->getMessage());
        return [];
    }
}
?> 