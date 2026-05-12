<?php
function dd($d, $k) { return intval($d[$k] ?? 0); }
function tongDiem($d) {
    return dd($d,'diem_1_1')+dd($d,'diem_1_2')+dd($d,'diem_1_3')+dd($d,'diem_1_4')+dd($d,'diem_1_5')
          +dd($d,'diem_2_1')+dd($d,'diem_2_2')+dd($d,'diem_2_3')+dd($d,'diem_2_4')+dd($d,'diem_2_5')+dd($d,'diem_2_6')+dd($d,'diem_2_7')
          +dd($d,'diem_3_1')+dd($d,'diem_3_2')+dd($d,'diem_3_3')
          +dd($d,'diem_4_1')+dd($d,'diem_4_2');
}

?>
<title>Phieu_<?= htmlspecialchars(count($phieus) > 1 ? 'Nhieu' : '') ?></title>
<div class="toolbar">
    <a href="<?= BASE_URL ?>/?c=sinhvien&a=xem_phieu" class="btn-back">← Quay lại</a>
    <span class="toolbar-title">🖨️ In <?= count($phieus) ?> phiếu đánh giá rèn luyện</span>
    <div style="display:flex;gap:8px">
        <button class="btn-print" onclick="window.print()">🖨️ In tất cả / Lưu PDF</button>
    </div>
</div>

<div class="wrap">
<?php foreach ($phieus as $p):
    $d    = $p['diem'];
    $t1   = dd($d,'diem_1_1')+dd($d,'diem_1_2')+dd($d,'diem_1_3')+dd($d,'diem_1_4')+dd($d,'diem_1_5');
    $t2   = dd($d,'diem_2_1')+dd($d,'diem_2_2')+dd($d,'diem_2_3')+dd($d,'diem_2_4')+dd($d,'diem_2_5')+dd($d,'diem_2_6')+dd($d,'diem_2_7');
    $t3   = dd($d,'diem_3_1')+dd($d,'diem_3_2')+dd($d,'diem_3_3');
    $t4   = dd($d,'diem_4_1')+dd($d,'diem_4_2');
    $tong = $t1+$t2+$t3+$t4;
