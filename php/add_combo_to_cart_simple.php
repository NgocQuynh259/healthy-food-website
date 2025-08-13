<?php
session_start();
header('Content-Type: application/json');

// Hàm ghi log debug
function logDebug($message) {
    file_put_contents(__DIR__ . '/debug.log', date('Y-m-d H:i:s') . " - $message\n", FILE_APPEND);
}

logDebug("=== ADD COMBO REQUEST ===");
logDebug("POST: " . print_r($_POST, true));
logDebug("SESSION makh: " . ($_SESSION['makh'] ?? 'NULL'));

// Kiểm tra đăng nhập
if (!isset($_SESSION['makh'])) {
    logDebug("ERROR: Chưa đăng nhập");
    echo json_encode(['error' => 'Vui lòng đăng nhập để mua combo']);
    exit;
}

// Nhận dữ liệu
$combo_type = $_POST['combo_type'] ?? '';
$meals_json = $_POST['meals'] ?? '[]';
$action = $_POST['action'] ?? 'buy_full_combo';
$day_name = $_POST['day_name'] ?? '';
$day_index = intval($_POST['day_index'] ?? 0);
$makh = $_SESSION['makh'];

logDebug("Parsed data: type=$combo_type, action=$action, day_name=$day_name, day_index=$day_index");

// Validate input
if (empty($combo_type)) {
    logDebug("ERROR: Empty combo_type");
    echo json_encode(['error' => 'Combo type không hợp lệ']);
    exit;
}

$meals = json_decode($meals_json, true);
if (empty($meals) || !is_array($meals)) {
    logDebug("ERROR: Invalid meals data: " . $meals_json);
    echo json_encode(['error' => 'Không có món ăn nào trong combo']);
    exit;
}

// Tạo combo ID duy nhất
$combo_id = 'combo_' . $combo_type . '_' . time() . '_' . rand(1000, 9999);
logDebug("Generated combo_id: $combo_id");

// Tính tổng giá và chuẩn bị meal details
$total_price = 0;
$meal_details = [];

foreach ($meals as $index => $meal) {
    if (!isset($meal['id'], $meal['name'], $meal['price'])) {
        logDebug("ERROR: Missing meal data at index $index: " . print_r($meal, true));
        echo json_encode(['error' => 'Thông tin món ăn không đầy đủ']);
        exit;
    }
    
    $price = floatval($meal['price']);
    if ($price <= 0) {
        logDebug("ERROR: Invalid price at index $index: " . $meal['price']);
        echo json_encode(['error' => 'Giá món ăn không hợp lệ']);
        exit;
    }
    
    $total_price += $price;
    $meal_details[] = [
        'id' => $meal['id'],
        'name' => $meal['name'],
        'price' => $price,
        'image' => $meal['image'] ?? '/CoSo/assets/img/avt/default.jpg',
        'type' => $meal['type'] ?? 'unknown'
    ];
}

logDebug("Total price calculated: $total_price");
logDebug("Meal details: " . print_r($meal_details, true));

// Tạo combo name và image
if ($action === 'buy_single_day') {
    $combo_name = "Combo " . ($day_name ?: "Ngày " . ($day_index + 1));
    $combo_image = '/CoSo/assets/img/avt/combo.jpg';
} else {
    $days_num = str_replace('day', '', $combo_type);
    $combo_name = "Combo " . $days_num . " Ngày";
    $combo_image = '/CoSo/assets/img/avt/combo2.jpg';
}

logDebug("Combo name: $combo_name");

// Khởi tạo session arrays
if (!isset($_SESSION['combo_details'])) {
    $_SESSION['combo_details'] = [];
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Lưu combo details vào session
$_SESSION['combo_details'][$combo_id] = [
    'name' => $combo_name,
    'price' => $total_price,
    'image' => $combo_image,
    'meals' => $meal_details,
    'combo_type' => $combo_type,
    'action' => $action,
    'day_name' => $day_name,
    'day_index' => $day_index,
    'created_at' => date('Y-m-d H:i:s')
];

// Thêm vào giỏ hàng session
$_SESSION['cart'][$combo_id] = 1;

// Lưu vào database
require_once __DIR__ . '/connect.php';

try {
    // Kiểm tra và tạo bảng combo_cart nếu chưa có
    $checkTable = $conn->query("SHOW TABLES LIKE 'combo_cart'");
    if (!$checkTable || $checkTable->num_rows == 0) {
        $createTable = "
            CREATE TABLE combo_cart (
                id INT AUTO_INCREMENT PRIMARY KEY,
                makh VARCHAR(50) NOT NULL,
                combo_id VARCHAR(100) NOT NULL,
                combo_type VARCHAR(20) NOT NULL,
                combo_name VARCHAR(255) NOT NULL,
                total_price DECIMAL(10,2) NOT NULL,
                combo_image VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_combo (makh, combo_id)
            )
        ";
        $conn->query($createTable);
    }
    
    // Lưu combo vào cart database
    $stmt = $conn->prepare("INSERT INTO combo_cart (makh, combo_id, combo_type, combo_name, total_price, combo_image) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE total_price = VALUES(total_price), combo_name = VALUES(combo_name)");
    if ($stmt) {
        $stmt->bind_param("ssssds", $makh, $combo_id, $combo_type, $combo_name, $total_price, $combo_image);
        $stmt->execute();
        logDebug("Combo saved to database cart");
    }
} catch (Exception $e) {
    logDebug("Database error: " . $e->getMessage());
}

logDebug("SUCCESS: Combo saved to session");
logDebug("Combo details count: " . count($_SESSION['combo_details']));
logDebug("Cart items count: " . count($_SESSION['cart']));

// Tính tổng số items trong giỏ hàng
$total_items = array_sum($_SESSION['cart']);

// Trả về thành công
echo json_encode([
    'success' => true,
    'count' => $total_items,
    'combo_id' => $combo_id,
    'combo_name' => $combo_name,
    'total_price' => $total_price,
    'message' => 'Đã thêm ' . $combo_name . ' vào giỏ hàng!'
]);

logDebug("Response sent successfully");
?>
