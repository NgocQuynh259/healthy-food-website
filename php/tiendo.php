<?php 
// header.php đã có session_start() rồi, không cần gọi lại
include __DIR__ . '/header.php'; 
// Form tư vấn đã được include trong header.php
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiến Độ Sức Khỏe</title>
    <link rel="stylesheet" href="/CoSo/css/reset.css">
    <link rel="stylesheet" href="/CoSo/css/general.css">
    <link rel="stylesheet" href="/CoSo/css/index.css">
    <link rel="stylesheet" href="/CoSo/css/tiendo.css">
    <link rel="stylesheet" href="/CoSo/css/multiForm.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
       



        .dashboard-grid {
            display: grid;
            grid-template-columns: 3fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .chart-section {
            background: var(--bg-white);
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--shadow);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .chart-title {
            font-size: 1.5em;
            font-weight: 700;
            color: var(--text-dark);
        }

        .chart-subtitle {
            color: var(--text-muted);
            font-size: 0.9em;
            margin-top: 5px;
        }

        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }

        .stats-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .progress-card {
            background: var(--bg-white);
            border-radius: 16px;
            padding: 25px;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .progress-title {
            font-size: 1.2em;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--text-dark);
        }

        .progress-circle {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
        }

        .progress-circle svg {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }

        .progress-circle-bg {
            fill: none;
            stroke: #e5e7eb;
            stroke-width: 8;
        }

        .progress-circle-fill {
            fill: none;
            stroke: var(--primary-color);
            stroke-width: 8;
            stroke-linecap: round;
            transition: stroke-dasharray 0.5s ease;
        }

        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.5em;
            font-weight: 700;
            color: var(--primary-color);
        }

        .progress-description {
            color: var(--text-muted);
            font-size: 0.9em;
            line-height: 1.4;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .metric-card {
            background: var(--bg-white);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .metric-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 1.2em;
            color: white;
        }

        .metric-icon.weight {
            background: var(--success-color);
        }

        .metric-icon.height {
            background: var(--primary-color);
        }

        .metric-icon.bmi {
            background: var(--warning-color);
        }

        .metric-icon.goal {
            background: var(--danger-color);
        }

        .metric-value {
            font-size: 1.8em;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .metric-label {
            color: var(--text-muted);
            font-size: 0.9em;
        }

        .chart-legend {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9em;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 2px;
        }

        .weight-color {
            background-color: var(--primary-color);
        }

        .height-color {
            background-color: var(--success-color);
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .dashboard-header h1 {
                font-size: 2em;
            }
            
            .chart-container {
                height: 300px;
            }
        }

        .combo-progress-section {
            background: var(--bg-white);
            border-radius: 16px;
            padding: 25px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
        }

        .combo-progress-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 20px;
        }

        .combo-card {
            text-align: center;
            padding: 20px;
            border: 2px solid var(--border-color);
            border-radius: 16px;
            transition: all 0.3s ease;
            position: relative;
        }

        /* Combo 3 ngày - Màu xanh lá */
        .combo-card[data-combo="day3"] {
            background: linear-gradient(135deg, #a7f3d0, #86efac);
            border-color: #10b981;
            color: #065f46;
        }

        /* Combo 5 ngày - Màu vàng */
        .combo-card[data-combo="day5"] {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-color: #f59e0b;
            color: #92400e;
        }

        /* Combo 1 ngày - Màu tím */
        .combo-card[data-combo="day1"] {
            background: linear-gradient(135deg, #e9d5ff, #ddd6fe);
            border-color: #8b5cf6;
            color: #5b21b6;
        }
        

        .combo-card.completed {
            border-color: var(--success-color);
            background: rgba(16, 185, 129, 0.1);
        }

        .combo-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: inherit;
        }

        .combo-stats {
            font-size: 0.9em;
            margin-bottom: 15px;
            line-height: 1.4;
            color: inherit;
            opacity: 0.8;
        }

        .combo-stats .highlight {
            font-weight: 600;
            opacity: 1;
        }

        .combo-stats .completed {
            font-weight: 600;
            opacity: 1;
        }

        .combo-number {
            font-size: 2.5em;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 5px;
            color: inherit;
        }

  

        .combo-status {
            font-size: 0.8em;
            color: inherit;
            opacity: 0.7;
        }


        .combo-progress {
            position: relative;
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
        }

        .combo-progress svg {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }

        .combo-progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1em;
            font-weight: 700;
            color: var(--primary-color);
        }

        .combo-info {
            text-align: center;
            margin: 15px 0;
        }

        .combo-number {
            font-size: 2.5em;
            font-weight: 700;
            color: #214e33;
            line-height: 1;
            margin-bottom: 5px;
        }

        .combo-label {
            font-size: 0.9em;
            color: #333;
            font-weight: 500;
        }

        .combo-overview {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }

        .overview-stat {
            text-align: center;
        }

        .overview-number {
            font-size: 2em;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1;
            margin-bottom: 5px;
        }

        .overview-label {
            font-size: 0.9em;
            color: var(--text-muted);
            font-weight: 500;
        }

        .combo-status {
            font-size: 0.8em;
            color: var(--text-muted);
        }

        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 400px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid var(--border-color);
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <?php
    require_once __DIR__ . '/connect.php';
    
    // Kiểm tra session và lấy mã khách hàng
    if (!isset($_SESSION['makh']) || empty($_SESSION['makh'])) {
        // Redirect về trang login nếu chưa đăng nhập
        // header('Location: /CoSo/php/login_form.php');
        // exit();
        $makh = 'KH02062025_016'; // Fallback cho development
    } else {
        $makh = $_SESSION['makh'];
    }

    // Lấy thông tin sức khỏe khách hàng - tất cả dữ liệu để có biểu đồ chi tiết hơn
    $stmt = $conn->prepare("
        SELECT 
            created_at,
            weight, 
            height, 
            goal,
            DATE(created_at) as date
        FROM khachhang_suckhoe 
        WHERE Makh = ? 
        AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ORDER BY created_at ASC
    ");
    $stmt->bind_param("s", $makh);
    $stmt->execute();
    $healthData = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Nếu không có dữ liệu trong 30 ngày, lấy dữ liệu mới nhất
    if (empty($healthData)) {
        $stmt = $conn->prepare("
            SELECT 
                created_at,
                weight, 
                height, 
                goal,
                DATE(created_at) as date
            FROM khachhang_suckhoe 
            WHERE Makh = ? 
            ORDER BY created_at DESC
            LIMIT 10
        ");
        $stmt->bind_param("s", $makh);
        $stmt->execute();
        $healthData = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Mapping combo_type về day1/day3/day5 chuẩn
    $combo_type_mapping = [
        'day1' => 'day1',
        'single_day' => 'day1', 
        '1' => 'day1',
        'day3' => 'day3',
        'three_day' => 'day3',
        '3' => 'day3',
        'day5' => 'day5',
        'five_day' => 'day5',
        '5' => 'day5'
    ];

    // Thống kê combo đã mua theo loại (từ bảng hóa đơn)
    $comboPurchased = [
        'day1' => ['purchased_combos' => 0, 'total_orders' => 0],
        'day3' => ['purchased_combos' => 0, 'total_orders' => 0],  
        'day5' => ['purchased_combos' => 0, 'total_orders' => 0]
    ];

    // Debug: Kiểm tra bảng hóa đơn và chi tiết hóa đơn
    $debug_info = [];
    
    // Lấy combo đã mua từ bảng hóa đơn (thông qua source_type và combo_info)
    $combo_orders_stmt = $conn->prepare("
        SELECT 
            cthd.source_type,
            cthd.combo_info,
            h.id as order_id,
            h.created_at,
            COUNT(DISTINCT h.id) as order_count
        FROM chitiethoadon cthd
        JOIN hoadon h ON cthd.hoadon_id = h.id
        WHERE h.user_id = ? AND cthd.source_type IN ('combo_full', 'combo_single_day')
        GROUP BY h.id, cthd.source_type, cthd.combo_info
        ORDER BY h.created_at DESC
    ");
    
    if ($combo_orders_stmt) {
        $combo_orders_stmt->bind_param("s", $makh);
        $combo_orders_stmt->execute();
        $combo_orders_result = $combo_orders_stmt->get_result();
        
        while ($row = $combo_orders_result->fetch_assoc()) {
            $source_type = $row['source_type'];
            $combo_info = $row['combo_info'] ?? '';
            $debug_info['combo_orders'][] = $row;
            
            // Phân loại combo theo combo_info
            if (strpos($combo_info, 'day1') !== false) {
                $comboPurchased['day1']['purchased_combos']++;
                $comboPurchased['day1']['total_orders']++;
            } elseif (strpos($combo_info, 'day3') !== false) {
                $comboPurchased['day3']['purchased_combos']++;
                $comboPurchased['day3']['total_orders']++;
            } elseif (strpos($combo_info, 'day5') !== false) {
                $comboPurchased['day5']['purchased_combos']++;
                $comboPurchased['day5']['total_orders']++;
            } else {
                // Nếu không xác định được, mặc định là combo 3 ngày
                $comboPurchased['day3']['purchased_combos']++;
                $comboPurchased['day3']['total_orders']++;
            }
        }
    }
    
    // Lấy thông tin đơn hàng gần đây để debug
    $recent_orders_stmt = $conn->prepare("
        SELECT h.id, h.created_at, h.user_id, COUNT(cthd.id) as item_count
        FROM hoadon h 
        LEFT JOIN chitiethoadon cthd ON h.id = cthd.hoadon_id 
        WHERE h.user_id = ? 
        GROUP BY h.id 
        ORDER BY h.created_at DESC 
        LIMIT 5
    ");
    $recent_orders_stmt->bind_param("s", $makh);
    $recent_orders_stmt->execute();
    $recent_orders_result = $recent_orders_stmt->get_result();
    while ($order_row = $recent_orders_result->fetch_assoc()) {
        $debug_info['recent_orders'][] = $order_row;
    }
    // Function để chuyển đổi mục tiêu sang tiếng Việt
    function getVietnameseGoal($goal) {
        $goalMapping = [
            'maintain' => 'Duy trì cân nặng',
            'loseweight' => 'Giảm cân',
            'gainweight' => 'Tăng cân', 
            'diet' => 'Ăn theo chế độ'
        ];
        
        return $goalMapping[strtolower($goal)] ?? ucfirst($goal);
    }

    // Debug: Hiển thị thông tin debug nếu có tham số debug
    if (isset($_GET['debug'])) {
        echo "<div style='background: #f8f9fa; border: 1px solid #ddd; margin: 20px; padding: 15px; border-radius: 8px;'>";
        echo "<h3>🔍 DEBUG INFO:</h3>";
        echo "<h4>Current User ID:</h4>";
        echo "<p><strong>" . htmlspecialchars($makh) . "</strong></p>";
        
        echo "<h4>Combo Purchased Result:</h4>";
        echo "<pre>" . print_r($comboPurchased, true) . "</pre>";
        
        echo "<h4>Raw Debug Data:</h4>";
        echo "<pre>" . print_r($debug_info, true) . "</pre>";
        
        echo "<h4>Summary:</h4>";
        foreach ($comboPurchased as $combo_type => $data) {
            echo "<div style='border: 1px solid #ccc; margin: 10px 0; padding: 10px;'>";
            echo "<strong>$combo_type:</strong><br>";
            echo "- Purchased Combos: {$data['purchased_combos']}<br>";
            echo "- Total Orders: {$data['total_orders']}<br>";
            echo "</div>";
        }
        echo "</div>";
    }
    ?>

    <div class="container">
        <div class="dashboard-header">
            <h1><i class="fas fa-chart-line"></i> Theo Dõi Tiến Độ Sức Khỏe</h1>
            <p>Hành trình chăm sóc sức khỏe của bạn</p>
        </div>

        <div class="dashboard-grid">
            <div class="chart-section">
                <div class="chart-header">
                    <div>
                        <div class="chart-title">Biến Động Cân Nặng & Chiều Cao</div>
                        <div class="chart-subtitle">
                            <?php if (!empty($healthData)): ?>
                                Dữ liệu từ <?= date('d/m/Y', strtotime($healthData[0]['created_at'])) ?> 
                                đến <?= date('d/m/Y', strtotime(end($healthData)['created_at'])) ?>
                                (<?= count($healthData) ?> điểm dữ liệu)
                                <?php if (isset($_GET['debug'])): ?>
                                    <br><small>Debug: User ID = <?= htmlspecialchars($makh) ?></small>
                                <?php endif; ?>
                            <?php else: ?>
                                Chưa có dữ liệu - User ID: <?= htmlspecialchars($makh) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="chart-container">
                    <canvas id="healthChart"></canvas>
                </div>
                
                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-color weight-color"></div>
                        <span>Cân nặng (kg)</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color height-color"></div>
                        <span>Chiều cao (cm)</span>
                    </div>
                </div>
            </div>

            <div class="stats-section">
                <div class="combo-progress-section">
                    <h3 class="progress-title"><i class="fas fa-utensils"></i> Tiến Độ Combo</h3>
                    <div class="combo-progress-grid">
                    
                        <div class="combo-card one" data-combo="day3">
                            <div class="combo-title">Combo 3 Ngày</div>
                            <div class="combo-info">
                                <div class="combo-number"><?php echo $comboPurchased['day3']['purchased_combos']; ?></div>
                                <div class="combo-label">Combo đã mua</div>
                            </div>
                        </div>

                        <div class="combo-card two" data-combo="day5">
                            <div class="combo-title">Combo 5 Ngày</div>
                            <div class="combo-info">
                                <div class="combo-number"><?php echo $comboPurchased['day5']['purchased_combos']; ?></div>
                                <div class="combo-label">Combo đã mua</div>
                            </div>
                        </div>
                        <div class="combo-card three" data-combo="day1">
                            <div class="combo-title">Combo 1 Ngày</div>
                            <div class="combo-info">
                                <div class="combo-number"><?php echo $comboPurchased['day1']['purchased_combos']; ?></div>
                                <div class="combo-label">Combo đã mua</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="metrics-grid">
            <?php if (!empty($healthData)): ?>
                <?php 
                // Lấy bản ghi mới nhất
                $latest = end($healthData); 
                reset($healthData); // Reset pointer
                
                // Tính BMI
                $bmi = 0;
                if ($latest['height'] > 0) {
                    $bmi = round($latest['weight'] / (($latest['height']/100) ** 2), 1);
                }
                ?>
                <div class="metric-card">
                    <div class="metric-icon weight">
                        <i class="fas fa-weight"></i>
                    </div>
                    <div class="metric-value"><?= number_format($latest['weight'], 1) ?>kg</div>
                    <div class="metric-label">Cân nặng hiện tại</div>
                </div>

                <div class="metric-card">
                    <div class="metric-icon height">
                        <i class="fas fa-ruler-vertical"></i>
                    </div>
                    <div class="metric-value"><?= $latest['height'] ?>cm</div>
                    <div class="metric-label">Chiều cao</div>
                </div>

                <div class="metric-card">
                    <div class="metric-icon bmi">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="metric-value"><?= $bmi ?></div>
                    <div class="metric-label">Chỉ số BMI</div>
                </div>

                <div class="metric-card">
                    <div class="metric-icon goal">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div class="metric-value" style="font-size: 1.2em; line-height: 1.2;"><?= getVietnameseGoal($latest['goal']) ?></div>
                    <div class="metric-label">Mục tiêu</div>
                </div>
            <?php else: ?>
                <!-- Hiển thị thông báo khi không có dữ liệu -->
                <div class="metric-card" style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                    <div class="metric-icon" style="background: #6b7280; margin: 0 auto 20px;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="metric-value" style="font-size: 1.2em; color: #6b7280;">Chưa có dữ liệu sức khỏe</div>
                    <div class="metric-label">Vui lòng cập nhật thông tin cân nặng và chiều cao</div>
                    <button class="btn_primary" style="margin-top: 15px;" onclick="window.location.href='/CoSo/php/congcu.php'">
                        <i class="fas fa-plus"></i> Cập nhật ngay
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Consultation CTA Section -->
        <div class="consultation-cta-section">
            <div class="consultation-cta-content">
                <div class="consultation-cta-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="consultation-cta-text">
                    <h3>Cần Tư Vấn Chuyên Nghiệp?</h3>
                    <p>Để đạt được mục tiêu sức khỏe tối ưu, hãy để chuyên gia dinh dưỡng của chúng tôi hỗ trợ bạn!</p>
                </div>
                <button class="btn_primary consultation-btn">
                    <i class="fas fa-comments"></i>
                    Tư Vấn Miễn Phí
                </button>
            </div>
        </div>

        <!-- Debug Information (chỉ hiển thị khi cần debug) -->
        <?php if (isset($_GET['debug'])): ?>
        <div style="background: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 8px; font-family: monospace; font-size: 12px;">
            <h4>🔍 Debug Information:</h4>
            <p><strong>User ID:</strong> <?= htmlspecialchars($makh) ?></p>
            <p><strong>Health Data Count:</strong> <?= count($healthData) ?> records</p>
            <p><strong>Health Data (Latest 5):</strong></p>
            <pre><?= json_encode(array_slice($healthData, -5), JSON_PRETTY_PRINT) ?></pre>
            <p><strong>Combo Purchased Data:</strong></p>
            <pre><?= json_encode($comboPurchased, JSON_PRETTY_PRINT) ?></pre>
            <p><strong>Raw Database Data:</strong></p>
            <pre><?= json_encode($debug_info, JSON_PRETTY_PRINT) ?></pre>
        </div>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/footer.php'; ?>

    <script>
        // Dữ liệu sức khỏe từ PHP
        const healthData = <?= json_encode($healthData) ?>;
        
        // Debug: In ra dữ liệu để kiểm tra
        console.log('🔍 Health Data:', healthData);
        console.log('📊 Data points count:', healthData.length);
        
        // Dữ liệu combo purchased từ database
        const comboPurchased = <?= json_encode($comboPurchased) ?>;
        
        // Khởi tạo biểu đồ
        const ctx = document.getElementById('healthChart');
        if (ctx && healthData.length > 0) {
            const chartCtx = ctx.getContext('2d');
            
            const chartData = {
                labels: healthData.map(item => {
                    const date = new Date(item.created_at);
                    return date.toLocaleDateString('vi-VN', { 
                        day: '2-digit', 
                        month: '2-digit'
                    });
                }),
                datasets: [
                    {
                        label: 'Cân nặng (kg)',
                        data: healthData.map(item => parseFloat(item.weight)),
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Chiều cao (cm)',
                        data: healthData.map(item => parseFloat(item.height)),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: false,
                        yAxisID: 'y1',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            };

            const config = {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y;
                                        if (context.datasetIndex === 0) {
                                            label += 'kg';
                                        } else {
                                            label += 'cm';
                                        }
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)',
                            },
                            ticks: {
                                color: '#6b7280',
                                maxRotation: 45,
                                minRotation: 0,
                                autoSkip: false,
                                maxTicksLimit: undefined
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)',
                            },
                            ticks: {
                                color: '#6b7280',
                                callback: function(value) {
                                    return value + 'kg';
                                }
                            },
                            title: {
                                display: true,
                                text: 'Cân nặng (kg)',
                                color: '#4f46e5'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false,
                            },
                            ticks: {
                                color: '#6b7280',
                                callback: function(value) {
                                    return value + 'cm';
                                }
                            },
                            title: {
                                display: true,
                                text: 'Chiều cao (cm)',
                                color: '#10b981'
                            }
                        },
                    },
                    elements: {
                        point: {
                            radius: 4,
                            hoverRadius: 6,
                            backgroundColor: 'white',
                            borderWidth: 2
                        }
                    }
                },
            };

            const healthChart = new Chart(chartCtx, config);
            console.log('✅ Chart initialized successfully');
        } else {
            console.log('❌ No chart data or canvas element found');
            // Hiển thị thông báo không có dữ liệu
            if (ctx) {
                ctx.style.display = 'none';
                const chartContainer = ctx.parentElement;
                if (chartContainer) {
                    chartContainer.innerHTML = `
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 400px; color: #6b7280;">
                            <i class="fas fa-chart-line" style="font-size: 3em; margin-bottom: 20px; opacity: 0.5;"></i>
                            <h3>Chưa có dữ liệu biểu đồ</h3>
                            <p>Vui lòng cập nhật thông tin sức khỏe để xem biểu đồ tiến độ</p>
                            <button class="btn_primary" onclick="window.location.href='/CoSo/php/congcu.php'" style="margin-top: 15px;">
                                <i class="fas fa-plus"></i> Cập nhật ngay
                            </button>
                        </div>
                    `;
                }
            }
        }
        
        // Xử lý hiển thị thông tin combo đã mua (đơn giản, không có % tiến độ)
        function updateComboDisplay() {
            // Sử dụng dữ liệu từ database
            const comboData = comboPurchased;
            
            // Debug: In ra dữ liệu để kiểm tra
            console.log('🔍 Combo Purchased Data:', comboData);
            
            // Cập nhật hiển thị cho từng combo
            ['day1', 'day3', 'day5'].forEach(combo => {
                const data = comboData[combo];
                console.log(`📊 Processing ${combo}:`, data);
                updateComboInfo(combo, data);
            });
        }

        function updateComboInfo(combo, data) {
            const comboNumber = combo.replace('day', '');
            
            // Cập nhật số liệu thống kê hiển thị
            const totalElement = document.querySelector(`.combo-${comboNumber}-total`);
            const ordersElement = document.querySelector(`.combo-${comboNumber}-orders`);
            const statusElement = document.querySelector(`.combo-${comboNumber}-status`);
            const cardElement = document.querySelector(`[data-combo="${combo}"]`);
            
            if (totalElement) totalElement.textContent = data.purchased_combos || 0;
            if (ordersElement) ordersElement.textContent = data.total_orders || 0;
            
            // Cập nhật trạng thái
            if (statusElement && cardElement) {
                cardElement.classList.remove('active', 'completed');
                
                if (data.purchased_combos === 0) {
                    statusElement.textContent = 'Chưa mua combo';
                } else {
                    statusElement.textContent = `Đã mua ${data.purchased_combos} combo`;
                    cardElement.classList.add('active');
                }
            }
        }

        // Lắng nghe sự kiện cập nhật từ các trang khác
        window.addEventListener('storage', function(e) {
            if (e.key === 'comboProgressUpdate') {
                // Reload trang để cập nhật dữ liệu mới từ database
                location.reload();
            }
        });

        // Khởi tạo ban đầu
        document.addEventListener('DOMContentLoaded', function() {
            updateComboDisplay();
            
            // Cập nhật định kỳ mỗi 30 giây để đồng bộ dữ liệu
            setInterval(function() {
                // Kiểm tra xem có cập nhật nào không qua localStorage
                const lastUpdate = localStorage.getItem('comboLastUpdate');
                if (lastUpdate && (Date.now() - parseInt(lastUpdate)) < 60000) {
                    location.reload();
                }
            }, 30000);
        });

        // API để các trang khác thông báo cập nhật
        window.notifyComboUpdate = function() {
            localStorage.setItem('comboLastUpdate', Date.now().toString());
            localStorage.setItem('comboProgressUpdate', 'true');
        };

        // Xử lý nút tư vấn miễn phí
        document.addEventListener('DOMContentLoaded', function() {
            // Xử lý nút consultation-btn
            const consultationBtn = document.querySelector('.consultation-btn');
            if (consultationBtn) {
                consultationBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const multiForm = document.querySelector(".multi_form");
                    
                    if (multiForm) {
                        multiForm.classList.add("active_multiForm");
                        console.log('✅ Đã mở form tư vấn');
                    } else {
                        console.error('❌ Form tư vấn không tìm thấy');
                    }
                });
            } else {
                console.error('❌ Nút consultation-btn không tìm thấy');
            }

            // Xử lý nút Tư Vấn trong header
            const headerTuVanBtn = document.querySelector('.tuvan');
            if (headerTuVanBtn) {
                headerTuVanBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const multiForm = document.querySelector(".multi_form");
                    if (multiForm) {
                        multiForm.classList.add("active_multiForm");
                        console.log('✅ Mở form từ header');
                    }
                });
            }

            // Xử lý đóng form
            const closeIcon = document.querySelector(".multi_form .icon_close");
            const multiForm = document.querySelector(".multi_form");
            const multiFormWrapper = document.querySelector(".multi_form_wrapper");
            
            if (closeIcon) {
                closeIcon.addEventListener('click', function() {
                    if (multiForm) {
                        multiForm.classList.remove("active_multiForm");
                    }
                });
            }

            // Click outside để đóng
            if (multiForm) {
                multiForm.addEventListener('click', function(e) {
                    if (!multiFormWrapper || !multiFormWrapper.contains(e.target)) {
                        multiForm.classList.remove("active_multiForm");
                    }
                });
            }

            // ...existing code...
        });
    </script>
    <!-- multiForm.js đã được thay thế bởi tuvan.js trong header.php -->
</body>

</html>
