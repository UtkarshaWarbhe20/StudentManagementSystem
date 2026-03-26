<?php
require_once '../includes/auth.php';
require_student();

$notices   = get_notices();
$schedules = get_schedules();
$user      = current_user();

$absent = array_filter($schedules, fn($s) => $s['absent']);

$page_title = 'Home – Student Portal';
$active_nav = 'home';
include '../includes/layout.php';
?>

<div class="page-hd">
  <h2>Welcome, <?= htmlspecialchars(explode(' ', $user['name'])[0]) ?>!</h2>
  <p><?= htmlspecialchars($user['dept']) ?> &middot; <?= htmlspecialchars($user['year']) ?> &middot; <?= date('l, d F Y') ?></p>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <div class="label">Notices</div>
    <div class="value"><?= count($notices) ?></div>
    <div class="sub">Posted this month</div>
  </div>
  <div class="stat-card">
    <div class="label">Classes Today</div>
    <div class="value"><?= count($schedules) ?></div>
    <div class="sub"><?= count($absent) ?> cancelled</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

  <!-- Latest notices -->
  <div class="card">
    <div class="card-hd">
      <h3>Latest Notices</h3>
      <a href="notices.php" class="btn btn-sm btn-ghost">View all</a>
    </div>
    <div class="notice-list">
      <?php foreach (array_slice($notices, 0, 3) as $n): ?>
      <div class="notice-item">
        <div class="notice-item-hd">
          <span class="notice-title"><?= htmlspecialchars($n['title']) ?></span>
          <span class="notice-date"><?= fmt_date($n['date']) ?></span>
        </div>
        <span class="tag tag-<?= $n['tag'] ?>"><?= ucfirst($n['tag']) ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Today's alerts -->
  <div class="card">
    <div class="card-hd">
      <h3>Today's Alerts</h3>
      <a href="schedule.php" class="btn btn-sm btn-ghost">Full schedule</a>
    </div>
    <?php if ($absent): ?>
      <?php foreach ($absent as $s): ?>
      <div class="alert alert-warning">
        Class cancelled: <strong><?= htmlspecialchars($s['subject']) ?></strong>
        (<?= $s['time'] ?>) — teacher absent.
      </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="alert alert-success">✓ No cancellations today. All classes are on.</div>
    <?php endif; ?>
  </div>

</div>

<?php include '../includes/layout_end.php'; ?>
