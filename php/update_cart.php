<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['makh'])) {
    echo json_encode(['success' => false, 'error' => 'Bạn cần đăng nhập!']);
    exit;
}

$cart = json_decode($_POST['cart'] ?? '[]', true);
if (!is_array($cart)) {
    echo json_encode(['success' => false, 'error' => 'Dữ liệu giỏ hàng không hợp lệ']);
    exit;
}

// Cập nhật giỏ hàng trong session
$_SESSION['cart'] = [];
foreach ($cart as $item) {
    if (isset($item['id'], $item['quantity']) && $item['quantity'] > 0) {
        $_SESSION['cart'][$item['id']] = $item['quantity'];
    }
}

echo json_encode(['success' => true]);
?>