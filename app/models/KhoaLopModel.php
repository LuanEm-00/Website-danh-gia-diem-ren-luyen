<?php
require_once ROOT . '/app/core/Model.php';

class KhoaLopModel extends Model {

    public function getAllKhoa(): array {
        $res = $this->query("
            SELECT k.*, COUNT(l.MaLop) AS SoLop
            FROM khoa k LEFT JOIN lop l ON l.MaKhoa = k.MaKhoa
            GROUP BY k.MaKhoa ORDER BY k.TenKhoa
        ");
        return $this->fetchAll($res);
    }

    public function getAllLop(): array {
        $res = $this->query("
            SELECT l.*, k.TenKhoa, COUNT(sv.MaSV) AS SoSV, gv.HoTen AS TenGV, gv.MaGV
            FROM lop l
            JOIN khoa k ON k.MaKhoa = l.MaKhoa
            LEFT JOIN giangvien gv ON gv.MaGV = l.MaGV
            LEFT JOIN sinhvien sv ON sv.MaLop = l.MaLop
            GROUP BY l.MaLop ORDER BY k.TenKhoa, l.TenLop
        ");
        return $this->fetchAll($res);
    }

    public function getKhoaOpts(): array {
        return $this->fetchAll($this->query("SELECT * FROM khoa ORDER BY TenKhoa"));
    }

    public function getGVOpts(): array {
        return $this->fetchAll($this->query("SELECT MaGV, HoTen FROM giangvien ORDER BY HoTen"));
    }

    public function getLopWithKhoa(): array {
        $res = $this->query("
            SELECT l.MaLop, l.TenLop, k.TenKhoa
            FROM lop l JOIN khoa k ON k.MaKhoa = l.MaKhoa
            ORDER BY k.TenKhoa, l.TenLop
        ");
        return $this->fetchAll($res);
    }

    public function countLop(): int {
        return $this->countQuery("SELECT COUNT(*) AS t FROM lop");
    }

    // ── KHOA ──────────────────────────────────────────────

    public function saveKhoa(int $id, string $ten): string {
        if ($ten === '') return 'error|Vui lòng nhập tên khoa.';
        if ($id === 0) {
            $s = $this->prepare("INSERT INTO khoa (TenKhoa) VALUES (?)");
            mysqli_stmt_bind_param($s, 's', $ten);
            mysqli_stmt_execute($s);
            return 'success|Thêm khoa thành công!';
        }
        $s = $this->prepare("UPDATE khoa SET TenKhoa=? WHERE MaKhoa=?");
        mysqli_stmt_bind_param($s, 'si', $ten, $id);
        mysqli_stmt_execute($s);
        return 'success|Cập nhật khoa thành công!';
    }

    public function deleteKhoa(int $id): string {
        $cnt = $this->countQuery("SELECT COUNT(*) AS t FROM lop WHERE MaKhoa=$id");
        if ($cnt > 0) return "error|Không thể xóa — khoa này còn $cnt lớp!";
        $this->query("DELETE FROM khoa WHERE MaKhoa=$id");
        return 'success|Đã xóa khoa.';
    }

    // ── LỚP ───────────────────────────────────────────────

    public function saveLop(int $id, string $ten, int $maKhoa, ?int $maGV): string {
        if ($ten === '' || $maKhoa === 0) return 'error|Vui lòng nhập đầy đủ thông tin lớp.';
        if ($id === 0) {
            $s = $this->prepare("INSERT INTO lop (TenLop, MaKhoa, MaGV) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($s, 'sii', $ten, $maKhoa, $maGV);
            mysqli_stmt_execute($s);
            return 'success|Thêm lớp thành công!';
        }
        $s = $this->prepare("UPDATE lop SET TenLop=?, MaKhoa=?, MaGV=? WHERE MaLop=?");
        mysqli_stmt_bind_param($s, 'siii', $ten, $maKhoa, $maGV, $id);
        mysqli_stmt_execute($s);
        return 'success|Cập nhật lớp thành công!';
    }

    public function deleteLop(int $id): string {
        $cnt = $this->countQuery("SELECT COUNT(*) AS t FROM sinhvien WHERE MaLop=$id");
        if ($cnt > 0) return "error|Không thể xóa — lớp này còn $cnt sinh viên!";
        $this->query("DELETE FROM lop WHERE MaLop=$id");
        return 'success|Đã xóa lớp.';
    }
}
