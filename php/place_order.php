<?php
header('Content-Type: application/json');
require_once 'connect.php';
session_start();

// Debug log function
function debugLog($message) {
    file_put_contents('debug.log', date('Y-m-d H:i:s') . " - PLACE_ORDER: $message\n", FILE_APPEND);
}

debugLog("=== PLACE ORDER START ===");
debugLog("POST data: " . print_r($_POST, true));
debugLog("Session makh: " . ($_SESSION['makh'] ?? 'NULL'));

// Lấy mã khách hàng từ session
$makh = isset($_SESSION['makh']) ? $_SESSION['makh'] : null;
if (!$makh) {
    debugLog("ERROR: No makh in session");
    echo json_encode(['success' => false, 'message' => 'Không xác định được khách hàng!']);
    exit;
}

// Nhận dữ liệu POST
$cartJson = $_POST['cart'] ?? '[]';
$deliveryJson = $_POST['delivery'] ?? '{}';

debugLog("Cart JSON: $cartJson");
debugLog("Delivery JSON: $deliveryJson");

$cart = json_decode($cartJson, true);
$delivery = json_decode($deliveryJson, true);

debugLog("Parsed cart: " . print_r($cart, true));
debugLog("Available combo_details: " . print_r(array_keys($_SESSION['combo_details'] ?? []), true));

$errors = [];
if (empty($cart) || !is_array($cart)) $errors[] = 'Giỏ hàng trống hoặc không hợp lệ';
if (!isset($delivery['address']) || !$delivery['address']) $errors[] = 'Thiếu địa chỉ';
if (!isset($delivery['phone']) || !$delivery['phone']) $errors[] = 'Thiếu số điện thoại';
if (!isset($delivery['date']) || !$delivery['date']) $errors[] = 'Thiếu ngày giao';
if (!isset($delivery['time']) || !$delivery['time']) $errors[] = 'Thiếu thời gian giao';
if (!isset($delivery['payment']) || !$delivery['payment']) $errors[] = 'Thiếu phương thức thanh toán';
if (!preg_match('/^\d{10}$/', $delivery['phone'] ?? '')) $errors[] = 'Số điện thoại phải là 10 chữ số';
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $delivery['date'] ?? '')) $errors[] = 'Ngày giao không hợp lệ';
if (!preg_match('/^\d{2}:\d{2}$/', $delivery['time'] ?? '')) $errors[] = 'Thời gian giao không hợp lệ';
if (!in_array($delivery['payment'] ?? '', ['momo', 'bank'])) $errors[] = 'Phương thức thanh toán không hợp lệ';

if (!empty($errors)) {
    echo json_encode([
        'success' => false,
        'msg' => 'Dữ liệu không hợp lệ',
        'errors' => $errors,
        'debug' => [
            'user_id' => $makh,
            'cart' => $cart,
            'delivery' => $delivery
        ]
    ]);
    exit;
}

// Bắt đầu transaction
$conn->begin_transaction();

