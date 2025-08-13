// Chatbox AI tư vấn sức khỏe
class HealthChatbot {
    constructor() {
        this.userHealthData = {};
        this.isOpen = false;
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        // Handle Enter key in chat input
        const chatInput = document.getElementById('chatboxInput');
        if (chatInput) {
            chatInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    const message = e.target.value.trim();
                    if (message) {
                        this.askQuestion(message);
                        e.target.value = '';
                    }
                }
            });

            // Auto-resize textarea
            chatInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 60) + 'px';
            });
        }
    }

    toggleChatbox() {
        const chatbox = document.getElementById('chatboxContainer');
        const trigger = document.getElementById('chatboxTrigger');
        
        if (!this.isOpen) {
            chatbox.style.display = 'block';
            trigger.style.display = 'none';
            this.isOpen = true;
        } else {
            chatbox.style.display = 'none';
            trigger.style.display = 'flex';
            this.isOpen = false;
        }
    }

    closeChatbox() {
        document.getElementById('chatboxContainer').style.display = 'none';
        document.getElementById('chatboxTrigger').style.display = 'flex';
        this.isOpen = false;
    }

    addMessage(message, isBot = true) {
        const chatContent = document.getElementById('chatboxContent');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isBot ? 'bot' : 'user'}`;
        messageDiv.innerHTML = `<div class="message-bubble">${message.replace(/\n/g, '<br>')}</div>`;
        chatContent.appendChild(messageDiv);
        chatContent.scrollTop = chatContent.scrollHeight;
    }

    showTypingIndicator() {
        const chatContent = document.getElementById('chatboxContent');
        const typingDiv = document.createElement('div');
        typingDiv.className = 'typing-indicator';
        typingDiv.id = 'typingIndicator';
        typingDiv.innerHTML = `
            <span>Đang suy nghĩ</span>
            <div class="typing-dots">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        `;
        chatContent.appendChild(typingDiv);
        chatContent.scrollTop = chatContent.scrollHeight;
    }

    hideTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    showNotification() {
        const trigger = document.getElementById('chatboxTrigger');
        trigger.classList.add('has-notification');
    }

    hideNotification() {
        const trigger = document.getElementById('chatboxTrigger');
        trigger.classList.remove('has-notification');
    }

    showChatboxWithBMIAdvice(bmi, category, weight, height) {
        this.userHealthData.bmi = bmi;
        this.userHealthData.category = category;
        this.userHealthData.weight = weight;
        this.userHealthData.height = height;
        
        this.showNotification();
        
        setTimeout(() => {
            this.addMessage(this.getBMIAdvice(bmi, category));
            this.updateSuggestions();
        }, 1000);
    }

    showChatboxWithTDEEAdvice(bmr, tdee, gender, age, weight, height, activity) {
        this.userHealthData.bmr = bmr;
        this.userHealthData.tdee = tdee;
        this.userHealthData.gender = gender;
        this.userHealthData.age = age;
        this.userHealthData.weight = weight;
        this.userHealthData.height = height;
        this.userHealthData.activity = activity;
        
        this.showNotification();
        
        setTimeout(() => {
            this.addMessage(this.getTDEEAdvice(bmr, tdee, gender, activity));
            this.updateSuggestions();
        }, 1000);
    }

    getBMIAdvice(bmi, category) {
        let advice = `📊 Dựa trên chỉ số BMI ${bmi.toFixed(1)} (${category}), đây là lời khuyên cho bạn:\n\n`;
        
        if (bmi < 18.5) {
            advice += `🔸 **Tăng cân lành mạnh:**
• Ăn nhiều bữa nhỏ trong ngày
• Tăng cường protein và carbs tốt
• Tập gym để tăng khối lượng cơ

🍽️ **Món ăn gợi ý:**
• Smoothie protein với chuối, bơ
• Cơm gạo lứt với thịt nạc
• Các loại hạt và sữa chua Hy Lạp

