<?php
require_once '../includes/auth.php';
require_student();

$user = current_user();
$m    = &mock_store();
$msg  = '';

// Change password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $_POST['old_pass'] ?? '';
    $new = $_POST['new_pass'] ?? '';
    $con = $_POST['confirm']  ?? '';

    // Find student in mock store
    $found = false;
    foreach ($m['students'] as &$s) {
        if ($s['id'] === $user['id']) {
            if (!password_verify($old, $s['pass'])) {
                $msg = ['type'=>'danger','text'=>'Current password is incorrect.'];
            } elseif (strlen($new) < 6) {
                $msg = ['type'=>'danger','text'=>'New password must be at least 6 characters.'];
            } elseif ($new !== $con) {
                $msg = ['type'=>'danger','text'=>'New passwords do not match.'];
            } else {
                $s['pass'] = password_hash($new, PASSWORD_DEFAULT);
                $msg = ['type'=>'success','text'=>'Password changed successfully.'];
            }
            $found = true;
            break;
        }
    }
}

$page_title = 'Profile – Student Portal';
$active_nav = 'profile';
include '../includes/layout.php';
?>

<div class="page-hd">
  <h2>My Profile</h2>
  <p>Your personal details and account settings</p>
</div>

<div class="profile-hero">
  <div class="profile-avatar-lg"><?= initials($user['name']) ?></div>
  <div>
    <div class="name"><?= htmlspecialchars($user['name']) ?></div>
    <div class="meta"><?= htmlspecialchars($user['dept']) ?> &middot; <?= htmlspecialchars($user['year']) ?></div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem">

  <!-- Details -->
  <div class="card">
    <div class="card-hd"><h3>Account Details</h3></div>
    <table class="data-table">
      <tr><td style="color:var(--gray5);width:40%">Student ID</td>
          <td style="font-family:'DM Mono',monospace;font-size:12px"><?= $user['id'] ?></td></tr>
      <tr><td style="color:var(--gray5)">Full Name</td>
          <td><?= htmlspecialchars($user['name']) ?></td></tr>
      <tr><td style="color:var(--gray5)">Email</td>
          <td><?= htmlspecialchars($user['email']) ?></td></tr>
      <tr><td style="color:var(--gray5)">Department</td>
          <td><?= htmlspecialchars($user['dept']) ?></td></tr>
      <tr><td style="color:var(--gray5)">Year</td>
          <td><?= htmlspecialchars($user['year']) ?></td></tr>
    </table>
  </div>

  <!-- Change password -->
  <div class="card">
    <div class="card-hd"><h3>Change Password</h3></div>

    <?php if ($msg): ?>
      <div class="alert alert-<?= $msg['type'] ?>" style="margin-bottom:1rem">
        <?= htmlspecialchars($msg['text']) ?>
      </div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group-m">
        <label>Current Password</label>
        <input type="password" name="old_pass" required>
      </div>
      <div class="form-group-m">
        <label>New Password</label>
        <input type="password" name="new_pass" required minlength="6">
      </div>
      <div class="form-group-m">
        <label>Confirm New Password</label>
        <input type="password" name="confirm" required>
      </div>
      <button type="submit" class="btn btn-accent" style="width:100%">Update Password</button>
    </form>
  </div>

</div>

<?php include '../includes/layout_end.php'; ?>
