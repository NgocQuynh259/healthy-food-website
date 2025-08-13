<?php
// admin.php
require 'connect.php';

// 1) Chạy query và gán kết quả
$sql    = "SELECT * FROM sanpham";
$result = $conn->query($sql);
if (!$result) {
    die("Lỗi truy vấn: " . $conn->error);
}

// 2) Lấy dữ liệu khách hàng
$customer_query = "SELECT * FROM khachhang ORDER BY Ngaydangky DESC";
$customer_result = $conn->query($customer_query);
if (!$customer_result) {
    die("Lỗi truy vấn khách hàng: " . $conn->error);
}

// Lấy 5 khách hàng mới nhất cho tổng quan
$recent_customers_query = "SELECT * FROM khachhang ORDER BY Ngaydangky DESC LIMIT 5";
$recent_customers_result = $conn->query($recent_customers_query);

// Thống kê tổng quan
$total_customers = $customer_result->num_rows;
$new_customers_today = 0;
$admin_count = 0;

// Đếm số món ăn (tất cả sản phẩm, không phân biệt trạng thái)
$product_count_query = "SELECT COUNT(*) as total_products FROM sanpham";
$product_count_result = $conn->query($product_count_query);
$total_products = $product_count_result->fetch_assoc()['total_products'];




// Tính tổng doanh thu và chi phí (30 ngày gần nhất) - Sử dụng chitiethoadon để đồng bộ với tab Doanh thu
$revenue_overview_query = "
    SELECT 
        SUM(cthd.quantity * cthd.price) as total_revenue,
        SUM(cthd.quantity * sp.Gianguyenlieu) as total_cost,
        COUNT(DISTINCT h.id) as total_orders,
        COUNT(cthd.id) as total_items
    FROM chitiethoadon cthd
    LEFT JOIN hoadon h ON cthd.hoadon_id = h.id
    LEFT JOIN sanpham sp ON cthd.product_id = sp.Masp  
    WHERE h.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
";
$revenue_overview_result = $conn->query($revenue_overview_query);
$revenue_overview = $revenue_overview_result->fetch_assoc();
$total_revenue = $revenue_overview['total_revenue'] ?? 0;
$total_cost = $revenue_overview['total_cost'] ?? 0;
$total_orders = $revenue_overview['total_orders'] ?? 0;

// Thống kê món ăn bán chạy nhất (30 ngày)
$best_selling_query = "
    SELECT 
        sp.Tensp,
        sp.Masp,
        SUM(cthd.quantity) as total_sold,
        SUM(cthd.quantity * cthd.price) as total_revenue_product,
        AVG(cthd.price) as avg_price
    FROM chitiethoadon cthd
    LEFT JOIN sanpham sp ON cthd.product_id = sp.Masp
    LEFT JOIN hoadon h ON cthd.hoadon_id = h.id
    WHERE h.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY sp.Masp
    ORDER BY total_sold DESC
    LIMIT 1
";
$best_selling_result = $conn->query($best_selling_query);
$best_selling = $best_selling_result->fetch_assoc();
$best_selling_name = $best_selling['Tensp'] ?? 'Chưa có dữ liệu';
$best_selling_quantity = $best_selling['total_sold'] ?? 0;

// Thống kê loại món ăn phổ biến nhất
$popular_category_query = "
    SELECT 
        sp.Maloai,
        COUNT(DISTINCT sp.Masp) as products_count,
        SUM(cthd.quantity) as total_sold,
        CASE sp.Maloai 
            WHEN 1 THEN 'Món chính'
            WHEN 2 THEN 'Tráng miệng'
            WHEN 3 THEN 'Nước uống'
            WHEN 4 THEN 'Salad'
            ELSE 'Khác'
        END as category_name
    FROM chitiethoadon cthd
    LEFT JOIN sanpham sp ON cthd.product_id = sp.Masp
    LEFT JOIN hoadon h ON cthd.hoadon_id = h.id
    WHERE h.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY sp.Maloai
    ORDER BY total_sold DESC
    LIMIT 1
";
$popular_category_result = $conn->query($popular_category_query);
$popular_category = $popular_category_result->fetch_assoc();
$popular_category_name = $popular_category['category_name'] ?? 'Chưa có dữ liệu';
$popular_category_sold = $popular_category['total_sold'] ?? 0;

// Tính tỷ lệ tăng trưởng đơn hàng (so với 30 ngày trước)
$growth_query_current = "
    SELECT COUNT(DISTINCT h.id) as current_orders
    FROM hoadon h
    WHERE h.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
";
$growth_query_previous = "
    SELECT COUNT(DISTINCT h.id) as previous_orders
    FROM hoadon h
    WHERE h.created_at >= DATE_SUB(NOW(), INTERVAL 60 DAY)
    AND h.created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)
";

$current_orders_result = $conn->query($growth_query_current);
$previous_orders_result = $conn->query($growth_query_previous);

$current_orders = $current_orders_result->fetch_assoc()['current_orders'] ?? 0;
$previous_orders = $previous_orders_result->fetch_assoc()['previous_orders'] ?? 0;