💪 **Bài tập phù hợp:**
• Squat, deadlift, bench press
• Tập tạ với trọng lượng tăng dần
• Yoga để cải thiện tính linh hoạt`;
        } else if (bmi < 25) {
            advice += `✅ **Duy trì cân nặng hiện tại:**
• Chế độ ăn cân bằng
• Tập luyện đều đặn 3-4 lần/tuần
• Uống đủ nước mỗi ngày

🍽️ **Món ăn gợi ý:**
• Salad quinoa với rau củ
• Cá hồi nướng với rau
• Combo protein + carbs + chất béo tốt

💪 **Bài tập phù hợp:**
• Kết hợp cardio và strength training
• Chạy bộ, bơi lội 3 lần/tuần
• Tập yoga hoặc pilates`;
        } else if (bmi < 30) {
            advice += `⚡ **Giảm cân từ từ và bền vững:**
• Giảm 300-500 calo/ngày
• Tăng hoạt động cardio
• Ăn nhiều rau xanh, ít đường

🍽️ **Món ăn gợi ý:**
• Súp rau củ không dầu
• Ức gà nướng với salad
• Cháo yến mạch với trái cây

💪 **Bài tập phù hợp:**
• Cardio cường độ cao (HIIT)
• Đi bộ nhanh 45-60 phút/ngày
• Tập tạ nhẹ để giữ cơ bắp`;
        } else {
            advice += `🚨 **Cần giảm cân nghiêm túc:**
• Tham khảo ý kiến bác sĩ dinh dưỡng
• Kết hợp chế độ ăn low-carb
• Bắt đầu với walking, bơi lội

🍽️ **Món ăn gợi ý:**
• Salad xanh với protein nạc
• Canh chua ít dầu
• Trứng luộc với rau củ

💪 **Bài tập phù hợp:**
• Đi bộ 30-45 phút/ngày
• Bơi lội hoặc aqua aerobics
• Tập thể dục nhẹ nhàng`;
        }
        
        return advice;
    }

    getTDEEAdvice(bmr, tdee, gender, activity) {
        let advice = `⚡ Với TDEE ${tdee} calo/ngày, đây là kế hoạch dinh dưỡng cho bạn:\n\n`;
        
        const activityLevel = parseFloat(activity);
        
        if (activityLevel <= 1.2) {
            advice += `🏠 **Lối sống ít vận động:**
• Tăng cường hoạt động hàng ngày
• Đi bộ ít nhất 30 phút/ngày
• Tập yoga hoặc stretching

🍽️ **Phân bổ calo:**
• Giảm cân: ${tdee - 500} calo/ngày
• Duy trì: ${tdee} calo/ngày
• Tăng cân: ${tdee + 300} calo/ngày

💡 **Gợi ý thực đơn:**
• Sáng: Yến mạch + trái cây (${Math.round(tdee * 0.25)} calo)
• Trưa: Cơm + protein + rau (${Math.round(tdee * 0.4)} calo)
• Tối: Salad + protein nạc (${Math.round(tdee * 0.3)} calo)`;
        } else if (activityLevel <= 1.55) {
            advice += `🚶 **Hoạt động vừa phải:**
• Duy trì thói quen tập luyện hiện tại
• Thêm 1-2 buổi cardio/tuần
• Kết hợp strength training

🍽️ **Phân bổ calo:**
• Giảm cân: ${tdee - 400} calo/ngày
• Duy trì: ${tdee} calo/ngày
• Tăng cân: ${tdee + 400} calo/ngày

💡 **Gợi ý thực đơn:**
• Pre-workout: Chuối + cà phê
• Post-workout: Protein shake
• Bữa chính: Cân bằng macro nutrients`;
        } else {
            advice += `🏃 **Hoạt động cao:**
• Đảm bảo nghỉ ngơi đầy đủ
• Ăn đủ protein phục hồi cơ bắp
• Hydrate tốt trước/sau tập

🍽️ **Phân bổ calo:**
• Giảm cân: ${tdee - 300} calo/ngày
• Duy trì: ${tdee} calo/ngày
• Tăng cân: ${tdee + 500} calo/ngày

