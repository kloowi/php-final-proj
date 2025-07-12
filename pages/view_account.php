<?php 
session_start();
include '../includes/header.php'; 
require_once '../includes/db_config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch user data from database
try {
    $stmt = $pdo->prepare("SELECT user_id, username, email, full_name FROM Users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user) {
        // User not found in database, redirect to login
        session_destroy();
        header('Location: login.php');
        exit;
    }
} catch (PDOException $e) {
    // Database error, redirect to login
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<link rel="stylesheet" href="../assets/css/view_account.css">

<div class="account-viewport-center">
  <div class="account-container">
    <div class="account-title">My Account</div>
    <div class="account-subtitle">Manage your StepIntoManila profile and security settings</div>
    <div class="account-card">
      <div class="account-section">
        <div class="account-section-title">
          <img src="../assets/images/acc/personalinfo.png" alt="Avatar Icon" style="width: 22px; height: 22px; vertical-align: middle; margin-right: 8px;"> Personal Information
          <span style="flex:1"></span>
          <button id="edit-name-btn" style="background:none;border:none;cursor:pointer;margin-left:8px;vertical-align:middle;">
            <img src="https://cdn-icons-png.flaticon.com/512/1250/1250615.png" alt="Edit Icon" style="width: 18px; vertical-align: middle;">
          </button>
        </div>
        <div class="account-section-content">Name:
          <input type="text" id="account-name" value="<?php echo htmlspecialchars($user['full_name']); ?>" style="border:none;background:transparent;color:#bbb;font-size:1rem;margin-left:8px;width:200px;outline:none;" readonly>
          <button id="save-name-btn" style="display:none;background:#2992f5;color:#fff;border:none;border-radius:4px;padding:2px 10px;margin-left:8px;cursor:pointer;font-size:0.95rem;">Save</button>
        </div>
      </div>
      <div class="account-section">
        <div class="account-section-title">
          <img src="../assets/images/acc/signin.png" alt="Security Icon" style="width: 22px; height: 22px; vertical-align: middle; margin-right: 8px;"> Sign in &amp; Security
          <span style="flex:1"></span>
          <button id="edit-security-btn" style="background:none;border:none;cursor:pointer;margin-left:8px;vertical-align:middle;">
            <img src="https://cdn-icons-png.flaticon.com/512/1250/1250615.png" alt="Edit Icon" style="width: 18px; vertical-align: middle;">
          </button>
        </div>
        <div class="account-section-content">Email:
          <input type="email" id="account-email" value="<?php echo htmlspecialchars($user['email']); ?>" style="border:none;background:transparent;color:#bbb;font-size:1rem;margin-left:8px;width:220px;outline:none;" readonly>
          <button id="save-security-btn" style="display:none;background:#2992f5;color:#fff;border:none;border-radius:4px;padding:2px 10px;margin-left:8px;cursor:pointer;font-size:0.95rem;">Save</button>
        </div>
        <div class="account-section-content">Password:
          <input type="password" id="account-password" value="••••••••" style="border:none;background:transparent;color:#bbb;font-size:1rem;margin-left:8px;width:120px;outline:none;" readonly>
        </div>
      </div>
      <div class="account-section">
        <div class="account-section-title">
          <img src="../assets/images/acc/manage.png" alt="Info Icon" style="width: 22px; height: 22px; vertical-align: middle; margin-right: 8px;"> Manage Account
          <span style="flex:1"></span>
        </div>
        <a href="#" class="delete-link">delete account</a>
      </div>
    </div>
    <a href="../logout.php" class="logout-btn">Logout</a>
  </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteModal">
  <div class="modal-content">
    <div class="modal-icon">
      <img src="https://cdn-icons-png.flaticon.com/512/860/860829.png" alt="Trash Icon" style="width: 54px; height: 54px;">
    </div>
    <div class="modal-title">You are about to delete your profile</div>
    <div class="modal-subtitle">Are you sure?</div>
    <div class="modal-buttons">
      <button id="cancelDeleteBtn" class="modal-btn cancel">Cancel</button>
      <button id="confirmDeleteBtn" class="modal-btn delete">Delete</button>
    </div>
  </div>
</div>

<script>
// Name edit functionality
const nameInput = document.getElementById('account-name');
const editNameBtn = document.getElementById('edit-name-btn');
const saveNameBtn = document.getElementById('save-name-btn');

editNameBtn.addEventListener('click', function() {
  nameInput.readOnly = false;
  nameInput.style.color = '#222';
  nameInput.focus();
  saveNameBtn.style.display = 'inline-block';
  editNameBtn.style.display = 'none';
});

saveNameBtn.addEventListener('click', function() {
  const newName = nameInput.value.trim();
  
  if (newName === '') {
    alert('Name cannot be empty');
    return;
  }
  
  // Send AJAX request to update name
  fetch('update_account.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'action=update_name&full_name=' + encodeURIComponent(newName)
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      nameInput.readOnly = true;
      nameInput.style.color = '#bbb';
      saveNameBtn.style.display = 'none';
      editNameBtn.style.display = 'inline-block';
      alert(data.message);
    } else {
      alert(data.message);
    }
  })
  .catch(error => {
    alert('Error updating name. Please try again.');
  });
});

nameInput.addEventListener('keydown', function(e) {
  if (e.key === 'Enter') {
    saveNameBtn.click();
  }
});

