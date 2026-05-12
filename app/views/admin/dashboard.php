<h1 class="page-title">📊 Trang chủ</h1>

<div class="stats-grid">
    <div class="stat-card">
        <div class="num"><?= $statSV ?></div>
        <div class="label">Sinh viên</div>
    </div>
    <div class="stat-card" style="border-color:#38a169">
        <div class="num" style="color:#38a169"><?= $statGV ?></div>
        <div class="label">Giảng viên</div>
    </div>
    <div class="stat-card" style="border-color:#d69e2e">
        <div class="num" style="color:#d69e2e"><?= $statLop ?></div>
        <div class="label">Lớp học</div>
    </div>
    <div class="stat-card" style="border-color:#3182ce">
        <div class="num" style="color:#3182ce"><?= $statPhieu ?></div>
        <div class="label">Phiếu đã nộp</div>
    </div>
</div>

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <div class="card-title" style="margin:0">📄 Phiếu nộp gần đây</div>
        <a href="<?= BASE_URL ?>/?c=admin&a=phieu" class="btn btn-secondary">Xem tất cả</a>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Sinh viên</th><th>Lớp</th>
                    <th>HK</th><th>Năm học</th>
                    <th style="text-align:center">Tổng điểm</th><th>Ngày nộp</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($phieuMoi as $r): ?>
            <tr>
                <td><?= $r['MaPhieu'] ?></td>
                <td><?= htmlspecialchars($r['HoTen']) ?></td>
                <td><?= htmlspecialchars($r['TenLop']) ?></td>
                <td>HK<?= $r['HocKy'] ?></td>
                <td><?= $r['NamHoc'] ?></td>
                <td style="text-align:center"><strong><?= $r['TongDiem'] ?? 0 ?></strong>/100</td>
                <td><?= date('d/m/Y', strtotime($r['NgayTao'])) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
