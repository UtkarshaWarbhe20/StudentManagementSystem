<?php
require_once '../includes/auth.php';
require_student();

$notices    = get_notices();
$page_title = 'Notices – Student Portal';
$active_nav = 'notices';
include '../includes/layout.php';
?>

<div class="page-hd">
  <h2>Notice Board</h2>
  <p>All announcements and updates from the administration</p>
</div>

<!-- Filter bar -->
<div style="display:flex;gap:8px;margin-bottom:1.25rem;flex-wrap:wrap">
  <button class="btn btn-sm btn-ghost filter-btn active" data-tag="all" onclick="filterTag(this,'all')">All</button>
  <button class="btn btn-sm filter-btn" data-tag="exam"    onclick="filterTag(this,'exam')"    style="background:var(--purple-l);color:var(--purple)">Exam</button>
  <button class="btn btn-sm filter-btn" data-tag="holiday" onclick="filterTag(this,'holiday')" style="background:var(--green-l);color:var(--green)">Holiday</button>
  <button class="btn btn-sm filter-btn" data-tag="general" onclick="filterTag(this,'general')" style="background:var(--blue-l);color:var(--blue-t)">General</button>
  <button class="btn btn-sm filter-btn" data-tag="urgent"  onclick="filterTag(this,'urgent')"  style="background:var(--red-l);color:var(--red)">Urgent</button>
</div>

<div class="notice-list" id="noticeList">
<?php foreach ($notices as $n): ?>
  <div class="notice-item" data-tag="<?= $n['tag'] ?>">
    <div class="notice-item-hd">
      <span class="notice-title"><?= htmlspecialchars($n['title']) ?></span>
      <span class="notice-date"><?= fmt_date($n['date']) ?></span>
    </div>
    <p class="notice-body"><?= nl2br(htmlspecialchars($n['body'])) ?></p>
    <span class="tag tag-<?= $n['tag'] ?>"><?= ucfirst($n['tag']) ?></span>
    <span style="font-size:11px;color:var(--gray4);margin-left:6px">Posted by Admin</span>
  </div>
<?php endforeach; ?>
</div>

<script>
function filterTag(btn, tag) {
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active','btn-ghost'));
  btn.classList.add('active');
  if (tag === 'all') btn.classList.add('btn-ghost');
  document.querySelectorAll('#noticeList .notice-item').forEach(item => {
    item.style.display = (tag === 'all' || item.dataset.tag === tag) ? '' : 'none';
  });
}
</script>

<?php include '../includes/layout_end.php'; ?>
