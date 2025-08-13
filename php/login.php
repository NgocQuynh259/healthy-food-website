<?php
session_start(); // Bắt buộc
require 'connect.php';
header('Content-Type: application/json');

// Kiểm tra đăng nhập (GET)
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_SESSION['username']) && isset($_SESSION['vaitro'])) {
        echo json_encode([
            "status" => "success",
            "username" => $_SESSION['username'],
            "vaitro" => $_SESSION['vaitro']
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Chưa đăng nhập"]);
    }
    exit;
}

// Đăng nhập (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? '';
    $matkhau = $_POST["matkhau"] ?? '';

    $stmt = $conn->prepare("SELECT * FROM khachhang WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if (password_verify($matkhau, $row["Matkhau"])) {
        $_SESSION['username'] = $row["Tenkh"];
        $_SESSION['vaitro']   = $row["Vaitro"];
        $_SESSION['makh']     = $row["Makh"]; 

        echo json_encode([
            "status" => "success",
            "message" => "Đăng nhập thành công!",
            "username" => $row["Tenkh"],
            "vaitro" => $row["Vaitro"]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Sai mật khẩu!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Email không tồn tại!"]);
}

}
?>