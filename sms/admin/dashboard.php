<?php
require_once '../includes/auth.php';
require_admin();

$notices   = get_notices();
$schedules = get_schedules();
$students  = get_students();

$absent_count = count(array_filter($schedules, fn($s) => $s['absent']));

$page_title = 'Dashboard – SMS Admin';
$active_nav = 'dashboard';
include '../includes/layout.php';
?>

<div class="page-hd">
  <h2>Good morning, <?= htmlspecialchars(explode(' ', current_user()['name'])[0]) ?>.</h2>
  <p>Here's an overview — <?= date('l, d F Y') ?></p>
</div>

<!-- Stats -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="label">Total Students</div>
    <div class="value"><?= count($students) ?></div>
    <div class="sub">Enrolled</div>
  </div>
  <div class="stat-card">
    <div class="label">Active Notices</div>
    <div class="value"><?= count($notices) ?></div>
    <div class="sub">Posted</div>
  </div>
  <div class="stat-card">
    <div class="label">Classes Today</div>
    <div class="value"><?= count($schedules) ?></div>
    <div class="sub"><?= $absent_count ?> teacher<?= $absent_count!==1?'s':'' ?> absent</div>
  </div>
  <div class="stat-card">
    <div class="label">Departments</div>
    <div class="value">4</div>
    <div class="sub">Active</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

  <!-- Recent notices -->
  <div class="card">
    <div class="card-hd">
      <h3>Recent Notices</h3>
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

  <!-- Absent teachers alert -->
  <div class="card">
    <div class="card-hd">
      <h3>Schedule Alerts Today</h3>
      <a href="schedules.php" class="btn btn-sm btn-ghost">Manage</a>
    </div>
    <?php $absents = array_filter($schedules, fn($s) => $s['absent']); ?>
    <?php if ($absents): ?>
      <?php foreach ($absents as $s): ?>
      <div class="alert alert-warning">
        ⚠ <strong><?= htmlspecialchars($s['subject']) ?></strong>
        (<?= $s['time'] ?>) — <?= htmlspecialchars($s['teacher']) ?> is absent.
      </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="alert alert-success">✓ All teachers present today.</div>
    <?php endif; ?>
  </div>

</div>

<?php include '../includes/layout_end.php'; ?>
