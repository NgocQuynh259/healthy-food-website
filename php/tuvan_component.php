
<!-- Form Tư Vấn Popup -->
<form id="multiForm" action="combo.php" method="post">
    <div class="multi_form">
        <div class="multi_form_wrapper">
            <div class="icon_close">
                <img src="../assets/img/avt/close.png" alt="Đóng" onerror="this.src='/CoSo/assets/img/avt/close.png'">
            </div>
            <div class="multi_form_header">
                <p>Sống Khỏe – Sống Vui</p>
            </div>
            <div class="multi_form_body">
                <div class="pagination">
                    <div class="number active">1</div>
                    <div class="bar"></div>
                    <div class="number">2</div>
                    <div class="bar"></div>
                    <div class="number">3</div>
                    <div class="bar"></div>
                    <div class="number">4</div>
                    <div class="bar"></div>
                    <div class="number">5</div>
                </div>
                <div class="multi_form_steps">
                    <!-- Step 1: Thông tin cá nhân -->
                    <div class="step active">
                        <h4>Thông tin cá nhân của bạn</h4>
                        <div class="multi_fill_out_form">
                            <div class="multi_form_inputbox">
                                <label for="weight">Cân nặng (kg): <span class="required">*</span></label>
                                <input type="number" id="weight" name="weight" placeholder="Ví dụ: 45" min="1" max="300" required>
                            </div>
                            <div class="multi_form_inputbox">
                                <label for="height">Chiều cao (cm): <span class="required">*</span></label>
                                <input type="number" id="height" name="height" placeholder="Ví dụ: 160" min="1" max="250" required>
                            </div>
                            <div class="multi_form_inputbox">
                                <label for="age">Độ tuổi: <span class="required">*</span></label>
                                <input type="number" id="age" name="age" placeholder="Ví dụ: 25" min="1" max="120" required>
                            </div>
                            <div class="multi_form_radio">
                                <p>Giới tính: <span class="required">*</span></p>
                                <input type="radio" id="male" name="gender" value="Nam" required>
                                <label for="male">Nam</label>
                                <input type="radio" id="female" name="gender" value="Nu" required>
                                <label for="female">Nữ</label>
                            </div>
                        </div>
                        <div class="form-section-activity">
                            <h2>Mức độ hoạt động thể chất của bạn như thế nào? <span class="required">*</span></h2>
                            <select id="activityLevel" name="activityLevel" required>
                                <option value="">-- Chọn mức độ hoạt động --</option>
                                <option value="1.2">Ít vận động (ít hoặc không tập thể dục)</option>
                                <option value="1.375">Vận động nhẹ (tập thể dục 1-3 ngày/tuần)</option>
                                <option value="1.55">Vận động vừa (tập thể dục 3-5 ngày/tuần)</option>
                                <option value="1.725">Vận động tích cực (tập thể dục 6-7 ngày/tuần)</option>
                                <option value="1.9">Vận động rất tích cực (tập luyện cường độ cao hàng ngày)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Step 2: Mục tiêu -->
                    <div class="step">
                        <h4>Mục tiêu đặt ra của bạn là gì? <span class="required">*</span></h4>
                        <div class="multi_form_steps_grid one">
                            <div class="col">
                                <div class="checkbox">
                                    <input type="radio" id="loseweight" name="goal" value="loseweight" required>
                                    <label for="loseweight">Giảm cân</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="checkbox">
                                    <input type="radio" id="gainweight" name="goal" value="gainweight" required>
                                    <label for="gainweight">Tăng cân</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="checkbox">
                                    <input type="radio" id="maintain" name="goal" value="maintain" required>
                                    <label for="maintain">Duy trì cân nặng</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="checkbox">
                                    <input type="radio" id="diet" name="goal" value="diet" required>
                                    <label for="diet">Ăn theo chế độ</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Chế độ ăn -->
                    <div class="step">
                        <h4>Bạn quan tâm đến những chế độ nào?</h4>
                        <div class="multi_form_img">
                            <div class="col">
                                <div class="form_img_box" data-value="balance">
                                    <img src="../assets/img/avt/balance.jpg" alt="Balanced" onerror="this.src='/CoSo/assets/img/avt/balance.jpg'">
                                    <p>Ăn Cân Bằng</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form_img_box" data-value="calorie">
                                    <img src="../assets/img/avt/CalorieSmart.jpg" alt="Calorie" onerror="this.src='/CoSo/assets/img/avt/CalorieSmart.jpg'">
                                    <p>Giảm Calo</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form_img_box" data-value="diabetic">
                                    <img src="../assets/img/avt/diabetic.jpg" alt="Diabetic" onerror="this.src='/CoSo/assets/img/avt/diabetic.jpg'">
                                    <p>Kiểm Soát Đường</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form_img_box" data-value="gluten">
                                    <img src="../assets/img/avt/gluten.jpg" alt="Gluten" onerror="this.src='/CoSo/assets/img/avt/gluten.jpg'">
                                    <p>Không Gluten</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form_img_box" data-value="heart">
                                    <img src="../assets/img/avt/heart.jpg" alt="Hearthealthy" onerror="this.src='/CoSo/assets/img/avt/heart.jpg'">
                                    <p>Tốt Cho Tim</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form_img_box" data-value="keto">
                                    <img src="../assets/img/avt/Keto.jpg" alt="Keto" onerror="this.src='/CoSo/assets/img/avt/Keto.jpg'">
                                    <p>Chế độ Keto</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form_img_box" data-value="protein">
                                    <img src="../assets/img/avt/protein.jpg" alt="hightprotein" onerror="this.src='/CoSo/assets/img/avt/protein.jpg'">
                                    <p>Giàu Protein</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form_img_box" data-value="vegan">
                                    <img src="../assets/img/avt/vegan.jpg" alt="vegan" onerror="this.src='/CoSo/assets/img/avt/vegan.jpg'">
                                    <p>Thuần chay</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Dị ứng -->
                    <div class="step">
                        <h4>Bạn dị ứng với những thành phần nào?</h4>
                        <div class="multi_form_steps_grid two">
                            <div class="col">
                                <div class="checkbox">
                                    <input type="checkbox" name="diung[]" value="sua" id="sua">
                                    <label for="sua" class="diung_content">
                                        <img src="../assets/img/avt/sua.png" alt="sua" onerror="this.src='/CoSo/assets/img/avt/sua.png'">
                                        Sữa
                                    </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="checkbox">
                                    <input type="checkbox" name="diung[]" value="trung" id="trung">
                                    <label for="trung" class="diung_content">
                                        <img src="../assets/img/avt/trung.png" alt="trung" onerror="this.src='/CoSo/assets/img/avt/trung.png'">
                                        Trứng
                                    </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="checkbox">
                                    <input type="checkbox" name="diung[]" value="haisan" id="haisan">
                                    <label for="haisan" class="diung_content">
                                        <img src="../assets/img/avt/haisan.png" alt="haisan" onerror="this.src='/CoSo/assets/img/avt/haisan.png'">
                                        Hải sản
                                    </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="checkbox">
                                    <input type="checkbox" name="diung[]" value="dauphong" id="dauphong">
                                    <label for="dauphong" class="diung_content">
                                        <img src="../assets/img/avt/dauphong.png" alt="dauphong" onerror="this.src='/CoSo/assets/img/avt/dauphong.png'">
                                        Đậu phộng
                                    </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="checkbox">
                                    <input type="checkbox" name="diung[]" value="hanh" id="hanh">
                                    <label for="hanh" class="diung_content">
                                        <img src="../assets/img/avt/hanh.png" alt="hanh" onerror="this.src='/CoSo/assets/img/avt/hanh.png'">
                                        Hành
                                    </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="checkbox">
                                    <input type="checkbox" name="diung[]" value="hat" id="hat">
                                    <label for="hat" class="diung_content">
                                        <img src="../assets/img/avt/hat.png" alt="hat" onerror="this.src='/CoSo/assets/img/avt/hat.png'">
                                        Các loại hạt
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Hoàn tất -->
                    <div class="step">
                        <div class="comfimation">
                            <h2>Hoàn tất!</h2>
                            <p>Cảm ơn bạn đã cung cấp thông tin. Chúng tôi sẽ tìm những món ăn phù hợp nhất cho bạn!</p>
                            <button type="button" class="submit-form">Tìm món ăn phù hợp</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="multi_form_footer">
                <button type="button" class="prev" disabled>Quay lại</button>
                <button type="button" class="next">Tiếp theo</button>
            </div>
        </div>
    </div>
</form>

<!-- Loading overlay -->
<div id="loadingOverlay" style="
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.8);
    color: #fff;
    font-size: 1.5rem;
    font-family: 'Open Sans', sans-serif;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    backdrop-filter: blur(4px);
">
    <div style="text-align: center;">
        <div style="
            width: 50px; 
            height: 50px; 
            border: 4px solid rgba(255,255,255,0.3);
            border-top: 4px solid #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        "></div>
        <p>Đang tìm món ăn phù hợp nhất với bạn…</p>
        <style>
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    </div>
</div>

<!-- CSS cho required indicator -->
<style>
.required {
    color: #e74c3c;
    font-weight: bold;
}
.step-note {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 20px;
    font-style: italic;
}
</style>

