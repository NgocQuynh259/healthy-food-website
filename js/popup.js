// Custom Popup System
class CustomPopup {
    constructor() {
        this.currentPopup = null;
    }

    // Show alert popup
    alert(message, type = 'info', title = '') {
        return new Promise((resolve) => {
            this.show({
                type: type,
                title: title || this.getDefaultTitle(type),
                message: message,
                buttons: [
                    {
                        text: 'OK',
                        type: 'primary',
                        callback: () => {
                            this.hide();
                            resolve(true);
                        }
                    }
                ]
            });
        });
    }

    // Show confirm popup
    confirm(message, title = 'Xác nhận') {
        return new Promise((resolve) => {
            this.show({
                type: 'warning',
                title: title,
                message: message,
                buttons: [
                    {
                        text: 'Hủy',
                        type: 'secondary',
                        callback: () => {
                            this.hide();
                            resolve(false);
                        }
                    },
                    {
                        text: 'Xác nhận',
                        type: 'primary',
                        callback: () => {
                            this.hide();
                            resolve(true);
                        }
                    }
                ]
            });
        });
    }

    // Show success popup
    success(message, title = 'Thành công') {
        return this.alert(message, 'success', title);
    }

    // Show error popup
    error(message, title = 'Lỗi') {
        return this.alert(message, 'error', title);
    }

    // Show warning popup
    warning(message, title = 'Cảnh báo') {
        return this.alert(message, 'warning', title);
    }

    // Show info popup
    info(message, title = 'Thông tin') {
        return this.alert(message, 'info', title);
    }

    show(options) {
        // Remove existing popup
        this.hide();

        const overlay = document.createElement('div');
        overlay.className = 'custom-popup-overlay';
        
        const popup = document.createElement('div');
        popup.className = 'custom-popup';

        const icon = this.createIcon(options.type);
        
        popup.innerHTML = `
            <button class="custom-popup-close" onclick="customPopup.hide()">&times;</button>
            <div class="custom-popup-content">
                ${icon}
                <div class="custom-popup-title">${options.title}</div>
                <div class="custom-popup-message">${options.message}</div>
                <div class="custom-popup-buttons">
                    ${this.createButtons(options.buttons)}
                </div>
            </div>
        `;

        overlay.appendChild(popup);
        document.body.appendChild(overlay);

        // Add event listeners for buttons
        options.buttons.forEach((button, index) => {
            const btnElement = popup.querySelector(`.popup-btn-${index}`);
            if (btnElement) {
                btnElement.onclick = button.callback;
            }
        });

        // Show with animation
        setTimeout(() => {
            overlay.classList.add('show');
        }, 10);

        // Close on overlay click
        overlay.onclick = (e) => {
            if (e.target === overlay) {
                this.hide();
            }
        };

        // Close on ESC key
        const handleEsc = (e) => {
            if (e.key === 'Escape') {
                this.hide();
                document.removeEventListener('keydown', handleEsc);
            }
        };
        document.addEventListener('keydown', handleEsc);

        this.currentPopup = overlay;
    }

    hide() {
        if (this.currentPopup) {
            this.currentPopup.classList.remove('show');
            setTimeout(() => {
                if (this.currentPopup && this.currentPopup.parentNode) {
                    this.currentPopup.parentNode.removeChild(this.currentPopup);
                }
                this.currentPopup = null;
            }, 300);
        }
    }

    createIcon(type) {
        const icons = {
            success: '', // Không hiển thị icon cho success
            error: '<div class="custom-popup-icon error">✕</div>',
            warning: '<div class="custom-popup-icon warning">⚠</div>',
            info: '' // Không hiển thị icon cho info
        };
        return icons[type] || '';
    }

    createButtons(buttons) {
        return buttons.map((button, index) => {
            const btnClass = `custom-popup-btn custom-popup-btn-${button.type} popup-btn-${index}`;
            return `<button class="${btnClass}">${button.text}</button>`;
        }).join('');
    }

    getDefaultTitle(type) {
        const titles = {
            success: 'Thành công',
            error: 'Lỗi',
            warning: 'Cảnh báo',
            info: 'Thông tin'
        };
        return titles[type] || 'Thông báo';
    }
}

// Global instance
const customPopup = new CustomPopup();

// Override default alert and confirm
window.originalAlert = window.alert;
window.originalConfirm = window.confirm;

window.alert = function(message) {
    customPopup.alert(message);
};

window.confirm = function(message) {
    return customPopup.confirm(message);
};

// Custom functions for different types
window.showSuccess = function(message, title) {
    return customPopup.success(message, title);
};

window.showError = function(message, title) {
    return customPopup.error(message, title);
};

window.showWarning = function(message, title) {
    return customPopup.warning(message, title);
};

window.showInfo = function(message, title) {
    return customPopup.info(message, title);
};

// For async confirm
window.confirmAsync = function(message, title) {
    return customPopup.confirm(message, title);
};
