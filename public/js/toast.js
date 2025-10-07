// Modern Toast Notification System
class ToastNotification {
    constructor() {
        this.container = this.createContainer();
        this.setupStyles();
    }

    createContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container';
        document.body.appendChild(container);
        return container;
    }

    setupStyles() {
        if (!document.getElementById('toast-styles')) {
            const styles = document.createElement('style');
            styles.id = 'toast-styles';
            styles.textContent = `
                .toast-container {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 9999;
                    pointer-events: none;
                }

                .toast {
                    background: white;
                    border-radius: 12px;
                    padding: 16px 20px;
                    margin-bottom: 12px;
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15), 0 4px 6px rgba(0, 0, 0, 0.1);
                    transform: translateX(400px);
                    opacity: 0;
                    pointer-events: auto;
                    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                    border-left: 4px solid;
                    max-width: 380px;
                    position: relative;
                    overflow: hidden;
                }

                .toast.show {
                    transform: translateX(0);
                    opacity: 1;
                }

                .toast.hide {
                    transform: translateX(400px);
                    opacity: 0;
                }

                .toast-success {
                    border-left-color: #10b981;
                    background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
                }

                .toast-error {
                    border-left-color: #ef4444;
                    background: linear-gradient(135deg, #fef2f2 0%, #fef5f5 100%);
                }

                .toast-warning {
                    border-left-color: #f59e0b;
                    background: linear-gradient(135deg, #fffbeb 0%, #fefce8 100%);
                }

                .toast-info {
                    border-left-color: #3b82f6;
                    background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
                }

                .toast-header {
                    display: flex;
                    align-items: center;
                    margin-bottom: 8px;
                }

                .toast-icon {
                    width: 24px;
                    height: 24px;
                    margin-right: 12px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 14px;
                    font-weight: bold;
                }

                .toast-success .toast-icon {
                    background: #10b981;
                    color: white;
                }

                .toast-error .toast-icon {
                    background: #ef4444;
                    color: white;
                }

                .toast-warning .toast-icon {
                    background: #f59e0b;
                    color: white;
                }

                .toast-info .toast-icon {
                    background: #3b82f6;
                    color: white;
                }

                .toast-title {
                    font-weight: 600;
                    font-size: 16px;
                    color: #1f2937;
                    margin: 0;
                }

                .toast-message {
                    color: #6b7280;
                    font-size: 14px;
                    line-height: 1.4;
                    margin: 0;
                }

                .toast-close {
                    position: absolute;
                    top: 12px;
                    right: 12px;
                    background: none;
                    border: none;
                    font-size: 18px;
                    color: #9ca3af;
                    cursor: pointer;
                    padding: 4px;
                    border-radius: 4px;
                    transition: color 0.2s;
                }

                .toast-close:hover {
                    color: #4b5563;
                }

                .toast-progress {
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    height: 3px;
                    background: rgba(0, 0, 0, 0.1);
                    transition: width linear;
                }

                .toast-success .toast-progress {
                    background: #10b981;
                }

                .toast-error .toast-progress {
                    background: #ef4444;
                }

                .toast-warning .toast-progress {
                    background: #f59e0b;
                }

                .toast-info .toast-progress {
                    background: #3b82f6;
                }

                @keyframes bounce {
                    0%, 20%, 53%, 80%, 100% {
                        transform: translate3d(0,0,0);
                    }
                    40%, 43% {
                        transform: translate3d(0, -8px, 0);
                    }
                    70% {
                        transform: translate3d(0, -4px, 0);
                    }
                    90% {
                        transform: translate3d(0, -2px, 0);
                    }
                }

                .toast.bounce {
                    animation: bounce 0.6s;
                }

                @media (max-width: 640px) {
                    .toast-container {
                        top: 10px;
                        right: 10px;
                        left: 10px;
                    }

                    .toast {
                        max-width: none;
                        transform: translateY(-100px);
                    }

                    .toast.show {
                        transform: translateY(0);
                    }

                    .toast.hide {
                        transform: translateY(-100px);
                    }
                }
            `;
            document.head.appendChild(styles);
        }
    }

    show(type, title, message, duration = 5000) {
        const toast = this.createToast(type, title, message, duration);
        this.container.appendChild(toast);

        // Trigger show animation
        setTimeout(() => {
            toast.classList.add('show', 'bounce');
        }, 10);

        // Auto remove
        if (duration > 0) {
            this.startProgress(toast, duration);
            setTimeout(() => {
                this.hide(toast);
            }, duration);
        }

        return toast;
    }

    createToast(type, title, message, duration) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'i'
        };

        toast.innerHTML = `
            <button class="toast-close" onclick="window.toast.hide(this.parentElement)">&times;</button>
            <div class="toast-header">
                <div class="toast-icon">${icons[type]}</div>
                <h4 class="toast-title">${title}</h4>
            </div>
            <p class="toast-message">${message}</p>
            ${duration > 0 ? '<div class="toast-progress"></div>' : ''}
        `;

        return toast;
    }

    startProgress(toast, duration) {
        const progress = toast.querySelector('.toast-progress');
        if (progress) {
            progress.style.width = '100%';
            progress.style.transition = `width ${duration}ms linear`;
            setTimeout(() => {
                progress.style.width = '0%';
            }, 10);
        }
    }

    hide(toast) {
        toast.classList.remove('show', 'bounce');
        toast.classList.add('hide');
        
        setTimeout(() => {
            if (toast.parentElement) {
                toast.parentElement.removeChild(toast);
            }
        }, 400);
    }

    success(title, message, duration = 5000) {
        return this.show('success', title, message, duration);
    }

    error(title, message, duration = 7000) {
        return this.show('error', title, message, duration);
    }

    warning(title, message, duration = 6000) {
        return this.show('warning', title, message, duration);
    }

    info(title, message, duration = 5000) {
        return this.show('info', title, message, duration);
    }
}

// Global toast instance
window.toast = new ToastNotification();

// Quick access functions
window.showSuccess = (title, message, duration) => window.toast.success(title, message, duration);
window.showError = (title, message, duration) => window.toast.error(title, message, duration);
window.showWarning = (title, message, duration) => window.toast.warning(title, message, duration);
window.showInfo = (title, message, duration) => window.toast.info(title, message, duration);