$order_growth_rate = 0;
if ($previous_orders > 0) {
    $order_growth_rate = round((($current_orders - $previous_orders) / $previous_orders) * 100, 1);
}

// Reset lại con trỏ để đếm
$customer_result->data_seek(0);
while ($row = $customer_result->fetch_assoc()) {
    if ($row['Ngaydangky'] == date('Y-m-d')) {
        $new_customers_today++;
    }
    if ($row['Vaitro'] == 1) {
        $admin_count++;
    }
}

// Reset lại con trỏ để hiển thị
$customer_result->data_seek(0);

// 3) Tính toán dữ liệu cho biểu đồ doanh thu theo 4 loại: bán lẻ, combo 1 ngày, combo 3 ngày, combo 5 ngày
$revenue_stats = [
    'menu_sales' => 0,        // Bán lẻ (món từ menu)
    'combo_1day' => 0,        // Combo 1 ngày
    'combo_3day' => 0,        // Combo 3 ngày  
    'combo_5day' => 0,        // Combo 5 ngày
    'total_revenue' => 0,
    'total_cost' => 0,
    'total_profit' => 0
];

// Lấy doanh thu từ chitiethoadon theo source_type (30 ngày gần nhất)
$revenue_query = "
    SELECT 
        cthd.source_type,
        cthd.combo_info,
        cthd.quantity,
        cthd.price,
        sp.Tensp,
        sp.Gianguyenlieu,
        h.created_at
    FROM chitiethoadon cthd
    LEFT JOIN sanpham sp ON cthd.product_id = sp.Masp  
    LEFT JOIN hoadon h ON cthd.hoadon_id = h.id
    WHERE h.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    ORDER BY h.created_at DESC
";

$revenue_result = $conn->query($revenue_query);
if ($revenue_result) {
    while ($row = $revenue_result->fetch_assoc()) {
        $revenue = $row['quantity'] * $row['price'];
        $cost = $row['quantity'] * ($row['Gianguyenlieu'] ?? 0);

        $revenue_stats['total_revenue'] += $revenue;
        $revenue_stats['total_cost'] += $cost;

        // Phân loại theo source_type và combo_info
        $source_type = $row['source_type'] ?? 'menu';
        $combo_info = $row['combo_info'] ?? '';

        if ($source_type === 'menu') {
            // Bán lẻ - món từ menu
            $revenue_stats['menu_sales'] += $revenue;
        } elseif ($source_type === 'combo_full') {
            // Combo đầy đủ - phân loại theo số ngày trong combo_info
            if (strpos($combo_info, 'day1') !== false) {
                $revenue_stats['combo_1day'] += $revenue;
            } elseif (strpos($combo_info, 'day3') !== false) {
                $revenue_stats['combo_3day'] += $revenue;
            } elseif (strpos($combo_info, 'day5') !== false) {
                $revenue_stats['combo_5day'] += $revenue;
            } else {
                // Nếu không xác định được, mặc định là combo 3 ngày
                $revenue_stats['combo_3day'] += $revenue;
            }
        } elseif ($source_type === 'combo_single_day') {
            // Combo từng ngày - phân loại theo combo_info  
            if (strpos($combo_info, 'day1') !== false) {
                $revenue_stats['combo_1day'] += $revenue;
            } elseif (strpos($combo_info, 'day3') !== false) {
                $revenue_stats['combo_3day'] += $revenue;
            } elseif (strpos($combo_info, 'day5') !== false) {
                $revenue_stats['combo_5day'] += $revenue;
            } else {
                // Nếu không xác định được, mặc định là combo 3 ngày
                $revenue_stats['combo_3day'] += $revenue;
            }
        }
    }
}

// Lấy doanh thu combo từ bảng combo_cart (tạm thời coi như đã thanh toán)
// TODO: Sau này có thể cần join với combo_progress để lọc chỉ combo đã thanh toán
$combo_revenue_query = "
    SELECT 
        combo_type,
        total_price,
        created_at
    FROM combo_cart
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
";

$combo_revenue_result = $conn->query($combo_revenue_query);
if ($combo_revenue_result) {
    while ($row = $combo_revenue_result->fetch_assoc()) {
        $revenue = $row['total_price'];
        // Không cộng vào total_revenue vì đã được tính ở trên từ chitiethoadon

        // Tạm thời để trống vì sẽ dùng source_type từ chitiethoadon
    }
}

// Tính lãi
$revenue_stats['total_profit'] = $revenue_stats['total_revenue'] - $revenue_stats['total_cost'];

// Tính phần trăm cho 4 loại
$total = $revenue_stats['total_revenue'];
if ($total > 0) {
    $revenue_percentages = [
        'menu_sales' => round(($revenue_stats['menu_sales'] / $total) * 100, 1),
        'combo_1day' => round(($revenue_stats['combo_1day'] / $total) * 100, 1),
        'combo_3day' => round(($revenue_stats['combo_3day'] / $total) * 100, 1),
        'combo_5day' => round(($revenue_stats['combo_5day'] / $total) * 100, 1)
    ];
} else {
    // Dữ liệu mặc định nếu chưa có doanh thu
    $revenue_percentages = [
        'menu_sales' => 70.0,
        'combo_1day' => 10.0,
        'combo_3day' => 15.0,
        'combo_5day' => 5.0
    ];
}

