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
    $stmt = $pdo->prepare("SELECT user_id, username, email, full_name, gender, birthday FROM Users WHERE user_id = ?");
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
<link rel="stylesheet" href="../assets/css/view.css">
<div class="account-container">
  <div class="account-box">
    <h2 class="account-title">Account Details</h2>
    <p class="account-subtitle">Manage your StepIntoManila Account</p>
    <hr class="account-divider">
    
    <form class="account-form">
      <label class="input-label">Username</label>
      <input type="text" class="input-field" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
      <label class="input-label">Full Name</label>
      <input type="text" id="account-name" class="input-field" value="<?php echo htmlspecialchars($user['full_name']); ?>" readonly>
      <div class="row-fields">
        <div class="row-half">
          <label class="input-label">Email Address</label>
          <input type="email" id="account-email" class="input-field" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
        </div>
        <div class="row-half">
          <label class="input-label">Password</label>
          <input type="password" id="account-password" class="input-field" value="••••••••" readonly>
        </div>
      </div>
      <div class="row-fields">
        <div class="row-half">
          <label class="input-label">Gender</label>
          <input type="text" id="account-gender" class="input-field" value="<?php echo htmlspecialchars($user['gender'] ?? ''); ?>" readonly placeholder="Enter gender">
        </div>
        <div class="row-half">
          <label class="input-label">Birthday</label>
          <input type="date" id="account-birthday" class="input-field" value="<?php echo $user['birthday'] ?? ''; ?>" readonly>
        </div>
      </div>
      <div class="account-actions">
        <a href="#" class="delete-account" id="delete-account-link">Delete Account</a>
      </div>
      <button type="button" class="save-changes-btn" id="save-changes-btn" style="display: none;">Save Changes</button>
      <button type="button" class="logout-btn" onclick="window.location.href='../logout.php'">Log Out</button>
    </form>
  </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
  <div style="background-color: white; margin: 15% auto; padding: 20px; border-radius: 10px; width: 80%; max-width: 400px; text-align: center;">
    <div style="margin-bottom: 20px;">
      <img src="https://cdn-icons-png.flaticon.com/512/860/860829.png" alt="Trash Icon" style="width: 54px; height: 54px;">
    </div>
    <div style="font-size: 18px; font-weight: bold; margin-bottom: 10px;">You are about to delete your profile</div>
    <div style="color: #666; margin-bottom: 20px;">Are you sure?</div>
    <div style="display: flex; gap: 10px; justify-content: center;">
      <button id="cancelDeleteBtn" style="background: #ccc; color: #333; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Cancel</button>
      <button id="confirmDeleteBtn" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Delete</button>
    </div>
  </div>
</div>

<script>
// Variables to track changes
let hasChanges = false;
let pendingChanges = {};

// Function to show save button when changes are made
function showSaveButton() {
  if (hasChanges) {
    document.getElementById('save-changes-btn').style.display = 'block';
  } else {
    document.getElementById('save-changes-btn').style.display = 'none';
  }
}

// Function to track changes
function trackChange(field, value) {
  pendingChanges[field] = value;
  hasChanges = true;
  showSaveButton();
}

// Save changes function
function saveChanges() {
  if (!hasChanges) return;

  const promises = [];
  
  // Process each pending change
  for (const [field, value] of Object.entries(pendingChanges)) {
    let action, data;
    
    switch(field) {
      case 'full_name':
        action = 'update_name';
        data = `action=${action}&full_name=${encodeURIComponent(value)}`;
        break;
      case 'email':
        action = 'update_email';
        data = `action=${action}&email=${encodeURIComponent(value)}`;
        break;
      case 'password':
        action = 'update_password';
        data = `action=${action}&password=${encodeURIComponent(value)}`;
        break;
      case 'gender':
        action = 'update_gender';
        data = `action=${action}&gender=${encodeURIComponent(value)}`;
        break;
      case 'birthday':
        action = 'update_birthday';
        data = `action=${action}&birthday=${encodeURIComponent(value)}`;
        break;
    }
    
    if (action && data) {
      promises.push(
        fetch('update_account.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: data
        }).then(response => response.json())
      );
    }
  }
  
  // Execute all updates
  Promise.all(promises)
    .then(results => {
      const success = results.every(result => result.success);
      if (success) {
        // Clear pending changes
        pendingChanges = {};
        hasChanges = false;
        showSaveButton();
        
        // Update button text temporarily
        const saveBtn = document.getElementById('save-changes-btn');
        const originalText = saveBtn.textContent;
        saveBtn.textContent = 'Changes Saved!';
        saveBtn.style.backgroundColor = '#4CAF50';
        
        setTimeout(() => {
          saveBtn.textContent = originalText;
          saveBtn.style.backgroundColor = '';
        }, 2000);
      }
    })
    .catch(error => {
      console.error('Error saving changes:', error);
    });
}

// Name edit functionality
const nameInput = document.getElementById('account-name');
let isNameEditing = false;

nameInput.addEventListener('click', function() {
  if (!isNameEditing) {
    nameInput.readOnly = false;
    nameInput.style.backgroundColor = '#f9f9f9';
    nameInput.style.border = '1px solid #2992f5';
    isNameEditing = true;
  }
});

nameInput.addEventListener('input', function() {
  trackChange('full_name', nameInput.value.trim());
});

