<?php
require_once __DIR__ . '/connect.php';

$hoadon_id = $_GET['id'] ?? '';

if (empty($hoadon_id)) {
    die('Không tìm thấy hóa đơn!');
}

// Lấy thông tin hóa đơn
$stmt = $conn->prepare("
    SELECT h.*, k.Tenkh, k.SDT, k.Diachi, k.Email 
    FROM hoadon h 
    JOIN khachhang k ON h.user_id = k.Makh 
    WHERE h.id = ?
");
$stmt->bind_param("i", $hoadon_id);
$stmt->execute();
$hoadon = $stmt->get_result()->fetch_assoc();

if (!$hoadon) {
    die('Hóa đơn không tồn tại!');
}

// Lấy chi tiết hóa đơn
$stmt2 = $conn->prepare("
    SELECT ct.*, sp.Tensp, sp.Hinhanh 
    FROM chitiethoadon ct 
    LEFT JOIN sanpham sp ON ct.product_id = sp.Masp 
    WHERE ct.hoadon_id = ?
");
$stmt2->bind_param("i", $hoadon_id);
$stmt2->execute();
$chitiets = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

$total = 0;
foreach ($chitiets as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Headers cho PDF download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="hoa-don-' . str_pad($hoadon_id, 6, '0', STR_PAD_LEFT) . '.pdf"');

// Tạo HTML cho PDF (đơn giản, không cần thư viện PDF phức tạp)
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hóa đơn #<?= $hoadon_id ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .info { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .info div { width: 48%; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { text-align: right; font-size: 18px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>HÓA ĐƠN BÁN HÀNG</h1>
        <p>Mã hóa đơn: #<?= str_pad($hoadon_id, 6, '0', STR_PAD_LEFT) ?></p>
        <p>Ngày lập: <?= date('d/m/Y H:i', strtotime($hoadon['created_at'])) ?></p>
    </div>

    <div class="info">
        <div>
            <h3>Thông tin khách hàng:</h3>
            <p><strong>Họ tên:</strong> <?= htmlspecialchars($hoadon['Tenkh']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($hoadon['Email']) ?></p>
            <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($hoadon['SDT']) ?></p>
            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($hoadon['Diachi']) ?></p>
        </div>
        <div>
            <h3>Thông tin giao hàng:</h3>
            <p><strong>Ngày giao:</strong> <?= date('d/m/Y', strtotime($hoadon['ship_date'])) ?></p>
            <p><strong>Thời gian:</strong> <?= $hoadon['ship_time'] ?></p>
            <p><strong>Thanh toán:</strong> 
                <?php 
                switch($hoadon['payment_method']) {
                    case 'momo': echo 'MoMo'; break;
                    case 'bank': echo 'Chuyển khoản'; break;
                    default: echo 'Tiền mặt'; break;
                }
                ?>
            </p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php $stt = 1; foreach ($chitiets as $item): ?>
            <tr>
                <td><?= $stt++ ?></td>
                <td><?= htmlspecialchars($item['Tensp'] ?: $item['product_name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                <td><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total">
        <p>Tổng cộng: <?= number_format($total, 0, ',', '.') ?>đ</p>
    </div>

    <script>
        // Tự động in khi trang load
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
