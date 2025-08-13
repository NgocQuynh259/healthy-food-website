<?php
session_start(); // Bắt buộc (dù không dùng session ở đây)
require 'connect.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["tendangnhap"], $_POST["email"], $_POST["matkhau"])) {
        $tendangnhap = $_POST["tendangnhap"];
        $email = $_POST["email"];
        $matkhau = password_hash($_POST["matkhau"], PASSWORD_DEFAULT);

        $check = $conn->prepare("SELECT 1 FROM khachhang WHERE Email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            echo json_encode(["status" => "error", "message" => "Email đã tồn tại!"]);
        } else {
            $stmt = $conn->prepare("INSERT INTO khachhang (Tenkh, Email, Matkhau) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $tendangnhap, $email, $matkhau);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Đăng ký thành công!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Lỗi đăng ký!"]);
            }
            $stmt->close();
        }
        $check->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Vui lòng nhập đầy đủ thông tin!"]);
    }
    $conn->close();
}
?>