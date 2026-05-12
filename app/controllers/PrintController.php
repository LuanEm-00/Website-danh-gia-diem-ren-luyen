<?php
require_once ROOT . '/app/core/Controller.php';
require_once ROOT . '/app/models/PhieuModel.php';

class PrintController extends Controller {

    /** In 1 phiếu */
    public function inPhieu(): void {
        requireLogin();
        $uid     = $_SESSION['user_id'];
        $vaitro  = $_SESSION['vaitro'];
        $maPhieu = intval($_GET['phieu'] ?? 0);

        $model = new PhieuModel();
        $phieu = $model->getForPrint($maPhieu, $vaitro, $uid);

        if (!$phieu) {
            http_response_code(404);
            exit('Không tìm thấy phiếu.');
        }

        $sv   = $phieu['sv'];
        $d    = $phieu['diem'];
        $dd   = fn($k) => intval($d[$k] ?? 0);

        $tong1   = $dd('diem_1_1')+$dd('diem_1_2')+$dd('diem_1_3')+$dd('diem_1_4')+$dd('diem_1_5');
        $tong2   = $dd('diem_2_1')+$dd('diem_2_2')+$dd('diem_2_3')+$dd('diem_2_4')+$dd('diem_2_5')+$dd('diem_2_6')+$dd('diem_2_7');
        $tong3   = $dd('diem_3_1')+$dd('diem_3_2')+$dd('diem_3_3');
        $tong4   = $dd('diem_4_1')+$dd('diem_4_2');
        $tongAll = $tong1+$tong2+$tong3+$tong4;

        if      ($tongAll >= 90) $xepLoai = 'Xuất sắc';
        elseif  ($tongAll >= 80) $xepLoai = 'Tốt';
        elseif  ($tongAll >= 70) $xepLoai = 'Khá';
        elseif  ($tongAll >= 50) $xepLoai = 'Trung bình';
        else                     $xepLoai = 'Yếu';

        $this->render('shared/in_phieu', [
            'phieu'   => $phieu,
            'sv'      => $sv,
            'd'       => $d,
            'dd'      => $dd,
            'tong1'   => $tong1,
            'tong2'   => $tong2,
            'tong3'   => $tong3,
            'tong4'   => $tong4,
            'tongAll' => $tongAll,
            'xepLoai' => $xepLoai,
        ], 'print');
    }

    /** In nhiều phiếu */
    public function inNhieu(): void {
        requireLogin();
        $uid    = $_SESSION['user_id'];
        $vaitro = $_SESSION['vaitro'];

        $ids = array_filter(array_map('intval', explode(',', $_GET['phieu'] ?? '')));
        if (empty($ids)) exit('Không có phiếu nào được chọn.');

        $model  = new PhieuModel();
        $phieus = $model->getForBatchPrint($ids, $vaitro, $uid);

        if (empty($phieus)) exit('Không tìm thấy phiếu hoặc bạn không có quyền xem.');

        $this->render('shared/in_nhieu', [
            'phieus' => $phieus,
        ], 'print');
    }
}