?>
<div class="phieu">
    <div class="hdr">
        <div class="hdr-col">
            <b>BỘ GIÁO DỤC VÀ ĐÀO TẠO</b>
            <b>TRƯỜNG <u>CAO ĐẲNG CÔNG NGHỆ BÁCH KHOA HÀ</u> NỘI</b>
        </div>
        
    </div>

    <div class="tieude">
        <h2>Phiếu đánh giá kết quả rèn luyện</h2>
        <p>Khóa <?= htmlspecialchars(substr($p['NamHoc'], 0, 4)) ?>
           (Niên khóa <?= htmlspecialchars($p['NienKhoa'] ?? '...') ?>)</p>
    </div>

    <div class="info">
        <div class="info-row"><span class="info-label">Họ và tên: </span><span class="info-val"><?= htmlspecialchars($p['HoTen']) ?></span></div>
        <div class="info-row"><span class="info-label">Ngày sinh: </span><span class="info-val"><?= $p['NgaySinh'] ? date('d/m/Y',strtotime($p['NgaySinh'])) : '...' ?></span></div>
        <div class="info-row"><span class="info-label">Mã số sinh viên: </span><span class="info-val"><?= htmlspecialchars($p['MaSVText']) ?></span></div>
        <div class="info-row"><span class="info-label">Lớp: </span><span class="info-val"><?= htmlspecialchars($p['TenLop']) ?></span></div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="font-size:12pt;">Nội dung đánh giá</th>
                <th class="col-max">Điểm tối đa</th>
                <th class="col-diem">Tự đánh giá</th>
                <th class="col-diem">GVCN đ/giá</th>
                <th class="col-note">Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            <tr class="row-cha"><td><b>I. Ý thức và kết quả học tập:</b></td><td class="col-max">30</td><td class="col-diem"><?=$t1?></td><td></td><td></td></tr>
            <tr><td class="td-main">1. Ý thức và thái độ trong học tập.</td><td class="col-max">0÷10</td><td class="col-diem"><?=dd($d,'diem_1_1')?></td><td></td><td></td></tr>
            <tr><td class="td-main">2. Ý thức và thái độ tham gia các hoạt động học tập, hoạt động ngoại khóa, hoạt động nghiên cứu khoa học.</td><td class="col-max" rowspan="4">0÷4</td><td class="col-diem" rowspan="4"><?=dd($d,'diem_1_2')?></td><td class="col-diem"></td><td class="col-note"></td></tr>
            <tr><td class="td-main">- Ý thức và thái độ tham gia các kỳ thi, cuộc thi Olympic sinh viên;</td></td></td><td class="col-diem"></td><td class="col-note"></td></tr>
            <tr><td class="td-main">Đạt giải nhất: 4 đ; Đạt giải nhì: 3 đ; Đạt giải ba: 2 đ;</td></td></td><td class="col-diem"></td><td class="col-note"></td></tr>
            <tr><td class="td-main">Đạt giải khuyến khích/có tham gia: 1 đ</td></td></td><td class="col-diem"></td><td class="col-note"></td></tr>
            <tr><td class="td-main">3. Sinh viên đạt được các thành tích đặc biệt trong học tập, rèn luyện.</td><td class="col-max">0÷5</td><td class="col-diem"><?=dd($d,'diem_1_3')?></td><td class="col-diem"></td><td class="col-note"></td></tr>
            <tr><td class="td-main">4. Tinh thần vượt khó, phấn đấu vươn lên trong học tập.</td><td class="col-max">0÷5</td><td class="col-diem"><?=dd($d,'diem_1_4')?></td><td></td><td></td></tr>
            <tr><td class="td-main">5. Kết quả học tập. Trong độ chỉ làm 2 nội dung sau:</td><td class="col-max" rowspan="5">0÷6</td><td class="col-diem" rowspan="5"><?=dd($d,'diem_1_5')?></td><td></td><td></td></tr>
            <tr><td class="td-main">- Kết quả học tập: Loại Xuất sắc: 6đ; Loại Giỏi: 5đ; Loại Khá: 4đ; Loại Trung bình: 2đ; Loại yếu, kém: 0 đ</td></td></td><td class="col-diem"></td><td class="col-note"></td></tr>
            <tr><td class="td-main">  Vi phạm quy chế thi:</td></td></td><td class="col-diem"></td><td class="col-note"></td></tr>
            <tr><td class="td-sub">+ Định chí, cạnh cạo toàn bộ mục I được 0đ</td></td></td><td class="col-diem"></td><td class="col-note"></td></tr>
            <tr><td class="td-sub">+ Khiến trách trừ 50% số điểm mục I</td></td></td><td class="col-diem"></td><td class="col-note"></td></tr>

            <tr class="row-cha">
                <td><b>II. Ý thức chấp hành pháp luật và nội quy, quy chế của Nhà trường</b></td>
                <td class="col-max">25</td><td class="col-diem"><?=$t2?></td><td class="col-diem"></td><td class="col-note"></td>
            </tr>
            <tr><td class="td-main">1. Ý thức chấp hành các quy định của pháp luật đối với công dân, các văn bản chỉ đạo của Bộ, ngành, của cơ quan quản lý Nhà trường.</td><td class="col-max">0÷5</td><td class="col-diem"><?=dd($d,'diem_2_1')?></td><td class="col-diem"></td><td class="col-note"></td></tr>
            <tr><td class="td-main">2. Tham gia đầy đủ các buổi sinh hoạt lớp, sinh hoạt chi đoàn, các buổi chào cờ.</td><td class="col-max">0÷4</td><td class="col-diem"><?=dd($d,'diem_2_2')?></td><td class="col-diem"></td><td class="col-note"></td></tr>
            <tr><td class="td-main">3. Thực hiện nghiêm túc đăng ký nội trú, ngoài trú (dựa vào nhận xét của địa phương hoặc Ban quản lý ký túc xá).</td><td class="col-max">0÷4</td><td class="col-diem"><?=dd($d,'diem_2_3')?></td><td></td><td></td></tr>
            <tr><td class="td-main">4. Thực hiện tốt nếp sống văn minh, ăn mặc sạch, đẹp, đầu tóc gọn gàng phù hợp với tư cách của người học sinh.</td><td class="col-max">0÷3</td><td class="col-diem"><?=dd($d,'diem_2_4')?></td><td class="col-diem"></td><td class="col-note"></td></tr>
            <tr><td class="td-main">5. Không hút thuốc lá, uống rượu, chơi bài. Trừ 1 điểm/lần vi phạm.</td><td class="col-max">0÷3</td><td class="col-diem"><?=dd($d,'diem_2_5')?></td><td class="col-diem"></td><td class="col-note"></td></tr>
            <tr><td class="td-main">6. Đóng học phí, lệ phí và các khoản đóng góp khác.</td><td class="col-max">0÷3</td><td class="col-diem"><?=dd($d,'diem_2_6')?></td><td class="col-diem"></td><td class="col-note"></td></tr>
            <tr><td class="td-main">7. Bảo vệ và giữ gìn tài sản của nhà trường và của nhân dân nơi ở trọ, thực hiện tiết kiệm điện, nước.</td><td class="col-max">0÷3</td><td class="col-diem"><?=dd($d,'diem_2_7')?></td><td class="col-diem"></td><td class="col-note"></td></tr>

            <tr class="row-cha">
                <td><b>III. Ý thức tham gia các hoạt động chính trị - xã hội, văn hóa, văn nghệ, thể thao, phòng, chống tội phạm, tệ nạn xã hội, bạo lực học đường</b></td>
                <td class="col-max">25</td><td class="col-diem"><?=$t3?></td><td class="col-diem"></td><td class="col-note"></td>
            </tr>
            <tr><td class="td-main">1. Ý thức và hiệu quả tham gia các hoạt động rèn luyện về chính trị, xã hội, văn hóa, văn nghệ, thể thao: Các ngày kỷ niệm, mít tinh, tìm hiểu truyền thống văn hóa, lịch sử dân tộc do nhà trường tổ chức; các buổi tập văn hóa, văn nghệ, TDTT của lớp, chi đoàn và đoàn trường tổ chức</td><td class="col-max">0÷10</td><td class="col-diem"><?=dd($d,'diem_3_1')?></td><td class="col-diem"></td><td class="col-note"></td></tr>
            <tr><td class="td-main">2. Ý thức tham gia các hoạt động công ích, tình nguyện, công tác xã hội.</td><td class="col-max">0÷10</td><td class="col-diem"><?=dd($d,'diem_3_2')?></td><td class="col-diem"></td><td class="col-note"></td></tr>
            <tr><td class="td-main">3. Tham gia tuyên truyền, phòng chống tội phạm và các tệ nạn xã hội.</td><td class="col-max">0÷5</td><td class="col-diem"><?=dd($d,'diem_3_3')?></td><td class="col-diem"></td><td class="col-note"></td></tr>

            <tr class="row-cha">
                <td><b>IV. Ý thức và kết quả tham gia công tác cán bộ lớp, công tác đoàn thể, các tổ chức khác của nhà trường hoặc có thành tích xuất sắc trong học tập, rèn luyện được cơ quan có thẩm quyền khen thưởng</b></td>
                <td class="col-max">20</td><td class="col-diem"><?=$t4?></td><td class="col-diem"></td><td class="col-note"></td>
            </tr>
            <tr><td class="td-main">1. Mạnh dạn đấu tranh, bảo vệ sự đoàn kết trong lớp, trong trường.</td><td class="col-max">0÷10</td><td class="col-diem"><?=dd($d,'diem_4_1')?></td><td class="col-diem"></td><td class="col-note"></td></tr>
            <tr><td class="td-main">2. Có hành vi cưu mang giúp đỡ người gặp khó khăn, hoạn nạn trong lớp, trong trường và ngoài xã hội được lớp và giáo viên chủ nhiệm lớp xác nhận.</td><td class="col-max">0÷10</td><td class="col-diem"><?=dd($d,'diem_4_2')?></td><td class="col-diem"></td><td class="col-note"></td></tr>
        </tbody>
        <tfoot>
            <tr class="row-tong">
                <td>Tổng cộng (từ mục I đến mục IV)</td>
                <td class="col-max">100</td>
                <td class="col-diem"><?=$tong?></td>
                <td class="col-diem"></td>
                <td class="col-note"></td>
            </tr>
            <tr><td class="td-main">Xếp loại rèn luyện <i>(Xuất sắc: 90-100), (Tốt: 80-89), (Khá: 70-79), (TB: 50-69), (Yếu: dưới 50)</i></td><td class="col-max"></td><td class="col-diem"></td><td class="col-diem"></td><td class="col-note"></td></tr>
        </tfoot>
    </table>
    <div class="ghichu">* Ghi chú: Điểm tối đa cho mỗi mục, nội dung cũng như điểm tổng cộng không vượt quá khung điểm quy định.</div>

    <div class="ketluan">– Điểm kết luận trong cuộc họp của tập thể lớp: <span class="line"></span> điểm</div>
    <div class="ketluan" style="margin-top:1.5mm">– Xếp loại rèn luyện: <span class="line"></span></div>

    <div class="ngay" style="margin-top:3mm">Ngày &nbsp;&nbsp;&nbsp;&nbsp; tháng &nbsp;&nbsp;&nbsp;&nbsp; năm <?= date('Y') ?></div>
    <div class="kyten">
        <div class="kyten-col">
            <div class="title">GIÁO VIÊN CHỦ NHIỆM</div>
            <div class="sub">(Ký và ghi rõ họ tên)</div>
            <div class="name"><?= htmlspecialchars($p['TenGV'] ?? '') ?></div>
        </div>
        <div class="kyten-col">
            <div class="title">LỚP TRƯỞNG</div>
            <div class="sub">(Ký và ghi rõ họ tên)</div>
            <div class="name"></div>
        </div>
        <div class="kyten-col">
            <div class="title">SINH VIÊN</div>
            <div class="sub">(Ký và ghi rõ họ tên)</div>
            <div class="name"><?= htmlspecialchars($p['HoTen']) ?></div>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>
