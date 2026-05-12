<h1 class="page-title">👋 Xin chào, <?= htmlspecialchars($gv['HoTen']) ?>!</h1>

<?php if (empty($lopRows)): ?>
<div class="alert alert-error">Bạn chưa được phân công chủ nhiệm lớp nào. Vui lòng liên hệ Admin.</div>
<?php else: ?>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;margin-bottom:24px">
<?php foreach ($lopRows as $l):
    $chuaNop = $l['SoSV'] - $l['DaNop'];
    $pct     = $l['SoSV'] > 0 ? round($l['DaNop'] / $l['SoSV'] * 100) : 0;
?>
<div class="card" style="margin:0;cursor:pointer;transition:box-shadow .2s"
     onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.12)'"
     onmouseout="this.style.boxShadow='0 1px 4px rgba(0,0,0,.07)'"
     onclick="location.href='<?= BASE_URL ?>/?c=giangvien&a=tra_cuu&malop=<?= $l['MaLop'] ?>'">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px">
        <div>
            <div style="font-size:1.1rem;font-weight:700;color:#1a2e4a"><?= htmlspecialchars($l['TenLop']) ?></div>
            <div style="font-size:.85rem;color:#888;margin-top:2px"><?= htmlspecialchars($l['TenKhoa']) ?></div>
        </div>
        <span style="font-size:1.6rem">🎓</span>
    </div>

    <div style="margin-bottom:10px">
        <div style="display:flex;justify-content:space-between;font-size:.82rem;color:#666;margin-bottom:4px">
            <span>Đã nộp phiếu</span>
            <span><?= $l['DaNop'] ?> / <?= $l['SoSV'] ?> SV (<?= $pct ?>%)</span>
        </div>
        <div style="height:6px;background:#e2e8f0;border-radius:4px">
            <div style="height:100%;width:<?= $pct ?>%;background:<?= $pct>=80?'#38a169':($pct>=50?'#d69e2e':'#e53e3e') ?>;border-radius:4px;transition:width .3s"></div>
        </div>
    </div>

    <div style="display:flex;gap:8px;flex-wrap:wrap">
        <span class="badge badge-info"><?= $l['SoSV'] ?> sinh viên</span>
        <span class="badge badge-success"><?= $l['DaNop'] ?> đã nộp</span>
        <?php if ($chuaNop > 0): ?>
        <span class="badge badge-warning"><?= $chuaNop ?> chưa nộp</span>
        <?php endif; ?>
    </div>

    <div style="margin-top:12px;color:#1a56a0;font-size:.85rem;font-weight:600">
        Xem danh sách →
    </div>
</div>
<?php endforeach; ?>
</div>

<?php endif; ?>
