<?php
// File đơn giản trả về dữ liệu mặc định
header('Content-Type: application/json');

// Trả về dữ liệu mặc định cho timeline progress
$response = [
    'success' => true,
    'data' => [
        'combo_id' => 1,
        'current_day' => 1,
        'total_days' => 7,
        'start_date' => date('Y-m-d'),
        'completed_days' => []
    ]
];

echo json_encode($response);
?>
