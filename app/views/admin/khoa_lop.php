<h1 class="page-title">🏛️ Quản lý Khoa / Lớp</h1>

<?php if ($msgText): ?>
    <div class="alert alert-<?= $msgType ?>"><?= htmlspecialchars($msgText) ?></div>
<?php endif; ?>

<div class="split-layout">

    <!-- CỘT TRÁI: KHOA -->
    <div class="split-panel" id="panel-khoa">
        <div class="split-header">
            <span>🏫 Danh sách Khoa</span>
            <button class="btn btn-primary btn-sm" onclick="openKhoaModal()">+ Thêm</button>
        </div>
        <div class="split-body">
            <?php if (empty($khoaRows)): ?>
                <div class="empty-state">Chưa có khoa nào.</div>
            <?php else: foreach ($khoaRows as $k): ?>
            <div class="khoa-item" id="khoa-<?= $k['MaKhoa'] ?>"
                 onclick="selectKhoa(<?= $k['MaKhoa'] ?>, '<?= addslashes($k['TenKhoa']) ?>')">
                <div class="khoa-info">
                    <div class="khoa-name"><?= htmlspecialchars($k['TenKhoa']) ?></div>
                    <div class="khoa-meta"><?= $k['SoLop'] ?> lớp</div>
                </div>
                <div class="khoa-actions" onclick="event.stopPropagation()">
                    <button class="btn btn-secondary btn-sm"
                        onclick="openKhoaModal(<?= $k['MaKhoa'] ?>, '<?= addslashes($k['TenKhoa']) ?>')">Sửa</button>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="action" value="delete_khoa">
                        <input type="hidden" name="id" value="<?= $k['MaKhoa'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm btn-confirm-delete">Xóa</button>
                    </form>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>

    <!-- CỘT PHẢI: LỚP -->
    <div class="split-panel" id="panel-lop" style="display:none">
        <div class="split-header">
            <span id="lop-header-title">📚 Lớp thuộc khoa</span>
            <div style="display:flex;gap:8px;align-items:center">
                <button class="btn btn-primary btn-sm" onclick="openLopModal()">+ Thêm lớp</button>
                <button class="btn btn-secondary btn-sm" onclick="closeKhoa()">✕</button>
            </div>
        </div>
        <div class="split-body" id="lop-body"></div>
    </div>
</div>

<!-- MODAL KHOA -->
<div id="modal-khoa" class="modal-overlay">
<div class="modal-box">
    <h3 id="khoa-modal-title" style="margin-bottom:20px;color:#1a2e4a">Thêm Khoa</h3>
    <form method="POST">
        <input type="hidden" name="action" value="save_khoa">
        <input type="hidden" name="id" id="khoa-id" value="0">
        <div class="form-group">
            <label>Tên Khoa</label>
            <input type="text" name="tenkhoa" id="khoa-ten" required placeholder="VD: Công nghệ thông tin">
        </div>
        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <button type="button" class="btn btn-secondary" onclick="closeModal('modal-khoa')">Hủy</button>
        </div>
    </form>
</div>
</div>

