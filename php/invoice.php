<?php
include __DIR__ . '/header.php';

$hoadon_id = $_GET['id'] ?? '';

if (empty($hoadon_id)) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showError('Không tìm thấy hóa đơn!').then(() => {
                window.location.href = '/CoSo/php/menu.php';
            });
        });
    </script>";
    exit;
}

require_once __DIR__ . '/connect.php';

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
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showError('Hóa đơn không tồn tại!').then(() => {
                window.location.href = '/CoSo/php/menu.php';
            });
        });
    </script>";
    exit;
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
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn #<?= $hoadon_id ?></title>
    <link rel="stylesheet" href="/CoSo/css/reset.css">
    <link rel="stylesheet" href="/CoSo/css/general.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .invoice-container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .invoice-header h1 {
            margin: 0;
            font-size: 2em;
            font-weight: bold;
        }

        .invoice-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }

        .invoice-body {
            padding: 30px;
        }

        .invoice-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-section h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.2em;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 8px;
        }

        .info-section p {
            margin: 8px 0;
            line-height: 1.5;
        }

        .invoice-items {
            margin: 30px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .items-table th {
            background-color: #f8f9fa;
            color: #333;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }

        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .items-table tr:hover {
            background-color: #f8f9fa;
        }

        .item-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }

        .invoice-total {
            text-align: right;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #667eea;
        }

        .total-amount {
            font-size: 1.5em;
            font-weight: bold;
            color: #667eea;
        }

        .invoice-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .payment-status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: bold;
        }

        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        @media print {
            .invoice-actions {
                display: none;
            }
            
            body {
                background: white;
            }
            
            .invoice-container {
                box-shadow: none;
                margin: 0;
            }
        }

        @media (max-width: 768px) {
            .invoice-info {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .invoice-actions {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1><i class="fas fa-receipt"></i> HÓA ĐƠN</h1>
            <p>Mã hóa đơn: #<?= str_pad($hoadon_id, 6, '0', STR_PAD_LEFT) ?></p>
        </div>

        <div class="invoice-body">
            <div class="invoice-info">
                <div class="info-section">
                    <h3><i class="fas fa-user"></i> Thông tin khách hàng</h3>
                    <p><strong>Họ tên:</strong> <?= htmlspecialchars($hoadon['Tenkh']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($hoadon['Email']) ?></p>
                    <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($hoadon['SDT']) ?></p>
                    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($hoadon['Diachi']) ?></p>
                </div>

                <div class="info-section">
                    <h3><i class="fas fa-info-circle"></i> Thông tin đơn hàng</h3>
                    <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($hoadon['created_at'])) ?></p>
                    <p><strong>Ngày giao:</strong> <?= date('d/m/Y', strtotime($hoadon['ship_date'])) ?></p>
                    <p><strong>Thời gian giao:</strong> <?= $hoadon['ship_time'] ?></p>
                    <p><strong>Thanh toán:</strong> 
                        <?php if ($hoadon['payment_method'] == 'momo'): ?>
                            <span class="payment-status status-paid">MoMo</span>
                        <?php elseif ($hoadon['payment_method'] == 'bank'): ?>
                            <span class="payment-status status-paid">Chuyển khoản</span>
                        <?php else: ?>
                            <span class="payment-status status-pending">Tiền mặt</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <div class="invoice-items">
                <h3><i class="fas fa-list"></i> Chi tiết đơn hàng</h3>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Hình ảnh</th>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($chitiets as $item): ?>
                        <tr>
                            <td>
                                <?php if ($item['Hinhanh']): ?>
                                    <img src="<?= htmlspecialchars($item['Hinhanh']) ?>" class="item-image" alt="<?= htmlspecialchars($item['Tensp'] ?: $item['product_name']) ?>">
                                <?php else: ?>
                                    <div class="item-image" style="background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image" style="color: #ccc;"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['Tensp'] ?: $item['product_name']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                            <td><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="invoice-total">
                <p class="total-amount">
                    <strong>Tổng cộng: <?= number_format($total, 0, ',', '.') ?>đ</strong>
                </p>
            </div>

            <div class="invoice-actions">
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> In hóa đơn
                </button>
                <a href="/CoSo/php/download_invoice.php?id=<?= $hoadon_id ?>" class="btn btn-primary" target="_blank">
                    <i class="fas fa-download"></i> Tải PDF
                </a>
                <a href="/CoSo/php/menu.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Về trang chủ
                </a>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
