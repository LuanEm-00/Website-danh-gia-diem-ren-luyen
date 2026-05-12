<?php
require_once ROOT . '/app/core/Controller.php';
require_once ROOT . '/app/models/UserModel.php';

class AuthController extends Controller {

    public function index(): void {
        $this->login();
    }

    public function login(): void {
        // Đã đăng nhập → chuyển hướng
        if (!empty($_SESSION['user_id'])) {
            $this->redirect($this->dashboardUrl($_SESSION['vaitro']));
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($username === '' || $password === '') {
                $error = 'Vui lòng nhập đầy đủ tài khoản và mật khẩu.';
            } else {
                $model = new UserModel();
                $user  = $model->findByUsername($username);

                if ($user && password_verify($password, $user['MatKhau'])) {
                    $_SESSION['user_id'] = $user['ID'];
                    $_SESSION['vaitro']  = $user['VaiTro'];
                    $_SESSION['hoten']   = $user['HoTen'];
                    $this->redirect($this->dashboardUrl($user['VaiTro']));
                } else {
                    $error = 'Tên tài khoản hoặc mật khẩu không đúng.';
                }
            }
        }

        $this->render('auth/login', ['error' => $error]);
    }

    public function logout(): void {
        session_destroy();
        $this->redirect(BASE_URL . '/');
    }

    private function dashboardUrl(string $role): string {
        return match($role) {
            'admin'     => BASE_URL . '/?c=admin&a=dashboard',
            'giangvien' => BASE_URL . '/?c=giangvien&a=index',
            'sinhvien'  => BASE_URL . '/?c=sinhvien&a=index',
            default     => BASE_URL . '/',
        };
    }
}