try {
    // Kiểm tra khách hàng tồn tại
    $stmt0 = $conn->prepare("SELECT SDT, Diachi FROM khachhang WHERE Makh = ?");
    if ($stmt0 === false) die("SQL Error (SELECT khachhang): " . $conn->error);
    $stmt0->bind_param("s", $makh);
    $stmt0->execute();
    $result0 = $stmt0->get_result();
    $old = $result0->fetch_assoc();
    if (!$old) {
        $conn->rollback();
        echo json_encode([
            'success' => false,
            'msg' => 'Người dùng không tồn tại'
        ]);
        exit;
    }

    // Cập nhật SDT/Diachi nếu thay đổi
    if ($old['SDT'] !== $delivery['phone'] || $old['Diachi'] !== $delivery['address']) {
        $stmt1 = $conn->prepare("UPDATE khachhang SET SDT = ?, Diachi = ? WHERE Makh = ?");
        if ($stmt1 === false) die("SQL Error (UPDATE khachhang): " . $conn->error);
        $phone = $delivery['phone'];
        $address = $delivery['address'];
        $stmt1->bind_param("sss", $phone, $address, $makh);
        $stmt1->execute();
    }

    // Lưu hóa đơn
    $stmt2 = $conn->prepare("INSERT INTO hoadon (user_id, ship_date, ship_time, payment_method) VALUES (?, ?, ?, ?)");
    if ($stmt2 === false) die("SQL Error (INSERT hoadon): " . $conn->error);
    $ship_date = $delivery['date'];
    $ship_time = $delivery['time'];
    $payment = $delivery['payment'];
    $stmt2->bind_param("ssss", $makh, $ship_date, $ship_time, $payment);
    $stmt2->execute();
    $hoadonId = $conn->insert_id;

    // Chuẩn bị câu lệnh kiểm tra sản phẩm và lưu chi tiết hóa đơn
    $stmt_check = $conn->prepare("SELECT Masp, Giaban FROM sanpham WHERE Masp = ?");
    if ($stmt_check === false) die("SQL Error (SELECT sanpham): " . $conn->error);

    $stmt3 = $conn->prepare("INSERT INTO chitiethoadon (hoadon_id, product_id, quantity, price, source_type, combo_info) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt3 === false) die("SQL Error (INSERT chitiethoadon): " . $conn->error);

    foreach ($cart as $item) {
        if (!isset($item['id'], $item['quantity']) || !is_numeric($item['quantity']) || $item['quantity'] < 1) {
            $errors[] = 'Dữ liệu sản phẩm không hợp lệ: ' . json_encode($item);
            continue;
        }

        $id = $item['id'];
        $qty = (int)$item['quantity'];
        
        // Debug log
        file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Processing item: ID=$id, qty=$qty\n", FILE_APPEND);

        if (strpos($id, 'combo_') === 0) {
            // Debug combo details
            file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Checking combo: $id\n", FILE_APPEND);
            file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Available combos in session: " . print_r(array_keys($_SESSION['combo_details'] ?? []), true) . "\n", FILE_APPEND);
            
            // Xử lý combo
            if (!isset($_SESSION['combo_details'][$id])) {
                $errors[] = 'Combo không tồn tại: ' . $id;
                file_put_contents('debug.log', date('Y-m-d H:i:s') . " - ERROR: Combo không tồn tại trong session: $id\n", FILE_APPEND);
                file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Session combo_details: " . print_r($_SESSION['combo_details'] ?? [], true) . "\n", FILE_APPEND);
                continue;
            }
            
            $combo = $_SESSION['combo_details'][$id];
            file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Found combo: " . print_r($combo, true) . "\n", FILE_APPEND);
            
            if (!isset($combo['meals']) || !is_array($combo['meals']) || empty($combo['meals'])) {
                $errors[] = 'Combo không chứa món ăn hợp lệ: ' . $id;
                file_put_contents('debug.log', date('Y-m-d H:i:s') . " - ERROR: Combo $id không có meals hợp lệ\n", FILE_APPEND);
                continue;
            }
            foreach ($combo['meals'] as $meal_index => $meal) {
                if (!isset($meal['id']) || empty($meal['id'])) {
                    $errors[] = 'Món thứ ' . ($meal_index + 1) . ' trong combo ' . $id . ' thiếu ID';
                    file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Lỗi: Món thứ " . ($meal_index + 1) . " trong combo $id thiếu ID: " . print_r($meal, true) . "\n", FILE_APPEND);
                    continue;
                }
                $meal_id = $conn->real_escape_string($meal['id']);
                $stmt_check->bind_param("s", $meal_id);
                $stmt_check->execute();
                $result_check = $stmt_check->get_result();
                $product = $result_check->fetch_assoc();
                if (!$product) {
                    $errors[] = 'Món trong combo không tồn tại: ID ' . $meal_id;
                    file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Lỗi: Món ID $meal_id trong combo $id không tồn tại trong sanpham\n", FILE_APPEND);
                    continue;
                }
                $meal_qty = $qty; // Số lượng combo áp dụng cho mỗi món
                $giaban = floatval($product['Giaban']);
                
                // Xác định source_type và combo_info
                $source_type = 'combo_full'; // Mặc định là combo đầy đủ
                $combo_info = $combo['combo_type']; // Ví dụ: day3, day5, single_day
                
                // Nếu là combo mua từng ngày (action = buy_single_day)
                if (isset($combo['action']) && $combo['action'] === 'buy_single_day') {
                    $source_type = 'combo_single_day';
                    $day_name = $combo['day_name'] ?? ('ngay' . (($combo['day_index'] ?? 0) + 1));
                    $combo_info = $combo['combo_type'] . '_' . $day_name; // Ví dụ: day3_ngay1, day5_ngay2
                }
                
                $stmt3->bind_param("isidss", $hoadonId, $meal_id, $meal_qty, $giaban, $source_type, $combo_info);
                $stmt3->execute();
                
                file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Saved combo meal: meal_id=$meal_id, source_type=$source_type, combo_info=$combo_info\n", FILE_APPEND);
            }
        } else {
            // Xử lý món riêng lẻ
            $stmt_check->bind_param("s", $id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            $product = $result_check->fetch_assoc();
            if (!$product) {
                $errors[] = 'Sản phẩm không tồn tại: ID ' . $id;
                continue;
            }
            $giaban = $product['Giaban'];
            
            // Món lẻ từ menu
            $source_type = 'menu';
            $combo_info = null;
            
            $stmt3->bind_param("isidss", $hoadonId, $id, $qty, $giaban, $source_type, $combo_info);
            $stmt3->execute();
            
            file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Saved menu item: product_id=$id, source_type=$source_type\n", FILE_APPEND);
        }
    }

    if (!empty($errors)) {
        $conn->rollback();
        echo json_encode([
            'success' => false,
            'msg' => 'Dữ liệu không hợp lệ',
            'errors' => $errors
        ]);
        exit;
    }

    // Xóa combo_details sau khi đặt hàng thành công và cập nhật combo_progress
    $combo_ids_to_update = [];
    foreach ($cart as $item) {
        if (strpos($item['id'], 'combo_') === 0) {
            $combo_ids_to_update[] = $item['id'];
        }
    }
    
    // Cập nhật trạng thái combo đã thanh toán TRƯỚC KHI xóa session
    foreach ($combo_ids_to_update as $combo_id) {
        // Lấy thông tin combo từ session trước khi bị xóa
        if (isset($_SESSION['combo_details'][$combo_id])) {
            $combo = $_SESSION['combo_details'][$combo_id];
            $combo_type = $combo['combo_type'];
            
            // Xác định số ngày dựa trên combo_type
            $total_days = 1; // Mặc định
            if ($combo_type === 'single_day') {
                $total_days = 1;
            } elseif (strpos($combo_type, 'day') !== false) {
                $total_days = intval(str_replace('day', '', $combo_type));
            } elseif (in_array($combo_type, ['1', '3', '5'])) {
                $total_days = intval($combo_type);
            }
            
            // Thêm vào combo_progress với trạng thái đã thanh toán
            $stmt_progress = $conn->prepare("
                INSERT INTO combo_progress (makh, combo_type, combo_id, total_days, completed_days, is_paid) 
                VALUES (?, ?, ?, ?, ?, 1)
                ON DUPLICATE KEY UPDATE 
                is_paid = 1, completed_days = VALUES(completed_days), updated_at = CURRENT_TIMESTAMP
            ");
            if ($stmt_progress) {
                // Combo 1 ngày tự động hoàn thành, combo nhiều ngày bắt đầu từ 0
                $completed_days = ($total_days === 1) ? 1 : 0;
                $stmt_progress->bind_param("sssii", $makh, $combo_type, $combo_id, $total_days, $completed_days);
                $stmt_progress->execute();
                
                file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Inserted combo_progress: makh=$makh, type=$combo_type, id=$combo_id, total_days=$total_days, completed_days=$completed_days\n", FILE_APPEND);
            }
        } else {
            // Fallback: Lấy thông tin combo từ combo_cart (cho trường hợp combo từ cart UI)
            $stmt_combo = $conn->prepare("SELECT combo_type FROM combo_cart WHERE makh = ? AND combo_id = ?");
            if ($stmt_combo) {
                $stmt_combo->bind_param("ss", $makh, $combo_id);
                $stmt_combo->execute();
                $combo_result = $stmt_combo->get_result();
                $combo_info = $combo_result->fetch_assoc();
                
                if ($combo_info) {
                    $combo_type = $combo_info['combo_type'];
                    $total_days = ($combo_type === 'single_day') ? 1 : intval(str_replace('day', '', $combo_type));
                    
                    // Thêm vào combo_progress với trạng thái đã thanh toán
                    $stmt_progress = $conn->prepare("
                        INSERT INTO combo_progress (makh, combo_type, combo_id, total_days, completed_days, is_paid) 
                        VALUES (?, ?, ?, ?, ?, 1)
                        ON DUPLICATE KEY UPDATE 
                        is_paid = 1, completed_days = total_days, updated_at = CURRENT_TIMESTAMP
                    ");
                    if ($stmt_progress) {
                        $stmt_progress->bind_param("sssii", $makh, $combo_type, $combo_id, $total_days, $total_days);
                        $stmt_progress->execute();
                    }
                    
                    // Xóa khỏi combo_cart
                    $stmt_delete_cart = $conn->prepare("DELETE FROM combo_cart WHERE makh = ? AND combo_id = ?");
                    if ($stmt_delete_cart) {
                        $stmt_delete_cart->bind_param("ss", $makh, $combo_id);
                        $stmt_delete_cart->execute();
                    }
                }
            }
        }
    }
    
    // SAU KHI cập nhật combo_progress, mới xóa session
    foreach ($combo_ids_to_update as $combo_id) {
        unset($_SESSION['combo_details'][$combo_id]);
    }

    // Tính và cập nhật total_amount cho hóa đơn
    $total_query = "
        SELECT SUM(quantity * price) as subtotal 
        FROM chitiethoadon 
        WHERE hoadon_id = ?
    ";
    $stmt_total = $conn->prepare($total_query);
    $stmt_total->bind_param("i", $hoadonId);
    $stmt_total->execute();
    $total_result = $stmt_total->get_result();
    $total_data = $total_result->fetch_assoc();
    $subtotal = $total_data['subtotal'] ?? 0;

    // Tính tổng tiền cuối cùng (bao gồm phí ship: 30k nếu dưới 150k, miễn phí nếu từ 150k trở lên)
    $ship_fee = ($subtotal < 150000) ? 30000 : 0;
    $final_total = $subtotal + $ship_fee;

    // Cập nhật total_amount vào hóa đơn
    $update_total_query = "UPDATE hoadon SET total_amount = ? WHERE id = ?";
    $stmt_update = $conn->prepare($update_total_query);
    $stmt_update->bind_param("di", $final_total, $hoadonId);
    $stmt_update->execute();

    file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Updated hoadon #$hoadonId: subtotal=$subtotal, ship_fee=$ship_fee, final_total=$final_total\n", FILE_APPEND);

    $conn->commit();
    echo json_encode(['success' => true, 'hoadon_id' => $hoadonId]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'msg' => 'Lỗi khi lưu đơn hàng',
        'error' => $e->getMessage()
    ]);
}
