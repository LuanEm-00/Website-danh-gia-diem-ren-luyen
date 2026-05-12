<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Sinh viên' ?> – HPC Rèn Luyện</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/sinhvien/layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/sinhvien/index.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/sinhvien/dien_phieu.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/sinhvien/xem_phieu.css">
</head>
<body>
<?php
$user = currentUser();
$a    = $_GET['a'] ?? 'index';
?>
<div class="layout">
    <aside class="sidebar">
        <div class="sidebar-logo">
            🎓 HPC Sinh Viên
            <span>Đánh giá rèn luyện</span>
        </div>
        <nav>
            <a href="<?= BASE_URL ?>/?c=sinhvien&a=index"
               class="<?= $a === 'index' ? 'active' : '' ?>">🏠 Trang chủ</a>
            <a href="<?= BASE_URL ?>/?c=sinhvien&a=dien_phieu"
               class="<?= $a === 'dien_phieu' ? 'active' : '' ?>">📝 Điền phiếu đánh giá</a>
            <a href="<?= BASE_URL ?>/?c=sinhvien&a=xem_phieu"
               class="<?= $a === 'xem_phieu' ? 'active' : '' ?>">📄 Xem phiếu đã nộp</a>
        </nav>
        <div class="sidebar-footer">
            👤 <?= htmlspecialchars($user['hoten']) ?><br>
            <a href="<?= BASE_URL ?>/?c=auth&a=logout">🚪 Đăng xuất</a>
        </div>
    </aside>
    <div class="main-content">
        <div class="topbar">
            <span class="topbar-title"><?= $pageTitle ?? '' ?></span>
            <span class="topbar-brand">🎓 HPC Rèn Luyện</span>
            <strong><?= htmlspecialchars($user['hoten']) ?></strong>
        </div>
        <div class="page-content">
            <?php require $viewFile; ?>
        </div>
    </div>
</div>
<nav class="bottom-nav">
    <a href="<?= BASE_URL ?>/?c=sinhvien&a=index" class="<?= $a === 'index' ? 'active' : '' ?>">
        <span class="nav-icon">🏠</span>
        <span>Trang chủ</span>
    </a>
    <a href="<?= BASE_URL ?>/?c=sinhvien&a=dien_phieu" class="<?= $a === 'dien_phieu' ? 'active' : '' ?>">
        <span class="nav-icon">📝</span>
        <span>Điền phiếu</span>
    </a>
    <a href="<?= BASE_URL ?>/?c=sinhvien&a=xem_phieu" class="<?= $a === 'xem_phieu' ? 'active' : '' ?>">
        <span class="nav-icon">📄</span>
        <span>Xem phiếu</span>
    </a>
    <a href="<?= BASE_URL ?>/?c=auth&a=logout">
        <span class="nav-icon">🚪</span>
        <span>Đăng xuất</span>
    </a>
</nav>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
