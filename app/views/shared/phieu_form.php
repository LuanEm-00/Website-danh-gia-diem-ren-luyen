<?php
/**
 * Template form điền/sửa phiếu
 * Tham số:
 * - $mode: 'dien' (điền mới) | 'sua' (sửa)
 * - $phieu: object phiếu (null nếu điền mới)
 * - $diemHienTai: mảng điểm hiện tại
 * - $mucList: danh sách tiêu chí
 */
$mode = $mode ?? 'dien';
$isEdit = $mode === 'sua';
$diemHienTai = $diemHienTai ?? [];
?>

<form method="POST" id="form-phieu">

<?php if (!$isEdit): ?>
<!-- PHẦN CHỌN HỌC KỲ (chỉ khi điền mới) -->
<div class="card card-mb-sm">
    <div class="card-title">📅 Thông tin học kỳ</div>
    <div class="hk-select-row">
        <div class="form-group">
            <label>Học kỳ</label>
            <select name="hocky" required>
                <option value="1">Học kỳ 1</option>
                <option value="2">Học kỳ 2</option>
            </select>
        </div>
        <div class="form-group">
            <label>Năm học</label>
            <select name="namhoc" required>
                <option value="">-- Chọn năm học --</option>
                <?php foreach (require ROOT . '/config/namhoc.php' as $nh): ?>
                <option value="<?= $nh ?>" <?= ($_POST['namhoc'] ?? '') == $nh ? 'selected' : '' ?>>
                    <?= $nh ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>
<?php else: ?>
<!-- PHẦN THÔNG TIN PHIẾU (khi sửa) -->
<div class="card card-mb-sm">
    <div class="card-title">📅 Thông tin phiếu</div>
    <div class="phieu-info">
        <div class="phieu-info-item">
            <label>Học kỳ</label>
            <strong>Học kỳ <?= $phieu['HocKy'] ?></strong>
        </div>
        <div class="phieu-info-item">
            <label>Năm học</label>
            <strong><?= htmlspecialchars($phieu['NamHoc']) ?></strong>
        </div>
        <div class="phieu-info-item">
            <label>Ngày nộp</label>
            <strong><?= date('d/m/Y H:i', strtotime($phieu['NgayTao'])) ?></strong>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- BẢNG ĐIỂM TỰ ĐÁNH GIÁ -->
<div class="card">
    <div class="card-title">📋 Tự đánh giá điểm rèn luyện</div>
    <div class="table-wrapper">
        <table id="tbl-diem">
            <thead>
                <tr>
                    <th>Nội dung đánh giá</th>
                    <th class="tbl-num th-toida">Điểm tối đa</th>
                    <th class="tbl-num th-tudanhgia">Tự đánh giá</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($mucList as $soMuc => $muc): ?>
                <tr class="muc-row">
                    <td><?= htmlspecialchars($muc['ten']) ?></td>
                    <td class="tbl-num"><?= $muc['tong'] ?></td>
                    <td class="tbl-num">
                        <span class="tong-muc" id="tong-muc-<?= $soMuc ?>">0</span>
                    </td>
                </tr>
                <?php foreach ($muc['tieuchi'] as $key => $tc): ?>
                <tr>
                    <td class="tc-ten"><?= $tc['ten'] ?></td>
                    <td class="tc-max">0÷<?= $tc['max'] ?></td>
                    <td class="tc-diem">
                        <input type="number"
                               name="diem[<?= $key ?>]"
                               class="diem-input"
                               data-muc="<?= $soMuc ?>"
                               data-max="<?= $tc['max'] ?>"
                               min="0" max="<?= $tc['max'] ?>"
                               value="<?= intval($diemHienTai['diem_' . $key] ?? ($_POST['diem'][$key] ?? 0)) ?>"
                               required>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="tbl-tong-row">
                    <td>Tổng cộng (từ mục I đến mục IV)</td>
                    <td class="tbl-num">100</td>
                    <td class="tbl-tong-diem">
                        <span id="tong-tat-ca">0</span> / 100
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary btn-lg">
            <?php if ($isEdit): ?>
                💾 Cập nhật phiếu
            <?php else: ?>
                📨 Gửi phiếu
            <?php endif; ?>
        </button>
        <button type="reset" class="btn btn-secondary" onclick="setTimeout(updateTong,10)">🔄 Nhập lại</button>
        <span class="hint-text">* Điểm mỗi tiêu chí không vượt quá điểm tối đa</span>
    </div>
