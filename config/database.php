<?php
// Local XAMPP
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hpc_renluyen');

// Remote InfinityFree (dùng khi deploy)
// define('DB_HOST', 'sql110.infinityfree.com');
// define('DB_USER', 'if0_41542549');
// define('DB_PASS', '0828131245Luan');
// define('DB_NAME', 'if0_41542549_hpcrenluyen');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_set_charset($conn, 'utf8mb4');

if (!$conn) {
    die('Kết nối thất bại: ' . mysqli_connect_error());
}