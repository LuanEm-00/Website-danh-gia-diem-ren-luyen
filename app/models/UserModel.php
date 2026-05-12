<?php
require_once ROOT . '/app/core/Model.php';

class UserModel extends Model {

    public function getAll(): array {
        $res = $this->query("
            SELECT nd.ID, nd.Username, nd.VaiTro,
                COALESCE(sv.HoTen, gv.HoTen, 'Admin') AS HoTen,
                sv.MaSV, sv.NgaySinh, sv.NienKhoa, sv.NamHoc,
                l.TenLop, l.MaLop, k.TenKhoa, k.MaKhoa AS KhoaID
            FROM nguoidung nd
            LEFT JOIN sinhvien sv ON sv.ID = nd.ID
            LEFT JOIN giangvien gv ON gv.ID = nd.ID
            LEFT JOIN lop l   ON l.MaLop  = sv.MaLop
            LEFT JOIN khoa k  ON k.MaKhoa = l.MaKhoa
            ORDER BY nd.VaiTro, HoTen
        ");
        return $this->fetchAll($res);
    }

    public function findByUsername(string $username): ?array {
        $stmt = $this->prepare("
            SELECT nd.*,
                CASE nd.VaiTro
                    WHEN 'sinhvien' THEN sv.HoTen
                    WHEN 'giangvien' THEN gv.HoTen
                    ELSE 'Admin'
                END AS HoTen
            FROM nguoidung nd
            LEFT JOIN sinhvien sv ON sv.ID = nd.ID
            LEFT JOIN giangvien gv ON gv.ID = nd.ID
            WHERE nd.Username = ?
        ");
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        return $this->fetchOne(mysqli_stmt_get_result($stmt));
    }

    /** Tạo tài khoản nguoidung, trả về ID mới hoặc false nếu lỗi */
    public function create(string $username, string $hash, string $vaitro): int|false {
        $stmt = $this->prepare("INSERT INTO nguoidung (Username, MatKhau, VaiTro) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sss', $username, $hash, $vaitro);
        if (!mysqli_stmt_execute($stmt)) return false;
        return $this->insertId();
    }

    public function createSinhVien(int $id, string $masv, string $hoten, ?string $ngaysinh, string $nienkhoa, int $malop, string $namhoc): void {
        $ngaysinhVal = $ngaysinh ?: null;
        $s = $this->prepare("INSERT INTO sinhvien (MaSV, HoTen, NgaySinh, NienKhoa, MaLop, NamHoc, ID) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($s, 'ssssssi', $masv, $hoten, $ngaysinhVal, $nienkhoa, $malop, $namhoc, $id);
        mysqli_stmt_execute($s);
    }

    public function createGiangVien(int $id, string $hoten): void {
        $s = $this->prepare("INSERT INTO giangvien (HoTen, ID) VALUES (?, ?)");
        mysqli_stmt_bind_param($s, 'si', $hoten, $id);
        mysqli_stmt_execute($s);
    }

    public function update(int $id, string $username, ?string $hash): void {
        if ($hash !== null) {
            $stmt = $this->prepare("UPDATE nguoidung SET Username=?, MatKhau=? WHERE ID=?");
            mysqli_stmt_bind_param($stmt, 'ssi', $username, $hash, $id);
        } else {
            $stmt = $this->prepare("UPDATE nguoidung SET Username=? WHERE ID=?");
            mysqli_stmt_bind_param($stmt, 'si', $username, $id);
        }
        mysqli_stmt_execute($stmt);
    }

    public function updateSinhVien(int $id, string $masv, string $hoten, ?string $ngaysinh, string $nienkhoa, int $malop, string $namhoc): void {
        $ngaysinhVal = $ngaysinh ?: null;
        $s = $this->prepare("UPDATE sinhvien SET MaSV=?, HoTen=?, NgaySinh=?, NienKhoa=?, MaLop=?, NamHoc=? WHERE ID=?");
        mysqli_stmt_bind_param($s, 'ssssssi', $masv, $hoten, $ngaysinhVal, $nienkhoa, $malop, $namhoc, $id);
        mysqli_stmt_execute($s);
    }

    public function updateGiangVien(int $id, string $hoten): void {
        $s = $this->prepare("UPDATE giangvien SET HoTen=? WHERE ID=?");
        mysqli_stmt_bind_param($s, 'si', $hoten, $id);
        mysqli_stmt_execute($s);
    }

    public function getRoleById(int $id): string {
        $res = $this->query("SELECT VaiTro FROM nguoidung WHERE ID=$id");
        return $this->fetchOne($res)['VaiTro'] ?? '';
    }

    public function delete(int $id): void {
        $this->query("DELETE FROM nguoidung WHERE ID=$id");
    }

    public function countSinhVien(): int {
        return $this->countQuery("SELECT COUNT(*) AS t FROM sinhvien");
    }

    public function countGiangVien(): int {
        return $this->countQuery("SELECT COUNT(*) AS t FROM giangvien");
    }

    public function getLopList(): array {
        $res = $this->query("
            SELECT l.MaLop, l.TenLop, k.TenKhoa
            FROM lop l JOIN khoa k ON k.MaKhoa = l.MaKhoa
            ORDER BY k.TenKhoa, l.TenLop
        ");
        return $this->fetchAll($res);
    }

    public function getKhoaList(): array {
        return $this->fetchAll($this->query("SELECT * FROM khoa ORDER BY TenKhoa"));
    }
}
