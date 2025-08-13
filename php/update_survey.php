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

// Nhận dữ liệu khảo sát từ JSON
$data = json_decode(file_get_contents('php://input'), true);

// Kiểm tra và lấy dữ liệu, đặt giá trị mặc định nếu không có
$weight = isset($data['weight']) ? floatval($data['weight']) : 0;
$height = isset($data['height']) ? floatval($data['height']) : 0;
$age = isset($data['age']) ? intval($data['age']) : 0;
$gender = isset($data['gender']) ? $conn->real_escape_string($data['gender']) : '';
$activity = isset($data['activityLevel']) ? floatval($data['activityLevel']) : 0;
$goal = isset($data['goal']) ? $conn->real_escape_string($data['goal']) : '';
$diets = isset($data['diets']) ? $data['diets'] : [];
$allergies = isset($data['allergies']) ? $data['allergies'] : [];

// 1. Xóa dữ liệu cũ để chỉ giữ thông tin mới nhất
$conn->query("DELETE FROM khachhang_suckhoe WHERE Makh = '$makh'");
$conn->query("DELETE FROM suckhoe_loai WHERE Mathongtin IN (SELECT id FROM khachhang_suckhoe WHERE Makh = '$makh')");
$conn->query("DELETE FROM khachhangdiung WHERE Makh = '$makh'");

// 2. Lưu thông tin sức khỏe mới
$stmt = $conn->prepare("INSERT INTO khachhang_suckhoe (Makh, weight, height, age, gender, activity_level, goal) VALUES (?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Lỗi prepare khachhang_suckhoe: ' . $conn->error]);
    exit;
}
$stmt->bind_param("siddds", $makh, $weight, $height, $age, $gender, $activity, $goal);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Lỗi execute khachhang_suckhoe: ' . $conn->error]);
    exit;
}
$Mathongtin = $conn->insert_id;

// 3. Lưu chế độ ăn mới
foreach ($diets as $diet) {
    $diet = $conn->real_escape_string($diet);
    $sqlLoai = "SELECT Maloai FROM loai WHERE Tenloai = ? LIMIT 1";
    $stmtLoai = $conn->prepare($sqlLoai);
    if (!$stmtLoai) {
        error_log("Lỗi prepare loai: " . $conn->error);
        continue;
    }
    $stmtLoai->bind_param("s", $diet);
    $stmtLoai->execute();
    $resultLoai = $stmtLoai->get_result();
    if ($rowLoai = $resultLoai->fetch_assoc()) {
        $maloai = $rowLoai['Maloai'];
        $stmtInsert = $conn->prepare("INSERT INTO suckhoe_loai (Mathongtin, Maloai) VALUES (?, ?)");
        if ($stmtInsert) {
            $stmtInsert->bind_param("ii", $Mathongtin, $maloai);
            $stmtInsert->execute();
        } else {
            error_log("Lỗi prepare suckhoe_loai: " . $conn->error);
        }
    } else {
        error_log("Không tìm thấy Maloai cho chế độ ăn: $diet, SQL: $sqlLoai");
    }
    $stmtLoai->close();
}

// 4. Lưu dị ứng mới
foreach ($allergies as $al) {
    $al = $conn->real_escape_string($al);
    $sqlTP = "SELECT Idthanhphan FROM thanhphan WHERE MaThanhPhan = ? LIMIT 1";
    $stmtTP = $conn->prepare($sqlTP);
    if (!$stmtTP) {
        error_log("Lỗi prepare thanhphan: " . $conn->error);
        continue;
    }
    $stmtTP->bind_param("s", $al);
    $stmtTP->execute();
    $resultTP = $stmtTP->get_result();
    if ($rowTP = $resultTP->fetch_assoc()) {
        $idtp = $rowTP['Idthanhphan'];
        $stmtInsert = $conn->prepare("INSERT INTO khachhangdiung (Makh, Thanhphandiungid) VALUES (?, ?)");
        if ($stmtInsert) {
            $stmtInsert->bind_param("si", $makh, $idtp);
            $stmtInsert->execute();
        } else {
            error_log("Lỗi prepare khachhangdiung: " . $conn->error);
        }
    } else {
        error_log("Không tìm thấy Idthanhphan cho dị ứng: $al, SQL: $sqlTP");
    }
    $stmtTP->close();
}

echo json_encode(['success' => true, 'message' => 'Lưu thông tin thành công!']);
$conn->close();
?>