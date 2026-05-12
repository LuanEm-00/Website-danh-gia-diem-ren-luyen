<h1 class="page-title">📄 Phiếu đã nộp</h1>

<div class="card">
    <?php if (empty($phieuDisplay) && $totalPhieu === 0): ?>
        <div class="empty-state">
            Chưa có phiếu nào.<br>
            <a href="<?= BASE_URL ?>/?c=sinhvien&a=dien_phieu" class="btn btn-primary">📝 Điền phiếu ngay</a>
        </div>
    <?php else: ?>
    <div class="table-wrapper">
        <table class="phieu-table">
            <thead>
                <tr>
                    <th>Học kỳ</th>
                    <th>Năm học</th>
                    <th class="tbl-num">Tổng điểm</th>
                    <th>Ngày nộp</th>
                    <th class="col-actions">Thao tác</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($phieuDisplay as $p): ?>
                <tr>
                    <td>Học kỳ <?= $p['HocKy'] ?></td>
                    <td><?= $p['NamHoc'] ?></td>
                    <td class="tbl-num"><?= $p['TongDiem'] ?? 0 ?> / 100</td>
                    <td><?= date('d/m/Y', strtotime($p['NgayTao'])) ?></td>
                    <td class="col-actions">
                        <a href="<?= BASE_URL ?>/?c=print&a=in_phieu&phieu=<?= $p['MaPhieu'] ?>" class="btn btn-sm btn-primary">👁️ Xem</a>
                        <a href="<?= BASE_URL ?>/?c=sinhvien&a=sua_phieu&phieu=<?= $p['MaPhieu'] ?>" class="btn btn-sm btn-secondary">✏️ Sửa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="<?= BASE_URL ?>/?c=sinhvien&a=xem_phieu&page=1" class="pag-btn">«</a>
            <a href="<?= BASE_URL ?>/?c=sinhvien&a=xem_phieu&page=<?= $currentPage - 1 ?>" class="pag-btn">‹</a>
        <?php endif; ?>
        
        <span class="pag-info">Trang <?= $currentPage ?> / <?= $totalPages ?></span>
        
        <?php if ($currentPage < $totalPages): ?>
            <a href="<?= BASE_URL ?>/?c=sinhvien&a=xem_phieu&page=<?= $currentPage + 1 ?>" class="pag-btn">›</a>
            <a href="<?= BASE_URL ?>/?c=sinhvien&a=xem_phieu&page=<?= $totalPages ?>" class="pag-btn">»</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