// Thống kê combo đã mua theo từng loại (30 ngày gần nhất)
$combo_stats = [
    'combo_1day_bought' => 0,
    'combo_3day_bought' => 0,
    'combo_5day_bought' => 0,
    'total_combo_bought' => 0
];

// Lấy số combo đã mua từ bảng chitiethoadon theo source_type
$combo_bought_query = "
    SELECT 
        cthd.source_type,
        cthd.combo_info,
        COUNT(DISTINCT h.id) as combo_count
    FROM chitiethoadon cthd
    LEFT JOIN hoadon h ON cthd.hoadon_id = h.id
    WHERE h.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    AND (cthd.source_type = 'combo_full' OR cthd.source_type = 'combo_single_day')
    GROUP BY cthd.source_type, cthd.combo_info
";

$combo_bought_result = $conn->query($combo_bought_query);
if ($combo_bought_result) {
    while ($row = $combo_bought_result->fetch_assoc()) {
        $combo_count = $row['combo_count'];
        $source_type = $row['source_type'] ?? 'combo_full';
        $combo_info = $row['combo_info'] ?? '';

        $combo_stats['total_combo_bought'] += $combo_count;

        // Phân loại theo combo_info
        if (strpos($combo_info, 'day1') !== false || strpos($combo_info, '1_day') !== false) {
            $combo_stats['combo_1day_bought'] += $combo_count;
        } elseif (strpos($combo_info, 'day3') !== false || strpos($combo_info, '3_day') !== false) {
            $combo_stats['combo_3day_bought'] += $combo_count;
        } elseif (strpos($combo_info, 'day5') !== false || strpos($combo_info, '5_day') !== false) {
            $combo_stats['combo_5day_bought'] += $combo_count;
        } else {
            // Nếu không xác định được, mặc định là combo 3 ngày
            $combo_stats['combo_3day_bought'] += $combo_count;
        }
    }
}

// Lấy thêm từ bảng combo_cart nếu có
$combo_cart_query = "
    SELECT 
        combo_type,
        COUNT(*) as combo_count
    FROM combo_cart
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY combo_type
";

