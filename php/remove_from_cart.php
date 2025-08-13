<?php
header('Content-Type: application/json');
session_start();

// Debug log function
function debugLog($message) {
    file_put_contents('debug.log', date('Y-m-d H:i:s') . " - REMOVE_CART: $message\n", FILE_APPEND);
}

debugLog("=== REMOVE FROM CART START ===");
debugLog("POST data: " . print_r($_POST, true));

try {
    // Nhận dữ liệu
    $id = $_POST['id'] ?? '';
    $cartJson = $_POST['cart'] ?? '[]';
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'error' => 'Thiếu ID sản phẩm']);
        exit;
    }
    
    $cart = json_decode($cartJson, true);
    if (!is_array($cart)) {
        echo json_encode(['success' => false, 'error' => 'Dữ liệu giỏ hàng không hợp lệ']);
        exit;
    }
    
    debugLog("Removing item with ID: $id");
    debugLog("Cart before remove: " . print_r($cart, true));
    
    // Tìm và xóa sản phẩm
    $found = false;
    $newCart = [];
    foreach ($cart as $item) {
        if ($item['id'] !== $id) {
            $newCart[] = $item;
        } else {
            $found = true;
            debugLog("Found and removed item: " . print_r($item, true));
        }
    }
    
    debugLog("Cart after remove: " . print_r($newCart, true));
    
    // Trả về kết quả thành công
    echo json_encode([
        'success' => true, 
        'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
        'cart' => $newCart,
        'removed_id' => $id
    ]);
    
} catch (Exception $e) {
    debugLog("ERROR: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'error' => 'Lỗi server: ' . $e->getMessage()
    ]);
}
?>
