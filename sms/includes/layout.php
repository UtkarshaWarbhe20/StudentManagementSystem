<?php
// includes/layout.php
// Usage: include this file after defining $page_title, $active_nav, $role
require_once __DIR__ . '/auth.php';
$user = current_user();

$is_admin = ($user['role'] === 'admin');

$admin_nav = [
  ['id'=>'dashboard', 'icon'=>'📊', 'label'=>'Dashboard',  'href'=>'dashboard.php'],
  ['id'=>'students',  'icon'=>'🎓', 'label'=>'Students',   'href'=>'students.php'],
  ['id'=>'notices',   'icon'=>'📋', 'label'=>'Notices',    'href'=>'notices.php'],
  ['id'=>'schedules', 'icon'=>'🗓', 'label'=>'Schedules',  'href'=>'schedules.php'],
];
$student_nav = [
  ['id'=>'home',        'icon'=>'🏠', 'label'=>'Home',      'href'=>'home.php'],
  ['id'=>'notices',     'icon'=>'📋', 'label'=>'Notices',   'href'=>'notices.php'],
  ['id'=>'schedule',    'icon'=>'🗓', 'label'=>'Schedule',  'href'=>'schedule.php'],
  ['id'=>'profile',     'icon'=>'👤', 'label'=>'Profile',   'href'=>'profile.php'],
];
$nav_items = $is_admin ? $admin_nav : $student_nav;
$logout_url = $is_admin ? '../logout.php' : '../logout.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title ?? 'SMS Portal') ?></title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="shell">

  <!-- Top bar -->
  <header class="topbar">
    <div class="topbar-brand">
      <span>SMS Portal</span>
      <span class="topbar-badge"><?= $is_admin ? 'Admin' : 'Student' ?></span>
    </div>
    <div class="topbar-right">
      <div>
        <div class="topbar-user-name"><?= htmlspecialchars($user['name']) ?></div>
        <div class="topbar-user-role"><?= $is_admin ? 'Administrator' : htmlspecialchars($user['dept'].' · '.$user['year']) ?></div>
      </div>
      <a href="<?= $logout_url ?>" class="btn-logout">Sign out</a>
    </div>
  </header>

  <!-- Sidebar -->
  <nav class="sidebar">
    <div class="nav-section-label"><?= $is_admin ? 'Management' : 'My Portal' ?></div>
    <?php foreach ($nav_items as $item): ?>
      <a href="<?= $item['href'] ?>"
         class="nav-item <?= ($active_nav??'') === $item['id'] ? 'active' : '' ?>">
        <span class="nav-icon"><?= $item['icon'] ?></span>
        <?= $item['label'] ?>
      </a>
    <?php endforeach; ?>
  </nav>

  <!-- Main content -->
  <main class="main-content">
    <div class="page-body">
