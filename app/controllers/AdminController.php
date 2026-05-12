<?php
require_once ROOT . '/app/core/Controller.php';
require_once ROOT . '/app/models/UserModel.php';
require_once ROOT . '/app/models/KhoaLopModel.php';
require_once ROOT . '/app/models/PhieuModel.php';

class AdminController extends Controller {

    public function index(): void {
        $this->dashboard();
    }

    // ── DASHBOARD ─────────────────────────────────────────

    public function dashboard(): void {
        requireRole('admin');
        $userModel  = new UserModel();
        $khoaModel  = new KhoaLopModel();
        $phieuModel = new PhieuModel();

        $this->render('admin/dashboard', [
            'pageTitle' => 'Trang chủ',
            'statSV'    => $userModel->countSinhVien(),
            'statGV'    => $userModel->countGiangVien(),
            'statLop'   => $khoaModel->countLop(),
            'statPhieu' => $phieuModel->countAll(),
            'phieuMoi'  => $phieuModel->getRecent(10),
        ], 'admin');
    }

    // ── NGƯỜI DÙNG ────────────────────────────────────────

    public function users(): void {
        requireRole('admin');
        $model = new UserModel();
        $msg   = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'save') {
                $id       = intval($_POST['id'] ?? 0);
                $username = trim($_POST['username']);
                $vaitro   = $_POST['vaitro'];
                $hoten    = trim($_POST['hoten']);
                $password = trim($_POST['password'] ?? '');
                $masv     = trim($_POST['masv'] ?? '');
                $malop    = intval($_POST['malop'] ?? 0);
                $namhoc   = trim($_POST['namhoc'] ?? '');
                $ngaysinh = trim($_POST['ngaysinh'] ?? '') ?: null;
                $nienkhoa = trim($_POST['nienkhoa'] ?? '');

                if ($id === 0) {
                    if ($password === '') {
                        $msg = 'error|Vui lòng nhập mật khẩu cho tài khoản mới.';
                    } else {
                        $hash  = password_hash($password, PASSWORD_DEFAULT);
                        $newId = $model->create($username, $hash, $vaitro);
                        if ($newId) {
                            if ($vaitro === 'sinhvien') {
                                $model->createSinhVien($newId, $masv, $hoten, $ngaysinh, $nienkhoa, $malop, $namhoc);
                            } elseif ($vaitro === 'giangvien') {
                                $model->createGiangVien($newId, $hoten);
                            }
                            $msg = 'success|Thêm người dùng thành công!';
                        } else {
                            $msg = 'error|Tài khoản đã tồn tại hoặc có lỗi xảy ra.';
                        }
                    }
                } else {
                    $hash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null;
                    $model->update($id, $username, $hash);
                    $vr = $model->getRoleById($id);
                    if ($vr === 'sinhvien') {
                        $model->updateSinhVien($id, $masv, $hoten, $ngaysinh, $nienkhoa, $malop, $namhoc);
                    } elseif ($vr === 'giangvien') {
                        $model->updateGiangVien($id, $hoten);
                    }
                    $msg = 'success|Cập nhật thành công!';
                }
            }

            if ($action === 'delete') {
                $id = intval($_POST['id']);
                if ($id === $_SESSION['user_id']) {
                    $msg = 'error|Không thể xóa tài khoản đang đăng nhập.';
                } else {
                    $model->delete($id);
                    $msg = 'success|Đã xóa người dùng.';
                }
            }
        }

        [$msgType, $msgText] = $msg ? explode('|', $msg, 2) : ['', ''];

        $this->render('admin/users', [
            'pageTitle'   => 'Quản lý Người dùng',
            'users'       => $model->getAll(),
            'danhSachLop' => $model->getLopList(),
            'khoaOpts'    => $model->getKhoaList(),
            'msgType'     => $msgType,
            'msgText'     => $msgText,
        ], 'admin');
    }

    // ── KHOA / LỚP ────────────────────────────────────────

    public function khoaLop(): void {
        requireRole('admin');
        $model = new KhoaLopModel();
        $msg   = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'save_khoa') {
                $msg = $model->saveKhoa(intval($_POST['id'] ?? 0), trim($_POST['tenkhoa']));
            }
            if ($action === 'delete_khoa') {
                $msg = $model->deleteKhoa(intval($_POST['id']));
            }
            if ($action === 'save_lop') {
                $maGV = intval($_POST['magv'] ?? 0) ?: null;
                $msg  = $model->saveLop(
                    intval($_POST['id'] ?? 0),
                    trim($_POST['tenlop']),
                    intval($_POST['makhoa']),
                    $maGV
                );
            }
            if ($action === 'delete_lop') {
                $msg = $model->deleteLop(intval($_POST['id']));
            }
        }

        [$msgType, $msgText] = $msg ? explode('|', $msg, 2) : ['', ''];

        $this->render('admin/khoa_lop', [
            'pageTitle' => 'Quản lý Khoa / Lớp',
            'khoaRows'  => $model->getAllKhoa(),
            'lopRows'   => $model->getAllLop(),
            'khoaOpts'  => $model->getKhoaOpts(),
            'gvOpts'    => $model->getGVOpts(),
            'msgType'   => $msgType,
            'msgText'   => $msgText,
        ], 'admin');
    }

    // ── PHIẾU ─────────────────────────────────────────────

    public function phieu(): void {
        requireRole('admin');
        $model = new PhieuModel();
        $msg   = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
            $model->delete(intval($_POST['id']));
            $msg = 'success|Đã xóa phiếu!';
        }

        $filters = [
            'hocky'  => intval($_GET['hocky'] ?? 0),
            'namhoc' => trim($_GET['namhoc'] ?? ''),
            'malop'  => intval($_GET['malop'] ?? 0),
            'tukhoa' => trim($_GET['tukhoa'] ?? ''),
        ];

        [$msgType, $msgText] = $msg ? explode('|', $msg, 2) : ['', ''];

        $this->render('admin/phieu', [
            'pageTitle'  => 'Quản lý Phiếu',
            'phieuRows'  => $model->getList($filters),
            'lopList'    => (new KhoaLopModel())->getLopWithKhoa(),
            'filters'    => $filters,
            'msgType'    => $msgType,
            'msgText'    => $msgText,
        ], 'admin');
    }

}
