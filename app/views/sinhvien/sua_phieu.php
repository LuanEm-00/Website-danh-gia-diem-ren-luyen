<h1 class="page-title">✏️ Sửa phiếu Đánh giá Rèn luyện</h1>

<?php if ($msgText): ?>
    <div class="alert alert-<?= $msgType ?>"><?= htmlspecialchars($msgText) ?></div>
<?php endif; ?>

<?php if (!$phieu): ?>
    <div class="card empty-state">
        Phiếu không tồn tại hoặc không phải của bạn.
    </div>
<?php else: ?>
    <?php
    $mode = 'sua';
    require ROOT . '/app/views/shared/phieu_form.php';
    ?>
<?php endif; ?>