💡 **Gợi ý thực đơn:**
• Protein cao: ${Math.round(tdee * 0.3)} calo từ protein
• Carbs phức hợp cho năng lượng
• Nhiều bữa ăn nhỏ trong ngày`;
        }
        
        advice += `\n\n💡 **Lưu ý:** Hãy chia calo thành nhiều bữa ăn nhỏ và uống đủ 2-3 lít nước mỗi ngày!`;
        
        return advice;
    }

    updateSuggestions() {
        const suggestions = document.getElementById('suggestionButtons');
        suggestions.innerHTML = `
            <button class="suggestion-btn" onclick="healthChatbot.askQuestion('Gợi ý thực đơn hôm nay')">🍽️ Thực đơn hôm nay</button>
            <button class="suggestion-btn" onclick="healthChatbot.askQuestion('Bài tập phù hợp với tôi')">💪 Bài tập phù hợp</button>
            <button class="suggestion-btn" onclick="healthChatbot.askQuestion('Cách tính calo trong món ăn')">📊 Tính calo món ăn</button>
            <button class="suggestion-btn" onclick="healthChatbot.askQuestion('Lịch tập luyện tuần')">📅 Lịch tập tuần</button>
        `;
    }

    askQuestion(question) {
        this.addMessage(question, false);
        this.showTypingIndicator();
        
        setTimeout(() => {
            this.hideTypingIndicator();
            const answer = this.generateAnswer(question);
            this.addMessage(answer);
        }, 1500);
    }

    generateAnswer(question) {
        const q = question.toLowerCase();
        
        if (q.includes('thực đơn') || q.includes('món ăn')) {
            return this.getMenuSuggestion();
        } else if (q.includes('bài tập') || q.includes('tập luyện')) {
            return this.getExerciseSuggestion();
        } else if (q.includes('calo')) {
            return this.getCalorieInfo();
        } else if (q.includes('lịch tập')) {
            return this.getWorkoutSchedule();
        } else if (q.includes('giảm cân') || q.includes('béo')) {
            return this.getWeightLossAdvice();
        } else if (q.includes('tăng cân') || q.includes('gầy')) {
            return this.getWeightGainAdvice();
        } else if (q.includes('nước') || q.includes('hydrate')) {
            return this.getHydrationAdvice();
        } else if (q.includes('ngủ') || q.includes('nghỉ ngơi')) {
            return this.getSleepAdvice();
        } else {
            return this.getGeneralAdvice();
        }
    }

    getMenuSuggestion() {
        const bmi = this.userHealthData.bmi || 22;
        const tdee = this.userHealthData.tdee || 2000;
        
        let menu = "🍽️ **Thực đơn gợi ý cho hôm nay:**\n\n";
        
        if (bmi < 18.5) {
            menu += `**🌅 Sáng (${Math.round(tdee * 0.3)} calo):**
• Bánh mì nguyên cám + trứng ốp la + sữa tươi
• Chuối + bơ đậu phộng
• Nước ép cam tươi

**☀️ Trưa (${Math.round(tdee * 0.4)} calo):**
• Cơm gạo lứt + sườn nướng + rau xào
• Canh chua cá
• Chè đậu xanh

**🌙 Tối (${Math.round(tdee * 0.25)} calo):**
• Phở gà + bánh phở thêm
• Nem nướng Nha Trang
• Sữa chua`;
        } else if (bmi > 25) {
            menu += `**🌅 Sáng (${Math.round(tdee * 0.25)} calo):**
• Yến mạch + quả berry + hạt chia
• Trứng luộc (2 quả)
• Trà xanh không đường

**☀️ Trưa (${Math.round(tdee * 0.4)} calo):**
• Salad quinoa + ức gà nướng
• Súp rau củ
• Nước lọc có chanh

**🌙 Tối (${Math.round(tdee * 0.3)} calo):**
• Cá hồi nướng + rau củ hấp
• Canh khổ qua nhồi thịt
• Trà thảo mộc`;
        } else {
            menu += `**🌅 Sáng (${Math.round(tdee * 0.3)} calo):**
