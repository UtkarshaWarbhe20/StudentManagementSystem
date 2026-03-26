<?php
require_once '../includes/auth.php';
require_admin();

$m   = &mock_store();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'toggle_absent') {
        $tid = (int)$_POST['sched_id'];
        foreach ($m['schedules'] as &$s) {
            if ($s['id'] === $tid) { $s['absent'] = !$s['absent']; break; }
        }
        $msg = ['type'=>'info','text'=>'Schedule updated.'];
    }

    if ($action === 'add') {
        $time    = trim($_POST['time']);
        $subject = trim($_POST['subject']);
        $teacher = trim($_POST['teacher']);
        $room    = trim($_POST['room']);
        if (!$time || !$subject || !$teacher || !$room) {
            $msg = ['type'=>'danger','text'=>'All fields are required.'];
        } else {
            $m['schedules'][] = [
                'id'      => $m['next_schedule_id']++,
                'time'    => $time,
                'subject' => $subject,
                'teacher' => $teacher,
                'room'    => $room,
                'absent'  => false,
            ];
            $msg = ['type'=>'success','text'=>'Period added to schedule.'];
        }
    }

    if ($action === 'delete') {
        $del = (int)$_POST['del_id'];
        $m['schedules'] = array_values(array_filter($m['schedules'], fn($s) => $s['id'] !== $del));
        $msg = ['type'=>'success','text'=>'Period removed.'];
    }
}

$schedules  = get_schedules();
$page_title = 'Schedules – SMS Admin';
$active_nav = 'schedules';
include '../includes/layout.php';
?>

<div class="page-hd">
  <h2>Today's Schedule</h2>
  <p><?= date('l, d F Y') ?> — Mark teachers absent/present and manage periods</p>
</div>

<?php if ($msg): ?>
  <div class="alert alert-<?= $msg['type'] ?>"><?= htmlspecialchars($msg['text']) ?></div>
<?php endif; ?>

<div class="card">
  <div class="card-hd">
    <h3>All Periods (<?= count($schedules) ?>)</h3>
    <button class="btn btn-sm btn-accent" onclick="openAddModal()">+ Add Period</button>
  </div>

  <div class="sched-list">
  <?php foreach ($schedules as $s): ?>
    <div class="sched-row <?= $s['absent'] ? 'absent' : '' ?>">
      <div class="sched-time"><?= htmlspecialchars($s['time']) ?></div>
      <div>
        <div class="sched-sub"><?= htmlspecialchars($s['subject']) ?></div>
        <div class="sched-teacher">
          <?= $s['absent'] ? '⚠ Absent: ' : '' ?>
          <?= htmlspecialchars($s['teacher']) ?>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;justify-content:flex-end">
        <span class="room-badge"><?= $s['absent'] ? 'Cancelled' : htmlspecialchars($s['room']) ?></span>

        <form method="POST" style="display:inline">
          <input type="hidden" name="action"   value="toggle_absent">
          <input type="hidden" name="sched_id" value="<?= $s['id'] ?>">
          <button type="submit" class="btn btn-sm <?= $s['absent'] ? 'btn-success' : 'btn-ghost' ?>" style="font-size:11px;padding:4px 10px">
            <?= $s['absent'] ? 'Mark Present' : 'Mark Absent' ?>
          </button>
        </form>

        <form method="POST" style="display:inline"
              onsubmit="return confirm('Remove this period?')">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="del_id" value="<?= $s['id'] ?>">
          <button type="submit" class="btn btn-sm btn-danger" style="font-size:11px;padding:4px 10px">Remove</button>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
  </div>
</div>

<script>
function openAddModal() {
  openModal('Add Class Period', `
    <form method="POST">
      <input type="hidden" name="action" value="add">
      <div class="form-grid">
        <div class="form-group-m">
          <label>Time Slot</label>
          <input name="time" placeholder="e.g. 3:15–4:15" required>
        </div>
        <div class="form-group-m">
          <label>Room</label>
          <input name="room" placeholder="e.g. B-301" required>
        </div>
        <div class="form-group-m span-2">
          <label>Subject</label>
          <input name="subject" placeholder="e.g. Machine Learning" required>
        </div>
        <div class="form-group-m span-2">
          <label>Teacher</label>
          <input name="teacher" placeholder="e.g. Prof. Aasha Kulkarni" required>
        </div>
      </div>
      <div class="form-actions">
        <button type="button" class="btn btn-ghost" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn btn-accent">Add Period</button>
      </div>
    </form>
  `);
}
</script>

<?php include '../includes/layout_end.php'; ?>
