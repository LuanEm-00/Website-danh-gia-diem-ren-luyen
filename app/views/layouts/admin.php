<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin' ?> – HPC Rèn Luyện</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/users.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/khoa_lop.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin/phieu.css">
</head>
<body>
<?php
$user = currentUser();
$a    = $_GET['a'] ?? 'dashboard';
?>
<div class="layout">
    <aside class="sidebar">
        <div class="sidebar-logo">
            🏫 HPC Admin
            <span>Quản trị hệ thống</span>
        </div>
        <nav>
            <a href="<?= BASE_URL ?>/?c=admin&a=dashboard"
               class="<?= $a === 'dashboard' ? 'active' : '' ?>">📊 Trang chủ</a>
            <a href="<?= BASE_URL ?>/?c=admin&a=users"
               class="<?= $a === 'users' ? 'active' : '' ?>">👥 Quản lý người dùng</a>
            <a href="<?= BASE_URL ?>/?c=admin&a=khoa_lop"
               class="<?= $a === 'khoa_lop' ? 'active' : '' ?>">🏛️ Quản lý khoa / Lớp</a>
            <a href="<?= BASE_URL ?>/?c=admin&a=phieu"
               class="<?= $a === 'phieu' ? 'active' : '' ?>">📄 Quản lý phiếu đánh giá</a>
        </nav>
        <div class="sidebar-footer">
            👤 <?= htmlspecialchars($user['hoten'] ?: 'Admin') ?><br>
            <a href="<?= BASE_URL ?>/?c=auth&a=logout">🚪 Đăng xuất</a>
        </div>
    </aside>
    <div class="main-content">
        <div class="topbar">
            <span><?= $pageTitle ?? '' ?></span>
            <strong><?= htmlspecialchars($user['hoten'] ?: 'Admin') ?></strong>
        </div>
        <div class="page-content">
            <?php require $viewFile; ?>
        </div>
    </div>
</div>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
