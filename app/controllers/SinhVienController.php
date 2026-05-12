<?php
require_once ROOT . '/app/core/Controller.php';
require_once ROOT . '/app/models/PhieuModel.php';

class SinhVienController extends Controller {

    public function index(): void {
        requireRole('sinhvien');
        $uid = $_SESSION['user_id'];

        global $conn;
        $res = mysqli_query($conn, "
            SELECT sv.*, l.TenLop, l.MaGV, k.TenKhoa, g.HoTen as GVTenHoTen
            FROM sinhvien sv
            JOIN lop l  ON l.MaLop  = sv.MaLop
            JOIN khoa k ON k.MaKhoa = l.MaKhoa
            LEFT JOIN giangvien g ON g.MaGV = l.MaGV
            WHERE sv.ID = $uid
        ");
        $sv = mysqli_fetch_assoc($res);

        $model = new PhieuModel();
        $phieuList = $model->getListBySV($sv['MaSV']);
        $stat = count($phieuList);

        $this->render('sinhvien/index', [
            'pageTitle'    => 'Trang chủ',
            'sv'           => $sv,
            'tongPhieu'    => $stat,
            'phieuGanNhat' => array_slice($phieuList, 0, 3),
        ], 'sinhvien');
    }

    public function dienPhieu(): void {
        requireRole('sinhvien');
        $uid    = $_SESSION['user_id'];
        $msg    = '';
        $mucList = require ROOT . '/config/criteria.php';

        global $conn;
        $res = mysqli_query($conn, "SELECT * FROM sinhvien WHERE ID=$uid");
        $sv  = mysqli_fetch_assoc($res);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hocky  = intval($_POST['hocky']);
            $namhoc = trim($_POST['namhoc']);
            $model  = new PhieuModel();

            if ($model->isDuplicate($sv['MaSV'], $hocky, $namhoc)) {
                $msg = "error|Bạn đã nộp phiếu học kỳ $hocky năm $namhoc rồi!";
            } else {
                $diemPost = $_POST['diem'] ?? [];
                $valid    = true;
                foreach ($mucList as $muc) {
                    foreach ($muc['tieuchi'] as $key => $tc) {
                        $d = intval($diemPost[$key] ?? 0);
                        if ($d < 0 || $d > $tc['max']) {
                            $valid = false;
                            $msg   = "error|Điểm mục $key không hợp lệ (tối đa {$tc['max']}đ).";
                            break 2;
                        }
                    }
                }

                if ($valid) {
                    $maPhieu = $model->create($sv['MaSV'], $hocky, $namhoc);
                    $model->saveChiTiet($maPhieu, $diemPost);
                    $msg = 'success|Nộp phiếu thành công!';
                }
            }
        }

        [$msgType, $msgText] = $msg ? explode('|', $msg, 2) : ['', ''];

        $this->render('sinhvien/dien_phieu', [
            'pageTitle' => 'Điền phiếu đánh giá',
            'sv'        => $sv,
            'mucList'   => $mucList,
            'msgType'   => $msgType,
            'msgText'   => $msgText,
        ], 'sinhvien');
    }

    public function xemPhieu(): void {
        requireRole('sinhvien');
        $uid   = $_SESSION['user_id'];
        $model = new PhieuModel();

        global $conn;
        $res = mysqli_query($conn, "SELECT * FROM sinhvien WHERE ID=$uid");
        $sv  = mysqli_fetch_assoc($res);

        $phieuRows = $model->getListBySV($sv['MaSV']);
        $totalPhieu = count($phieuRows);
        $itemsPerPage = 10;
        $currentPage = max(1, intval($_GET['page'] ?? 1));
        $totalPages = max(1, ceil($totalPhieu / $itemsPerPage));
        $currentPage = min($currentPage, $totalPages);
        $offset = ($currentPage - 1) * $itemsPerPage;
        $phieuDisplay = array_slice($phieuRows, $offset, $itemsPerPage);

        $this->render('sinhvien/xem_phieu', [
            'pageTitle'     => 'Phiếu đã nộp',
            'sv'            => $sv,
            'phieuDisplay'  => $phieuDisplay,
            'currentPage'   => $currentPage,
            'totalPages'    => $totalPages,
            'totalPhieu'    => $totalPhieu,
        ], 'sinhvien');
    }

    public function suaPhieu(): void {
        requireRole('sinhvien');
        $uid     = $_SESSION['user_id'];
        $msg     = '';
        $mucList = require ROOT . '/config/criteria.php';
        $model   = new PhieuModel();

        global $conn;
        $res = mysqli_query($conn, "SELECT * FROM sinhvien WHERE ID=$uid");
        $sv  = mysqli_fetch_assoc($res);

        $maPhieu = intval($_GET['phieu'] ?? 0);
        $phieu   = $model->getByIdForSV($maPhieu, $sv['MaSV']);
        
        if (!$phieu) {
            $msg = "error|Phiếu không tồn tại hoặc không phải của bạn.";
        } else {
            $diemHienTai = $model->getChiTiet($maPhieu);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $diemPost = $_POST['diem'] ?? [];
                $valid    = true;
                
                foreach ($mucList as $muc) {
                    foreach ($muc['tieuchi'] as $key => $tc) {
                        $d = intval($diemPost[$key] ?? 0);
                        if ($d < 0 || $d > $tc['max']) {
                            $valid = false;
                            $msg   = "error|Điểm mục $key không hợp lệ (tối đa {$tc['max']}đ).";
                            break 2;
                        }
                    }
                }

                if ($valid) {
                    $model->updateChiTiet($maPhieu, $diemPost);
                    $msg = 'success|Cập nhật phiếu thành công!';
                    $diemHienTai = $diemPost;
                }
            }
        }

        [$msgType, $msgText] = $msg ? explode('|', $msg, 2) : ['', ''];

        $this->render('sinhvien/sua_phieu', [
            'pageTitle'      => 'Sửa phiếu đánh giá',
            'sv'             => $sv,
            'phieu'          => $phieu,
            'mucList'        => $mucList,
            'diemHienTai'    => $diemHienTai,
            'msgType'        => $msgType,
            'msgText'        => $msgText,
        ], 'sinhvien');
    }

}