nameInput.addEventListener('blur', function() {
  if (isNameEditing) {
    const newName = nameInput.value.trim();
    
    if (newName === '') {
      nameInput.value = '<?php echo htmlspecialchars($user['full_name']); ?>';
      delete pendingChanges.full_name;
      hasChanges = Object.keys(pendingChanges).length > 0;
      showSaveButton();
    }
    
    nameInput.readOnly = true;
    nameInput.style.backgroundColor = 'transparent';
    nameInput.style.border = 'none';
    isNameEditing = false;
  }
});

// Email edit functionality
const emailInput = document.getElementById('account-email');
let isEmailEditing = false;

emailInput.addEventListener('click', function() {
  if (!isEmailEditing) {
    emailInput.readOnly = false;
    emailInput.style.backgroundColor = '#f9f9f9';
    emailInput.style.border = '1px solid #2992f5';
    isEmailEditing = true;
  }
});

emailInput.addEventListener('input', function() {
  trackChange('email', emailInput.value.trim());
});

emailInput.addEventListener('blur', function() {
  if (isEmailEditing) {
    const newEmail = emailInput.value.trim();
    
    if (newEmail === '' || !newEmail.includes('@')) {
      emailInput.value = '<?php echo htmlspecialchars($user['email']); ?>';
      delete pendingChanges.email;
      hasChanges = Object.keys(pendingChanges).length > 0;
      showSaveButton();
    }
    
    emailInput.readOnly = true;
    emailInput.style.backgroundColor = 'transparent';
    emailInput.style.border = 'none';
    isEmailEditing = false;
  }
});

// Password edit functionality
const passwordInput = document.getElementById('account-password');
let isPasswordEditing = false;

passwordInput.addEventListener('click', function() {
  if (!isPasswordEditing) {
    passwordInput.readOnly = false;
    passwordInput.type = 'text';
    passwordInput.value = '';
    passwordInput.placeholder = 'Enter new password';
    passwordInput.style.backgroundColor = '#f9f9f9';
    passwordInput.style.border = '1px solid #2992f5';
    isPasswordEditing = true;
  }
});

passwordInput.addEventListener('input', function() {
  trackChange('password', passwordInput.value);
});

passwordInput.addEventListener('blur', function() {
  if (isPasswordEditing) {
    const newPassword = passwordInput.value;
    
    if (newPassword === '') {
      passwordInput.value = '••••••••';
      passwordInput.placeholder = '';
      delete pendingChanges.password;
      hasChanges = Object.keys(pendingChanges).length > 0;
      showSaveButton();
    } else if (newPassword.length < 6) {
      passwordInput.value = '••••••••';
      passwordInput.placeholder = '';
      delete pendingChanges.password;
      hasChanges = Object.keys(pendingChanges).length > 0;
      showSaveButton();
    }
    
    passwordInput.readOnly = true;
    passwordInput.type = 'password';
    passwordInput.style.backgroundColor = 'transparent';
    passwordInput.style.border = 'none';
    isPasswordEditing = false;
  }
});

// Gender edit functionality
const genderInput = document.getElementById('account-gender');
let isGenderEditing = false;

genderInput.addEventListener('click', function() {
  if (!isGenderEditing) {
    genderInput.readOnly = false;
    genderInput.style.backgroundColor = '#f9f9f9';
    genderInput.style.border = '1px solid #2992f5';
    isGenderEditing = true;
  }
});

genderInput.addEventListener('input', function() {
  trackChange('gender', genderInput.value.trim());
});

genderInput.addEventListener('blur', function() {
  if (isGenderEditing) {
    genderInput.readOnly = true;
    genderInput.style.backgroundColor = 'transparent';
    genderInput.style.border = 'none';
    isGenderEditing = false;
  }
});

// Birthday edit functionality
const birthdayInput = document.getElementById('account-birthday');
let isBirthdayEditing = false;

birthdayInput.addEventListener('click', function() {
  if (!isBirthdayEditing) {
    birthdayInput.readOnly = false;
    birthdayInput.style.backgroundColor = '#f9f9f9';
    birthdayInput.style.border = '1px solid #2992f5';
    isBirthdayEditing = true;
  }
});

birthdayInput.addEventListener('input', function() {
  trackChange('birthday', birthdayInput.value);
});

birthdayInput.addEventListener('blur', function() {
  if (isBirthdayEditing) {
    birthdayInput.readOnly = true;
    birthdayInput.style.backgroundColor = 'transparent';
    birthdayInput.style.border = 'none';
    isBirthdayEditing = false;
  }
});

// Save changes button event listener
document.getElementById('save-changes-btn').addEventListener('click', saveChanges);

// Delete account modal logic
const deleteLink = document.getElementById('delete-account-link');
const deleteModal = document.getElementById('deleteModal');
const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

deleteLink.addEventListener('click', function(e) {
  e.preventDefault();
  deleteModal.style.display = 'block';
});

cancelDeleteBtn.addEventListener('click', function() {
  deleteModal.style.display = 'none';
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
      // Redirect to index.php
      setTimeout(() => {
        window.location.href = data.redirect;
      }, 1000);
    } else {
      deleteModal.style.display = 'none';
    }
  })
  .catch(error => {
    console.error('Error deleting account:', error);
    deleteModal.style.display = 'none';
  });
});

// Close modal when clicking outside
window.addEventListener('click', function(event) {
  if (event.target === deleteModal) {
    deleteModal.style.display = 'none';
  }
});

// Always redirect user icon in header to viewacc.php when clicked
window.addEventListener('DOMContentLoaded', function() {
  var loginLink = document.querySelector('.login-link a');
  if (loginLink) {
    loginLink.addEventListener('click', function(e) {
      e.preventDefault();
      window.location.href = 'viewacc.php';
    });
  }
});
</script>
