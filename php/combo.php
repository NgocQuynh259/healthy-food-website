<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeline Thực Đọn" - Combo</title>
    <link rel="stylesheet" href="/CoSoloi/css/reset.css">
    <link rel="stylesheet" href="/CoSoloi/css/general.css">
    <link rel="stylesheet" href="/CoSoloi/css/combo.css">
    <link rel="stylesheet" href="/CoSoloi/css/menu.css">
    <link rel="stylesheet" href="/CoSoloi/css/index.css">
    <link rel="stylesheet" href="/CoSoloi/css/multiForm.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Styles for day actions */
        .day-actions {
            margin-top: 15px;
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .buy-day-btn {
            background-color: #28a745;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .buy-day-btn:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }



        /* Styles for timeline markers with numbers and checks */
        .timeline-marker {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
            z-index: 2;
        }

        .day-number,
        .day-check {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            background-color: #28a745;
            color: white;
            border-radius: 50%;
            font-weight: bold;
            font-size: 14px;
            margin-right: 10px;
        }

        .day-check {
            background-color: #17a2b8;
            font-size: 16px;
        }

        .day-text {
            font-weight: 600;
            color: #333;
        }

        /* Styles for completed marker */
        .timeline-marker.completed {
            background-color: #28a745;
            padding: 5px 10px;
            border-radius: 15px;
        }

        .timeline-marker.completed .day-check {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/header.php'; ?>
    
    <?php
    require_once __DIR__ . '/connect.php';

    // Kiểm tra đăng nhập
    if (!isset($_SESSION['makh']) || empty($_SESSION['makh'])) {
        echo '<script>alert("Vui lòng đăng nhập để sử dụng tính năng combo!"); window.location.href="index.php";</script>';
        exit;
    }

    $makh = $_SESSION['makh']; // Lấy mã khách hàng từ session

    // Lấy thông tin khách và tính TDEE
    $stmt = $conn->prepare("SELECT * FROM khachhang_suckhoe WHERE Makh = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $makh);
    $stmt->execute();
    $kh = $stmt->get_result()->fetch_assoc();

    if (!$kh) {
        echo '<script>alert("Không tìm thấy thông tin sức khỏe! Vui lòng cập nhật thông tin."); window.location.href="congcu.php";</script>';
        exit;
    }

    $w = $kh['weight'];
    $h = $kh['height'];
    $age = $kh['age'];
    $gender = strtolower($kh['gender']) === 'nu' ? 'female' : 'male';
    $act = floatval($kh['activity_level']);
    $goal = $kh['goal'];
    if ($gender === 'female') {
        $bmr = 655 + 9.6 * $w + 1.8 * $h - 4.7 * $age;
    } else {
        $bmr = 66 + 13.7 * $w + 5 * $h - 6.8 * $age;
    }
    $tdee = round($bmr * $act + ($goal === 'loseweight' ? -500 : ($goal === 'gainweight' ? 400 : 0)));

    // Lấy loại món
    $stmt = $conn->prepare("SELECT Maloai FROM suckhoe_loai WHERE Mathongtin = ?");
    $stmt->bind_param("i", $kh['id']);
    $stmt->execute();
    $maloaiArr = array_column($stmt->get_result()->fetch_all(MYSQLI_ASSOC), 'Maloai');
    
    // Nếu không có dữ liệu loại món, sử dụng tất cả loại món có sẵn
    if (empty($maloaiArr)) {
        $result = $conn->query("SELECT DISTINCT Maloai FROM sanpham WHERE Maloai IS NOT NULL");
        $maloaiArr = array_column($result->fetch_all(MYSQLI_ASSOC), 'Maloai');
    }

    // Lấy dị ứng
    $allergyArr = [];
    $stmt = $conn->prepare("SELECT Thanhphandiungid FROM khachhangdiung WHERE Makh = ?");
    $stmt->bind_param("s", $makh);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) $allergyArr[] = intval($r['Thanhphandiungid']);

    // Hàm lấy món ngẫu nhiên
    function getMeal($conn, array $maloaiArr, array $allergyArr, int $minCal, int $maxCal, array $excluded = [])
    {
        if (empty($maloaiArr)) return null;
        $inLoai = implode(',', array_map('intval', $maloaiArr));
        $notIn = '';
        if (!empty($allergyArr)) {
            $ai = implode(',', array_map('intval', $allergyArr));
            $notIn .= " AND m.Masp NOT IN (SELECT Masp FROM sanphamthanhphan WHERE Thanhphanid IN ($ai))";
        }
        if (!empty($excluded)) {
            $ex = implode(',', array_map('intval', $excluded));
            $notIn .= " AND m.Masp NOT IN ($ex)";
        }
        
        // Thử tìm món với calories trong khoảng chỉ định
        $sql = "SELECT * FROM sanpham m
            WHERE m.Maloai IN ($inLoai)
              AND m.Calories BETWEEN ? AND ?
              $notIn
            ORDER BY RAND() LIMIT 1";
        $st = $conn->prepare($sql);
        $st->bind_param("ii", $minCal, $maxCal);
        $st->execute();
        $result = $st->get_result()->fetch_assoc();
        
        // Nếu không tìm thấy, thử tìm món bất kỳ trong category
        if (!$result) {
            $sql = "SELECT * FROM sanpham m
                WHERE m.Maloai IN ($inLoai)
                  $notIn
                ORDER BY RAND() LIMIT 1";
            $st = $conn->prepare($sql);
            $st->execute();
            $result = $st->get_result()->fetch_assoc();
        }
        
        return $result ?: null;
    }

    // Hàm lấy món duy nhất
    function getUniqueMeal($conn, array $maloaiArr, array $allergyArr, int $minCal, int $maxCal, array &$excluded)
    {
        $maxTries = 10;
        while ($maxTries-- > 0) {
            $meal = getMeal($conn, $maloaiArr, $allergyArr, $minCal, $maxCal, $excluded);
            if (!$meal) {
                return null;
            }
            if (!in_array($meal['Masp'], $excluded, true)) {
                $excluded[] = $meal['Masp'];
                return $meal;
            }
        }
        return null;
    }

    // Hàm format giá tiền
    function formatPrice($price)
    {
        return number_format($price, 0, ',', '.') . 'đ';
    }

    // Map tên hiển thị
    $displayNames = [
        'sang'  => 'Sáng',
        'trua'  => 'Trưa',
        'toi'   => 'Tối',
        'snack' => 'Snack',
    ];

    function vnDay($dateString)
    {
        $en = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $vn = ['Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy', 'Chủ Nhật'];
        return str_replace($en, $vn, $dateString);
    }
    $currentDate = new DateTime();
    $days = [
        'day1' => [vnDay($currentDate->format('l, d/m/Y'))],
        'day3' => [],
        'day5' => []
    ];
    for ($i = 0; $i < 3; $i++) {
        $date = (clone $currentDate)->modify("+$i days");
        $days['day3'][] = vnDay($date->format('l, d/m/Y'));
    }
    for ($i = 0; $i < 5; $i++) {
        $date = (clone $currentDate)->modify("+$i days");
        $days['day5'][] = vnDay($date->format('l, d/m/Y'));
    }

    // Chuẩn bị dữ liệu
    $all_meals = [];

    // Khởi tạo combo_details trong session nếu chưa có
    if (!isset($_SESSION['combo_details'])) {
        $_SESSION['combo_details'] = [];
    }

    foreach ($days as $key => $list) {
        $all_meals[$key] = [];
        foreach ($list as $day) {
            $ex = [];
            $m1 = getUniqueMeal($conn, $maloaiArr, $allergyArr, 201, 300, $ex);
            $m2 = getUniqueMeal($conn, $maloaiArr, $allergyArr, 301, round($tdee * 0.6), $ex);
            $m3 = getUniqueMeal($conn, $maloaiArr, $allergyArr, 301, round($tdee * 0.5), $ex);
            $m4 = getUniqueMeal($conn, $maloaiArr, $allergyArr, 0, 200, $ex);

            $all_meals[$key][$day] = ['sang' => $m1, 'trua' => $m2, 'toi' => $m3, 'snack' => $m4];
        }

        // Tạo sẵn combo_details cho từng loại combo
        $combo_id = 'combo_' . $key . '_' . time();
        $total_price = 0;
        $meal_details = [];

        foreach ($all_meals[$key] as $day => $meals) {
            foreach ($meals as $type => $meal) {
                if ($meal) {
                    $meal_details[] = [
                        'id' => $meal['Masp'],
                        'name' => $meal['Tensp'],
                        'price' => floatval($meal['Giaban']),
                        'image' => $meal['Hinhanh'],
                        'type' => $type
                    ];
                    $total_price += floatval($meal['Giaban']);
                }
            }
        }

        $_SESSION['combo_details'][$combo_id] = [
            'name' => "Combo " . str_replace('day', '', $key) . " Ngày",
            'price' => $total_price,
            'image' => '/CoSoloi/assets/img/avt/combo2.jpg',
            'meals' => $meal_details,
            'combo_type' => $key
        ];
    }
    ?>
    <div class="background">
        <div class="overlay-text">
            <h1>HÃY TẠO NÊN BỮA ĂN MÀ BẠN YÊU THÍCH</h1>
        </div>
    </div>
    <div class="container">
        <div class="timeline-container">
            <div class="tab-wrap meal-tabs">
                <button class="tab active" data-tab="day1">1 Ngày</button>
                <button class="tab" data-tab="day3">3 Ngày</button>
                <button class="tab" data-tab="day5">5 Ngày</button>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
            </div>
            <div class="timeline day1">
                <?php foreach (['day1'] as $key): ?>
                    <?php foreach ($all_meals[$key] as $day => $meals): ?>
                        <div class="timeline-day1"><span></span>Ngày 1</div>
                        <div class="timeline-item day1 active">
                            <div class="timeline-content">
                                <div class="meal-list">
                                    <?php foreach ($meals as $type => $meal): ?>
                                        <div class="meal-item-compact" data-masp="<?= $meal['Masp'] ?? '' ?>">
                                            <?php if ($meal): ?>
                                                <img src="<?= htmlspecialchars($meal['Hinhanh']) ?>" class="meal-img" alt="<?= htmlspecialchars($meal['Tensp']) ?>">
                                                <div class="meal-label meal-<?= $type ?>">
                                                    <?= $displayNames[$type] ?>
                                                </div>
                                                <div class="meal-info">
                                                    <h3 class="meal-name"><?= htmlspecialchars($meal['Tensp']) ?></h3>
                                                    <span class="meal-price">Giá: <?= formatPrice($meal['Giaban']) ?></span>
                                                    <button class="change-meal-btn"
                                                        data-type="<?= $type ?>"
                                                        data-min="<?= floor($meal['Calories'] * 0.9) ?>"
                                                        data-max="<?= ceil($meal['Calories'] * 1.1) ?>">
                                                        Đổi món
                                                    </button>
                                                </div>
                                            <?php else: ?>
                                                <p>Không có món phù hợp!</p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
            <div class="timeline day3 hidden">
                <?php foreach (['day3'] as $key): ?>
                    <?php $dayIdx = 0; ?>
                    <?php foreach ($all_meals[$key] as $day => $meals): ?>
                        <div class="timeline-marker day3 hidden">
                            <span class="day-number"><?= $dayIdx + 1 ?></span>
                            <span class="day-check" style="display: none;">✓</span>
                            <span class="day-text">Ngày <?= $dayIdx + 1 ?></span>
                        </div>
                        <div class="timeline-item <?= $dayIdx % 2 === 0 ? 'left' : 'right' ?> day3 hidden">
                            <div class="timeline-content">
                                <div class="meal-list">
                                    <?php foreach ($meals as $type => $meal): ?>
                                        <div class="meal-item-compact" data-masp="<?= $meal['Masp'] ?? '' ?>">
                                            <?php if ($meal): ?>
                                                <img src="<?= htmlspecialchars($meal['Hinhanh']) ?>" class="meal-img" alt="<?= htmlspecialchars($meal['Tensp']) ?>">
                                                <div class="meal-label meal-<?= $type ?>">
                                                    <?= $displayNames[$type] ?>
                                                </div>
                                                <div class="meal-info">
                                                    <h3 class="meal-name"><?= htmlspecialchars($meal['Tensp']) ?></h3>
                                                    <span class="meal-price">Giá: <?= formatPrice($meal['Giaban']) ?></span>
                                                    <button class="change-meal-btn"
                                                        data-type="<?= $type ?>"
                                                        data-min="<?= floor($meal['Calories'] * 0.9) ?>"
                                                        data-max="<?= ceil($meal['Calories'] * 1.1) ?>">
                                                        Đổi món
                                                    </button>
                                                </div>
                                            <?php else: ?>
                                                <p>Không có món phù hợp!</p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="day-actions">
                                    <button class="buy-day-btn" data-day="Ngày <?= $dayIdx + 1 ?>" data-day-index="<?= $dayIdx ?>">Mua</button>
                                </div>
                            </div>
                        </div>
                        <?php $dayIdx++; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
            <div class="timeline day5 hidden">
                <?php foreach (['day5'] as $key): ?>
                    <?php $dayIdx = 0; ?>
                    <?php foreach ($all_meals[$key] as $day => $meals): ?>
                        <div class="timeline-marker day5 hidden">
                            <span class="day-number"><?= $dayIdx + 1 ?></span>
                            <span class="day-check" style="display: none;">✓</span>
                            <span class="day-text">Ngày <?= $dayIdx + 1 ?></span>
                        </div>
                        <div class="timeline-item <?= $dayIdx % 2 === 0 ? 'left' : 'right' ?> day5 hidden">
                            <div class="timeline-content">
                                <div class="meal-list">
                                    <?php foreach ($meals as $type => $meal): ?>
                                        <div class="meal-item-compact" data-masp="<?= $meal['Masp'] ?? '' ?>">
                                            <?php if ($meal): ?>
                                                <img src="<?= htmlspecialchars($meal['Hinhanh']) ?>" class="meal-img" alt="<?= htmlspecialchars($meal['Tensp']) ?>">
                                                <div class="meal-label meal-<?= $type ?>">
                                                    <?= $displayNames[$type] ?>
                                                </div>
                                                <div class="meal-info">
                                                    <h3 class="meal-name"><?= htmlspecialchars($meal['Tensp']) ?></h3>
                                                    <span class="meal-price">Giá: <?= formatPrice($meal['Giaban']) ?></span>
                                                    <button class="change-meal-btn"
                                                        data-type="<?= $type ?>"
                                                        data-min="<?= floor($meal['Calories'] * 0.9) ?>"
                                                        data-max="<?= ceil($meal['Calories'] * 1.1) ?>">
                                                        Đổi món
                                                    </button>
                                                </div>
                                            <?php else: ?>
                                                <p>Không có món phù hợp!</p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="day-actions">
                                    <button class="buy-day-btn" data-day="Ngày <?= $dayIdx + 1 ?>" data-day-index="<?= $dayIdx ?>">Mua</button>
                                </div>
                            </div>
                        </div>
                        <?php $dayIdx++; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
            <div class="btn-buy-combo">
                <button class="buy-combo-btn">Mua Combo Ngay</button>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/footer.php'; ?>

    <script>
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                const target = tab.dataset.tab;
                document.querySelectorAll('.timeline').forEach(t => t.classList.add('hidden'));
                document.querySelector('.timeline.' + target).classList.remove('hidden');
                document.querySelectorAll('.timeline.' + target + ' .timeline-item, .timeline.' + target + ' .timeline-marker').forEach(e => {
                    e.classList.remove('hidden');
                    if (e.classList.contains('timeline-item')) {
                        e.classList.add('active');
                    }
                });

                // Reset giao diện markers và load trạng thái từ database
                resetAllMarkers(target);
                loadTimelineProgress();
            });
        });

        // Function để reset markers về trạng thái ban đầu (chỉ giao diện)
        function resetAllMarkers(tabName) {
            const markers = document.querySelectorAll(`.timeline-marker.${tabName}`);
            markers.forEach(marker => {
                const numberSpan = marker.querySelector('.day-number');
                const checkSpan = marker.querySelector('.day-check');
                const dayText = marker.querySelector('.day-text');

                if (numberSpan && checkSpan && dayText) {
                    numberSpan.style.display = 'inline-flex';
                    checkSpan.style.display = 'none';
                    dayText.style.display = 'block';
                    marker.classList.remove('completed');
                }
            });

            const timeline = document.querySelector(`.timeline.${tabName}`);
            if (timeline) {
                timeline.classList.remove('has-completed');
                for (let i = 1; i <= 5; i++) {
                    timeline.classList.remove(`progress-${i}`);
                }
            }

            // Không reset nút mua ở đây, sẽ được xử lý bởi loadTimelineProgress
        }

        // Function để cập nhật màu timeline line và progress
        function updateTimelineColor(tabName) {
            const timeline = document.querySelector(`.timeline.${tabName}`);
            const completedMarkers = document.querySelectorAll(`.timeline-marker.${tabName}.completed`);
            const totalMarkers = document.querySelectorAll(`.timeline-marker.${tabName}`).length;

            if (timeline) {
                timeline.classList.remove('has-completed');
                for (let i = 1; i <= 5; i++) {
                    timeline.classList.remove(`progress-${i}`);
                }

                const completedCount = completedMarkers.length;
                if (completedCount > 0) {
                    timeline.classList.add('has-completed');
                    timeline.classList.add(`progress-${completedCount}`);
                }
            }
        }

        // Function để hoàn thành tất cả các ngày trên timeline
        function completeAllDays(tabName) {
            const markers = document.querySelectorAll(`.timeline-marker.${tabName}`);
            const buyBtns = document.querySelectorAll(`.timeline.${tabName} .buy-day-btn`);

            updateTimelineColor(tabName);
        }

        document.querySelectorAll('.change-meal-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent event bubbling to meal item
                const card = btn.closest('.meal-item-compact');
                const mealList = btn.closest('.meal-list');
                const excluded = [];
                mealList.querySelectorAll('.meal-item-compact').forEach(c => {
                    if (c !== card && c.dataset.masp) excluded.push(c.dataset.masp);
                });
                const type = btn.dataset.type;
                const min = (type === 'sang') ? 201 : btn.dataset.min;
                const max = (type === 'sang') ? 300 : btn.dataset.max;
                const params = new URLSearchParams({
                    type,
                    min,
                    max,
                    maloai: "<?= implode(',', $maloaiArr) ?>",
                    allergy: "<?= implode(',', $allergyArr) ?>",
                    excluded: excluded.join(',')
                });
                fetch('TDEE.php?' + params)
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP ${response.status}`);
                        return response.json();
                    })
                    .then(res => {
                        if (!res.success) {
                            return alert(res.message || 'Không có món nào phù hợp!');
                        }
                        const m = res.meal;
                        card.querySelector('.meal-img').src = m.image;
                        card.querySelector('.meal-name').textContent = m.name;
                        card.querySelector('.meal-price').textContent = 'Giá: ' + m.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + 'đ';
                        card.dataset.masp = m.id;
                        btn.dataset.min = Math.floor(m.kcal * 0.9);
                        btn.dataset.max = Math.ceil(m.kcal * 1.1);
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Lỗi khi đổi món: ' + err.message);
                    });
            });
        });

        // Xử lý mua từng ngày
        document.querySelectorAll('.buy-day-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const dayIndex = btn.dataset.dayIndex;
                const dayName = btn.dataset.day;
                const timelineItem = btn.closest('.timeline-item');
                const tabName = timelineItem.closest('.timeline').classList[1]; // day3 hoặc day5
                const meals = [];

                timelineItem.querySelectorAll('.meal-item-compact').forEach(item => {
                    const masp = item.dataset.masp;
                    if (masp) {
                        meals.push({
                            id: masp,
                            name: item.querySelector('.meal-name').textContent,
                            price: parseFloat(item.querySelector('.meal-price').textContent.replace(/[^0-9]/g, '')),
                            image: item.querySelector('.meal-img').src
                        });
                    }
                });

                if (!meals.length) {
                    alert('Không có món nào trong ngày này!');
                    return;
                }

                // Gửi request mua ngày đơn lẻ
                fetch('add_combo_to_cart_simple.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            combo_type: 'single_day',
                            day_name: dayName,
                            day_index: dayIndex,
                            meals: JSON.stringify(meals),
                            action: 'buy_single_day',
                            tab_name: tabName
                        })
                    })
                    .then(response => {
                        console.log('Single day response status:', response.status);
                        return response.text();
                    })
                    .then(text => {
                        console.log('Single day response text:', text);
                        let res;
                        try {
                            res = JSON.parse(text);
                        } catch (e) {
                            console.error('JSON parse error:', e);
                            alert('Lỗi server: ' + text);
                            return;
                        }

                        if (res.error) {
                            alert('Lỗi: ' + res.error);
                            return;
                        }

                        // Cập nhật localStorage
                        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                        cart.push({
                            id: res.combo_id,
                            name: "Combo " + dayName,
                            price: meals.reduce((sum, meal) => sum + meal.price, 0),
                            image: '/CoSoloi/assets/img/avt/combo.jpg',
                            quantity: 1,
                            type: 'single_day',
                            tab_name: tabName
                        });
                        localStorage.setItem('cart', JSON.stringify(cart));
                        updateCartBadge();

                        // Cập nhật tiến độ combo
                        updateComboProgressData(tabName, 'add_day', res.combo_id);

                        alert(res.message || 'Đã thêm combo ' + dayName + ' vào giỏ hàng!');

                        // Kiểm tra nếu đã mua đủ ngày
                        checkComboCompletion(tabName);
                    })
                    .catch(err => {
                        console.error('Single day fetch error:', err);
                        alert('Lỗi khi thêm combo vào giỏ hàng: ' + err.message);
                    });
            });
        });

        document.querySelector('.buy-combo-btn').addEventListener('click', () => {
            const activeTab = document.querySelector('.tab.active').dataset.tab;
            const timeline = document.querySelector('.timeline.' + activeTab);
            const meals = [];

            timeline.querySelectorAll('.meal-item-compact').forEach(item => {
                const masp = item.dataset.masp;
                if (masp) {
                    meals.push({
                        id: masp,
                        name: item.querySelector('.meal-name').textContent,
                        price: parseFloat(item.querySelector('.meal-price').textContent.replace(/[^0-9]/g, '')),
                        image: item.querySelector('.meal-img').src
                    });
                }
            });

            if (!meals.length) {
                alert('Không có món nào trong combo!');
                return;
            }

            fetch('add_combo_to_cart_simple.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        combo_type: activeTab,
                        meals: JSON.stringify(meals),
                        action: 'buy_full_combo',
                        tab_name: activeTab
                    })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.text();
                })
                .then(text => {
                    console.log('Response text:', text);
                    let res;
                    try {
                        res = JSON.parse(text);
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        alert('Lỗi server: ' + text);
                        return;
                    }

                    if (res.error) {
                        if (res.error.includes('đăng nhập')) {
                            showLoginAlert('Bạn cần đăng nhập để mua combo!');
                        } else {
                            alert('Lỗi: ' + res.error);
                        }
                        return;
                    }

                    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                    cart.push({
                        id: res.combo_id,
                        name: "Combo " + activeTab.replace('day', '') + " Ngày",
                        price: meals.reduce((sum, meal) => sum + meal.price, 0),
                        image: '/CoSoloi/assets/img/avt/combo2.jpg',
                        quantity: 1,
                        type: 'combo',
                        tab_name: activeTab
                    });
                    localStorage.setItem('cart', JSON.stringify(cart));
                    updateCartBadge();

                    // Cập nhật tiến độ combo
                    updateComboProgressData(activeTab, 'add_combo', res.combo_id);

                    alert(res.message || 'Đã thêm Combo ' + activeTab.replace('day', '') + ' Ngày vào giỏ hàng!');

                    // Cập nhật giao diện
                    completeAllDays(activeTab);
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    alert('Lỗi khi thêm combo vào giỏ hàng: ' + err.message);
                });
        });

        function updateCartBadge() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const totalCount = cart.reduce((sum, item) => sum + (item.quantity || 0), 0);
            const badge = document.getElementById('cartCount');
            if (badge) {
                badge.textContent = totalCount;
                badge.style.display = totalCount > 0 ? 'inline-block' : 'none';
            }
        }

        // Kiểm tra hoàn thành combo và reset nếu đủ ngày
        function checkComboCompletion(tabName) {
            fetch('get_timeline_progress.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.timeline_progress && data.timeline_progress[tabName]) {
                        const progress = data.timeline_progress[tabName];
                        const totalDays = parseInt(tabName.replace('day', ''));
                        const paidDays = progress.paid;

                        if (paidDays >= totalDays) {
                            // Đã mua đủ ngày, reset trạng thái
                            resetComboProgress(tabName);
                            updateTimelineColor(tabName);

                            // Gửi request để reset tiến độ trong database
                            fetch('reset_combo_progress.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        makh: '<?= $makh ?>',
                                        combo_type: tabName
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        console.log('Combo progress reset in database');
                                        alert('Chúc mừng! Bạn đã hoàn thành combo ' + tabName.replace('day', '') + ' ngày. Tiến độ đã được reset.');
                                    } else {
                                        console.error('Error resetting combo progress:', data.error);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error resetting combo progress:', error);
                                });
                        } else {
                            // Cập nhật giao diện timeline
                            updateTimelineColor(tabName);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error checking combo completion:', error);
                });
        }

        // Load timeline progress khi trang được tải
        document.addEventListener('DOMContentLoaded', function() {
            loadTimelineProgress();

            window.addEventListener('cartUpdated', function() {
                loadTimelineProgress();
            });

            setInterval(loadTimelineProgress, 10000);
        });

        // Hàm cập nhật dữ liệu tiến độ combo
        function updateComboProgressData(comboType, action, comboId = '') {
            const comboData = JSON.parse(localStorage.getItem('comboProgress') || '{}');

            if (!comboData[comboType]) {
                comboData[comboType] = {
                    total: 0,
                    completed: 0
                };
            }

            if (action === 'add_combo') {
                const days = parseInt(comboType.replace('day', ''));
                comboData[comboType].total += days;
                comboData[comboType].completed += days;
            } else if (action === 'add_day') {
                comboData[comboType].total += 1;
                comboData[comboType].completed += 1;
            }

            localStorage.setItem('comboProgress', JSON.stringify(comboData));

            fetch('update_combo_progress.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        makh: '<?= $makh ?>',
                        combo_type: comboType,
                        action: action,
                        combo_id: comboId || 'combo_' + Date.now()
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Combo progress updated in database');
                    } else {
                        console.error('Error updating combo progress:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error calling combo progress API:', error);
                });

            window.dispatchEvent(new StorageEvent('storage', {
                key: 'comboProgress',
                newValue: JSON.stringify(comboData)
            }));
        }

        // Hàm reset tiến độ combo
        function resetComboProgress(tabName) {
            const comboData = JSON.parse(localStorage.getItem('comboProgress') || '{}');
            if (comboData[tabName]) {
                comboData[tabName].total = 0;
                comboData[tabName].completed = 0;
                localStorage.setItem('comboProgress', JSON.stringify(comboData));
            }

            const buyBtns = document.querySelectorAll(`.timeline.${tabName} .buy-day-btn`);
        }

        // Hàm load trạng thái timeline từ database
        function loadTimelineProgress() {
            fetch('get_timeline_progress.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(text => {
                    if (!text.trim().startsWith('{')) {
                        console.error('Invalid JSON response:', text);
                        return {
                            success: false,
                            error: 'Server returned invalid response',
                            timeline_progress: {
                                day1: {
                                    total: 0,
                                    paid: 0
                                },
                                day3: {
                                    total: 0,
                                    paid: 0
                                },
                                day5: {
                                    total: 0,
                                    paid: 0
                                }
                            },
                            cart_combos: {
                                day1: 0,
                                day3: 0,
                                day5: 0
                            }
                        };
                    }

                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('JSON parse error:', e, 'Response:', text);
                        return {
                            success: false,
                            error: 'JSON parse error',
                            timeline_progress: {
                                day1: {
                                    total: 0,
                                    paid: 0
                                },
                                day3: {
                                    total: 0,
                                    paid: 0
                                },
                                day5: {
                                    total: 0,
                                    paid: 0
                                }
                            },
                            cart_combos: {
                                day1: 0,
                                day3: 0,
                                day5: 0
                            }
                        };
                    }
                })
                .then(data => {
                    if (data.success && data.timeline_progress && data.cart_combos) {
                        updateTimelineFromDatabase(data.timeline_progress, data.cart_combos);
                    } else {
                        console.warn('Timeline progress not loaded:', data.error || 'Unknown error');
                        if (data.timeline_progress && data.cart_combos) {
                            updateTimelineFromDatabase(data.timeline_progress, data.cart_combos);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading timeline progress:', error);
                    updateTimelineFromDatabase({
                        day1: {
                            total: 0,
                            paid: 0
                        },
                        day3: {
                            total: 0,
                            paid: 0
                        },
                        day5: {
                            total: 0,
                            paid: 0
                        }
                    }, {
                        day1: 0,
                        day3: 0,
                        day5: 0
                    });
                });
        }

        // Hàm cập nhật timeline dựa trên dữ liệu database
        function updateTimelineFromDatabase(timelineProgress, cartCombos) {
            ['day1', 'day3', 'day5'].forEach(tabName => {
                const progress = timelineProgress[tabName] || {
                    total: 0,
                    paid: 0
                };
                const cartCount = cartCombos[tabName] || 0;

                const paidDays = progress.paid;
                const totalDays = parseInt(tabName.replace('day', ''));
                const completedDays = paidDays;

                const markers = document.querySelectorAll(`.timeline-marker.${tabName}`);
                markers.forEach((marker, index) => {
                    const dayIndex = index + 1;
                    const numberSpan = marker.querySelector('.day-number');
                    const checkSpan = marker.querySelector('.day-check');
                    const dayText = marker.querySelector('.day-text');

                    const timelineItem = marker.parentNode.querySelector('.timeline-item');
                    const buyBtn = timelineItem ? timelineItem.querySelector('.buy-day-btn') : null;

                    if (dayIndex <= completedDays) {
                        if (numberSpan && checkSpan && dayText) {
                            numberSpan.style.display = 'none';
                            dayText.style.display = 'none';
                            checkSpan.style.display = 'inline-flex';
                            marker.classList.add('completed');
                        }

                        if (buyBtn) {
                            // Không thay đổi trạng thái nút
                        }
                    } else {
                        if (numberSpan && checkSpan && dayText) {
                            numberSpan.style.display = 'inline-flex';
                            checkSpan.style.display = 'none';
                            dayText.style.display = 'block';
                            marker.classList.remove('completed');
                        }

                        if (buyBtn) {
                            // Giữ nguyên trạng thái nút
                        }
                    }
                });

                if (completedDays > 0) {
                    updateTimelineColor(tabName);
                }
            });
        }

        document.addEventListener("DOMContentLoaded", () => {
            const items = document.querySelectorAll(".timeline-item, .timeline-marker");

            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("visible");
                    }
                });
            }, {
                threshold: 0.2
            });

            items.forEach(item => observer.observe(item));
        });
    </script>

    <script>
        // Hàm hiển thị thông báo yêu cầu đăng nhập
        function showLoginAlert(message) {
            const alertHtml = `
                <div id="loginAlert" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,0.8);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 10000;
                ">
                    <div style="
                        background: white;
                        padding: 30px;
                        border-radius: 15px;
                        text-align: center;
                        max-width: 400px;
                        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                    ">
                        <i class="fas fa-lock" style="font-size: 3rem; color: #ff6b6b; margin-bottom: 20px;"></i>
                        <h3 style="margin-bottom: 15px; color: #333;">${message}</h3>
                        <p style="color: #666; margin-bottom: 25px;">Vui lòng đăng nhập để tiếp tục</p>
                        <button onclick="triggerLogin()" style="
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            color: white;
                            padding: 12px 25px;
                            border: none;
                            border-radius: 25px;
                            cursor: pointer;
                            margin-right: 10px;
                        ">Đăng nhập</button>
                        <button onclick="closeLoginAlert()" style="
                            background: #ccc;
                            color: #333;
                            padding: 12px 25px;
                            border: none;
                            border-radius: 25px;
                            cursor: pointer;
                        ">Đóng</button>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', alertHtml);
        }

        function triggerLogin() {
            closeLoginAlert();
            // Trigger sự kiện đăng nhập từ header
            const loginBtn = document.querySelector('.navbar_log_in');
            if (loginBtn) {
                loginBtn.click();
            }
        }

        function closeLoginAlert() {
            const alert = document.getElementById('loginAlert');
            if (alert) {
                alert.remove();
            }
        }

        // Thêm event listener cho click vào món ăn để hiển thị chi tiết
        document.addEventListener('DOMContentLoaded', function() {
            // Add click event to meal images only (not the entire item)
            document.querySelectorAll('.meal-img').forEach(img => {
                img.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent event bubbling
                    const mealItem = this.closest('.meal-item-compact');
                    const masp = mealItem.getAttribute('data-masp');
                    if (masp) {
                        showProductDetail(masp);
                    }
                });
            });
        });

        function showProductDetail(productId) {
            fetch(`get_product_detail.php?id=${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Không thể tải thông tin món ăn');
                        return;
                    }
                    
                    const popup = document.createElement('div');
                    popup.className = 'popup_sanpham';
                    popup.innerHTML = `
                        <div class="popup-container">
                            <button class="close-popup" onclick="closeProductDetail()">×</button>
                            <div class="popup-header">
                                <h2 class="dish-title">${data.Tensp}</h2>
                            </div>
                            <div class="popup-body">
                                <div class="column-left">
                                    <img src="${data.Hinhanh}" alt="${data.Tensp}" class="dish-image">
                                    <p class="dish-price">Giá: ${data.Giaban ? new Intl.NumberFormat('vi-VN').format(data.Giaban) + 'đ' : 'Giá liên hệ'}</p>
                                </div>
                                <div class="column-right">
                                    <div class="info-section">
                                        <h3>Dinh Dưỡng <small>(mỗi khẩu phần)</small></h3>
                                        <ul class="nutrition-list">
                                            <li><span>Calo</span> <strong>${data.Calories || 0}kcal</strong></li>
                                            <li><span>Đạm</span> <strong>${data.Protein || 0}g</strong></li>
                                            <li><span>Tổng Béo</span> <strong>${data.Fat || 0}g</strong></li>
                                            <li><span>Carbs</span> <strong>${data.Carbs || 0}g</strong></li>
                                            <li><span>Chất xơ</span> <strong>${data.Fiber || 0}g</strong></li>
                                            <li><span>Đường</span> <strong>${data.Sugar || 0}g</strong></li>
                                        </ul>
                                    </div>
                                    <div class="info-section">
                                        <h3>Về Món Ăn</h3>
                                        <p class="about-dish-text">${data.Mota || 'Món ăn này được chế biến với những nguyên liệu tươi ngon, đảm bảo dinh dưỡng và hương vị tuyệt vời.'}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    popup.style.display = 'flex';
                    document.body.appendChild(popup);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi tải thông tin món ăn');
                });
        }

        function closeProductDetail() {
            const popup = document.querySelector('.popup_sanpham');
            if (popup) {
                popup.remove();
            }
        }

        // Close popup when clicking overlay
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('popup_sanpham')) {
                closeProductDetail();
            }
        });
    </script>

    <style>
        /* Sử dụng CSS popup giống menu */
        .popup_sanpham {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(2px);
            z-index: 999;
        }

        /* ===== Popup chính ===== */
        .popup-container {
            position: relative;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            max-width: 900px;
            width: 90%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .popup-body {
            display: flex;
            flex-direction: row;
        }

        /* Hiệu ứng xuất hiện */
        @keyframes fadeIn {
            from {
                transform: scale(0.95);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Ten mon an */
        .popup-header {
            width: 100%;
            background-color: #fdfdfd;
            padding: 20px 32px;
            border-bottom: 1px solid #eee;
            text-align: center;
            font-size: 28px;
        }

        .popup-header .dish-title {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            color: #333;
        }

        /* ----- Cột Trái ----- */
        .column-left {
            padding: 28px;
            background-color: #fff;
            flex: 1;
        }

        .dish-image {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
            object-fit: cover;
            max-height: 300px;
        }

        .dish-price {
            font-size: 22px;
            font-weight: 600;
            color: #2c3e50;
            margin: 15px 0;
        }

        /* ----- Cột Phải ----- */
        .column-right {
            flex: 1.2;
            padding: 28px;
            background-color: #fafafa;
            border-left: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .info-section h3 {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
            position: relative;
        }

        .info-section h3 small {
            font-size: 18px;
            color: #2c3e50;
        }

        .info-section h3::after {
            content: "";
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 50px;
            height: 2px;
            background-color: #2e7d4f;
        }

        .nutrition-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nutrition-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f5f5f5;
            font-size: 15px;
        }

        .nutrition-list li:last-child {
            border-bottom: none;
        }

        .nutrition-list span {
            color: #555;
            font-size: 17px;
        }

        .nutrition-list strong {
            color: #333;
            font-weight: 500;
            font-size: 18px;
        }

        .about-dish-text {
            color: #555;
            line-height: 1.5;
            font-size: 17px;
        }

        .close-popup {
            position: absolute;
            top: 16px;
            right: 20px;
            font-size: 26px;
            background: none;
            border: none;
            color: #aaa;
            cursor: pointer;
            z-index: 1001;
            transition: color 0.2s;
        }

        .close-popup:hover {
            color: #333;
        }

        /* Make meal items clickable but without hover scale */
        .meal-item-compact .meal-img {
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .meal-item-compact .meal-img:hover {
            opacity: 0.9;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .popup-body {
                flex-direction: column;
            }

            .column-right {
                border-left: none;
                border-top: 1px solid #e0e0e0;
            }

            .popup-container {
                width: 95%;
                max-height: 90vh;
                overflow-y: auto;
            }
        }
    </style>

</body>

</html>