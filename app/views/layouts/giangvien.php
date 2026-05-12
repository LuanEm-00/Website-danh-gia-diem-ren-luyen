<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Giảng viên' ?> – HPC Rèn Luyện</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/giangvien/layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/giangvien/index.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/giangvien/tra_cuu.css">
</head>
<body>
<?php
$user = currentUser();
$a    = $_GET['a'] ?? 'index';
?>
<div class="layout">
    <aside class="sidebar">
        <div class="sidebar-logo">
            👨‍🏫 HPC Giảng Viên
            <span>Quản lý rèn luyện</span>
        </div>
        <nav>
            <a href="<?= BASE_URL ?>/?c=giangvien&a=index"
               class="<?= $a === 'index' ? 'active' : '' ?>">🏠 Trang chủ</a>
            <a href="<?= BASE_URL ?>/?c=giangvien&a=tra_cuu"
               class="<?= $a === 'tra_cuu' ? 'active' : '' ?>">🔍 Tra cứu phiếu</a>
        </nav>
        <div class="sidebar-footer">
            👤 <?= htmlspecialchars($user['hoten']) ?><br>
            <a href="<?= BASE_URL ?>/?c=auth&a=logout">🚪 Đăng xuất</a>
        </div>
    </aside>
    <div class="main-content">
        <div class="topbar">
            <span><?= $pageTitle ?? '' ?></span>
            <strong><?= htmlspecialchars($user['hoten']) ?></strong>
        </div>
        <div class="page-content">
            <?php require $viewFile; ?>
        </div>
    </div>
</div>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
