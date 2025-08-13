<?php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Xử lý cập nhật
  $m    = (int)$_POST['Masp'];
  $t    = $conn->real_escape_string($_POST['Tensp']);
  $l    = (int)$_POST['Maloai'];
  $gn   = (int)$_POST['Gianguyenlieu'];
  $gb   = (int)$_POST['Giaban'];
  $tt   = (int)$_POST['Trangthai'];
  $hinhanh_sql = ""; // mặc định không cập nhật ảnh

  if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] === UPLOAD_ERR_OK) {
    $hinhanh_name = str_replace(' ', '_', $_FILES['hinhanh']['name']);
    $hinhanh_tmp  = $_FILES['hinhanh']['tmp_name'];
    $hinhanh_url  = 'assets/img/mon_an/' . basename($hinhanh_name);
    $hinhanh_path = '../' . $hinhanh_url;

    if (!file_exists('../assets/img/mon_an/')) {
      mkdir('../assets/img/mon_an/', 0777, true);
    }

    if (move_uploaded_file($hinhanh_tmp, $hinhanh_path)) {
      $hinhanh_sql = ", Hinhanh = '$hinhanh_url'";
    }
  }

  $sql = "
  UPDATE sanpham SET
    Tensp          = '$t',
    Maloai         = $l,
    Gianguyenlieu  = $gn,
    Giaban         = $gb,
    Trangthai      = $tt
    $hinhanh_sql
  WHERE Masp      = $m
";

  $conn->query($sql);
  header('Location: index.php');
  exit;
}

// Load dữ liệu sản phẩm cần sửa
$masp = isset($_GET['masp']) ? (int)$_GET['masp'] : 0;
$row  = null;
if ($masp > 0) {
  $res = $conn->query("SELECT * FROM sanpham WHERE Masp=$masp");
  $row = $res->fetch_assoc();
}
if (!$row) {
  echo "Không tìm thấy sản phẩm #$masp";
  exit;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title>Sửa sản phẩm</title>
</head>

<body>
  <h1>Sửa sản phẩm <?= htmlspecialchars($row['Masp']) ?></h1>
  <form method="post" action="">
    <input type="hidden" name="Masp" value="<?= htmlspecialchars($row['Masp']) ?>">
    <p>
      <label>Tên SP:<br>
        <input type="text" name="Tensp" value="<?= htmlspecialchars($row['Tensp']) ?>">
      </label>
    </p>
    <p>
      <label>Loại:<br>
        <input type="number" name="Maloai" value="<?= htmlspecialchars($row['Maloai']) ?>">
      </label>
    </p>
    <p>
      <label>Giá NL:<br>
        <input type="number" name="Gianguyenlieu" value="<?= htmlspecialchars($row['Gianguyenlieu']) ?>">
      </label>
    </p>
    <p>
      <label>Giá Bán:<br>
        <input type="number" name="Giaban" value="<?= htmlspecialchars($row['Giaban']) ?>">
      </label>
    </p>
    <p>
      <label>Trạng thái:<br>
        <select name="Trangthai">
          <option value="1" <?= $row['Trangthai'] == 1 ? 'selected' : '' ?>>Có sẵn</option>
          <option value="0" <?= $row['Trangthai'] == 0 ? 'selected' : '' ?>>Hết hàng</option>
        </select>
      </label>
    </p>
    <p>
      <button type="submit">Lưu thay đổi</button>
      <a href="index.php">Hủy</a>
    </p>
  </form>
</body>

</html>