$combo_cart_result = $conn->query($combo_cart_query);
if ($combo_cart_result) {
    while ($row = $combo_cart_result->fetch_assoc()) {
        $combo_count = $row['combo_count'];
        $combo_type = $row['combo_type'] ?? '';

        // Phân loại theo combo_type từ combo_cart
        if (strpos($combo_type, '1') !== false) {
            $combo_stats['combo_1day_bought'] += $combo_count;
        } elseif (strpos($combo_type, '3') !== false) {
            $combo_stats['combo_3day_bought'] += $combo_count;
        } elseif (strpos($combo_type, '5') !== false) {
            $combo_stats['combo_5day_bought'] += $combo_count;
        }

        $combo_stats['total_combo_bought'] += $combo_count;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/admin-improvements.css">
    <link rel="stylesheet" href="../css/admin-stats.css">
    <link rel="stylesheet" href="../css/popup.css">

    <title>Quản Lý Trang Web TQFOOD</title>
</head>

<body>
    <header>
        <div class="logo-image">
            <img src="../assets/img/avt/logo.png" alt="">
        </div>
        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="../php/index.php">
                        <img src="../assets/img/avt/home.png" alt="">
                        <span class="link-name">Trang chủ</span>
                    </a></li>
                <li><a href="#" data-tab="tongquan" class="active">
                        <img src="../assets/img/avt/list.png" alt="">
                        <span class="link-name">Tổng quan</span>
                    </a></li>
                <li><a href="#" data-tab="khachhang">
                        <img src="../assets/img/avt/khachhang.png" alt="">
                        <span class="link-name">Khách hàng</span>
                    </a></li>
                <li><a href="#" data-tab="monan">
                        <img src="../assets/img/avt/diet_food.png" alt="">
                        <span class="link-name">Món ăn</span>
                    </a></li>
                <li><a href="#" data-tab="doanhthu">
                        <img src="../assets/img/avt/financial-profit.png" alt="">
                        <span class="link-name">Doanh thu</span>
                    </a></li>
            </ul>
            
        </div>
    </header>

    <section class="dashboard">
        <div class="top">
            <div class="left-section">
                <img src="../assets/img/avt/toggle.png" alt="" class="sidebar-toggle">
            </div>

            <div class="right-section">
                <span id="datetime"></span>
                <img src="../assets/img/avt/bell.png" alt="" class="icon_bell">
                <img src="../assets/img/avt/avt_admin.jpg" alt="User Avatar" class="user-avatar">
            </div>
        </div>



        <!-- Tong quan -->
        <div class="tongquan" style="display: block;">
                       
            <div class="dash-content">
                <div class="overview">
                    <div class="title">
                        <span class="text">Thống Kê Tổng Quan</span>
                    </div>

                    <div class="boxes">
                        <div class="box box1">
                            <img src="../assets/img/avt/customer.png" alt="khachhang">
                            <span class="text">Tổng Số Khách Hàng</span>
                            <span class="number"><?php echo number_format($total_customers); ?></span>
                        </div>
                        <div class="box box2">
                            <img src="../assets/img/avt/diet.png" alt="monan">
                            <span class="text">Tổng Số Món Ăn</span>
                            <span class="number"><?php echo number_format($total_products); ?></span>
                        </div>
                        <div class="box box3">
                            <img src="../assets/img/avt/salary.png" alt="doanhthu">
                            <span class="text">Doanh Thu (30 ngày)</span>
                            <span class="number"><?php echo number_format($total_revenue, 0, ',', '.'); ?>đ</span>
                        </div>
                        <div class="box box4">
                            <img src="../assets/img/avt/list.png" alt="donhang">
                            <span class="text">Tổng Đơn Hàng</span>
                            <span class="number"><?php echo number_format($total_orders); ?></span>
                        </div>
                    </div>

                    <!-- Thống kê chi tiết món ăn -->
                    <div class="detailed-stats">
                        <div class="stat-card" style="display: flex;">
                            <div class="stat-icon">🏆</div>
                            <div class="stat-content">
                                <h3>Món Bán Chạy Nhất</h3>
                                <p class="stat-value"><?php echo htmlspecialchars($best_selling_name); ?></p>
                                <p class="stat-sub">Đã bán: <?php echo number_format($best_selling_quantity); ?> phần</p>
                            </div>
                        </div>

                        <div class="stat-card" style="display: flex;">
                            <div class="stat-icon">📈</div>
                            <div class="stat-content">
                                <h3>Tăng Trưởng Đơn Hàng</h3>
                                <p class="stat-value <?php echo $order_growth_rate >= 0 ? 'positive' : 'negative'; ?>">
                                    <?php echo $order_growth_rate >= 0 ? '+' : ''; ?><?php echo $order_growth_rate; ?>%
                                </p>
                                <p class="stat-sub">So với 30 ngày trước</p>
                            </div>
                        </div>

                        <div class="stat-card" style="display: flex;">
                            <div class="stat-icon">💰</div>
                            <div class="stat-content">
                                <h3>Doanh Thu Trung Bình</h3>
                                <p class="stat-value"><?php echo number_format($total_orders > 0 ? $total_revenue / $total_orders : 0, 0, ',', '.'); ?>đ</p>
                                <p class="stat-sub">Trung bình/đơn hàng</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top 5 món ăn bán chạy nhất -->
                <div class="activity">
                    <div class="title">
                        <span class="text">🏆 Top 5 Món Ăn Bán Chạy Nhất (30 ngày)</span>
                    </div>

                    <?php
                    // Query top 5 món ăn bán chạy
                    $top_products_query = "
                        SELECT 
                            sp.Tensp,
                            sp.Masp,
                            sp.Hinhanh,
                            SUM(cthd.quantity) as total_sold,
                            SUM(cthd.quantity * cthd.price) as total_revenue_product,
                            AVG(cthd.price) as avg_price,
                            CASE sp.Maloai 
                                WHEN 1 THEN 'Món chính'
                                WHEN 2 THEN 'Tráng miệng'
                                WHEN 3 THEN 'Nước uống'
                                WHEN 4 THEN 'Salad'
                                ELSE 'Khác'
                            END as category_name
                        FROM chitiethoadon cthd
                        LEFT JOIN sanpham sp ON cthd.product_id = sp.Masp
                        LEFT JOIN hoadon h ON cthd.hoadon_id = h.id
                        WHERE h.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                        GROUP BY sp.Masp
                        ORDER BY total_sold DESC
                        LIMIT 3
                    ";
                    $top_products_result = $conn->query($top_products_query);
                    ?>

                    <div class="top-products-grid">
                        <?php
                        if ($top_products_result && $top_products_result->num_rows > 0) {
                            $rank = 1;
                            while ($product = $top_products_result->fetch_assoc()) {
                                $rank_medal = ['🥇', '🥈', '🥉'][$rank - 1] ?? $rank;
                                echo "<div class='product-card rank-{$rank}'>";
                                echo "<div class='rank-badge'>{$rank_medal}</div>";
                                echo "<div class='product-info'>";
                                echo "<h4>" . htmlspecialchars($product['Tensp']) . "</h4>";
                                echo "<p class='category'>" . htmlspecialchars($product['category_name']) . "</p>";
                                echo "<div class='stats'>";
                                echo "<span class='sold'>Đã bán: <strong>" . number_format($product['total_sold']) . "</strong> phần</span>";
                                echo "<span class='revenue'>Doanh thu: <strong>" . number_format($product['total_revenue_product'], 0, ',', '.') . "đ</strong></span>";
                                echo "<span class='avg-price'>Giá: <strong>" . number_format($product['avg_price'], 0, ',', '.') . "đ</strong></span>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                $rank++;
                            }
                        } else {
                            echo "<p style='text-align: center; color: #6c757d; grid-column: 1 / -1;'>Chưa có dữ liệu bán hàng trong 30 ngày qua.</p>";
                        }
                        ?>
                    </div>
                </div>
                <div class="activity">
                    <div class="title">
                        <span class="text">Khách Hàng Mới Nhất</span>
                    </div>
                    <table class="customer-table" id="customerTable">
                        <thead>
                            <tr>
                                <th>Mã KH</th>
                                <th>Tên khách hàng</th>
                                <th>Email</th>
                                <th>Ngày đăng ký</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($recent_customers_result && $recent_customers_result->num_rows > 0) {
                                // Đặt lại con trỏ trước khi bắt đầu
                                $recent_customers_result->data_seek(0);
                                while ($recent_customer = $recent_customers_result->fetch_assoc()) {
                                    $makh = htmlspecialchars($recent_customer['Makh']);
                                    $tenkh = htmlspecialchars($recent_customer['Tenkh']);
                                    $email = htmlspecialchars($recent_customer['Email']);
                                    $ngaydangky = date('d/m/Y', strtotime($recent_customer['Ngaydangky']));

                                    // Tính số ngày kể từ ngày đăng ký
                                    $days_ago = (strtotime('now') - strtotime($recent_customer['Ngaydangky'])) / (60 * 60 * 24);
                                    if ($days_ago == 0) {
                                        $trangthai = "<span style='color: #27ae60; font-weight: 600;'>Mới</span>";
                                    } else if ($days_ago <= 7) {
                                        $trangthai = "<span style='color: #f39c12; font-weight: 600;'>Tuần này</span>";
                                    } else {
                                        $trangthai = "<span style='color: #95a5a6;'>Cũ</span>";
                                    }

                                    echo "<tr>";
                                    echo "<td>$makh</td>";
                                    echo "<td>$tenkh</td>";
                                    echo "<td>$email</td>";
                                    echo "<td>$ngaydangky</td>";
                                    echo "<td>$trangthai</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' style='text-align: center;'>Chưa có khách hàng</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                </div>

            </div>
        </div>

        <!-- Khach hang -->
        <div class="khachhang" style="display: none;">
            <div class="dash-content">
                <div class="overview">
                    <div class="title">
                        <span class="text">Quản Lý Khách Hàng</span>
                    </div>

                    <!-- Thống kê khách hàng -->
                    <div class="customer-stats">
                        <div class="stats-grid">
                            <div class="stat-box" style="background: #bceb93;">
                                <h3>Tổng khách hàng</h3>
                                <p class="stat-number"><?php echo $total_customers; ?></p>
                            </div>
                            <div class="stat-box" style="background: #ffe6ac;">
                                <h3>Khách hàng mới hôm nay</h3>
                                <p class="stat-number"><?php echo $new_customers_today; ?></p>
                            </div>
                            <div class="stat-box" style="background: #e7d1fc;">
                                <h3>Quản trị viên</h3>
                                <p class="stat-number"><?php echo $admin_count; ?></p>
                            </div>
                            <div class="stat-box" style="background: #fab3ff;">
                                <h3>Thành viên</h3>
                                <p class="stat-number"><?php echo $total_customers - $admin_count; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Bảng danh sách khách hàng -->
                    <div class="customer-table-container">
                        <h3>Danh sách khách hàng</h3>
                        <div class="table-wrapper-scrollable">
                            <table class="customer-table">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã KH</th>
                                        <th>Tên khách hàng</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Địa chỉ</th>
                                        <th>Ngày đăng ký</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($customer_result->num_rows > 0) {
                                        $stt = 1;
                                        while ($customer = $customer_result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $stt . "</td>";
                                            echo "<td>" . htmlspecialchars($customer['Makh']) . "</td>";
                                            echo "<td>" . htmlspecialchars($customer['Tenkh']) . "</td>";
                                            echo "<td>" . htmlspecialchars($customer['Email']) . "</td>";
                                            echo "<td>" . htmlspecialchars($customer['SDT'] ?? 'Chưa cập nhật') . "</td>";
                                            echo "<td>" . htmlspecialchars($customer['Diachi'] ?? 'Chưa cập nhật') . "</td>";
                                            echo "<td>" . date('d/m/Y', strtotime($customer['Ngaydangky'])) . "</td>";
                                            echo "</tr>";
                                            $stt++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' style='text-align: center;'>Không có dữ liệu khách hàng</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mon an -->
        <div class="monan" style="display: none;">
            <h1>Danh sách sản phẩm</h1>
            <!-- Them mon -->
            <div class="add_product">
                <button id="openAddFormBtn">Thêm món mới ➕</button>
                <div id="addFormPopup" class="modal">
                    <div class="modal-content">
                        <span class="close_btn">×</span>
                        <h2>Thêm món ăn mới</h2>
                        <form method="POST" action="xuly_admin.php" enctype="multipart/form-data">
                            <h3>Thông tin sản phẩm</h3>
                            <div class="form-group full-width">
                                <label for="ten_sp">Tên món ăn</label>
                                <input type="text" id="ten_sp" name="ten_sp" placeholder="Ví dụ: Cơm sườn đặc biệt" required>
                            </div>
                            <div class="full-width_gr2">
                                <div class="form-group">
                                    <label for="maloai">Loại sản phẩm</label>
                                    <select id="maloai" name="maloai" required>
                                        <option value="" disabled selected>-- Chọn loại món ăn --</option>
                                        <?php
                                        $result_loai = $conn->query("SELECT Maloai, Tenloai FROM loai ORDER BY Tenloai");
                                        while ($row_loai = $result_loai->fetch_assoc()) {
                                            echo "<option value='" . $row_loai['Maloai'] . "'>" . htmlspecialchars($row_loai['Tenloai']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="trangthai">Trạng thái</label>
                                    <select id="trangthai" name="trangthai" required>
                                        <option value="1" selected>Còn hàng</option>
                                        <option value="0">Hết hàng</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group full-width">
                                <label for="hinhanh">Ảnh món ăn</label>
                                <input type="file" id="hinhanh" name="hinhanh" accept="image/*" required>
                            </div>
                            <div class="full-width_gr2">
                                <div class="gianl">
                                    <label for="gianguyenlieu">Giá Nguyên Liệu (VNĐ)</label>
                                    <input type="number" id="gianguyenlieu" name="gianguyenlieu" placeholder="Ví dụ: 35000" required>
                                </div>
                                <div class="giaban">
                                    <label for="giaban">Giá Bán(VNĐ)</label>
                                    <input type="number" id="giaban" name="giaban" placeholder="Ví dụ: 35000" required>
                                </div>
                            </div>
                            <div class="form-group full-width">
                                <label for="mota">Mô tả món ăn</label>
                                <textarea id="mota" name="mota" placeholder="Mô tả chi tiết, nguyên liệu, v.v."></textarea>
                            </div>
                            <div class="form-group full-width">
                                <label for="thanhphan">Thành phần món ăn</label>
                                <textarea id="thanhphan" name="thanhphan" placeholder="Nhập các thành phần, cách nhau bằng dấu phẩy"></textarea>
                            </div>
                            <h3>Thông tin dinh dưỡng / 100g</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="calo">Calories</label>
                                    <input type="number" id="calo" name="calo" placeholder="kcal" required>
                                </div>
                                <div class="form-group">
                                    <label for="protein">Protein (g)</label>
                                    <input type="number" id="protein" name="protein" step="0.1" placeholder="gram" required>
                                </div>
                                <div class="form-group">
                                    <label for="fat">Fat (g)</label>
                                    <input type="number" id="fat" name="fat" step="0.1" placeholder="gram" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="carbs">Carbs (g)</label>
                                    <input type="number" id="carbs" name="carbs" step="0.1" placeholder="gram" required>
                                </div>
                                <div class="form-group">
                                    <label for="sugar">Sugar (g)</label>
                                    <input type="number" id="sugar" name="sugar" step="0.1" placeholder="gram" required>
                                </div>
                                <div class="form-group">
                                    <label for="fiber">Fiber (g)</label>
                                    <input type="number" id="fiber" name="fiber" step="0.1" placeholder="gram" required>
                                </div>
                            </div>
                            <input type="submit" value="Lưu sản phẩm">
                        </form>
                    </div>
                </div>
            </div>


            <div class="table-container">
                <table id="productTable">
                    <thead>
                        <tr>
                            <th>Mã SP</th>
                            <th>Tên</th>
                            <th>Loại</th>
                            <th>Giá NL</th>
                            <th>Giá Bán</th>
                            <th>TT</th>
                            <th>Chỉnh sửa</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

            <!-- Danh sach san pham -->
            <div id="editFormPopup" class="modal">
                <div class="modal-content">
                    <span class="close_btn">×</span>
                    <h2>Chỉnh sửa món ăn</h2>
                    <form id="editForm" method="POST" enctype="multipart/form-data" action="update_product.php">
                        <input type="hidden" name="ma_sp" id="ma_sp">
                        <h3>Thông tin sản phẩm</h3>
                        <div class="form-group full-width">
                            <label for="ten_sp">Tên món ăn</label>
                            <input type="text" id="ten_sp" name="ten_sp" placeholder="Ví dụ: Cơm sườn đặc biệt" required>
                        </div>
                        <div class="full-width_gr2">
                            <div class="form-group">
                                <label for="maloai">Loại sản phẩm</label>
                                <select id="maloai" name="maloai" required>
                                    <option value="" disabled selected>-- Chọn loại món ăn --</option>
                                    <?php
                                    $result_loai = $conn->query("SELECT Maloai, Tenloai FROM loai ORDER BY Tenloai");
                                    while ($row_loai = $result_loai->fetch_assoc()) {
                                        echo "<option value='" . $row_loai['Maloai'] . "'>" . htmlspecialchars($row_loai['Tenloai']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="trangthai">Trạng thái</label>
                                <select id="trangthai" name="trangthai" required>
                                    <option value="1">Còn hàng</option>
                                    <option value="0">Hết hàng</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group full-width">
                            <label for="hinhanh">Ảnh món ăn</label>
                            <input type="file" id="hinhanh" name="hinhanh" accept="image/*">
                        </div>
                        <div class="full-width_gr2">
                            <div class="gianl">
                                <label for="gianguyenlieu">Giá Nguyên Liệu (VNĐ)</label>
                                <input type="number" id="gianguyenlieu" name="gianguyenlieu" placeholder="Ví dụ: 35000" required>
                            </div>
                            <div class="giaban">
                                <label for="giaban">Giá Bán (VNĐ)</label>
                                <input type="number" id="giaban" name="giaban" placeholder="Ví dụ: 35000" required>
                            </div>
                        </div>
                        <div class="form-group full-width">
                            <label for="mota">Mô tả món ăn</label>
                            <textarea id="mota" name="mota" placeholder="Mô tả chi tiết, nguyên liệu, v.v."></textarea>
                        </div>
                        <div class="form-group full-width">
                            <label for="thanhphan">Thành phần món ăn</label>
                            <textarea id="thanhphan" name="thanhphan" placeholder="Nhập các thành phần, cách nhau bằng dấu phẩy"></textarea>
                        </div>
                        <h3>Thông tin dinh dưỡng / 100g</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="calo">Calories</label>
                                <input type="number" id="calo" name="calo" placeholder="kcal" required>
                            </div>
                            <div class="form-group">
                                <label for="protein">Protein (g)</label>
                                <input type="number" id="protein" name="protein" step="0.1" placeholder="gram" required>
                            </div>
                            <div class="form-group">
                                <label for="fat">Fat (g)</label>
                                <input type="number" id="fat" name="fat" step="0.1" placeholder="gram" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="carbs">Carbs (g)</label>
                                <input type="number" id="carbs" name="carbs" step="0.1" placeholder="gram" required>
                            </div>
                            <div class="form-group">
                                <label for="sugar">Sugar (g)</label>
                                <input type="number" id="sugar" name="sugar" step="0.1" placeholder="gram" required>
                            </div>
                            <div class="form-group">
                                <label for="fiber">Fiber (g)</label>
                                <input type="number" id="fiber" name="fiber" step="0.1" placeholder="gram" required>
                            </div>
                        </div>
                        <input type="submit" value="Lưu sản phẩm">
                    </form>
                </div>
            </div>
        </div>

        <!-- Doanh thu -->
        <div class="doanhthu" style="display: none;">
            <div class="dash-content">
                <div class="overview">
                    <div class="title">
                        <span class="text">Thống Kê Doanh Thu & Lợi Nhuận</span>
                    </div>

                    <div class="revenue-summary">
                        <div class="revenue-stats-grid">
                            <div class="stat-card revenue-card">
                                <div class="card-icon revenue-icon">💰</div>
                                <h3 style="text-align: center;">Doanh thu (30 ngày)</h3>
                                <p class="stat-number"><?php echo number_format($revenue_stats['total_revenue'], 0, ',', '.'); ?>đ</p>
                                <span class="stat-growth">Doanh thu 30 ngày qua</span>
                            </div>
                            <div class="stat-card cost-card">
                                <div class="card-icon cost-icon">📊</div>
                                <h3 style="text-align: center;">Tổng chi phí</h3>
                                <p class="stat-number"><?php echo number_format($revenue_stats['total_cost'], 0, ',', '.'); ?>đ</p>
                                <span class="stat-growth">Chi phí nguyên liệu</span>
                            </div>
                            <div class="stat-card profit-card">
                                <div class="card-icon profit-icon">📈</div>
                                <h3 style="text-align: center;">Lợi nhuận</h3>
                                <p class="stat-number"><?php echo number_format($revenue_stats['total_profit'], 0, ',', '.'); ?>đ</p>
                                <span class="stat-growth profit-positive">
                                    <?php
                                    $profit_margin = $revenue_stats['total_revenue'] > 0 ?
                                        round(($revenue_stats['total_profit'] / $revenue_stats['total_revenue']) * 100, 1) : 0;
                                    echo "Tỷ suất lợi nhuận: {$profit_margin}%";
                                    ?>
                                </span>
                            </div>

                        </div>
                    </div>

                    <div class="chart-container">
                        <div class="chart-title">
                            <h3>Phân bố doanh thu theo loại sản phẩm</h3>
                        </div>
                        <div class="pie-chart-wrapper">
                            <canvas id="revenueChart" width="400" height="400"></canvas>
                            <div class="chart-legend">
                                <div class="legend-item">
                                    <span class="legend-color" style="background-color: #ff6b6b;"></span>
                                    <span class="legend-text">Bán lẻ (Menu): <?php echo $revenue_percentages['menu_sales']; ?>%</span>
                                    <span class="legend-amount"><?php echo number_format($revenue_stats['menu_sales'], 0, ',', '.'); ?>đ</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-color" style="background-color: #4ecdc4;"></span>
                                    <span class="legend-text">Combo 1 ngày: <?php echo $revenue_percentages['combo_1day']; ?>%</span>
                                    <span class="legend-amount"><?php echo number_format($revenue_stats['combo_1day'], 0, ',', '.'); ?>đ</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-color" style="background-color: #45b7d1;"></span>
                                    <span class="legend-text">Combo 3 ngày: <?php echo $revenue_percentages['combo_3day']; ?>%</span>
                                    <span class="legend-amount"><?php echo number_format($revenue_stats['combo_3day'], 0, ',', '.'); ?>đ</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-color" style="background-color: #ffa726;"></span>
                                    <span class="legend-text">Combo 5 ngày: <?php echo $revenue_percentages['combo_5day']; ?>%</span>
                                    <span class="legend-amount"><?php echo number_format($revenue_stats['combo_5day'], 0, ',', '.'); ?>đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Dữ liệu doanh thu từ PHP cho 4 loại
        const revenueData = {
            labels: ['Bán lẻ (Menu)', 'Combo 1 ngày', 'Combo 3 ngày', 'Combo 5 ngày'],
            values: [
                <?php echo $revenue_percentages['menu_sales']; ?>,
                <?php echo $revenue_percentages['combo_1day']; ?>,
                <?php echo $revenue_percentages['combo_3day']; ?>,
                <?php echo $revenue_percentages['combo_5day']; ?>
            ],
            colors: ['#ff6b6b', '#4ecdc4', '#45b7d1', '#ffa726']
        };

        // Vẽ biểu đồ tròn
        function drawPieChart() {
            const canvas = document.getElementById('revenueChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const centerX = canvas.width / 2;
            const centerY = canvas.height / 2;
            const radius = 140;

            // Clear canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            let currentAngle = -Math.PI / 2; // Bắt đầu từ góc 12 giờ

            revenueData.values.forEach((value, index) => {
                const sliceAngle = (value / 100) * 2 * Math.PI;

                // Vẽ phần tròn với gradient
                const gradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, radius);
                gradient.addColorStop(0, revenueData.colors[index]);
                gradient.addColorStop(1, revenueData.colors[index] + '88');

                ctx.beginPath();
                ctx.moveTo(centerX, centerY);
                ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
                ctx.closePath();
                ctx.fillStyle = gradient;
                ctx.fill();

                // Vẽ viền mỏng giữa các phần (không có viền ngoài)
                if (index < revenueData.values.length - 1) {
                    ctx.beginPath();
                    ctx.moveTo(centerX, centerY);
                    ctx.lineTo(
                        centerX + Math.cos(currentAngle + sliceAngle) * radius,
                        centerY + Math.sin(currentAngle + sliceAngle) * radius
                    );
                    ctx.strokeStyle = '#ffffff';
                    ctx.lineWidth = 2;
                    ctx.stroke();
                }

                // Vẽ text phần trăm
                if (value > 5) {
                    const textAngle = currentAngle + sliceAngle / 2;
                    const textX = centerX + Math.cos(textAngle) * (radius * 0.7);
                    const textY = centerY + Math.sin(textAngle) * (radius * 0.7);

                    ctx.fillStyle = '#ffffff';
                    ctx.font = 'bold 16px Arial';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.shadowColor = 'rgba(0, 0, 0, 0.5)';
                    ctx.shadowBlur = 2;
                    ctx.fillText(value + '%', textX, textY);
                    ctx.shadowBlur = 0;
                }

                currentAngle += sliceAngle;
            });
        }

        // Xử lý chuyển đổi tab
        function initTabSwitching() {
            const tabLinks = document.querySelectorAll('[data-tab]');
            const sections = {
                'tongquan': document.querySelector('.tongquan'),
                'khachhang': document.querySelector('.khachhang'),
                'monan': document.querySelector('.monan'),
                'doanhthu': document.querySelector('.doanhthu')
            };

            // Hiển thị tab đầu tiên (tổng quan) và đánh dấu active
            if (sections.tongquan) {
                sections.tongquan.style.display = 'block';
            }

            // Đánh dấu tab đầu tiên là active
            const firstTab = document.querySelector('[data-tab="tongquan"]');
            if (firstTab) {
                firstTab.classList.add('active');
            }

            tabLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const targetTab = link.getAttribute('data-tab');

                    // Ẩn tất cả sections
                    Object.values(sections).forEach(section => {
                        if (section) {
                            section.style.display = 'none';
                            section.style.opacity = '0';
                            section.style.transform = 'translateY(20px)';
                        }
                    });

                    // Hiển thị section được chọn với hiệu ứng
                    if (sections[targetTab]) {
                        sections[targetTab].style.display = 'block';

                        // Animate in
                        setTimeout(() => {
                            sections[targetTab].style.opacity = '1';
                            sections[targetTab].style.transform = 'translateY(0)';
                        }, 10);

                        // Nếu là tab doanh thu, vẽ biểu đồ
                        if (targetTab === 'doanhthu') {
                            setTimeout(() => {
                                drawPieChart();
                            }, 200);
                        }
                    }

                    // Cập nhật trạng thái active cho tab
                    tabLinks.forEach(l => l.classList.remove('active'));
                    link.classList.add('active');
                });
            });
        }

        // Khởi tạo khi DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            initTabSwitching();

            // Thiết lập transition cho sections
            const sections = document.querySelectorAll('.tongquan, .khachhang, .monan, .doanhthu');
            sections.forEach(section => {
                section.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';
            });

            // Hiển thị section đầu tiên
            const firstSection = document.querySelector('.tongquan');
            if (firstSection) {
                firstSection.style.opacity = '1';
                firstSection.style.transform = 'translateY(0)';
            }
        });
    </script>
    <script src="../js/popup.js"></script>
    <script src="../js/admin.js"></script>
</body>

</html>