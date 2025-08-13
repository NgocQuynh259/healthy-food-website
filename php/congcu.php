<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/CoSo/css/reset.css">
    <link rel="stylesheet" href="/CoSo/css/general.css">
    <link rel="stylesheet" href="/CoSo/css/index.css">
    <link rel="stylesheet" href="/CoSo/css/congcu.css">
    <link rel="stylesheet" href="/CoSo/css/multiForm.css">
    <!-- <link rel="stylesheet" href="/CoSo/css/menu.css"> -->
    <title>Công Cụ Tính Các Chỉ Số Sức Khoẻ</title>
    <style>
        .congcu_title {
            font-size: 4rem;
            font-family: Play;
            padding: 40px 0 20px;
            color: var(--color-letter);
            text-align: center;
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .tab {
            padding: 10px 20px;
            margin: 5px;
            background-color: #eee;
            border-radius: 5px;
            cursor: pointer;
        }

        .tab.active {
            background-color: green;
            color: white;
        }

        .tab-content {
            display: none;
            padding: 20px;
            margin: auto;
        }

        .tab-content.active {
            display: block;
        }

        .bmi-form {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .bmi-form label {
            display: block;
            margin-top: 18px;
            margin-bottom: 5px;
        }

        .bmi-form input,
        .bmi-form select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .bmi-form button {
            background-color: green;
            color: white;
            padding: 10px 20px;
            border: none;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .result {
            margin-top: 15px;
            font-weight: bold;
            white-space: pre-line;
            color: var(--color-letter);
        }

        .bmi-info {
            background-color: #f8fef8;
            padding: 20px;
            border-left: 6px solid #4caf50;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 18px;
            line-height: 1.6;
        }

        .bmi-info h3 {
            color: #2e7d32;
            margin-top: 0;
        }

        .bmi-info h4 {
            color: #388e3c;
            margin-top: 20px;
            margin-bottom: 8px;
        }

        .bmi-info ul,
        .bmi-info ol {
            list-style-type: none;
            padding-left: 0;
        }


        .bmi-info li {
            position: relative;
            padding-left: 25px;
            margin-bottom: 10px;
        }


        .bmi-info li::before {
            content: '✓';
            position: absolute;
            left: 0;
            top: 0;

            color: #4caf50;
            font-weight: bold;
            font-size: 18px;
        }

        .bmi-form label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        .img_bmi img {
            width: 80%;
            height: auto;
            margin: 20px 150px;
        }

        .img_chuan img {
            width: 50%;
            height: auto;
            margin: 20px 250px;
        }

        .img_tdee img {
            width: 60%;
            height: auto;
            margin: 20px 280px;
        }
    </style>
</head>

<body>
    <?php include_once 'header.php'; ?>

    <h2 class="congcu_title">Công Cụ Tính Các Chỉ Số Sức Khoẻ</h2>

    <div class="container">
        <div class="tabs">
            <div class="tab active" data-tab="bmi">Tính BMI</div>
            <div class="tab" data-tab="ideal">Cân Nặng Chuẩn</div>
            <div class="tab" data-tab="bmr">BMR & TDEE</div>
        </div>

        <!-- Tab 1: BMI -->
        <div class="tab-content active" id="bmi">
            <div class="bmi-form">
                <label>Chiều cao (cm):</label>
                <input type="number" id="height" placeholder="Nhập chiều cao">

                <label>Cân nặng (kg):</label>
                <input type="number" id="weight" placeholder="Nhập cân nặng">

                <button onclick="calculateBMI()">Tính BMI</button>
                <div class="result" id="bmi-result"></div>
            </div>
            <div class="img_bmi"><img src="../assets/img/avt/BMI.png" alt=""></div>
            <div class="bmi-info">
                <h3>Chỉ số BMI là gì?</h3>
                <p>
                    BMI hay còn gọi là chỉ số khối cơ thể, là một trong những chỉ số quan trọng để đánh giá tình trạng
                    sức
                    khỏe của con người. BMI là tỉ lệ giữa cân nặng và chiều cao của một người. Cách tính BMI thường mà
                    mọi
                    người thường sử dụng là: chia cân nặng (kg) cho bình phương chiều cao (m).
                </p>
                <p>
                    Cách tính BMI rất dễ dàng và nhanh chóng, không yêu cầu kiến thức chuyên môn cao, tuy nhiên việc
                    hiểu và
                    áp dụng đúng cách tính BMI là một câu chuyện khác. Trong bài viết này, chúng ta sẽ tìm hiểu chi tiết
                    về
                    cách tính BMI và cách áp dụng nó vào đánh giá sức khỏe.
                </p>

                <h4>Cách tính BMI</h4>
                <p>
                    Công thức và cách tính BMI khá đơn giản, bạn chỉ cần chia cân nặng (kg) cho bình phương chiều cao
                    (m):<br>
                    <strong>BMI = cân nặng (kg) / (chiều cao (m))<sup>2</sup></strong>
                </p>
                <p>
                    Ví dụ: Nếu bạn cao 1,7m và nặng 65kg, thì BMI của bạn sẽ được tính như sau:<br>
                    BMI = 65 / (1.7)<sup>2</sup> = 22.5
                </p>

                <h4>Đánh giá chỉ số BMI của bạn</h4>
                <ul>
                    <li>Dưới 18.5: Thiếu cân</li>
                    <li>Từ 18.5 đến 24.9: Bình thường</li>
                    <li>Từ 25 đến 29.9: Thừa cân</li>
                    <li>Từ 30 đến 34.9: Béo phì độ I</li>
                    <li>Từ 35 đến 39.9: Béo phì độ II</li>
                    <li>Trên 40: Béo phì độ III</li>
                </ul>

                <p>
                    Tuy nhiên, chỉ số BMI chỉ là một phần trong quá trình đánh giá tình trạng sức khỏe của con người. Để
                    đánh giá một cách chính xác hơn, chúng ta cần kết hợp với các chỉ số khác như vòng eo, vòng bụng, tỷ
                    lệ
                    mỡ cơ thể, tỷ lệ cơ và mỡ cơ thể, huyết áp, đường huyết, lipid máu, chức năng tim mạch, phổi, não
                    bộ,...
                </p>

                <h4>Tại sao chỉ số BMI quan trọng?</h4>
                <p>
                    BMI là chỉ số đơn giản, dễ đo và tính toán, có thể áp dụng cho mọi lứa tuổi và giới tính. Chỉ số này
                    giúp phát hiện sớm nguy cơ mắc các bệnh liên quan đến cân nặng như tiểu đường, tim mạch, huyết áp
                    cao,
                    ung thư, xương khớp...
                </p>
                <p>
                    Tuy nhiên, BMI không phản ánh đúng thể trạng của vận động viên hoặc người cao tuổi vì không phân
                    biệt
                    được tỷ lệ cơ và mỡ.
                </p>

                <h4>Cách giảm chỉ số BMI</h4>
                <ol>
                    <li>Tập trung thay đổi lối sống lành mạnh</li>
                    <li>Ăn ít calo hơn nhưng vẫn đủ chất</li>
                    <li>Tập thể dục đều đặn ít nhất 30 phút/ngày</li>
                    <li>Điều chỉnh khẩu phần ăn hợp lý</li>
                </ol>

                <h4>Cách tăng chỉ số BMI</h4>
                <ol>
                    <li>Ăn nhiều calo hơn mức tiêu hao</li>
                    <li>Tăng cường tập luyện để phát triển cơ bắp</li>
                    <li>Bổ sung thực phẩm giàu chất đạm và dinh dưỡng</li>
                    <li>Uống đủ nước và chia nhỏ bữa ăn trong ngày</li>
                </ol>
            </div>

        </div>

        <!-- Tab 2: Ideal Weight -->
        <div class="tab-content" id="ideal">
            <div class="bmi-form">
                <label>Chiều cao (cm):</label>
                <input type="number" id="ideal-height" placeholder="Nhập chiều cao">

                <label>Cân nặng hiện tại (kg):</label>
                <input type="number" id="ideal-current-weight" placeholder="Nhập cân nặng hiện tại">

                <label>Giới tính:</label>
                <select id="ideal-gender">
                    <option value="nam">Nam</option>
                    <option value="nu">Nữ</option>
                </select>

                <button onclick="calculateIdealWeight()">Tính cân nặng chuẩn</button>
                <div class="result" id="ideal-result"></div>
            </div>
            <div class="img_chuan"><img src="../assets/img/avt/can-nang-chuan.jpg" alt=""></div>
            <div class="bmi-info">
                <h3>Cân nặng chuẩn là gì?</h3>
                <p>
                    Cân nặng là một trong những chỉ số quan trọng để đánh giá sức khỏe của một người. Tuy nhiên, cách
                    tính
                    cân nặng chuẩn không phải là điều đơn giản.
                </p>
                <p>
                    Cân nặng chuẩn là một chỉ số được tính toán dựa trên một số yếu tố như chiều cao, giới tính, độ tuổi
                    và
                    cơ thể con người. Có rất nhiều công thức như BMI, BMR, TDEE... Tuy nhiên, cách phổ biến là dùng công
                    thức Broca.
                </p>

                <h4>Công thức Broca</h4>
                <p><strong>
                        • Nam: (Chiều cao (cm) – 100) × 0.9<br>
                        • Nữ: (Chiều cao (cm) – 100) × 0.85
                    </strong></p>
                <p>Ví dụ: Nam cao 175cm → (175 - 100) × 0.9 = 67.5kg</p>
                <p>Nữ cao 160cm → (160 - 100) × 0.85 = 51kg</p>

                <p>
                    Công thức Broca không hoàn toàn chính xác với mọi người vì không tính đến tỉ lệ cơ - mỡ, tuổi
                    tác,...
                    nhưng là cách ước lượng nhanh và tiện lợi.
                </p>
            </div>


        </div>



        <!-- Tab 3: BMR & TDEE -->
        <div class="tab-content" id="bmr">
            <div class="bmi-form">
                <label>Giới tính:</label>
                <select id="bmr-gender">
                    <option value="nam">Nam</option>
                    <option value="nu">Nữ</option>
                </select>

                <label>Tuổi:</label>
                <input type="number" id="bmr-age" placeholder="Nhập tuổi">

                <label>Chiều cao (cm):</label>
                <input type="number" id="bmr-height" placeholder="Nhập chiều cao">

                <label>Cân nặng (kg):</label>
                <input type="number" id="bmr-weight" placeholder="Nhập cân nặng">

                <label>Mức độ vận động:</label>
                <select id="activity-level">
                    <option value="1.2">Ít vận động</option>
                    <option value="1.375">Vận động nhẹ (1-3 lần/tuần)</option>
                    <option value="1.55">Vận động trung bình (3-5 lần/tuần)</option>
                    <option value="1.725">Vận động nhiều (6-7 lần/tuần)</option>
                    <option value="1.9">Vận động rất nặng</option>
                </select>

                <button onclick="calculateTDEE()">Tính BMR &amp; TDEE</button>
                <div class="result" id="bmr-result"></div>
            </div>
            <div class="img_tdee"><img src="../assets/img/avt/tdee.jpg" alt=""></div>
            <div class="bmi-info">
                <h3>BMR &amp; TDEE là gì và vì sao quan trọng?</h3>
                <p>
                    Calo là một thành phần quan trọng của chế độ ăn uống hàng ngày của chúng ta, bởi vì chúng cung cấp
                    năng
                    lượng cần thiết cho cơ thể hoạt động đúng cách. Tuy nhiên, không phải tất cả các calo được tạo ra
                    bằng
                    nhau và việc biết cần bao nhiêu calo cho cơ thể của bạn là rất quan trọng để duy trì trọng lượng
                    khỏe
                    mạnh.
                </p>

                <p>
                    Trong bài đăng này, chúng ta sẽ khám phá hai khái niệm quan trọng để hiểu bạn nên tiêu thụ bao nhiêu
                    calo bằng cách tính lượng calo cần nạp từ: tỷ lệ trao đổi chất cơ bản (BMR) và tổng lượng năng lượng
                    tiêu thụ hàng ngày (TDEE).
                </p>

                <h4>BMR là gì và cách tính?</h4>
                <p>
                    BMR là lượng calo cơ thể đốt cháy khi nghỉ ngơi để duy trì các chức năng cơ bản. Công thức tính BMR:
                </p>
                <ul>
                    <li><strong>Nam:</strong> (10 × cân nặng kg) + (6.25 × chiều cao cm) − (5 × tuổi) + 5</li>
                    <li><strong>Nữ:</strong> (10 × cân nặng kg) + (6.25 × chiều cao cm) − (5 × tuổi) − 161</li>
                </ul>

                <h4>TDEE là gì và cách tính?</h4>
                <p>
                    TDEE là tổng lượng calo bạn tiêu hao mỗi ngày. Tính bằng cách nhân BMR với hệ số hoạt động:</p>
                <ul>
                    <li>Ít vận động: × 1.2</li>
                    <li>Vận động nhẹ: × 1.375</li>
                    <li>Vận động vừa: × 1.55</li>
                    <li>Vận động nhiều: × 1.725</li>
                    <li>Vận động rất nặng: × 1.9</li>
                </ul>

                <p>
                    <strong>Ví dụ:</strong> BMR = 1500, vận động vừa → TDEE = 1500 × 1.55 = 2325 calo/ngày
                </p>

                <h4>Tại sao nên tính BMR và TDEE?</h4>
                <p>
                    Hiểu rõ BMR và TDEE giúp bạn kiểm soát cân nặng hiệu quả hơn. Ăn vượt TDEE → tăng cân, ăn thấp hơn →
                    giảm cân. Đồng thời giúp bạn lập kế hoạch ăn uống – tập luyện khoa học và cá nhân hóa.
                </p>

                <p>
                    Lưu ý: Kết quả chỉ mang tính tham khảo. Hãy kết hợp với tư vấn chuyên gia nếu bạn có mục tiêu đặc
                    biệt
                    về sức khỏe hoặc thể hình.
                </p>
            </div>
        </div>

    </div>
    <?php include_once 'footer.php'; ?>

    <!-- Chatbox HTML -->
    <button class="chatbox-trigger" onclick="toggleChatbox()" id="chatboxTrigger">
        💬
    </button>

    <div class="chatbox-container" id="chatboxContainer">
        <div class="chatbox-header">
            <img class="chatbox-header-img" src="../assets/img/avt/logo.png" alt="">
            <p class="chatbox-title">Tư vấn sức khỏe</p>
            <button class="chatbox-close" onclick="closeChatbox()">×</button>
        </div>
        <div class="chatbox-content" id="chatboxContent">
            <div class="message bot">
                <div class="message-bubble">
                    Xin chào! Tôi là trợ lý tư vấn sức khỏe của bạn. Hãy tính toán các chỉ số BMI, BMR, TDEE để tôi có thể đưa ra lời khuyên phù hợp về chế độ ăn uống và tập luyện! 😊
                </div>
            </div>
        </div>
        <div class="chatbox-input-area">
            <textarea class="chatbox-input" id="chatboxInput" placeholder="Nhập câu hỏi của bạn..." rows="1"></textarea>
        </div>
    </div>
    <script>
        // Chuyển tab
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                const tabId = tab.dataset.tab;
                document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Tính BMI
        function calculateBMI() {
            // Đảm bảo tab BMI đang active
            const bmiTab = document.getElementById('bmi');
            if (!bmiTab.classList.contains('active')) {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelector('[data-tab="bmi"]').classList.add('active');
                document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));
                bmiTab.classList.add('active');
            }
            
            // Lấy elements từ tab BMI cụ thể
            const heightInput = bmiTab.querySelector('#height');
            const weightInput = bmiTab.querySelector('#weight');
            const result = bmiTab.querySelector('#bmi-result');
            
            // Debug: kiểm tra xem có tìm thấy elements không
            if (!heightInput || !weightInput) {
                result.textContent = "Lỗi: Không tìm thấy input fields.";
                return;
            }
            
            const heightValue = heightInput.value.trim();
            const weightValue = weightInput.value.trim();
            
            // Debug: hiển thị giá trị đã lấy được
            console.log("Height value:", heightValue);
            console.log("Weight value:", weightValue);
            console.log("Height input:", heightInput);
            console.log("Weight input:", weightInput);
            
            // Kiểm tra xem có nhập dữ liệu không
            if (!heightValue || !weightValue || heightValue === "" || weightValue === "") {
                result.textContent = "Vui lòng nhập đầy đủ chiều cao và cân nặng.";
                return;
            }
            
            const height = parseFloat(heightValue);
            const weight = parseFloat(weightValue);
            
            // Kiểm tra dữ liệu hợp lệ
            if (isNaN(height) || isNaN(weight) || height <= 0 || weight <= 0) {
                result.textContent = "Vui lòng nhập chiều cao và cân nặng là số dương.";
                return;
            }
            
            const heightInMeters = height / 100;
            const bmi = weight / (heightInMeters * heightInMeters);
            
            let category = '';
            if (bmi < 18.5) category = 'Thiếu cân';
            else if (bmi < 25) category = 'Bình thường';
            else if (bmi < 30) category = 'Thừa cân';
            else category = 'Béo phì';
            
            result.textContent = `Chỉ số BMI của bạn là: ${bmi.toFixed(1)} (${category})`;
            
            // Hiển thị chatbox với lời khuyên BMI
            showChatboxWithBMIAdvice(bmi, category, weight, height);
        }

        // Tính cân nặng chuẩn
        function calculateIdealWeight() {
            const height = parseFloat(document.getElementById('ideal-height').value);
            const gender = document.getElementById('ideal-gender').value;
            const result = document.getElementById('ideal-result');

            if (!height || height <= 0) {
                result.textContent = "Vui lòng nhập chiều cao hợp lệ.";
                return;
            }

            let weight;
            if (gender === "nam") {
                weight = (height - 100) * 0.9;
            } else {
                weight = (height - 100) * 0.85;
            }

            result.textContent = `Cân nặng chuẩn của bạn là khoảng ${weight.toFixed(1)} kg.`;

            // Hiển thị chatbox với lời khuyên về cân nặng chuẩn
            const currentWeight = parseFloat(document.getElementById('ideal-current-weight')?.value);
            if (currentWeight) {
                const bmi = currentWeight / ((height / 100) ** 2);
                showChatboxWithBMIAdvice(bmi, bmi < 18.5 ? 'Gầy' : bmi < 25 ? 'Bình thường' : 'Thừa cân', currentWeight, height);
            }
        }


        // Tính BMR và TDEE
        function calculateTDEE() {
            const gender = document.getElementById('bmr-gender').value;
            const age = parseInt(document.getElementById('bmr-age').value);
            const height = parseFloat(document.getElementById('bmr-height').value);
            const weight = parseFloat(document.getElementById('bmr-weight').value);
            const activity = parseFloat(document.getElementById('activity-level').value);
            const result = document.getElementById('bmr-result');

            if (!age || !height || !weight || age <= 0 || height <= 0 || weight <= 0) {
                result.textContent = "Vui lòng nhập đầy đủ và hợp lệ.";
                return;
            }

            let bmr = gender === "nam" ?
                10 * weight + 6.25 * height - 5 * age + 5 :
                10 * weight + 6.25 * height - 5 * age - 161;

            const tdee = Math.round(bmr * activity);
            result.textContent = `BMR của bạn là ${Math.round(bmr)} calo/ngày. TDEE là khoảng ${tdee} calo/ngày.`;

            // Hiển thị chatbox sau khi tính TDEE
            showChatboxWithTDEEAdvice(Math.round(bmr), tdee, gender, age, weight, height, activity);
        }

        // Chatbox functions - Now handled by chatbot.js

        // Auto-resize textarea
        document.getElementById('chatboxInput').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 60) + 'px';
        });
    </script>


    <!-- Include chatbot script -->
    <script src="/CoSo/js/chatbot.js"></script>

</body>

</html>