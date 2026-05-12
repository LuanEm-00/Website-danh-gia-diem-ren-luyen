<?php
require_once ROOT . '/app/core/Model.php';

class PhieuModel extends Model {

    private const TONG_SQL = "
        (SELECT SUM(diem_1_1+diem_1_2+diem_1_3+diem_1_4+diem_1_5+
                    diem_2_1+diem_2_2+diem_2_3+diem_2_4+diem_2_5+diem_2_6+diem_2_7+
                    diem_3_1+diem_3_2+diem_3_3+diem_4_1+diem_4_2)
         FROM chitietdiem cd WHERE cd.MaPhieu=p.MaPhieu)";

    public function countAll(): int {
        return $this->countQuery("SELECT COUNT(*) AS t FROM phieudanhgia");
    }

    public function getRecent(int $limit = 10): array {
        $res = $this->query("
            SELECT p.MaPhieu, sv.HoTen, sv.MaSV AS MaSVText,
                   l.TenLop, k.TenKhoa, p.HocKy, p.NamHoc, p.NgayTao,
                   " . self::TONG_SQL . " AS TongDiem
            FROM phieudanhgia p
            JOIN sinhvien sv ON sv.MaSV = p.MaSV
            JOIN lop l       ON l.MaLop = sv.MaLop
            JOIN khoa k      ON k.MaKhoa= l.MaKhoa
            ORDER BY p.NgayTao DESC LIMIT $limit
        ");
        return $this->fetchAll($res);
    }

    public function getList(array $filters): array {
        $where = "1=1";
        if ($filters['hocky'])  $where .= " AND p.HocKy={$filters['hocky']}";
        if ($filters['namhoc']) $where .= " AND p.NamHoc='" . $this->escape($filters['namhoc']) . "'";
        if ($filters['malop'])  $where .= " AND sv.MaLop={$filters['malop']}";
        if ($filters['tukhoa']) {
            $kw = $this->escape($filters['tukhoa']);
            $where .= " AND (sv.HoTen LIKE '%$kw%' OR sv.MaSV LIKE '%$kw%')";
        }
        $res = $this->query("
            SELECT p.MaPhieu, sv.HoTen, sv.MaSV AS MaSVText,
                   l.TenLop, k.TenKhoa, p.HocKy, p.NamHoc, p.NgayTao,
                   " . self::TONG_SQL . " AS TongDiem
            FROM phieudanhgia p
            JOIN sinhvien sv ON sv.MaSV  = p.MaSV
            JOIN lop l       ON l.MaLop  = sv.MaLop
            JOIN khoa k      ON k.MaKhoa = l.MaKhoa
            WHERE $where ORDER BY p.NgayTao DESC
        ");
        return $this->fetchAll($res);
    }

    public function delete(int $id): void {
        $this->query("DELETE FROM phieudanhgia WHERE MaPhieu=$id");
    }

    // ── SINH VIÊN ─────────────────────────────────────────

    public function getListBySV(string $maSV): array {
        $esc = $this->escape($maSV);
        $res = $this->query("
            SELECT p.*,
                " . self::TONG_SQL . " AS TongDiem
            FROM phieudanhgia p
            WHERE p.MaSV = '$esc'
            ORDER BY p.NamHoc DESC, p.HocKy DESC
        ");
        return $this->fetchAll($res);
    }

    public function getByIdForSV(int $maPhieu, string $maSV): ?array {
        $esc = $this->escape($maSV);
        $res = $this->query("SELECT * FROM phieudanhgia WHERE MaPhieu=$maPhieu AND MaSV='$esc'");
        return $this->fetchOne($res);
    }

    public function getChiTiet(int $maPhieu): array {
        $res = $this->query("SELECT * FROM chitietdiem WHERE MaPhieu=$maPhieu");
        return $this->fetchOne($res) ?? [];
    }

    public function isDuplicate(string $maSV, int $hocky, string $namhoc): bool {
        $check = $this->prepare("SELECT MaPhieu FROM phieudanhgia WHERE MaSV=? AND HocKy=? AND NamHoc=?");
        mysqli_stmt_bind_param($check, 'iis', $maSV, $hocky, $namhoc);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);
        return mysqli_stmt_num_rows($check) > 0;
    }

    public function create(string $maSV, int $hocky, string $namhoc): int {
        $ins = $this->prepare("INSERT INTO phieudanhgia (MaSV, HocKy, NamHoc) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($ins, 'iis', $maSV, $hocky, $namhoc);
        mysqli_stmt_execute($ins);
        return $this->insertId();
    }

    public function saveChiTiet(int $maPhieu, array $diem): void {
        $d = fn(string $k) => intval($diem[$k] ?? 0);
        $ins = $this->prepare("
            INSERT INTO chitietdiem
            (MaPhieu,
             diem_1_1,diem_1_2,diem_1_3,diem_1_4,diem_1_5,
             diem_2_1,diem_2_2,diem_2_3,diem_2_4,diem_2_5,diem_2_6,diem_2_7,
             diem_3_1,diem_3_2,diem_3_3,
             diem_4_1,diem_4_2)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");
        $d11=$d('1_1'); $d12=$d('1_2'); $d13=$d('1_3'); $d14=$d('1_4'); $d15=$d('1_5');
        $d21=$d('2_1'); $d22=$d('2_2'); $d23=$d('2_3'); $d24=$d('2_4'); $d25=$d('2_5'); $d26=$d('2_6'); $d27=$d('2_7');
        $d31=$d('3_1'); $d32=$d('3_2'); $d33=$d('3_3');
        $d41=$d('4_1'); $d42=$d('4_2');
        mysqli_stmt_bind_param($ins, 'iiiiiiiiiiiiiiiiii',
            $maPhieu,
            $d11,$d12,$d13,$d14,$d15,
            $d21,$d22,$d23,$d24,$d25,$d26,$d27,
            $d31,$d32,$d33,
            $d41,$d42
        );
        mysqli_stmt_execute($ins);
    }

    // ── IN PHIẾU (SINGLE) ─────────────────────────────────

    public function getForPrint(int $maPhieu, string $vaitro, int $uid): ?array {
        if ($vaitro === 'sinhvien') {
            $resSV = $this->query("SELECT MaSV FROM sinhvien WHERE ID=$uid");
            $tmp   = $this->fetchOne($resSV);
            $esc   = $this->escape($tmp['MaSV']);
            $res   = $this->query("SELECT * FROM phieudanhgia WHERE MaPhieu=$maPhieu AND MaSV='$esc'");
        } else {
            $res   = $this->query("SELECT * FROM phieudanhgia WHERE MaPhieu=$maPhieu");
        }
        $phieu = $this->fetchOne($res);
        if (!$phieu) return null;

        $resSV = $this->query("
            SELECT sv.*, l.TenLop, k.TenKhoa, gv.HoTen AS TenGV
            FROM sinhvien sv
            JOIN lop l  ON l.MaLop  = sv.MaLop
            JOIN khoa k ON k.MaKhoa = l.MaKhoa
            LEFT JOIN giangvien gv ON gv.MaGV = l.MaGV
            WHERE sv.MaSV = {$phieu['MaSV']}
        ");
        $phieu['sv'] = $this->fetchOne($resSV) ?? [];
        $phieu['diem'] = $this->getChiTiet($maPhieu);
        return $phieu;
    }

    // ── IN HÀNG LOẠT ──────────────────────────────────────

    public function getForBatchPrint(array $ids, string $vaitro, int $uid): array {
        $idStr = implode(',', $ids);
        if ($vaitro === 'sinhvien') {
            $resSV = $this->query("SELECT MaSV FROM sinhvien WHERE ID=$uid");
            $sv    = $this->fetchOne($resSV);
            $esc   = $this->escape($sv['MaSV']);
            $res   = $this->query("
                SELECT p.*, sv.HoTen, sv.MaSV AS MaSVText, sv.NgaySinh, sv.NienKhoa,
                       l.TenLop, k.TenKhoa, gv.HoTen AS TenGV
                FROM phieudanhgia p
                JOIN sinhvien sv ON sv.MaSV=p.MaSV
                JOIN lop l ON l.MaLop=sv.MaLop
                JOIN khoa k ON k.MaKhoa=l.MaKhoa
                LEFT JOIN giangvien gv ON gv.MaGV=l.MaGV
                WHERE p.MaPhieu IN ($idStr) AND p.MaSV='$esc'
                ORDER BY FIELD(p.MaPhieu, $idStr)
            ");
        } elseif ($vaitro === 'giangvien') {
            $resGV = $this->query("SELECT MaGV FROM giangvien WHERE ID=$uid");
            $gv    = $this->fetchOne($resGV);
            $res   = $this->query("
                SELECT p.*, sv.HoTen, sv.MaSV AS MaSVText, sv.NgaySinh, sv.NienKhoa,
                       l.TenLop, k.TenKhoa, gv.HoTen AS TenGV
                FROM phieudanhgia p
                JOIN sinhvien sv ON sv.MaSV=p.MaSV
                JOIN lop l ON l.MaLop=sv.MaLop
                JOIN khoa k ON k.MaKhoa=l.MaKhoa
                LEFT JOIN giangvien gv ON gv.MaGV=l.MaGV
                WHERE p.MaPhieu IN ($idStr) AND l.MaGV={$gv['MaGV']}
                ORDER BY FIELD(p.MaPhieu, $idStr)
            ");
        } else {
            $res = $this->query("
                SELECT p.*, sv.HoTen, sv.MaSV AS MaSVText, sv.NgaySinh, sv.NienKhoa,
                       l.TenLop, k.TenKhoa, gv.HoTen AS TenGV
                FROM phieudanhgia p
                JOIN sinhvien sv ON sv.MaSV=p.MaSV
                JOIN lop l ON l.MaLop=sv.MaLop
                JOIN khoa k ON k.MaKhoa=l.MaKhoa
                LEFT JOIN giangvien gv ON gv.MaGV=l.MaGV
                WHERE p.MaPhieu IN ($idStr)
                ORDER BY FIELD(p.MaPhieu, $idStr)
            ");
        }
        $phieus = [];
        while ($r = mysqli_fetch_assoc($res)) {
            $r['diem'] = $this->getChiTiet($r['MaPhieu']);
            $phieus[] = $r;
        }
        return $phieus;
    }

    // ── GIẢNG VIÊN ────────────────────────────────────────

    public function getListByLop(int $maLop, int $hocky, string $namhoc): array {
        $where  = "sv.MaLop=$maLop";
        $joinEx = '';
        if ($hocky)  $joinEx .= " AND p.HocKy=$hocky";
        if ($namhoc) $joinEx .= " AND p.NamHoc='" . $this->escape($namhoc) . "'";

        $res = $this->query("
            SELECT sv.MaSV, sv.HoTen, sv.MaSV AS MaSVText,
                   p.MaPhieu, p.HocKy, p.NamHoc, p.NgayTao,
                   " . self::TONG_SQL . " AS TongDiem
            FROM sinhvien sv
            LEFT JOIN phieudanhgia p ON p.MaSV=sv.MaSV $joinEx
            WHERE $where
            ORDER BY sv.HoTen
        ");
        return $this->fetchAll($res);
    }

    public function updateChiTiet(int $maPhieu, array $diem): void {
        $d = fn(string $k) => intval($diem[$k] ?? 0);
        $upd = $this->prepare("
            UPDATE chitietdiem SET
             diem_1_1=?,diem_1_2=?,diem_1_3=?,diem_1_4=?,diem_1_5=?,
             diem_2_1=?,diem_2_2=?,diem_2_3=?,diem_2_4=?,diem_2_5=?,diem_2_6=?,diem_2_7=?,
             diem_3_1=?,diem_3_2=?,diem_3_3=?,
             diem_4_1=?,diem_4_2=?
            WHERE MaPhieu=?
        ");
        $d11=$d('1_1'); $d12=$d('1_2'); $d13=$d('1_3'); $d14=$d('1_4'); $d15=$d('1_5');
        $d21=$d('2_1'); $d22=$d('2_2'); $d23=$d('2_3'); $d24=$d('2_4'); $d25=$d('2_5'); $d26=$d('2_6'); $d27=$d('2_7');
        $d31=$d('3_1'); $d32=$d('3_2'); $d33=$d('3_3');
        $d41=$d('4_1'); $d42=$d('4_2');
        mysqli_stmt_bind_param($upd, 'iiiiiiiiiiiiiiiiii',
            $d11,$d12,$d13,$d14,$d15,
            $d21,$d22,$d23,$d24,$d25,$d26,$d27,
            $d31,$d32,$d33,
            $d41,$d42,
            $maPhieu
        );
        mysqli_stmt_execute($upd);
    }
}