• Bánh mì sandwich + rau lettuce + thịt nguội
• Sinh tố bơ + sữa tươi
• Cà phê sữa ít đường

**☀️ Trưa (${Math.round(tdee * 0.4)} calo):**
• Bún bò Huế + rau thơm
• Chả cá Lã Vọng + bánh tráng
• Nước dừa tươi

**🌙 Tối (${Math.round(tdee * 0.25)} calo):**
• Cơm chiên hải sản
• Gỏi cuốn tôm thịt
• Chè ba màu`;
        }
        
        menu += "\n\n💡 **Lưu ý:** Ăn chậm nhai kỹ, uống nước trước bữa ăn 30 phút!";
        return menu;
    }

    getExerciseSuggestion() {
        const bmi = this.userHealthData.bmi || 22;
        const activity = this.userHealthData.activity || 1.375;
        
        let exercise = "💪 **Bài tập phù hợp cho bạn:**\n\n";
        
        if (bmi < 18.5) {
            exercise += `**🎯 Mục tiêu: Tăng khối lượng cơ**
🏋️ **Strength Training (4-5 ngày/tuần):**
• Squat: 3 sets x 8-12 reps
• Deadlift: 3 sets x 6-10 reps
• Bench Press: 3 sets x 8-12 reps
• Pull-ups: 3 sets x 5-10 reps

🚶 **Cardio nhẹ (2-3 ngày/tuần):**
• Đi bộ 30 phút
• Yoga/Pilates
• Bơi lội thư giãn`;
        } else if (bmi > 25) {
            exercise += `**🎯 Mục tiêu: Giảm cân hiệu quả**
🔥 **Cardio (5-6 ngày/tuần):**
• Chạy bộ: 30-45 phút
• Xe đạp: 45-60 phút
• HIIT: 20-30 phút
• Bơi lội: 30-45 phút

💪 **Strength Training (3 ngày/tuần):**
• Full body workout
• Circuit training
• Resistance bands`;
        } else {
            exercise += `**🎯 Mục tiêu: Duy trì form và tăng sức khỏe**
⚖️ **Kết hợp cân bằng:**
🏃 **Cardio (3-4 ngày/tuần):**
• Chạy bộ: 30 phút
• Aerobic dance: 45 phút
• Hiking: 60 phút

🏋️ **Strength (2-3 ngày/tuần):**
• Upper body: Thứ 2, 5
• Lower body: Thứ 4, 7
• Core training: Hàng ngày`;
        }
        
        exercise += "\n\n⚠️ **Lưu ý:** Khởi động 10 phút trước tập, thư giãn 10 phút sau tập!";
        return exercise;
    }

    getCalorieInfo() {
        return `📊 **Cách tính calo trong món ăn:**

🍚 **Nhóm tinh bột (4 calo/g):**
• Cơm trắng: 130 calo/100g
• Bánh mì: 250 calo/100g
• Mì ăn liền: 450 calo/100g
• Khoai lang: 86 calo/100g

🥩 **Nhóm protein (4 calo/g):**
• Thịt bò nạc: 150 calo/100g
• Ức gà: 165 calo/100g
• Cá hồi: 200 calo/100g
• Trứng gà: 155 calo/100g
• Đậu phụ: 76 calo/100g

🥑 **Nhóm chất béo (9 calo/g):**
• Dầu ô liu: 884 calo/100ml
• Bơ: 717 calo/100g
• Hạt điều: 553 calo/100g
• Hạt óc chó: 654 calo/100g

🥬 **Rau củ (0.5-2 calo/g):**
• Rau xanh: 20-30 calo/100g
• Cà rót: 25 calo/100g
• Bí đao: 13 calo/100g
• Cà chua: 18 calo/100g

🍎 **Trái cây:**
• Táo: 52 calo/100g
• Chuối: 89 calo/100g
• Cam: 47 calo/100g

💡 **Mẹo:** Dùng app MyFitnessPal hoặc cân thực phẩm để tính chính xác!`;
    }

    getWorkoutSchedule() {
        const activity = this.userHealthData.activity || 1.375;
        
        let schedule = "📅 **Lịch tập luyện tuần:**\n\n";
        
        if (activity <= 1.2) {
            schedule += `**👶 Người mới bắt đầu:**
