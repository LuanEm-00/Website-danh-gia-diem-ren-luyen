<?php
function requireLogin(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . '/');
        exit;
    }
}

function requireRole(string $role): void {
    requireLogin();
    if ($_SESSION['vaitro'] !== $role) {
        http_response_code(403);
        exit('Bạn không có quyền truy cập trang này.');
    }
}

function currentUser(): array {
    return [
        'id'     => $_SESSION['user_id'] ?? null,
        'vaitro' => $_SESSION['vaitro']  ?? null,
        'hoten'  => $_SESSION['hoten']   ?? '',
    ];
}
