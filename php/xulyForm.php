<?php
session_start();
header('Content-Type: application/json');
require 'connect.php';

// Lấy mã khách hàng từ session
$makh = isset($_SESSION['makh']) ? $_SESSION['makh'] : null;
if (!$makh) {
    echo json_encode(['success' => false, 'message' => 'Không xác định được khách hàng!']);
    exit;
}

// Nhận dữ liệu khảo sát
$data = json_decode(file_get_contents('php://input'), true);

// Lấy dữ liệu từng trường, có kiểm tra isset
$weight    = isset($data['weight']) ? floatval($data['weight']) : 0;
$height    = isset($data['height']) ? floatval($data['height']) : 0;
$age       = isset($data['age']) ? intval($data['age']) : 0;
$gender    = isset($data['gender']) ? $conn->real_escape_string($data['gender']) : '';
$activity  = isset($data['activityLevel']) ? $conn->real_escape_string($data['activityLevel']) : '';
$goal      = isset($data['goal']) ? $conn->real_escape_string($data['goal']) : '';
$diets     = isset($data['diets']) ? $data['diets'] : [];
$allergies = isset($data['allergies']) ? $data['allergies'] : [];

// 1. Lưu vào bảng khachhang_suckhoe
$sql = "INSERT INTO khachhang_suckhoe (Makh, weight, height, age, gender, activity_level, goal)
        VALUES ('$makh', $weight, $height, $age, '$gender', '$activity', '$goal')";
if ($conn->query($sql) === TRUE) {
    $Mathongtin = $conn->insert_id;

    // 2. Lưu từng chế độ ăn vào suckhoe_loai
    foreach ($diets as $diet) {
        $diet = $conn->real_escape_string($diet);
        $sqlLoai = "SELECT Maloai FROM loai WHERE Tenloai = '$diet' LIMIT 1";
        $resultLoai = $conn->query($sqlLoai);

        if ($resultLoai && $rowLoai = $resultLoai->fetch_assoc()) {
            $maloai = $rowLoai['Maloai'];
            $conn->query("INSERT INTO suckhoe_loai (Mathongtin, Maloai) VALUES ($Mathongtin, $maloai)");
        } else {
            // In ra lỗi để debug
            error_log("Không tìm thấy Maloai cho chế độ ăn: [$diet], SQL: $sqlLoai - Lỗi: " . $conn->error);
        }
    }

    // 3. Lưu dị ứng vào khachhangdiung (nếu cần, chỉ thêm nếu chưa có)
    foreach ($allergies as $al) {
        $al = $conn->real_escape_string($al);
        $sqlTP = "SELECT Idthanhphan FROM thanhphan WHERE MaThanhPhan = '$al' LIMIT 1";
        $resultTP = $conn->query($sqlTP);

        if ($resultTP && $rowTP = $resultTP->fetch_assoc()) {
            $idtp = $rowTP['Idthanhphan'];
            $conn->query("INSERT IGNORE INTO khachhangdiung (Makh, Thanhphandiungid) VALUES ('$makh', $idtp)");
        } else {
            error_log("Không tìm thấy Idthanhphan cho dị ứng: [$al], SQL: $sqlTP - Lỗi: " . $conn->error);
            error_log("Chế độ ăn gửi vào: [$diet]");
            error_log("SQL: $sqlLoai");
        }
    }


    echo json_encode(['success' => true, 'message' => 'Lưu thông tin thành công!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi lưu thông tin: ' . $conn->error]);
}

$conn->close();