• **Thứ 2:** Đi bộ 30 phút + Yoga 15 phút
• **Thứ 3:** Nghỉ ngơi / Stretching
• **Thứ 4:** Tập tại nhà 30 phút
• **Thứ 5:** Đi bộ 30 phút
• **Thứ 6:** Yoga 30 phút
• **Thứ 7:** Hoạt động ngoài trời
• **Chủ nhật:** Nghỉ ngơi hoàn toàn`;
        } else if (activity <= 1.55) {
            schedule += `**💪 Trình độ trung bình:**
• **Thứ 2:** Upper Body + Cardio 20 phút
• **Thứ 3:** Yoga/Pilates 45 phút
• **Thứ 4:** Lower Body + Abs
• **Thứ 5:** Cardio 40 phút
• **Thứ 6:** Full Body Strength
• **Thứ 7:** Hoạt động vui chơi
• **Chủ nhật:** Active recovery`;
        } else {
            schedule += `**🏆 Trình độ cao:**
• **Thứ 2:** Push Day + HIIT 20 phút
• **Thứ 3:** Pull Day + Cardio steady
• **Thứ 4:** Leg Day + Core
• **Thứ 5:** Push Day + Swimming
• **Thứ 6:** Pull Day + Functional
• **Thứ 7:** Cardio dài + Yoga
• **Chủ nhật:** Active recovery`;
        }
        
        schedule += "\n\n⏰ **Thời gian tốt nhất:** 6-8h sáng hoặc 17-19h chiều";
        return schedule;
    }

    getWeightLossAdvice() {
        return `🔥 **Chiến lược giảm cân hiệu quả:**

📉 **Nguyên tắc cơ bản:**
• Tạo deficit calo 300-500 calo/ngày
• Giảm 0.5-1kg/tuần là lý tưởng
• Kết hợp diet + exercise

🍽️ **Chế độ ăn:**
• Tăng protein (giữ cơ bắp)
• Giảm carbs tinh chế
• Ăn nhiều rau xanh và chất xơ
• Uống nước trước bữa ăn

💪 **Tập luyện:**
• Cardio 5-6 lần/tuần
• Strength training 3 lần/tuần
• HIIT 2-3 lần/tuần

⏰ **Thời gian biểu:**
• Ăn sáng đầy đủ
• Bữa tối nhẹ nhàng
• Không ăn sau 8h tối

💡 **Mẹo nhỏ:**
• Dùng đĩa nhỏ hơn
• Ăn chậm, nhai kỹ
• Ngủ đủ 7-8 tiếng
• Kiểm soát stress`;
    }

    getWeightGainAdvice() {
        return `📈 **Chiến lược tăng cân lành mạnh:**

📊 **Nguyên tắc cơ bản:**
• Tăng 300-500 calo so với TDEE
• Tăng 0.5kg/tuần là lý tưởng
• Tập trung vào tăng cơ bắp

🍽️ **Chế độ ăn:**
• Ăn nhiều bữa nhỏ (5-6 bữa/ngày)
• Tăng protein và carbs tốt
• Thêm healthy fats
• Uống smoothie protein

💪 **Tập luyện:**
• Strength training ưu tiên
• Compound exercises
• Progressive overload
• Hạn chế cardio quá nhiều

🥤 **Đồ uống:**
• Sữa tươi full-fat
• Protein shake
• Nước ép trái cây tự nhiên

💡 **Mẹo nhỏ:**
• Ăn trước khi đói
• Thêm nuts và seeds
• Dùng dầu ô liu khi nấu
• Theo dõi tiến độ hàng tuần`;
    }

    getHydrationAdvice() {
        const weight = this.userHealthData.weight || 60;
        const recommendedWater = Math.round(weight * 35);
        
        return `💧 **Hướng dẫn hydrate hiệu quả:**