</div>
</form>

<script>
const inputs = document.querySelectorAll('.diem-input');

function updateTong() {
    const mucTong = {};
    let tongTatCa = 0;

    inputs.forEach(inp => {
        const val = Math.min(parseInt(inp.value) || 0, parseInt(inp.dataset.max));
        const muc = inp.dataset.muc;
        mucTong[muc] = (mucTong[muc] || 0) + val;
        tongTatCa   += val;
        inp.style.borderColor = (parseInt(inp.value)||0) > parseInt(inp.dataset.max)
            ? '#e53e3e' : '#d1d5db';
    });

    Object.keys(mucTong).forEach(m => {
        const el = document.getElementById('tong-muc-' + m);
        if (el) el.textContent = mucTong[m];
    });
    document.getElementById('tong-tat-ca').textContent = tongTatCa;
}

inputs.forEach(inp => inp.addEventListener('input', updateTong));
updateTong();

// Mobile enhancements
if (window.innerWidth <= 768) {
    // Stepper [−] input [+] + max hint
    document.querySelectorAll('.diem-input').forEach(inp => {
        const wrap = document.createElement('div');
        wrap.className = 'stepper';
        inp.parentNode.replaceChild(wrap, inp);

        const btnM = document.createElement('button');
        btnM.type = 'button'; btnM.className = 'stepper-btn'; btnM.textContent = '−';

        const btnP = document.createElement('button');
        btnP.type = 'button'; btnP.className = 'stepper-btn'; btnP.textContent = '+';

        wrap.appendChild(btnM);
        wrap.appendChild(inp);
        wrap.appendChild(btnP);

        const updateHasValue = () => {
            inp.style.borderColor = '';
            inp.classList.toggle('has-value', (parseInt(inp.value) || 0) > 0);
        };
        updateHasValue();
        inp.addEventListener('input', updateHasValue);

        btnM.addEventListener('click', () => {
            const v = parseInt(inp.value) || 0;
            if (v > 0) { inp.value = v - 1; inp.dispatchEvent(new Event('input')); }
        });
        btnP.addEventListener('click', () => {
            const v = parseInt(inp.value) || 0;
            const mx = parseInt(inp.dataset.max);
            if (v < mx) { inp.value = v + 1; inp.dispatchEvent(new Event('input')); }
        });

        // "cao nhất: X" bám mép phải
        const hint = document.createElement('span');
        hint.className = 'max-hint';
        hint.textContent = 'cao nhất: ' + inp.dataset.max;
        inp.closest('.tc-diem, .tbl-item-diem')?.appendChild(hint);
    });

    // Subtotal card cuối mỗi mục
    const tbody = document.querySelector('#tbl-diem tbody');
    if (tbody) {
        const mucRows = Array.from(tbody.querySelectorAll('.muc-row, .tbl-muc-row'));
        mucRows.forEach((mucRow, idx) => {
            const maxVal  = mucRow.cells[1].textContent.trim();
            const tongEl  = mucRow.cells[2].querySelector('.tong-muc');
            const nextMuc = mucRows[idx + 1] || null;

            const subRow = document.createElement('tr');
            subRow.className = 'subtotal-row';
            const td = document.createElement('td');
            td.colSpan = 3;

            const curSpan = document.createElement('span');
            curSpan.className = 'subtotal-current';
            curSpan.textContent = tongEl ? tongEl.textContent : '0';

            if (tongEl) {
                new MutationObserver(() => {
                    curSpan.textContent = tongEl.textContent;
                }).observe(tongEl, { childList: true, characterData: true, subtree: true });
            }

            td.appendChild(document.createTextNode('Tạm tính: '));
            td.appendChild(curSpan);
            td.appendChild(document.createTextNode(' / ' + maxVal + ' đ'));
            subRow.appendChild(td);
            tbody.insertBefore(subRow, nextMuc);
        });
    }
}

document.getElementById('form-phieu').addEventListener('submit', e => {
    let ok = true;
    inputs.forEach(inp => {
        if ((parseInt(inp.value)||0) > parseInt(inp.dataset.max)) ok = false;
    });
    if (!ok) {
        e.preventDefault();
        alert('Có tiêu chí vượt điểm tối đa. Vui lòng kiểm tra lại!');
    }
});
</script>
