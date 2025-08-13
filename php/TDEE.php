<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connect.php';

// 1) Lấy params
$minCal  = isset($_GET['min'])    ? (int)$_GET['min']    : 0;
$maxCal  = isset($_GET['max'])    ? (int)$_GET['max']    : PHP_INT_MAX;
$maloai  = isset($_GET['maloai']) ? explode(',', $_GET['maloai']) : [];
$allergy = isset($_GET['allergy']) ? explode(',', $_GET['allergy']) : [];
$excluded = isset($_GET['excluded']) ? explode(',', $_GET['excluded']) : [];

// 2) Xây điều kiện
$conds = [];
if ($maloai) {
    $inLoai = implode(',', array_map('intval', $maloai));
    $conds[] = "m.Maloai IN ($inLoai)";
} else {
    echo json_encode(['success' => false, 'message' => 'Chưa có loại món!']);
    exit;
}
$conds[] = "m.Calories BETWEEN ? AND ?";
if ($allergy) {
    $inA = implode(',', array_map('intval', $allergy));
    $conds[] = "m.Masp NOT IN (
        SELECT Masp FROM sanphamthanhphan WHERE Thanhphanid IN ($inA)
    )";
}
if ($excluded) {
    $inE = implode(',', array_map('intval', $excluded));
    $conds[] = "m.Masp NOT IN ($inE)";
}
$where = implode(' AND ', $conds);

// 3) Chuẩn bị và debug SQL
$sql = "SELECT Masp, Hinhanh, Tensp, Calories, Sugar, Protein, Fiber, Giaban
        FROM sanpham AS m
        WHERE $where
        ORDER BY RAND() LIMIT 1";


$stmt = $conn->prepare($sql);
if (!$stmt) {
    // debug lỗi prepare + SQL
    die(json_encode([
        'success' => false,
        'message' => "SQL lỗi: ({$conn->errno}) {$conn->error}",
        'query' => $sql
    ]));
}

// 4) Bind & execute
$stmt->bind_param("ii", $minCal, $maxCal);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy món phù hợp!']);
    exit;
}

// 5) Trả JSON
echo json_encode([
    'success' => true,
    'meal' => [
        'id' => $row['Masp'],
        'image' => $row['Hinhanh'],
        'name' => $row['Tensp'],
        'price' => $row["Giaban"],
        'kcal' => (int)$row['Calories'],
        'sugar' => (float)$row['Sugar'],
        'protein' => (float)$row['Protein'],
        'fiber' => (float)$row['Fiber'],
    ]
]);