📊 **Lượng nước cần thiết:**
• Theo cân nặng: ${recommendedWater}ml/ngày
• Trung bình: 2-3 lít/ngày
• Tăng thêm khi tập luyện

⏰ **Lịch uống nước:**
• Thức dậy: 1-2 ly (bù nước mất đêm)
• Trước bữa ăn: 1 ly (30 phút)
• Sau tập: 150% lượng mồ hôi mất

🚰 **Loại nước tốt:**
• Nước lọc thường
• Nước dừa tươi
• Trà xanh nhạt
• Nước detox chanh/bạc hà

⚠️ **Dấu hiệu thiếu nước:**
• Nước tiểu vàng đậm
• Khô miệng, mệt mỏi
• Đau đầu nhẹ
• Da khô, kém đàn hồi

💡 **Mẹo nhỏ:**
• Mang chai nước bên mình
• Đặt báo thức nhắc uống
• Ăn trái cây nhiều nước
• Theo dõi màu nước tiểu`;
    }

    getSleepAdvice() {
        return `😴 **Hướng dẫn ngủ ngon cho sức khỏe:**

⏰ **Thời gian ngủ:**
• 7-9 tiếng/đêm cho người lớn
• Đi ngủ và thức cùng giờ
• Ngủ trước 23h là tốt nhất

🛏️ **Môi trường ngủ:**
• Phòng tối, mát mẻ (18-22°C)
• Giường thoải mái
• Tắt điện thoại 1h trước ngủ

🚫 **Tránh trước khi ngủ:**
• Caffeine sau 14h
• Bữa ăn nặng
• Tập luyện cường độ cao
• Ánh sáng xanh từ màn hình

✅ **Thói quen tốt:**
• Đọc sách nhẹ
• Nghe nhạc thư giãn
• Tắm nước ấm
• Thiền/thở sâu

💊 **Hỗ trợ tự nhiên:**
• Trà hoa cúc
• Sữa ấm + mật ong
• Tinh dầu lavender
• Magnesium supplement

💡 **Tác động đến sức khỏe:**
• Giúp phục hồi cơ bắp
• Cân bằng hormone
• Tăng miễn dịch
• Kiểm soát cân nặng`;
    }

    getGeneralAdvice() {
        return `💡 **Lời khuyên tổng quát cho sức khỏe:**

🥗 **Dinh dưỡng:**
• Ăn đủ 5 nhóm chất dinh dưỡng
• Uống 2-3 lít nước/ngày
• Hạn chế đường và muối
• Ăn nhiều rau củ quả

🏃 **Vận động:**
• Ít nhất 150 phút/tuần cường độ vừa
• Tập cơ 2-3 lần/tuần
• Đi bộ sau bữa ăn
• Tăng hoạt động hàng ngày

😴 **Nghỉ ngơi:**
• Ngủ 7-9 tiếng/đêm
• Đi ngủ và thức dậy đều giờ
• Tránh màn hình trước khi ngủ
• Tạo môi trường ngủ thoải mái

🧘 **Tinh thần:**
• Meditation 10 phút/ngày
• Giao lưu xã hội tích cực
• Tìm sở thích để giải stress
• Tư duy tích cực

📊 **Theo dõi:**
• Cân nặng hàng tuần
• Vòng eo, vòng mông
• Năng lượng hàng ngày
• Chất lượng giấc ngủ

Bạn có câu hỏi cụ thể nào khác không? 😊`;
    }
}

// Khởi tạo chatbot
const healthChatbot = new HealthChatbot();

// Expose functions to global scope for onclick handlers
window.toggleChatbox = () => healthChatbot.toggleChatbox();
window.closeChatbox = () => healthChatbot.closeChatbox();
window.showChatboxWithBMIAdvice = (bmi, category, weight, height) => 
    healthChatbot.showChatboxWithBMIAdvice(bmi, category, weight, height);
window.showChatboxWithTDEEAdvice = (bmr, tdee, gender, age, weight, height, activity) => 
    healthChatbot.showChatboxWithTDEEAdvice(bmr, tdee, gender, age, weight, height, activity);
