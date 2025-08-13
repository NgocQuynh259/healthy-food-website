<?php
header('Content-Type: application/json');
require_once 'connect.php';

try {
    $hoadon_id = $_GET['id'] ?? null;
    
    if (!$hoadon_id || !is_numeric($hoadon_id)) {
        echo json_encode([
            'success' => false,
            'message' => 'ID hóa đơn không hợp lệ'
        ]);
        exit;
    }

    // Lấy thông tin hóa đơn
    $stmt = $conn->prepare("
        SELECT h.*, k.Tenkh, k.SDT, k.Diachi 
        FROM hoadon h 
        LEFT JOIN khachhang k ON h.user_id = k.Makh 
        WHERE h.id = ?
    ");
    $stmt->bind_param("i", $hoadon_id);
    $stmt->execute();
    $hoadon_result = $stmt->get_result();
    $hoadon = $hoadon_result->fetch_assoc();

    if (!$hoadon) {
        echo json_encode([
            'success' => false,
            'message' => 'Không tìm thấy hóa đơn'
        ]);
        exit;
    }

    // Lấy chi tiết hóa đơn
    $stmt2 = $conn->prepare("
        SELECT cthd.*, sp.Tensp 
        FROM chitiethoadon cthd 
        LEFT JOIN sanpham sp ON cthd.product_id = sp.Masp 
        WHERE cthd.hoadon_id = ?
    ");
    $stmt2->bind_param("i", $hoadon_id);
    $stmt2->execute();
    $items_result = $stmt2->get_result();
    $items = [];

    $subtotal = 0;
    while ($item = $items_result->fetch_assoc()) {
        $total_price = $item['price'] * $item['quantity'];
        $subtotal += $total_price;
        
        $items[] = [
            'name' => $item['Tensp'] ?: 'Sản phẩm không xác định',
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'total_price' => $total_price
        ];
    }

    // Tính phí ship: 30k nếu dưới 150k, miễn phí nếu từ 150k
    $shipping_fee = $subtotal < 150000 ? 30000 : 0;
    $total_amount = $subtotal + $shipping_fee;

    // Chuẩn bị dữ liệu trả về
    $response = [
        'success' => true,
        'hoadon' => [
            'id' => $hoadon['id'],
            'created_at' => $hoadon['created_at'],
            'ship_date' => $hoadon['ship_date'],
            'ship_time' => $hoadon['ship_time'],
            'payment_method' => $hoadon['payment_method'],
            'status' => 'Đã xác nhận',
            'subtotal' => $subtotal,
            'shipping_fee' => $shipping_fee,
            'total_amount' => $total_amount,
            'customer' => [
                'name' => $hoadon['Tenkh'] ?: 'Khách hàng',
                'phone' => $hoadon['SDT'] ?: 'Chưa cập nhật',
                'address' => $hoadon['Diachi'] ?: 'Chưa cập nhật'
            ]
        ],
        'items' => $items
    ];

    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi server: ' . $e->getMessage()
    ]);
}
?>
