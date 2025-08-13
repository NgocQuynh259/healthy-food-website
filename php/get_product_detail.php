<?php
require 'connect.php';
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("SELECT Masp, Tensp, Mota, Hinhanh, Calories, Protein, Fat, Carbs, Sugar, Fiber, Giaban FROM sanpham WHERE Masp = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    echo json_encode($res->fetch_assoc(), JSON_UNESCAPED_UNICODE);
    $stmt->close();
} else {
    echo json_encode(["error" => "Thiếu ID sản phẩm"], JSON_UNESCAPED_UNICODE);
}
$conn->close();
?>
