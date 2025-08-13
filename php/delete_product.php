<?php
require 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$masp = $data['masp'] ?? null;

if ($masp) {
    // Xoá trong bảng trung gian trước nếu có
    $conn->query("DELETE FROM sanphamthanhphan WHERE Masp = $masp");

    // Sau đó xoá sản phẩm chính
    $stmt = $conn->prepare("DELETE FROM sanpham WHERE Masp = ?");
    $stmt->bind_param("i", $masp);
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Thiếu mã sản phẩm"]);
}
?>
