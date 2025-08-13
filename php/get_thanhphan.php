<?php
header('Content-Type: application/json');
require 'connect.php';

$masp = $_GET['masp'] ?? '';

$thanhphans = [];
if ($masp) {
    // Lấy các thành phần đã chọn từ sanphamthanhphan
    $result = $conn->query("SELECT t.Tenthanhphan 
                            FROM sanphamthanhphan sp 
                            JOIN thanhphan t ON sp.Thanhphanid = t.Idthanhphan 
                            WHERE sp.Masp = " . intval($masp));
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $thanhphans[] = $row['Tenthanhphan'];
        }
    } else {
        echo json_encode(['error' => 'Lỗi truy vấn: ' . $conn->error]);
        exit;
    }
} else {
    echo json_encode(['error' => 'Masp không được cung cấp']);
    exit;
}

echo json_encode($thanhphans ? implode(", ", $thanhphans) : '', JSON_UNESCAPED_UNICODE);
$conn->close();
?>