<?php
require_once ROOT . '/app/core/Controller.php';
require_once ROOT . '/app/models/PhieuModel.php';

class GiangVienController extends Controller {

    public function index(): void {
        requireRole('giangvien');
        $uid = $_SESSION['user_id'];

        global $conn;
        $resGV = mysqli_query($conn, "SELECT * FROM giangvien WHERE ID=$uid");
        $gv    = mysqli_fetch_assoc($resGV);

        $resLop = mysqli_query($conn, "
            SELECT l.MaLop, l.TenLop, k.TenKhoa,
                (SELECT COUNT(*) FROM sinhvien sv WHERE sv.MaLop=l.MaLop) AS SoSV,
                (SELECT COUNT(DISTINCT p.MaSV) FROM phieudanhgia p
                 JOIN sinhvien sv ON sv.MaSV=p.MaSV WHERE sv.MaLop=l.MaLop) AS DaNop
            FROM lop l
            JOIN khoa k ON k.MaKhoa=l.MaKhoa
            WHERE l.MaGV={$gv['MaGV']}
            ORDER BY k.TenKhoa, l.TenLop
        ");
        $lopRows = [];
        while ($r = mysqli_fetch_assoc($resLop)) $lopRows[] = $r;

        $this->render('giangvien/index', [
            'pageTitle' => 'Trang chủ',
            'gv'        => $gv,
            'lopRows'   => $lopRows,
        ], 'giangvien');
    }

    public function traCuu(): void {
        requireRole('giangvien');
        $uid = $_SESSION['user_id'];

        global $conn;
        $resGV = mysqli_query($conn, "SELECT * FROM giangvien WHERE ID=$uid");
        $gv    = mysqli_fetch_assoc($resGV);

        $resLop = mysqli_query($conn, "
            SELECT l.MaLop, l.TenLop FROM lop l WHERE l.MaGV={$gv['MaGV']} ORDER BY l.TenLop
        ");
        $lopChuNhiem = [];
        while ($r = mysqli_fetch_assoc($resLop)) $lopChuNhiem[] = $r;

        if (empty($lopChuNhiem)) {
            $this->render('giangvien/index', [
                'pageTitle' => 'Tra cứu phiếu',
                'gv'        => $gv,
                'lopRows'   => [],
            ], 'giangvien');
            return;
        }

        $maLopChon = intval($_GET['malop'] ?? $lopChuNhiem[0]['MaLop']);
        $lopHopLe  = array_filter($lopChuNhiem, fn($l) => $l['MaLop'] == $maLopChon);
        if (empty($lopHopLe)) $maLopChon = $lopChuNhiem[0]['MaLop'];

        $hocky  = intval($_GET['hocky'] ?? 0);
        $namhoc = trim($_GET['namhoc'] ?? '');

        $model   = new PhieuModel();
        $rows    = $model->getListByLop($maLopChon, $hocky, $namhoc);

        $tenLopChon = '';
        foreach ($lopChuNhiem as $l) {
            if ($l['MaLop'] == $maLopChon) { $tenLopChon = $l['TenLop']; break; }
        }

        $this->render('giangvien/tra_cuu', [
            'pageTitle'   => 'Tra cứu phiếu',
            'gv'          => $gv,
            'lopChuNhiem' => $lopChuNhiem,
            'maLopChon'   => $maLopChon,
            'tenLopChon'  => $tenLopChon,
            'rows'        => $rows,
            'hocky'       => $hocky,
            'namhoc'      => $namhoc,
        ], 'giangvien');
    }
}
