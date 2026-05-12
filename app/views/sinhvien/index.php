<h1 class="page-title">👋 Xin chào, <?= htmlspecialchars($sv['HoTen']) ?>!</h1>

<div class="card">
    <div class="card-title">📋 Thông tin cá nhân</div>
    <div class="sv-info-grid">
        <div><span style="color:#888;font-size:.85rem">Mã SV</span><br><strong><?= htmlspecialchars($sv['MaSV'] ?? '—') ?></strong></div>
        <div><span style="color:#888;font-size:.85rem">Lớp</span><br><strong><?= htmlspecialchars($sv['TenLop']) ?></strong></div>
        <div><span style="color:#888;font-size:.85rem">Khoa</span><br><strong><?= htmlspecialchars($sv['TenKhoa']) ?></strong></div>
        <div><span style="color:#888;font-size:.85rem">Năm học</span><br><strong><?= htmlspecialchars($sv['NamHoc']) ?></strong></div>
        <div><span style="color:#888;font-size:.85rem">GVCN</span><br><strong><?= htmlspecialchars($sv['GVTenHoTen'] ?? '—') ?></strong></div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <div class="card-title">📄 Phiếu gần đây</div>
        <a href="<?= BASE_URL ?>/?c=sinhvien&a=xem_phieu" class="btn btn-secondary">Xem tất cả</a>
    </div>
    <?php if (empty($phieuGanNhat)): ?>
        <div style="text-align:center;padding:30px;color:#888">
            Bạn chưa nộp phiếu nào.<br><br>
            <a href="<?= BASE_URL ?>/?c=sinhvien&a=dien_phieu" class="btn btn-primary">📝 Điền phiếu ngay</a>
        </div>
    <?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Học kỳ</th>
                    <th>Năm học</th>
                    <th>Ngày nộp</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($phieuGanNhat as $p): ?>
                <tr>
                    <td>Học kỳ <?= $p['HocKy'] ?></td>
                    <td><?= $p['NamHoc'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($p['NgayTao'])) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
