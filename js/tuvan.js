// Tư vấn Form Handler - Có thể sử dụng ở mọi trang
class TuVanFormHandler {
    constructor() {
        this.isInitialized = false;
        this.isFormOpen = false;
        this.init();
    }

    init() {
        console.log('🚀 TuVanFormHandler initializing...');
        console.log('📄 Document ready state:', document.readyState);
        
        // Đợi DOM load xong
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                console.log('📦 DOM Content Loaded - Starting initialization');
                this.bindEvents();
                this.initMultiStep();
            });
        } else {
            console.log('📦 DOM already ready - Starting initialization immediately');
            this.bindEvents();
            this.initMultiStep();
        }
        
        // Backup: đảm bảo init sau khi window load
        window.addEventListener('load', () => {
            console.log('🔄 Window loaded - Re-checking form initialization');
            if (!this.isInitialized) {
                console.log('⚠️ Form not initialized yet, retrying...');
                this.bindEvents();
                this.initMultiStep();
            }
        });
    }

    bindEvents() {
        // Bind tất cả các nút tư vấn
        this.bindTuVanButtons();
        
        // Bind nút đóng form
        this.bindCloseButtons();
        
        // Bind click outside để đóng form
        this.bindOutsideClick();
    }

    bindTuVanButtons() {
        // Nút tư vấn trong header
        const headerTuVanBtn = document.querySelector(".tuvan");
        if (headerTuVanBtn) {
            headerTuVanBtn.addEventListener("click", (e) => {
                e.preventDefault();
                this.openForm();
            });
        }

        // Nút tư vấn trong hero section
        const heroTuVanBtn = document.querySelector('.hero_btn');
        if (heroTuVanBtn) {
            heroTuVanBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.openForm();
            });
        }

        // Các nút "Tư Vấn Miễn Phí"
        const consultationBtns = document.querySelectorAll('.btn_primary');
        consultationBtns.forEach(btn => {
            if (btn.textContent.includes('Tư Vấn Miễn Phí')) {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.openForm();
                });
            }
        });

        // Nút tư vấn với class khác (nếu có)
        const otherTuVanBtns = document.querySelectorAll('[data-action="tuvan"], .tuvan-btn');
        otherTuVanBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.openForm();
            });
        });
    }

    bindCloseButtons() {
        const closeIcon = document.querySelector(".multi_form .icon_close");
        if (closeIcon) {
            closeIcon.addEventListener("click", () => {
                this.closeForm();
            });
        }

        // ESC key để đóng form
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeForm();
            }
        });
    }

    bindOutsideClick() {
        const multiForm = document.querySelector(".multi_form");
        const multiFormWrapper = document.querySelector(".multi_form_wrapper");
        
        if (multiForm) {
            multiForm.addEventListener("click", (e) => {
                if (!multiFormWrapper?.contains(e.target)) {
                    this.closeForm();
                }
            });
        }
    }

    openForm() {
        if (this.isFormOpen) {
            console.log('📝 Form is already open');
            return;
        }
        
        const multiForm = document.querySelector(".multi_form");
        if (multiForm) {
            this.isFormOpen = true;
            multiForm.classList.add("active_multiForm");
            document.body.style.overflow = 'hidden'; // Ngăn scroll trang chính
            console.log('📂 Opening consultation form...');
            
            // Reset form về step đầu tiên mỗi khi mở
            this.resetFormToFirstStep();
            
            // Chỉ re-initialize nếu chưa được init
            if (!this.isInitialized) {
                setTimeout(() => {
                    this.initMultiStep();
                }, 100);
            } else {
                // Nếu đã init rồi, chỉ cần reset về step đầu
                setTimeout(() => {
                    const steps = document.querySelectorAll('.step');
                    const numbers = document.querySelectorAll('.number');
                    const nextBtn = document.querySelector('.next');
                    const prevBtn = document.querySelector('.prev');
                    
                    // Reset về step 0
                    steps.forEach((step, index) => {
                        if (index === 0) {
                            step.classList.add('active');
                            step.style.display = 'flex';
                        } else {
                            step.classList.remove('active');
                            step.style.display = 'none';
                        }
                    });
                    
                    // Reset pagination numbers
                    numbers.forEach((num, index) => {
                        if (index === 0) {
                            num.classList.add('active');
                        } else {
                            num.classList.remove('active');
                        }
                    });
                    
                    // Reset pagination bars
                    const bars = document.querySelectorAll('.pagination .bar');
                    bars.forEach(bar => {
                        bar.classList.remove('active');
                    });
                    
                    // Reset buttons
                    if (prevBtn) {
                        prevBtn.disabled = true;
                        prevBtn.style.opacity = '0.5';
                        prevBtn.style.cursor = 'not-allowed';
                    }
                    
                    if (nextBtn) {
                        nextBtn.style.display = 'block';
                        nextBtn.disabled = false;
                        nextBtn.style.opacity = '1';
                    }
                    
                    const submitBtn = document.querySelector('.submit-form');
                    if (submitBtn) {
                        submitBtn.style.display = 'none';
                    }
                    
                    console.log('✅ Form reset to step 1');
                }, 50);
            }
        }
    }
    
    resetFormToFirstStep() {
        console.log('🔄 Resetting form to first step...');
        
        // Clear all form inputs
        const form = document.getElementById('multiForm');
        if (form) {
            // Reset form values
            form.reset();
            
            // Clear any selected diet boxes
            const dietBoxes = document.querySelectorAll('.form_img_box.selected');
            dietBoxes.forEach(box => {
                box.classList.remove('selected');
            });
            
            // Clear hidden diet input
            const hiddenInput = document.getElementById('selectedDiet');
            if (hiddenInput) {
                hiddenInput.value = '';
            }
        }
        
        // The step reset will be handled by initMultiStep() when it calls updateStep()
        console.log('✅ Form reset completed');
    }

    closeForm() {
        const multiForm = document.querySelector(".multi_form");
        if (multiForm) {
            this.isFormOpen = false;
            multiForm.classList.remove("active_multiForm");
            document.body.style.overflow = ''; // Khôi phục scroll
            console.log('📝 Consultation form closed');
        }
    }

    initMultiStep() {
        // Prevent multiple initializations
        if (this.isInitialized) {
            console.log('🔄 Multi-step form already initialized, skipping...');
            return;
        }
        
        console.log('🔧 Initializing multi-step form...');
        const steps = document.querySelectorAll('.step');
        const nextBtn = document.querySelector('.next');
        const prevBtn = document.querySelector('.prev');
        const numbers = document.querySelectorAll('.number');
        let currentStep = 0; // Always start from first step

        console.log('📋 Form elements found:', {
            steps: steps.length,
            nextBtn: !!nextBtn,
            prevBtn: !!prevBtn,
            numbers: numbers.length
        });
        
        // Debug: In ra danh sách các steps
        steps.forEach((step, index) => {
            console.log(`📝 Step ${index}:`, step.querySelector('h4')?.textContent || 'No title');
        });

        if (!steps.length || !nextBtn || !prevBtn) {
            console.warn('⚠️ Multi-step form elements not found!');
            return;
        }
        
        if (steps.length !== numbers.length) {
            console.warn(`⚠️ Mismatch: ${steps.length} steps but ${numbers.length} pagination numbers!`);
        }

        // Clear any existing event listeners by cloning and replacing elements
        const newNextBtn = nextBtn.cloneNode(true);
        const newPrevBtn = prevBtn.cloneNode(true);
        nextBtn.parentNode.replaceChild(newNextBtn, nextBtn);
        prevBtn.parentNode.replaceChild(newPrevBtn, prevBtn);

        // Clear existing listeners on pagination numbers
        numbers.forEach((num, index) => {
            const newNum = num.cloneNode(true);
            num.parentNode.replaceChild(newNum, num);
        });

        // Get the new elements after replacement
        const finalNextBtn = document.querySelector('.next');
        const finalPrevBtn = document.querySelector('.prev');
        const finalNumbers = document.querySelectorAll('.number');

        const updateStep = () => {
            console.log(`🔄 Updating step to: ${currentStep}`);
            
            // 1. Ẩn tất cả steps và chỉ hiện step hiện tại
            steps.forEach((step, index) => {
                if (index === currentStep) {
                    step.classList.add('active');
                    step.style.display = 'flex';
                } else {
                    step.classList.remove('active');
                    step.style.display = 'none';
                }
            });

            // 2. Update pagination numbers (1,2,3,4,5) và bars - active cho current step
            finalNumbers.forEach((num, index) => {
                if (index <= currentStep) {
                    num.classList.add('active');
                    console.log(`✅ Number ${index + 1} set to active`);
                } else {
                    num.classList.remove('active');
                    console.log(`⚫ Number ${index + 1} set to inactive`);
                }
            });

            // 3. Update pagination bars - active cho bars giữa các step đã hoàn thành
            const bars = document.querySelectorAll('.pagination .bar');
            bars.forEach((bar, index) => {
                // Bar thứ index nối giữa step index và step index+1
                // Chỉ active khi step index+1 đã được hoàn thành
                if (index < currentStep) {
                    bar.classList.add('active');
                    console.log(`✅ Bar ${index + 1} set to active`);
                } else {
                    bar.classList.remove('active');
                    console.log(`⚫ Bar ${index + 1} set to inactive`);
                }
            });

            // 3. Update trạng thái buttons
            // Previous button: disable khi ở step đầu
            if (currentStep === 0) {
                finalPrevBtn.disabled = true;
                finalPrevBtn.style.opacity = '0.5';
                finalPrevBtn.style.cursor = 'not-allowed';
            } else {
                finalPrevBtn.disabled = false;
                finalPrevBtn.style.opacity = '1';
                finalPrevBtn.style.cursor = 'pointer';
            }
            
            // Next button: ẩn khi ở step cuối, hiện submit button
            if (currentStep === steps.length - 1) {
                finalNextBtn.style.display = 'none';
                // Hiện submit button nếu có
                const submitBtn = document.querySelector('.submit-form');
                if (submitBtn) {
                    submitBtn.style.display = 'block';
                }
            } else {
                finalNextBtn.style.display = 'block';
                finalNextBtn.disabled = false;
                finalNextBtn.style.opacity = '1';
                // Ẩn submit button nếu có
                const submitBtn = document.querySelector('.submit-form');
                if (submitBtn) {
                    submitBtn.style.display = 'none';
                }
            }
            
            console.log(`✅ Step updated. Current: ${currentStep}, Total: ${steps.length}`);
        };

        finalNextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            console.log(`⏭️ Next button clicked. Current step: ${currentStep}`);
            
            // Validation trước khi chuyển step
            if (this.validateCurrentStep(currentStep)) {
                if (currentStep < steps.length - 1) {
                    currentStep++;
                    updateStep();
                    
                    // Scroll to top của form để user thấy content mới
                    const formWrapper = document.querySelector('.multi_form_wrapper');
                    if (formWrapper) {
                        formWrapper.scrollTop = 0;
                    }
                } else {
                    console.log('📝 Already at last step');
                }
            } else {
                console.log('❌ Validation failed for current step');
            }
        });

        finalPrevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            console.log(`⏮️ Previous button clicked. Current step: ${currentStep}`);
            
            if (currentStep > 0) {
                currentStep--;
                updateStep();
                
                // Scroll to top của form
                const formWrapper = document.querySelector('.multi_form_wrapper');
                if (formWrapper) {
                    formWrapper.scrollTop = 0;
                }
            } else {
                console.log('📝 Already at first step');
            }
        });

        // Form submission
        const submitBtn = document.querySelector('.submit-form');
        if (submitBtn) {
            // Clear existing listeners
            const newSubmitBtn = submitBtn.cloneNode(true);
            submitBtn.parentNode.replaceChild(newSubmitBtn, submitBtn);
            
            // Add new listener
            const finalSubmitBtn = document.querySelector('.submit-form');
            finalSubmitBtn.addEventListener('click', () => {
                this.handleSubmit();
            });
        }

        // Cho phép click trực tiếp vào pagination numbers
        finalNumbers.forEach((num, index) => {
            num.addEventListener('click', () => {
                console.log(`🔢 Pagination number ${index + 1} clicked`);
                
                // Chỉ cho phép quay lại các step đã hoàn thành hoặc step kế tiếp
                if (index <= currentStep + 1 && index < steps.length) {
                    // Validate step hiện tại trước khi chuyển (nếu đi tiến)
                    if (index > currentStep) {
                        if (this.validateCurrentStep(currentStep)) {
                            currentStep = index;
                            updateStep();
                        }
                    } else {
                        // Cho phép quay lại step trước đó mà không cần validate
                        currentStep = index;
                        updateStep();
                    }
                }
            });
            
            // Thêm cursor pointer cho các number có thể click
            num.style.cursor = 'pointer';
            num.title = `Chuyển đến bước ${index + 1}`;
        });

        // Diet selection
        this.initDietSelection();
        
        // Khởi tạo step đầu tiên
        updateStep();
        
        this.isInitialized = true;
        console.log('🎯 Multi-step form initialized successfully!');
    }

    validateCurrentStep(step) {
        console.log(`🔍 Validating step: ${step}`);
        
        switch(step) {
            case 0: // Step 1: Thông tin cá nhân
                const weight = document.getElementById('weight').value.trim();
                const height = document.getElementById('height').value.trim();
                const age = document.getElementById('age').value.trim();
                const gender = document.querySelector('input[name="gender"]:checked');
                const activity = document.getElementById('activityLevel').value;

                if (!weight || !height || !age || !gender || !activity) {
                    showWarning('Vui lòng điền đầy đủ thông tin cá nhân!');
                    return false;
                }

                // Validate số
                if (isNaN(weight) || weight <= 0 || weight > 300) {
                    showWarning('Cân nặng không hợp lệ (1-300kg)!');
                    return false;
                }
                if (isNaN(height) || height <= 0 || height > 250) {
                    showWarning('Chiều cao không hợp lệ (1-250cm)!');
                    return false;
                }
                if (isNaN(age) || age <= 0 || age > 120) {
                    showWarning('Tuổi không hợp lệ (1-120)!');
                    return false;
                }
                break;

            case 1: // Step 2: Mục tiêu
                const goal = document.querySelector('input[name="goal"]:checked');
                if (!goal) {
                    showWarning('Vui lòng chọn mục tiêu của bạn!');
                    return false;
                }
                break;

            case 2: // Step 3: Chế độ ăn (optional)
                // Không bắt buộc chọn
                console.log('✅ Step 3 (Diet) - Optional, no validation needed');
                break;

            case 3: // Step 4: Dị ứng (optional)
                // Không bắt buộc chọn
                console.log('✅ Step 4 (Allergies) - Optional, no validation needed');
                break;
                
            case 4: // Step 5: Hoàn tất (final step)
                // Step cuối cùng, không cần validation
                console.log('✅ Step 5 (Final) - No validation needed');
                break;
        }
        
        console.log(`✅ Step ${step} validation passed`);
        return true;
    }

    initDietSelection() {
        console.log('🍎 Initializing diet selection...');
        const dietBoxes = document.querySelectorAll('.form_img_box');
        console.log('📦 Found diet boxes:', dietBoxes.length);
        
        dietBoxes.forEach((box, index) => {
            console.log(`🎯 Adding listener to box ${index}:`, box.dataset.value);
            box.addEventListener('click', () => {
                console.log('🔥 CLICKED on:', box.dataset.value);
                console.log('📋 Before toggle:', box.className);
                
                // Toggle selection
                box.classList.toggle('selected');
                
                console.log('📋 After toggle:', box.className);
                console.log('✅ Has selected class:', box.classList.contains('selected'));
                
                // Tạo/update hidden input để lưu giá trị
                let hiddenInput = document.getElementById('selectedDiet');
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'selectedDiet';
                    hiddenInput.id = 'selectedDiet';
                    const form = document.getElementById('multiForm');
                    if (form) {
                        form.appendChild(hiddenInput);
                    }
                    console.log('✅ Created hidden input');
                }

                // Lấy tất cả diet đã chọn
                const selectedDiets = document.querySelectorAll('.form_img_box.selected');
                const values = Array.from(selectedDiets).map(box => box.dataset.value);
                hiddenInput.value = values.join(',');
                console.log('💾 Selected diets:', values);
            });
        });
    }

    handleSubmit() {
        // Validate required fields
        const weight = document.getElementById('weight').value.trim();
        const height = document.getElementById('height').value.trim();
        const age = document.getElementById('age').value.trim();
        const gender = document.querySelector('input[name="gender"]:checked');
        const activity = document.getElementById('activityLevel').value;
        const goal = document.querySelector('input[name="goal"]:checked');

        if (!weight || !height || !age || !gender || !activity || !goal) {
            showWarning('Vui lòng điền đầy đủ thông tin bắt buộc!');
            return;
        }

        // Hiển thị loading
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'flex';
        }

        // Chuyển trang sau 4 giây
        setTimeout(() => {
            window.location.href = 'combo.php';
        }, 4000);
    }
}

// Khởi tạo handler
new TuVanFormHandler();
