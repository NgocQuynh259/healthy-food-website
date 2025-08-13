<?php
session_start();
session_unset();
session_destroy();

// Trả về JSON cho AJAX
header('Content-Type: application/json');
echo json_encode(['status' => 'success', 'message' => 'Đăng xuất thành công']);
exit;
?>