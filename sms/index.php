<?php
require_once 'includes/auth.php';

// Already logged in? redirect
$u = current_user();
if ($u) {
    header('Location: ' . ($u['role']==='admin' ? 'admin/dashboard.php' : 'student/home.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = attempt_login($_POST['user_id'] ?? '', $_POST['password'] ?? '');
    if ($result['ok']) {
        login_user($result);
        header('Location: ' . ($result['role']==='admin' ? 'admin/dashboard.php' : 'student/home.php'));
        exit;
    } else {
        $error = $result['error'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SMS Portal – Login</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="login-wrap">
  <div class="login-card">

    <div class="login-logo">
      <div class="icon-wrap">
        <svg viewBox="0 0 24 24"><path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/></svg>
      </div>
      <h1>Student Management System</h1>
      <p>School Administration Portal</p>
    </div>

    <!-- Role tabs -->
    <div class="tabs">
      <button class="tab-btn active" id="tab-admin"   onclick="switchRole('admin')">Admin</button>
      <button class="tab-btn"        id="tab-student" onclick="switchRole('student')">Student</button>
    </div>

    <form method="POST" action="">
      <input type="hidden" name="role" id="roleField" value="admin">

      <div class="form-group">
        <label id="idLabel">Admin ID</label>
        <input type="text" name="user_id" id="userId"
               placeholder="Enter your ID" required autocomplete="username"
               value="<?= htmlspecialchars($_POST['user_id'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter password"
               required autocomplete="current-password">
      </div>

      <?php if ($error): ?>
        <p class="error-msg"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <button type="submit" class="btn btn-primary" style="margin-top:.5rem">Sign In</button>
    </form>

    <div class="login-hint">
      <strong>Admin:</strong> ADMIN001 / admin123<br>
      <strong>Students:</strong> STU001–STU005 / pass123
    </div>

  </div>
</div>

<script>
function switchRole(role) {
  document.getElementById('roleField').value = role;
  document.getElementById('idLabel').textContent = role === 'admin' ? 'Admin ID' : 'Student ID';
  document.getElementById('tab-admin').classList.toggle('active', role==='admin');
  document.getElementById('tab-student').classList.toggle('active', role==='student');
  document.getElementById('userId').placeholder = role==='admin' ? 'e.g. ADMIN001' : 'e.g. STU001';
}
</script>
</body>
</html>
