<?php
class Router {
    private array $map = [
        'auth'      => 'AuthController',
        'admin'     => 'AdminController',
        'giangvien' => 'GiangVienController',
        'sinhvien'  => 'SinhVienController',
        'print'     => 'PrintController',
    ];

    public function dispatch(): void {
        $c = $_GET['c'] ?? 'auth';
        $a = $_GET['a'] ?? 'index';

        if (!isset($this->map[$c])) {
            http_response_code(404);
            exit('Trang không tồn tại.');
        }

        $class = $this->map[$c];
        require_once ROOT . '/app/controllers/' . $class . '.php';

        $controller = new $class();

        // Convert action: khoa_lop → khoaLop, tra_cuu → traCuu
        $method = lcfirst(str_replace('_', '', ucwords($a, '_')));

        if (!method_exists($controller, $method)) {
            http_response_code(404);
            exit('Hành động không tồn tại: ' . htmlspecialchars($method));
        }

        $controller->$method();
    }
}
