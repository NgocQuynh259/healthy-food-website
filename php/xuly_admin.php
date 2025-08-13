<?php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Lấy dữ liệu từ form
    $ten_sp          = $_POST['ten_sp'];
    $mota            = $_POST['mota'];
    $gianguyenlieu   = $_POST['gianguyenlieu'];
    $giaban          = $_POST['giaban'];
    $calo            = $_POST['calo'];
    $protein         = $_POST['protein'];
    $fat             = $_POST['fat'];
    $carbs           = $_POST['carbs'];
    $sugar           = $_POST['sugar'];
    $fiber           = $_POST['fiber'];
    $maloai          = $_POST['maloai'];
    $trangthai       = $_POST['trangthai'];

    // 2. Thiết lập đường dẫn upload và URL prefix
    //    File này nằm trong /var/www/html/Coso/php/xuly_admin.php
    $baseDir   = realpath(__DIR__ . '/..');               // /var/www/html/Coso
    $uploadDir = $baseDir . '/assets/img/mon_an/';        // filesystem path
    $urlPrefix = '/Coso/assets/img/mon_an/';              // URL dùng hiển thị và lưu DB

    // Debug: xem đường dẫn chính xác
    error_log("UPLOAD DIR: $uploadDir");

    // 3. Xử lý upload ảnh
    $hinhanh_url = '';
    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['hinhanh']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Lỗi upload: code ' . $_FILES['hinhanh']['error']]);
            exit;
        }
        // Kiểm tra loại
        $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
        if (!in_array($_FILES['hinhanh']['type'], $allowed)) {
            echo json_encode(['success' => false, 'message' => 'Chỉ hỗ trợ JPEG, PNG, GIF, WEBP']);
            exit;
        }
        // Tạo thư mục nếu cần
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (!is_writable($uploadDir)) {
            echo json_encode(['success' => false, 'message' => 'Thư mục không có quyền ghi']);
            exit;
        }
        // Di chuyển file
        $filename    = str_replace(' ', '_', basename($_FILES['hinhanh']['name']));
        $tmpPath     = $_FILES['hinhanh']['tmp_name'];
        $destPath    = $uploadDir . $filename;
        $hinhanh_url = $urlPrefix . $filename;
        if (!move_uploaded_file($tmpPath, $destPath)) {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi di chuyển file đến: ' . $destPath]);
            exit;
        }
        error_log("UPLOAD OK -> $destPath");
    }

    // 4. Lưu sản phẩm, sử dụng URL relative, không filesystem path
    $sql = "INSERT INTO sanpham (
        Tensp, Mota, Hinhanh, Gianguyenlieu, Giaban, Calories,
        Protein, Fat, Carbs, Sugar, Fiber, Maloai, Trangthai
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Lỗi prepare SQL: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param(
        'sssiiiiiiiiii',
        $ten_sp,
        $mota,
        $hinhanh_url,
        $gianguyenlieu,
        $giaban,
        $calo,
        $protein,
        $fat,
        $carbs,
        $sugar,
        $fiber,
        $maloai,
        $trangthai
    );
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Lỗi SQL: ' . $stmt->error]);
        exit;
    }
    $masp = $stmt->insert_id;

    // 5. Xử lý thành phần: tách bằng dấu phẩy, không JSON
    if (!empty($_POST['thanhphan'])) {
        $parts = array_filter(array_map('trim', explode(',', $_POST['thanhphan'])));
        foreach ($parts as $tp_name) {
            if ($tp_name === '') continue;
            // Tìm hoặc tạo
            $check = $conn->prepare("SELECT Idthanhphan FROM thanhphan WHERE Tenthanhphan=?");
            $check->bind_param('s', $tp_name);
            $check->execute();
            $res = $check->get_result();
            if ($res->num_rows) {
                $tp_id = $res->fetch_assoc()['Idthanhphan'];
            } else {
                $ins = $conn->prepare("INSERT INTO thanhphan (Tenthanhphan) VALUES (?)");
                $ins->bind_param('s', $tp_name);
                $ins->execute();
                $tp_id = $ins->insert_id;
                $ins->close();
            }
            $check->close();
            // Link
            $link = $conn->prepare("INSERT INTO sanphamthanhphan (Masp, Thanhphanid) VALUES (?, ?)");
            $link->bind_param('ii', $masp, $tp_id);
            $link->execute();
            $link->close();
        }
    }

    echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm thành công!']);
    exit;
}
?>
