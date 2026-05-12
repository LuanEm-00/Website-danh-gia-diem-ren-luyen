<h1 class="page-title">📝 Điền phiếu Đánh giá Rèn luyện</h1>

<?php if ($msgText): ?>
    <div class="alert alert-<?= $msgType ?>"><?= htmlspecialchars($msgText) ?></div>
<?php endif; ?>

<?php
$mode = 'dien';
require ROOT . '/app/views/shared/phieu_form.php';
?>

