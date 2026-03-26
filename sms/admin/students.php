<?php
require_once '../includes/auth.php';
require_admin();

$m = &mock_store();
$msg = '';

// ── Handle POST actions ───────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $new = [
            'id'   => strtoupper(trim($_POST['id'])),
            'name' => trim($_POST['name']),
            'dept' => $_POST['dept'],
            'year' => $_POST['year'],
            'email'=> trim($_POST['email']),
            'pass' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        ];
        // Check duplicate ID
        $exists = array_filter($m['students'], fn($s) => $s['id'] === $new['id']);
        if ($exists) {
            $msg = ['type'=>'danger', 'text'=>'Student ID "'.$new['id'].'" already exists.'];
        } elseif (!$new['name'] || !$new['id'] || !$new['email'] || !$_POST['password']) {
            $msg = ['type'=>'danger', 'text'=>'Please fill all fields.'];
        } else {
            $m['students'][] = $new;
            $msg = ['type'=>'success', 'text'=>'Student '.$new['name'].' added successfully.'];
        }
    }

    if ($action === 'delete') {
        $del_id = $_POST['del_id'];
        $m['students'] = array_values(array_filter($m['students'], fn($s) => $s['id'] !== $del_id));
        $msg = ['type'=>'success', 'text'=>'Student removed.'];
    }
}

$students = get_students();
$page_title = 'Students – SMS Admin';
$active_nav = 'students';
include '../includes/layout.php';
?>

<div class="page-hd">
  <h2>Students</h2>
  <p>Manage student accounts and enrolment records</p>
</div>

<?php if ($msg): ?>
  <div class="alert alert-<?= $msg['type'] ?>"><?= htmlspecialchars($msg['text']) ?></div>
<?php endif; ?>

<div class="card">
  <div class="card-hd">
    <h3>All Students (<?= count($students) ?>)</h3>
    <button class="btn btn-sm btn-accent" onclick="openAddModal()">+ Add Student</button>
  </div>

  <table class="data-table">
    <thead>
      <tr>
        <th>Student</th>
        <th>ID</th>
        <th>Department</th>
        <th>Year</th>
        <th>Email</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($students as $s): ?>
      <tr>
        <td>
          <span class="avatar"><?= initials($s['name']) ?></span>
          <?= htmlspecialchars($s['name']) ?>
        </td>
        <td style="font-family:'DM Mono',monospace;font-size:12px"><?= $s['id'] ?></td>
        <td><?= htmlspecialchars($s['dept']) ?></td>
        <td><?= htmlspecialchars($s['year']) ?></td>
        <td><?= htmlspecialchars($s['email']) ?></td>
        <td>
          <form method="POST" style="display:inline"
                onsubmit="return confirm('Remove this student?')">
            <input type="hidden" name="action"  value="delete">
            <input type="hidden" name="del_id"  value="<?= $s['id'] ?>">
            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
function openAddModal() {
  openModal('Create New Student Account', `
    <form method="POST">
      <input type="hidden" name="action" value="add">
      <div class="form-grid">
        <div class="form-group-m">
          <label>Full Name</label>
          <input name="name" placeholder="e.g. Vikram Mehta" required>
        </div>
        <div class="form-group-m">
          <label>Student ID</label>
          <input name="id" placeholder="e.g. STU006" required>
        </div>
        <div class="form-group-m">
          <label>Department</label>
          <select name="dept">
            <option>Computer Science</option>
            <option>Electronics</option>
            <option>Mechanical</option>
            <option>Civil</option>
          </select>
        </div>
        <div class="form-group-m">
          <label>Year</label>
          <select name="year">
            <option>1st Year</option>
            <option>2nd Year</option>
            <option>3rd Year</option>
            <option>4th Year</option>
          </select>
        </div>
        <div class="form-group-m">
          <label>Email</label>
          <input name="email" type="email" placeholder="student@sms.edu" required>
        </div>
        <div class="form-group-m">
          <label>Password</label>
          <input name="password" type="password" placeholder="Initial password" required>
        </div>
      </div>
      <div class="form-actions">
        <button type="button" class="btn btn-ghost" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn btn-accent">Create Account</button>
      </div>
    </form>
  `);
}
</script>

<?php include '../includes/layout_end.php'; ?>
