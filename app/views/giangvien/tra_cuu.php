<h1 class="page-title">🔍 Tra cứu phiếu</h1>

<!-- TABS LỚP -->
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px">
<?php foreach ($lopChuNhiem as $l): ?>
    <a href="<?= BASE_URL ?>/?c=giangvien&a=tra_cuu&malop=<?= $l['MaLop'] ?><?= $hocky?"&hocky=$hocky":'' ?><?= $namhoc?'&namhoc='.urlencode($namhoc):'' ?>"
       class="btn <?= $l['MaLop']==$maLopChon ? 'btn-primary' : 'btn-secondary' ?>">
        🎓 <?= htmlspecialchars($l['TenLop']) ?>
    </a>
<?php endforeach; ?>
</div>

<!-- BỘ LỌC -->
<div class="card" style="margin-bottom:16px">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
        <input type="hidden" name="c" value="giangvien">
        <input type="hidden" name="a" value="tra_cuu">
        <input type="hidden" name="malop" value="<?= $maLopChon ?>">
        <div class="form-group" style="margin:0;flex:1;min-width:120px">
            <label>Học kỳ</label>
            <select name="hocky">
                <option value="">Tất cả</option>
                <option value="1" <?= $hocky==1?'selected':'' ?>>Học kỳ 1</option>
                <option value="2" <?= $hocky==2?'selected':'' ?>>Học kỳ 2</option>
            </select>
        </div>
        <div class="form-group" style="margin:0;flex:1;min-width:140px">
            <label>Năm học</label>
            <select name="namhoc">
                <option value="">Tất cả</option>
                <?php foreach (require ROOT . '/config/namhoc.php' as $nh): ?>
                <option value="<?= $nh ?>" <?= $namhoc == $nh ? 'selected' : '' ?>>
                    <?= $nh ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="height:40px">Lọc</button>
        <a href="<?= BASE_URL ?>/?c=giangvien&a=tra_cuu&malop=<?= $maLopChon ?>" class="btn btn-secondary" style="height:40px;line-height:24px">Xóa lọc</a>
    </form>
</div>

<!-- TOOLBAR IN HÀNG LOẠT -->
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

<!-- BẢNG DANH SÁCH -->
<div class="card">
    <div style="font-weight:600;color:#1a2e4a;margin-bottom:12px">
        Lớp <?= htmlspecialchars($tenLopChon) ?> —
        <?= count(array_filter($rows, fn($r) => $r['MaPhieu'])) ?> / <?= count($rows) ?> SV đã nộp
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width:36px"></th>
                    <th>Họ tên</th><th>Mã SV</th>
                    <th>Học kỳ</th><th>Năm học</th>
                    <th style="text-align:center">Tổng điểm</th>
                    <th>Ngày nộp</th><th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
            <tr style="<?= !$r['MaPhieu'] ? 'opacity:.55' : '' ?>">
                <td style="text-align:center">
                    <?php if ($r['MaPhieu']): ?>
                    <input type="checkbox" class="chk-phieu" value="<?= $r['MaPhieu'] ?>"
                           onchange="updateSoChon()">
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($r['HoTen']) ?></td>
                <td><?= htmlspecialchars($r['MaSVText']) ?></td>
                <td><?= $r['HocKy'] ? 'HK '.$r['HocKy'] : '—' ?></td>
                <td><?= $r['NamHoc'] ?? '—' ?></td>
                <td style="text-align:center">
                    <?php if ($r['MaPhieu']): ?>
                        <strong style="color:#1a56a0"><?= $r['TongDiem'] ?? 0 ?></strong>/100
                    <?php else: ?>
                        <span style="color:#aaa;font-size:.85rem">Chưa nộp</span>
                    <?php endif; ?>
                </td>
                <td><?= $r['NgayTao'] ? date('d/m/Y', strtotime($r['NgayTao'])) : '—' ?></td>
                <td>
                    <?php if ($r['MaPhieu']): ?>
                    <a href="<?= BASE_URL ?>/?c=print&a=in_phieu&phieu=<?= $r['MaPhieu'] ?>"
                       target="_blank" class="btn btn-secondary" style="padding:4px 10px;font-size:.82rem">
                       🖨️ Xem
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
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
