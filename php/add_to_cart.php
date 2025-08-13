<?php
session_start();
header('Content-Type: application/json');

// Nhận ID và cart từ POST
$masp = isset($_POST['id']) ? trim($_POST['id']) : '';
$cartData = isset($_POST['cart']) ? json_decode($_POST['cart'], true) : null;

if (empty($masp)) {
    echo json_encode(['success' => false, 'error' => 'Invalid id']);
    exit;
}

// Khởi tạo giỏ
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['combo_details'])) {
    $_SESSION['combo_details'] = [];
}

// Cập nhật $_SESSION['cart'] từ cartData nếu có
if ($cartData !== null) {
    $_SESSION['cart'] = [];
    foreach ($cartData as $item) {
        if (isset($item['id'], $item['quantity']) && is_numeric($item['quantity'])) {
            $_SESSION['cart'][$item['id']] = (int)$item['quantity'];
        }
    }
    // Cập nhật combo_details
    $_SESSION['combo_details'] = [];
    foreach ($cartData as $item) {
        if (isset($item['id'], $item['combo_details']) && is_array($item['combo_details'])) {
            $_SESSION['combo_details'][$item['id']] = $item['combo_details'];
        }
    }
} else {
    // Nếu không có cartData, tăng qty như cũ
    if (isset($_SESSION['cart'][$masp])) {
        $_SESSION['cart'][$masp]++;
    } else {
        $_SESSION['cart'][$masp] = 1;
    }
}

// Trả về count mới và giỏ hàng
$count = array_sum($_SESSION['cart']);
echo json_encode([
    'success' => true,
    'count' => $count,
    'cart' => $_SESSION['cart'],
    'combo_details' => $_SESSION['combo_details']
]);
?>