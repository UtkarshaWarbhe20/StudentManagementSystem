<?php
require_once '../includes/auth.php';
require_admin();

$m   = &mock_store();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $title = trim($_POST['title']);
        $body  = trim($_POST['body']);
        $tag   = $_POST['tag'];
        if (!$title || !$body) {
            $msg = ['type'=>'danger','text'=>'Title and content are required.'];
        } else {
            $m['notices'][] = [
                'id'   => $m['next_notice_id']++,
                'title'=> $title,
                'body' => $body,
                'tag'  => $tag,
                'date' => date('Y-m-d'),
                'by'   => 'Admin',
            ];
            // newest first
            usort($m['notices'], fn($a,$b) => strcmp($b['date'], $a['date']));
            $msg = ['type'=>'success','text'=>'Notice posted successfully.'];
        }
    }

    if ($action === 'delete') {
        $del = (int)$_POST['del_id'];
        $m['notices'] = array_values(array_filter($m['notices'], fn($n) => $n['id'] !== $del));
        $msg = ['type'=>'success','text'=>'Notice deleted.'];
    }
}

$notices    = get_notices();
$page_title = 'Notices – SMS Admin';
$active_nav = 'notices';
include '../includes/layout.php';
?>

<div class="page-hd">
  <h2>Notice Board</h2>
  <p>Post and manage notices visible to all students</p>
</div>

<?php if ($msg): ?>
  <div class="alert alert-<?= $msg['type'] ?>"><?= htmlspecialchars($msg['text']) ?></div>
<?php endif; ?>

<div class="card">
  <div class="card-hd">
    <h3>All Notices (<?= count($notices) ?>)</h3>
    <button class="btn btn-sm btn-accent" onclick="openPostModal()">+ Post Notice</button>
  </div>

  <div class="notice-list">
  <?php foreach ($notices as $n): ?>
    <div class="notice-item">
      <div class="notice-item-hd">
        <span class="notice-title"><?= htmlspecialchars($n['title']) ?></span>
        <div style="display:flex;align-items:center;gap:8px">
          <span class="notice-date"><?= fmt_date($n['date']) ?></span>
          <form method="POST" style="display:inline"
                onsubmit="return confirm('Delete this notice?')">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="del_id" value="<?= $n['id'] ?>">
            <button type="submit" class="btn btn-sm btn-danger" style="padding:3px 8px;font-size:11px">Delete</button>
          </form>
        </div>
      </div>
      <p class="notice-body"><?= nl2br(htmlspecialchars($n['body'])) ?></p>
      <span class="tag tag-<?= $n['tag'] ?>"><?= ucfirst($n['tag']) ?></span>
    </div>
  <?php endforeach; ?>
  </div>
</div>

<script>
function openPostModal() {
  openModal('Post New Notice', `
    <form method="POST">
      <input type="hidden" name="action" value="add">
      <div class="form-group-m">
        <label>Notice Title</label>
        <input name="title" placeholder="e.g. Exam Schedule Update" required>
      </div>
      <div class="form-group-m">
        <label>Category</label>
        <select name="tag">
          <option value="general">General</option>
          <option value="exam">Exam</option>
          <option value="holiday">Holiday</option>
          <option value="urgent">Urgent</option>
        </select>
      </div>
      <div class="form-group-m">
        <label>Notice Content</label>
        <textarea name="body" placeholder="Write the full notice here..." required></textarea>
      </div>
      <div class="form-actions">
        <button type="button" class="btn btn-ghost" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn btn-accent">Post Notice</button>
      </div>
    </form>
  `);
}
</script>

<?php include '../includes/layout_end.php'; ?>
