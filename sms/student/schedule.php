<?php
require_once '../includes/auth.php';
require_student();

$schedules  = get_schedules();
$page_title = 'Schedule – Student Portal';
$active_nav = 'schedule';
include '../includes/layout.php';

$absent_count = count(array_filter($schedules, fn($s) => $s['absent']));
?>

<div class="page-hd">
  <h2>Today's Schedule</h2>
  <p><?= date('l, d F Y') ?></p>
</div>

<?php if ($absent_count > 0): ?>
<div class="alert alert-warning" style="margin-bottom:1rem">
  ⚠ <?= $absent_count ?> class<?= $absent_count>1?'es are':' is' ?> cancelled today due to teacher absence.
</div>
<?php endif; ?>

<div class="card">
  <div class="sched-list">
  <?php foreach ($schedules as $s): ?>
    <div class="sched-row <?= $s['absent'] ? 'absent' : '' ?>">
      <div class="sched-time"><?= htmlspecialchars($s['time']) ?></div>
      <div>
        <div class="sched-sub"><?= htmlspecialchars($s['subject']) ?></div>
        <div class="sched-teacher">
          <?php if ($s['absent']): ?>
            ⚠ Class cancelled — teacher absent
          <?php else: ?>
            <?= htmlspecialchars($s['teacher']) ?>
          <?php endif; ?>
        </div>
      </div>
      <span class="room-badge">
        <?= $s['absent'] ? 'Cancelled' : htmlspecialchars($s['room']) ?>
      </span>
    </div>
  <?php endforeach; ?>
  </div>
</div>

<?php include '../includes/layout_end.php'; ?>
