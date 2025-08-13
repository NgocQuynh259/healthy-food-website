<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
require 'connect.php';

// Xác định loại yêu cầu
$type = isset($_GET['type']) ? $_GET['type'] : 'products';
$category = isset($_GET['category']) ? $_GET['category'] : 'balance';

$products = [];

if ($type === 'menu') {
    // Debug: Hiển thị category được nhận
    //echo "Category received: " . $category . "\n";

    // Tìm Maloai dựa trên Tenloai
    $sql_loai = "SELECT Maloai FROM loai WHERE Tenloai = ?";
    $stmt_loai = $conn->prepare($sql_loai);
    if ($stmt_loai === false) {
        die("Error preparing loai query: " . $conn->error . "\n" . json_encode([]));
    }
    $stmt_loai->bind_param("s", $category);
    $stmt_loai->execute();
    $result_loai = $stmt_loai->get_result();

    $Maloai = null;
    if ($row = $result_loai->fetch_assoc()) {
        $Maloai = $row['Maloai'];
        //echo "Found Maloai: " . $Maloai . "\n";
    } else {
        //echo "No Maloai found for category: " . $category . "\n";
    }

    if ($Maloai) {
        $sql_sanpham = "SELECT MASP AS Masp, Tensp AS Tensp, Hinhanh, Calories, Sugar, Protein, Fiber FROM sanpham WHERE Maloai = ? AND Trangthai = 1";
        $stmt_sanpham = $conn->prepare($sql_sanpham);
        if ($stmt_sanpham === false) {
            die("Error preparing sanpham query: " . $conn->error . "\n" . json_encode([]));
        }
        $stmt_sanpham->bind_param("i", $Maloai);
        $stmt_sanpham->execute();
        $result_sanpham = $stmt_sanpham->get_result();

        $count = 0;
        while ($row = $result_sanpham->fetch_assoc()) {
            $products[] = $row;
            $count++;
        }
        //echo "Found $count products with Maloai = $Maloai\n";
    } else {
        //echo "No products fetched due to null Maloai\n";
    }
} else {
    // Truy vấn tất cả sản phẩm cho bảng
    $sql = "SELECT * FROM sanpham";
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
}

echo json_encode($products, JSON_UNESCAPED_UNICODE);

// Đóng kết nối
if (isset($stmt_loai) && $stmt_loai) $stmt_loai->close();
if (isset($stmt_sanpham) && $stmt_sanpham) $stmt_sanpham->close(); // Kiểm tra đối tượng còn hợp lệ
$conn->close();
?>