// Security (email/password) edit functionality
const emailInput = document.getElementById('account-email');
const passwordInput = document.getElementById('account-password');
const editSecurityBtn = document.getElementById('edit-security-btn');
const saveSecurityBtn = document.getElementById('save-security-btn');

editSecurityBtn.addEventListener('click', function() {
  emailInput.readOnly = false;
  emailInput.style.color = '#222';
  passwordInput.readOnly = false;
  passwordInput.type = 'text';
  passwordInput.value = ''; // Clear the dots and allow user to enter new password
  passwordInput.placeholder = 'Enter new password';
  passwordInput.style.color = '#222';
  emailInput.focus();
  saveSecurityBtn.style.display = 'inline-block';
  editSecurityBtn.style.display = 'none';
});

const originalEmail = emailInput.value;
const originalPassword = '••••••••';

saveSecurityBtn.addEventListener('click', function() {
  const newEmail = emailInput.value.trim();
  const newPassword = passwordInput.value;
  let emailChanged = newEmail !== originalEmail;
  let passwordChanged = newPassword && newPassword !== originalPassword && newPassword !== '';

  if (!emailChanged && !passwordChanged) {
    alert('No changes to save.');
    return;
  }

  // Helper to reset UI
  function resetSecurityUI() {
    emailInput.readOnly = true;
    emailInput.style.color = '#bbb';
    passwordInput.readOnly = true;
    passwordInput.type = 'password';
    passwordInput.value = '••••••••';
    passwordInput.placeholder = '';
    passwordInput.style.color = '#bbb';
    saveSecurityBtn.style.display = 'none';
    editSecurityBtn.style.display = 'inline-block';
  }

  // Update email if changed
  if (emailChanged) {
    if (newEmail === '' || !newEmail.includes('@')) {
      alert('Please enter a valid email address');
      return;
    }
    const formData = new FormData();
    formData.append('action', 'update_email');
    formData.append('email', newEmail);
    fetch('update_account.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        if (!passwordChanged) {
          resetSecurityUI();
          alert('Email updated successfully');
        } else {
          // If password also changed, update password next
          updatePassword();
        }
      } else {
        alert(data.message);
      }
    })
    .catch(error => {
      alert('Error updating email: ' + error.message);
    });
  } else if (passwordChanged) {
    // Only password changed
    updatePassword();
  }

  function updatePassword() {
    if (newPassword.length < 6) {
      alert('Password must be at least 6 characters long');
      return;
    }
    const passwordFormData = new FormData();
    passwordFormData.append('action', 'update_password');
    passwordFormData.append('password', newPassword);
    fetch('update_account.php', {
      method: 'POST',
      body: passwordFormData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        resetSecurityUI();
        if (emailChanged) {
          alert('Email and password updated successfully');
        } else {
          alert('Password updated successfully');
        }
      } else {
        alert('Error updating password: ' + data.message);
      }
    })
    .catch(error => {
      alert('Error updating password: ' + error.message);
    });
  }
});

emailInput.addEventListener('keydown', function(e) {
  if (e.key === 'Enter') {
    saveSecurityBtn.click();
  }
  if (e.key === 'Escape') {
    // Cancel editing and revert to original state
    emailInput.readOnly = true;
    emailInput.style.color = '#bbb';
    passwordInput.readOnly = true;
    passwordInput.type = 'password';
    passwordInput.value = '••••••••';
    passwordInput.placeholder = '';
    passwordInput.style.color = '#bbb';
    saveSecurityBtn.style.display = 'none';
    editSecurityBtn.style.display = 'inline-block';
  }
});
passwordInput.addEventListener('keydown', function(e) {
  if (e.key === 'Enter') {
    saveSecurityBtn.click();
  }
  if (e.key === 'Escape') {
    // Cancel editing and revert to original state
    emailInput.readOnly = true;
    emailInput.style.color = '#bbb';
    passwordInput.readOnly = true;
    passwordInput.type = 'password';
    passwordInput.value = '••••••••';
    passwordInput.placeholder = '';
    passwordInput.style.color = '#bbb';
    saveSecurityBtn.style.display = 'none';
    editSecurityBtn.style.display = 'inline-block';
  }
});

// Delete account modal logic
const deleteLink = document.querySelector('.delete-link');
const deleteModal = document.getElementById('deleteModal');
const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

deleteLink.addEventListener('click', function(e) {
  e.preventDefault();
  deleteModal.classList.add('active');
});

cancelDeleteBtn.addEventListener('click', function() {
  deleteModal.classList.remove('active');
});

confirmDeleteBtn.addEventListener('click', function() {
  // Send AJAX request to delete account
  fetch('delete_account.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert(data.message);
      // Redirect to index.php
      window.location.href = data.redirect;
    } else {
      alert(data.message);
      deleteModal.classList.remove('active');
    }
  })
  .catch(error => {
    alert('Error deleting account. Please try again.');
    deleteModal.classList.remove('active');
  });
});
</script> 
<script>
// Always redirect user icon in header to view_account.php when clicked
window.addEventListener('DOMContentLoaded', function() {
  var loginLink = document.querySelector('.login-link a');
  if (loginLink) {
    loginLink.addEventListener('click', function(e) {
      e.preventDefault();
      window.location.href = 'view_account.php';
    });
  }
});
</script> 