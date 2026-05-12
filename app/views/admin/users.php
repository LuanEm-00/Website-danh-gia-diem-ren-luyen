<h1 class="page-title">👥 Quản lý Người dùng</h1>

<?php if ($msgText): ?>
    <div class="alert alert-<?= $msgType ?>"><?= htmlspecialchars($msgText) ?></div>
<?php endif; ?>

<div class="card" style="margin-bottom:20px">
    <button class="btn btn-primary" onclick="openModal()">+ Thêm người dùng</button>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Họ tên</th>
                    <th>Mã SV</th>
                    <th>Tài khoản</th>
                    <th>Vai trò</th>
                    <th>Lớp / Khoa</th>
                    <th>Năm học</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['ID'] ?></td>
                <td><?= htmlspecialchars($u['HoTen']) ?></td>
                <td><?= htmlspecialchars($u['MaSV'] ?? '—') ?></td>
                <td><?= htmlspecialchars($u['Username']) ?></td>
                <td>
                    <?php
                    $badges = ['admin'=>'badge-info','giangvien'=>'badge-success','sinhvien'=>'badge-warning'];
                    $labels = ['admin'=>'Admin','giangvien'=>'Giảng viên','sinhvien'=>'Sinh viên'];
                    ?>
                    <span class="badge <?= $badges[$u['VaiTro']] ?>"><?= $labels[$u['VaiTro']] ?></span>
                </td>
                <td><?= htmlspecialchars($u['TenLop'] ?? $u['TenKhoa'] ?? '—') ?></td>
                <td><?= htmlspecialchars($u['NamHoc'] ?? '—') ?></td>
                <td>
                    <button class="btn btn-secondary"
                        onclick="openModal(<?= htmlspecialchars(json_encode($u)) ?>)">Sửa</button>
                    <?php if ($u['ID'] != $_SESSION['user_id']): ?>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $u['ID'] ?>">
                        <button type="submit" class="btn btn-danger btn-confirm-delete">Xóa</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL -->
<div id="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:999;align-items:center;justify-content:center">
<div style="background:#fff;border-radius:12px;padding:32px;width:500px;max-width:95%;max-height:90vh;overflow-y:auto">
    <h3 id="modal-title" style="margin-bottom:20px;color:#1a2e4a">Thêm người dùng</h3>
    <form method="POST">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" id="f-id" value="0">

        <div class="form-group">
            <label>Tài khoản</label>
            <input type="text" name="username" id="f-username" required>
        </div>
        <div class="form-group">
            <label>Mật khẩu <small id="pass-hint" style="color:#888">(bắt buộc khi thêm mới)</small></label>
            <input type="password" name="password" id="f-password" placeholder="Để trống nếu không đổi">
        </div>
        <div class="form-group">
            <label>Họ tên</label>
            <input type="text" name="hoten" id="f-hoten" required>
        </div>
        <div class="form-group">
            <label>Vai trò</label>
            <select name="vaitro" id="f-vaitro" onchange="toggleRole(this.value)" required>
                <option value="sinhvien">Sinh viên</option>
                <option value="giangvien">Giảng viên</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <!-- SINH VIÊN -->
        <div id="row-sinhvien">
            <div class="form-group">
                <label>Mã số sinh viên</label>
                <input type="text" name="masv" id="f-masv" placeholder="VD: 2209620198">
            </div>
            <div class="form-group">
                <label>Ngày sinh</label>
                <input type="date" name="ngaysinh" id="f-ngaysinh">
            </div>
            <div class="form-group">
                <label>Lớp</label>
                <select name="malop" id="f-malop">
                    <?php foreach ($danhSachLop as $l): ?>
                    <option value="<?= $l['MaLop'] ?>">
                        <?= htmlspecialchars($l['TenLop'] . ' — ' . $l['TenKhoa']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Niên khóa</label>
                <input type="text" name="nienkhoa" id="f-nienkhoa" placeholder="VD: 2024-2027">
            </div>
            <div class="form-group">
                <label>Năm học hiện tại</label>
                <select name="namhoc" id="f-namhoc">
                    <option value="">-- Chọn --</option>
                    <?php foreach (require ROOT . '/config/namhoc.php' as $nh): ?>
                    <option value="<?= $nh ?>"><?= $nh ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- GIẢNG VIÊN -->
        <div id="row-giangvien" style="display:none">
            <p style="color:#888;font-size:.88rem;padding:8px;background:#f8fafc;border-radius:6px">
                💡 Sau khi thêm, vào <strong>Quản lý Khoa/Lớp</strong> để gán giảng viên này làm GVCN cho lớp.
            </p>
        </div>

        <div style="display:flex;gap:10px;margin-top:12px">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Hủy</button>
        </div>
    </form>
</div>
</div>

<script>
const overlay = document.getElementById('modal-overlay');

function openModal(u = null) {
    overlay.style.display = 'flex';
    if (u) {
        document.getElementById('modal-title').textContent = 'Sửa người dùng';
        document.getElementById('f-id').value        = u.ID;
        document.getElementById('f-username').value  = u.Username;
        document.getElementById('f-hoten').value     = u.HoTen;
        document.getElementById('f-vaitro').value    = u.VaiTro;
        document.getElementById('f-masv').value      = u.MaSV || '';
        document.getElementById('f-ngaysinh').value  = u.NgaySinh || '';
        document.getElementById('f-nienkhoa').value  = u.NienKhoa || '';
        document.getElementById('f-namhoc').value    = u.NamHoc || '';  // Select options match by value
        if (u.MaLop) document.getElementById('f-malop').value = u.MaLop;
        document.getElementById('pass-hint').textContent = '(để trống nếu không đổi)';
        toggleRole(u.VaiTro);
    } else {
        document.getElementById('modal-title').textContent = 'Thêm người dùng';
        document.getElementById('f-id').value = '0';
        document.querySelector('form').reset();
        document.getElementById('pass-hint').textContent = '(bắt buộc khi thêm mới)';
        toggleRole('sinhvien');
    }
}

function closeModal() { overlay.style.display = 'none'; }

function toggleRole(val) {
    document.getElementById('row-sinhvien').style.display  = val === 'sinhvien'  ? '' : 'none';
    document.getElementById('row-giangvien').style.display = val === 'giangvien' ? '' : 'none';
}

overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });
</script>