<!-- MODAL LỚP -->
<div id="modal-lop" class="modal-overlay">
<div class="modal-box">
    <h3 id="lop-modal-title" style="margin-bottom:20px;color:#1a2e4a">Thêm Lớp</h3>
    <form method="POST">
        <input type="hidden" name="action" value="save_lop">
        <input type="hidden" name="id" id="lop-id" value="0">

        <div class="form-group">
            <label>Tên Lớp</label>
            <input type="text" name="tenlop" id="lop-ten" required placeholder="VD: 2623CNT02">
        </div>
        <div class="form-group">
            <label>Thuộc Khoa</label>
            <select name="makhoa" id="lop-makhoa" required>
                <?php foreach ($khoaOpts as $k): ?>
                <option value="<?= $k['MaKhoa'] ?>"><?= htmlspecialchars($k['TenKhoa']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>GVCN <small style="color:#888">(có thể bỏ trống)</small></label>
            <select name="magv" id="lop-magv">
                <option value="">— Chưa phân công —</option>
                <?php foreach ($gvOpts as $g): ?>
                <option value="<?= $g['MaGV'] ?>"><?= htmlspecialchars($g['HoTen']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <button type="button" class="btn btn-secondary" onclick="closeModal('modal-lop')">Hủy</button>
        </div>
    </form>
</div>
</div>

<style>
.split-layout { display:flex; gap:16px; height:calc(100vh - 180px); }
.split-panel  { flex:1; background:#fff; border-radius:10px; box-shadow:0 1px 4px rgba(0,0,0,.07); display:flex; flex-direction:column; overflow:hidden; }
.split-header { display:flex; justify-content:space-between; align-items:center; padding:16px 20px; border-bottom:1px solid #e2e8f0; font-weight:600; color:#1a2e4a; flex-shrink:0; background:#f8fafc; border-radius:10px 10px 0 0; }
.split-body   { flex:1; overflow-y:auto; padding:12px; }
.btn-sm { padding:5px 12px; font-size:.82rem; }
.khoa-item { display:flex; justify-content:space-between; align-items:center; padding:14px 16px; border-radius:8px; cursor:pointer; transition:background .15s; margin-bottom:6px; border:2px solid transparent; }
.khoa-item:hover  { background:#eef2ff; }
.khoa-item.active { background:#e8f0fe; border-color:#1a56a0; }
.khoa-name { font-weight:600; color:#1a2e4a; }
.khoa-meta { font-size:.8rem; color:#888; margin-top:2px; }
.khoa-actions { display:flex; gap:6px; opacity:0; transition:opacity .15s; }
.khoa-item:hover .khoa-actions { opacity:1; }
.lop-item { display:flex; justify-content:space-between; align-items:center; padding:12px 14px; border-radius:8px; margin-bottom:6px; background:#f8fafc; border:1px solid #e2e8f0; }
.lop-name { font-weight:600; color:#1a2e4a; }
.lop-meta { font-size:.8rem; color:#888; margin-top:2px; }
.lop-actions { display:flex; gap:6px; flex-shrink:0; }
.empty-state { text-align:center; color:#aaa; padding:40px 0; font-size:.9rem; }
.modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:999; align-items:center; justify-content:center; }
.modal-box { background:#fff; border-radius:12px; padding:32px; width:440px; max-width:95%; }
</style>

<script>
const allLop = <?= json_encode($lopRows) ?>;
let selectedKhoaId = null;

function selectKhoa(maKhoa, tenKhoa) {
    document.querySelectorAll('.khoa-item').forEach(el => el.classList.remove('active'));
    document.getElementById('khoa-' + maKhoa)?.classList.add('active');
    selectedKhoaId = maKhoa;
    document.getElementById('lop-header-title').textContent = '📚 Lớp — ' + tenKhoa;
    document.getElementById('panel-lop').style.display = 'flex';
    renderLop(maKhoa);
}

function renderLop(maKhoa) {
    const body = document.getElementById('lop-body');
    const lops = allLop.filter(l => l.MaKhoa == maKhoa);
    if (lops.length === 0) {
        body.innerHTML = '<div class="empty-state">Khoa này chưa có lớp nào.<br>Nhấn "+ Thêm lớp" để thêm.</div>';
        return;
    }
    body.innerHTML = lops.map(l => `
        <div class="lop-item">
            <div>
                <div class="lop-name">${escHtml(l.TenLop)}</div>
                <div class="lop-meta">
                    ${l.SoSV} sinh viên
                    ${l.TenGV ? '&nbsp;|&nbsp; GVCN: <strong>' + escHtml(l.TenGV) + '</strong>' : ''}
                </div>
            </div>
            <div class="lop-actions">
                <button class="btn btn-secondary btn-sm"
                    onclick="openLopModal(${l.MaLop},'${escJs(l.TenLop)}',${l.MaKhoa},${l.MaGV||0})">Sửa</button>
                <form method="POST" style="display:inline">
                    <input type="hidden" name="action" value="delete_lop">
                    <input type="hidden" name="id" value="${l.MaLop}">
                    <button type="submit" class="btn btn-danger btn-sm btn-confirm-delete">Xóa</button>
                </form>
            </div>
        </div>
    `).join('');
}

function closeKhoa() {
    document.getElementById('panel-lop').style.display = 'none';
    document.querySelectorAll('.khoa-item').forEach(el => el.classList.remove('active'));
    selectedKhoaId = null;
}

function openKhoaModal(id = 0, ten = '') {
    document.getElementById('khoa-modal-title').textContent = id ? 'Sửa Khoa' : 'Thêm Khoa';
    document.getElementById('khoa-id').value  = id;
    document.getElementById('khoa-ten').value = ten;
    document.getElementById('modal-khoa').style.display = 'flex';
}

function openLopModal(id = 0, ten = '', maKhoa = null, maGV = 0) {
    document.getElementById('lop-modal-title').textContent = id ? 'Sửa Lớp' : 'Thêm Lớp';
    document.getElementById('lop-id').value  = id;
    document.getElementById('lop-ten').value = ten;
    const khoaVal = maKhoa || selectedKhoaId;
    if (khoaVal) document.getElementById('lop-makhoa').value = khoaVal;
    document.getElementById('lop-magv').value = maGV || '';
    document.getElementById('modal-lop').style.display = 'flex';
}

function closeModal(id) { document.getElementById(id).style.display = 'none'; }

['modal-khoa','modal-lop'].forEach(id => {
    document.getElementById(id).addEventListener('click', e => {
        if (e.target.id === id) closeModal(id);
    });
});

function escHtml(s) { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
function escJs(s)   { return String(s).replace(/'/g,"\\'"); }
</script>
