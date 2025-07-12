<?php include '../includes/header.php'; ?>
<link rel="stylesheet" href="../assets/css/view_account.css">

<div class="account-viewport-center">
  <div class="account-container">
    <div class="account-title">My Account</div>
    <div class="account-subtitle">Manage your StepIntoManila profile and security settings</div>
    <div class="account-card">
      <div class="account-section">
        <div class="account-section-title">
          <img src="https://cdn-icons-png.flaticon.com/512/266/266033.png" alt="Avatar Icon" style="width: 22px; height: 22px; vertical-align: middle; margin-right: 8px;"> Personal Information
          <span style="flex:1"></span>
          <button id="edit-name-btn" style="background:none;border:none;cursor:pointer;margin-left:8px;vertical-align:middle;">
            <img src="https://cdn-icons-png.flaticon.com/512/1250/1250615.png" alt="Edit Icon" style="width: 18px; vertical-align: middle;">
          </button>
        </div>
        <div class="account-section-content">Name:
          <input type="text" id="account-name" value="John Doe" style="border:none;background:transparent;color:#bbb;font-size:1rem;margin-left:8px;width:200px;outline:none;" readonly>
          <button id="save-name-btn" style="display:none;background:#2992f5;color:#fff;border:none;border-radius:4px;padding:2px 10px;margin-left:8px;cursor:pointer;font-size:0.95rem;">Save</button>
        </div>
      </div>
      <div class="account-section">
        <div class="account-section-title">
          <img src="https://icons.iconarchive.com/icons/icons8/windows-8/512/Security-Password-2-icon.png" alt="Security Icon" style="width: 22px; height: 22px; vertical-align: middle; margin-right: 8px;"> Sign in &amp; Security
          <span style="flex:1"></span>
          <button id="edit-security-btn" style="background:none;border:none;cursor:pointer;margin-left:8px;vertical-align:middle;">
            <img src="https://cdn-icons-png.flaticon.com/512/1250/1250615.png" alt="Edit Icon" style="width: 18px; vertical-align: middle;">
          </button>
        </div>
        <div class="account-section-content">Email:
          <input type="email" id="account-email" value="johndoe@gmail.com" style="border:none;background:transparent;color:#bbb;font-size:1rem;margin-left:8px;width:220px;outline:none;" readonly>
          <button id="save-security-btn" style="display:none;background:#2992f5;color:#fff;border:none;border-radius:4px;padding:2px 10px;margin-left:8px;cursor:pointer;font-size:0.95rem;">Save</button>
        </div>
        <div class="account-section-content">Password:
          <input type="password" id="account-password" value="password123" style="border:none;background:transparent;color:#bbb;font-size:1rem;margin-left:8px;width:120px;outline:none;" readonly>
        </div>
      </div>
      <div class="account-section">
        <div class="account-section-title">
          <img src="https://img.icons8.com/ios-filled/50/000000/info.png" alt="Info Icon" style="width: 22px; height: 22px; vertical-align: middle; margin-right: 8px;"> Manage Account
          <span style="flex:1"></span>
        </div>
        <a href="#" class="delete-link">delete account</a>
      </div>
    </div>
    <a href="../index.php" class="logout-btn">Logout</a>
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
  nameInput.readOnly = true;
  nameInput.style.color = '#bbb';
  saveNameBtn.style.display = 'none';
  editNameBtn.style.display = 'inline-block';
  // Here you would add AJAX to save the new name to the server
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
  passwordInput.style.color = '#222';
  emailInput.focus();
  saveSecurityBtn.style.display = 'inline-block';
  editSecurityBtn.style.display = 'none';
});

saveSecurityBtn.addEventListener('click', function() {
  emailInput.readOnly = true;
  emailInput.style.color = '#bbb';
  passwordInput.readOnly = true;
  passwordInput.type = 'password';
  passwordInput.style.color = '#bbb';
  saveSecurityBtn.style.display = 'none';
  editSecurityBtn.style.display = 'inline-block';
  // Here you would add AJAX to save the new email/password to the server
});

emailInput.addEventListener('keydown', function(e) {
  if (e.key === 'Enter') {
    saveSecurityBtn.click();
  }
});
passwordInput.addEventListener('keydown', function(e) {
  if (e.key === 'Enter') {
    saveSecurityBtn.click();
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
  // TODO: Add your account deletion logic here (AJAX or redirect)
  alert('Account deleted! (Implement actual deletion logic)');
  deleteModal.classList.remove('active');
});
</script> 