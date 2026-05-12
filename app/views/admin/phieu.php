<h1 class="page-title">📄 Quản lý Phiếu Đánh Giá</h1>

<?php if ($msgText): ?>
    <div class="alert alert-<?= $msgType ?>"><?= htmlspecialchars($msgText) ?></div>
<?php endif; ?>

<!-- BỘ LỌC -->
<div class="card" style="margin-bottom:16px">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
        <input type="hidden" name="c" value="admin">
        <input type="hidden" name="a" value="phieu">
        <div class="form-group" style="margin:0;flex:1;min-width:100px">
            <label>Học kỳ</label>
            <select name="hocky">
                <option value="">Tất cả</option>
                <option value="1" <?= $filters['hocky']==1?'selected':'' ?>>HK 1</option>
                <option value="2" <?= $filters['hocky']==2?'selected':'' ?>>HK 2</option>
            </select>
        </div>
        <div class="form-group" style="margin:0;flex:1;min-width:120px">
            <label>Năm học</label>
            <select name="namhoc">
                <option value="">Tất cả</option>
                <?php foreach (require ROOT . '/config/namhoc.php' as $nh): ?>
                <option value="<?= $nh ?>" <?= $filters['namhoc'] == $nh ? 'selected' : '' ?>>
                    <?= $nh ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="margin:0;flex:1;min-width:150px">
            <label>Lớp</label>
            <select name="malop">
                <option value="">Tất cả</option>
                <?php foreach ($lopList as $l): ?>
                <option value="<?= $l['MaLop'] ?>" <?= $filters['malop']==$l['MaLop']?'selected':'' ?>>
                    <?= htmlspecialchars($l['TenLop'].' — '.$l['TenKhoa']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="margin:0;flex:2;min-width:160px">
            <label>Tìm tên / mã SV</label>
            <input type="text" name="tukhoa" value="<?= htmlspecialchars($filters['tukhoa']) ?>" placeholder="Tên hoặc mã SV...">
        </div>
        <button type="submit" class="btn btn-primary" style="height:40px">Tìm</button>
        <a href="<?= BASE_URL ?>/?c=admin&a=phieu" class="btn btn-secondary" style="height:40px;line-height:24px">Xóa lọc</a>
    </form>
</div>

<!-- TOOLBAR CHỌN HÀNG LOẠT -->
<div class="card" style="margin-bottom:16px;padding:12px 20px">
    <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-weight:600">
            <input type="checkbox" id="chk-all" onchange="toggleAll(this)"> Chọn tất cả
        </label>
        <span id="so-chon" style="color:#888;font-size:.88rem">0 phiếu được chọn</span>
        <button class="btn btn-primary" onclick="inHangLoat()" style="padding:7px 16px">
            🖨️ In phiếu đã chọn
        </button>
    </div>
</div>

<!-- BẢNG PHIẾU -->
<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width:36px"></th>
                    <th>#</th><th>Sinh viên</th><th>Mã SV</th>
                    <th>Lớp</th><th>Khoa</th><th>HK</th><th>Năm học</th>
                    <th style="text-align:center">Tổng điểm</th>
                    <th>Ngày nộp</th><th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($phieuRows)): ?>
                <tr><td colspan="11" style="text-align:center;color:#888;padding:30px">Không có phiếu nào.</td></tr>
            <?php else: foreach ($phieuRows as $p): ?>
            <tr>
                <td style="text-align:center">
                    <input type="checkbox" class="chk-phieu" value="<?= $p['MaPhieu'] ?>"
                           onchange="updateSoChon()">
                </td>
                <td><?= $p['MaPhieu'] ?></td>
                <td><?= htmlspecialchars($p['HoTen']) ?></td>
                <td><?= htmlspecialchars($p['MaSVText']) ?></td>
                <td><?= htmlspecialchars($p['TenLop']) ?></td>
                <td><?= htmlspecialchars($p['TenKhoa']) ?></td>
                <td>HK<?= $p['HocKy'] ?></td>
                <td><?= $p['NamHoc'] ?></td>
                <td style="text-align:center"><strong style="color:#1a56a0"><?= $p['TongDiem'] ?? 0 ?></strong>/100</td>
                <td><?= date('d/m/Y', strtotime($p['NgayTao'])) ?></td>
                <td>
                    <div style="display:flex;gap:4px">
                        <a href="<?= BASE_URL ?>/?c=print&a=in_phieu&phieu=<?= $p['MaPhieu'] ?>"
                           target="_blank" class="btn btn-secondary" style="padding:4px 10px;font-size:.82rem">🖨️ Xem</a>
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $p['MaPhieu'] ?>">
                            <button type="submit" class="btn btn-danger btn-confirm-delete"
                                    style="padding:4px 10px;font-size:.82rem">🗑</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleAll(chk) {
    document.querySelectorAll('.chk-phieu').forEach(c => c.checked = chk.checked);
    updateSoChon();
}
function updateSoChon() {
    const n = document.querySelectorAll('.chk-phieu:checked').length;
    document.getElementById('so-chon').textContent = n + ' phiếu được chọn';
    document.getElementById('chk-all').checked =
        n === document.querySelectorAll('.chk-phieu').length && n > 0;
}
function inHangLoat() {
    const ids = [...document.querySelectorAll('.chk-phieu:checked')].map(c => c.value);
    if (!ids.length) { alert('Vui lòng chọn ít nhất 1 phiếu!'); return; }
    window.open('<?= BASE_URL ?>/?c=print&a=in_nhieu&phieu=' + ids.join(','), '_blank');
}
</script>
