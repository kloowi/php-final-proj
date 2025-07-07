<?php include '../includes/header.php'; ?>

<style>
body {
  background: #fff;
  background: #f6f8fa;
}
.account-container {
  max-width: 1200px;
  margin: 120px auto 0 auto;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.account-title {
  background: #fafafa;
  border-radius: 24px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.12);
  text-align: center;
  font-size: 2.625rem;
  font-weight: 700;
  color: #2992f5;
  padding: 18px 0 10px 0;
  margin-top: 40px;
  margin-bottom: 10px;
  width: 100%;
  max-width: 1200px;
  margin-left: auto;
  margin-right: auto;
}
.account-subtitle {
  color: #888;
  margin-bottom: 50px;
  font-size: 1rem;
  text-align: left;
  width: 100%;
  margin-left: 20px;
}
.account-card {
  width: 100%;
  max-width: 1200px;
  background: #fafafa;
  border-radius: 24px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.13);
  padding: 0;
  overflow: hidden;
  margin-bottom: 32px;
  margin-left: auto;
  margin-right: auto;
}
.account-section {
  padding: 32px 48px 24px 48px;
  border-bottom: 1px solid #e0e0e0;
  transition: background 0.2s;
}
.account-section:last-child {
  border-bottom: none;
  padding-bottom: 32px;
}
.account-section:hover {
  background: #f0f4f8;
}
.account-section-title {
  font-weight: 700;
  font-size: 1.325rem;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.account-section-title .icon {
  font-size: 1.2em;
  color: #2992f5;
}
.account-section-content {
  margin-left: 28px;
  color: #888;
  font-size: 1.225rem;
  line-height: 1.5;
}
.account-section-content span {
  color: #888;
  margin-left: 8px;
  font-size: 1.225rem;
  line-height: 1.5;
}
.delete-link {
  color: #e43e2b;
  text-decoration: none;
  font-weight: 500;
  margin-left: 28px;
  display: inline-block;
  margin-top: 4px;
}
.logout-btn {
  display: block;
  background: #2992f5;
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 14px 44px;
  font-size: 1.225rem;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s, box-shadow 0.2s;
  margin-left: 48px;
  margin-top: 48px;
  box-shadow: 0 2px 8px rgba(41,146,245,0.08);
}
.logout-btn:hover {
  background: #1877c9;
  box-shadow: 0 4px 16px rgba(41,146,245,0.13);
}
#account-name, #account-email, #account-password {
  font-size: 1.125rem;
  padding: 2px 6px;
  border-radius: 4px;
}
</style>

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
  <div style="max-width: 1200px; margin: 0 auto; width: 100%;">
    <button class="logout-btn" style="margin-left: 0;">Logout</button>
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
</script> 