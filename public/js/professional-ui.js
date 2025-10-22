/**
 * Professional UI Enhancement Script
 * Adds world-class interactions, animations, and user experience
 */

(function() {
    'use strict';

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeAnimations();
        initializeLoadingStates();
        initializeFormEnhancements();
        initializeScrollEffects();
        initializeTooltips();
        initializeCardAnimations();
        initializeButtonEffects();
        initializeImageLoading();
        initializeNumberAnimations();
    });

    /**
     * Smooth entrance animations for page elements
     */
    function initializeAnimations() {
        // Fade in elements on page load
        const fadeElements = document.querySelectorAll('.order-card, .product-card, .checkout-card, .info-section');
        
        fadeElements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, 100 * index);
        });

        // Add pulse animation to payment badges
        const paymentBadges = document.querySelectorAll('.payment-unpaid');
        paymentBadges.forEach(badge => {
            setInterval(() => {
                badge.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    badge.style.transform = 'scale(1)';
                }, 300);
            }, 3000);
        });
    }

    /**
     * Enhanced loading states for buttons
     */
    function initializeLoadingStates() {
        const actionButtons = document.querySelectorAll('.btn-pay, .btn-checkout, .pay-now-btn');
        
        actionButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!this.classList.contains('loading')) {
                    this.classList.add('loading');
                    const originalText = this.innerHTML;
                    
                    // Add loading spinner
                    this.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Processing...
                    `;
                    this.disabled = true;
                    
                    // Simulate loading (will be overridden by actual navigation)
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('loading');
                        this.disabled = false;
                    }, 5000);
                }
            });
        });
    }

    /**
     * Real-time form validation and enhancement
     */
    function initializeFormEnhancements() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            // Add floating labels effect
            const inputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"]');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });
                
                // Real-time validation feedback
                input.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });
            });
            
            // Prevent double submission
            form.addEventListener('submit', function(e) {
                const submitButton = this.querySelector('button[type="submit"]');
                if (submitButton && !submitButton.disabled) {
                    submitButton.disabled = true;
                    submitButton.classList.add('submitting');
                }
            });
        });
    }

    /**
     * Smooth scroll effects and parallax
     */
    function initializeScrollEffects() {
        let lastScrollTop = 0;
        const heroSections = document.querySelectorAll('.orders-hero, .checkout-hero, .order-details-hero');
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Parallax effect for hero sections
            heroSections.forEach(hero => {
                if (hero) {
                    const offset = scrollTop * 0.5;
                    hero.style.transform = `translateY(${offset}px)`;
                }
            });
            
            // Show/hide back-to-top button
            const backToTop = document.getElementById('backToTop');
            if (backToTop) {
                if (scrollTop > 300) {
                    backToTop.style.opacity = '1';
                    backToTop.style.visibility = 'visible';
                } else {
                    backToTop.style.opacity = '0';
                    backToTop.style.visibility = 'hidden';
                }
            }
            
            lastScrollTop = scrollTop;
        });

        // Add back-to-top button if not exists
        if (!document.getElementById('backToTop')) {
            const backToTop = document.createElement('button');
            backToTop.id = 'backToTop';
            backToTop.innerHTML = '<i class="fas fa-arrow-up"></i>';
            backToTop.className = 'back-to-top';
            backToTop.style.cssText = `
                position: fixed;
                bottom: 30px;
                right: 30px;
                width: 50px;
                height: 50px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: none;
                border-radius: 50%;
                cursor: pointer;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
                z-index: 1000;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
            `;
            
            backToTop.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
            
            backToTop.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.1)';
                this.style.boxShadow = '0 12px 35px rgba(102, 126, 234, 0.6)';
            });
            
            backToTop.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
                this.style.boxShadow = '0 8px 25px rgba(102, 126, 234, 0.4)';
            });
            
            document.body.appendChild(backToTop);
        }
    }

    /**
     * Enhanced tooltips for better UX
     */
    function initializeTooltips() {
        const tooltipElements = document.querySelectorAll('[data-tooltip]');
        
        tooltipElements.forEach(element => {
            element.style.position = 'relative';
            element.style.cursor = 'help';
            
            element.addEventListener('mouseenter', function() {
                const tooltip = document.createElement('div');
                tooltip.className = 'custom-tooltip';
                tooltip.textContent = this.getAttribute('data-tooltip');
                tooltip.style.cssText = `
                    position: absolute;
                    bottom: 100%;
                    left: 50%;
                    transform: translateX(-50%) translateY(-10px);
                    background: rgba(0, 0, 0, 0.9);
                    color: white;
                    padding: 8px 12px;
                    border-radius: 6px;
                    font-size: 13px;
                    white-space: nowrap;
                    z-index: 1000;
                    opacity: 0;
                    transition: opacity 0.3s ease, transform 0.3s ease;
                    pointer-events: none;
                `;
                
                this.appendChild(tooltip);
                
                setTimeout(() => {
                    tooltip.style.opacity = '1';
                    tooltip.style.transform = 'translateX(-50%) translateY(-5px)';
                }, 10);
            });
            
            element.addEventListener('mouseleave', function() {
                const tooltip = this.querySelector('.custom-tooltip');
                if (tooltip) {
                    tooltip.style.opacity = '0';
                    setTimeout(() => {
                        tooltip.remove();
                    }, 300);
                }
            });
        });
    }

    /**
     * Card hover effects and interactions
     */
    function initializeCardAnimations() {
        const cards = document.querySelectorAll('.order-card, .product-card, .checkout-card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            });
            
            // Add ripple effect on click
            card.addEventListener('click', function(e) {
                if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON') return;
                
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    border-radius: 50%;
                    background: rgba(102, 126, 234, 0.2);
                    left: ${x}px;
                    top: ${y}px;
                    transform: scale(0);
                    animation: ripple 0.6s ease-out;
                    pointer-events: none;
                `;
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            });
        });
        
        // Add ripple animation
        if (!document.getElementById('ripple-styles')) {
            const style = document.createElement('style');
            style.id = 'ripple-styles';
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }

    /**
     * Button click effects and feedback
     */
    function initializeButtonEffects() {
        const buttons = document.querySelectorAll('.btn, button, [role="button"]');
        
        buttons.forEach(button => {
            // Add click animation
            button.addEventListener('click', function(e) {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
            
            // Add keyboard accessibility
            button.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    this.click();
                }
            });
        });
    }

    /**
     * Lazy loading for images with fade-in effect
     */
    function initializeImageLoading() {
        const images = document.querySelectorAll('img:not(.loaded)');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.style.opacity = '0';
                    img.style.transition = 'opacity 0.6s ease';
                    
                    img.addEventListener('load', function() {
                        this.style.opacity = '1';
                        this.classList.add('loaded');
                    });
                    
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                    } else if (img.complete) {
                        img.style.opacity = '1';
                        img.classList.add('loaded');
                    }
                    
                    observer.unobserve(img);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '50px'
        });
        
        images.forEach(img => imageObserver.observe(img));
    }

    /**
     * Animate numbers counting up
     */
    function initializeNumberAnimations() {
        const numberElements = document.querySelectorAll('.total-amount, .grand-total, .price-highlight');
        
        const numberObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                    animateNumber(entry.target);
                    entry.target.classList.add('animated');
                }
            });
        }, { threshold: 0.5 });
        
        numberElements.forEach(el => numberObserver.observe(el));
    }

    function animateNumber(element) {
        const text = element.textContent;
        const match = text.match(/[\d,]+\.?\d*/);
        
        if (match) {
            const number = parseFloat(match[0].replace(/,/g, ''));
            const prefix = text.substring(0, match.index);
            const suffix = text.substring(match.index + match[0].length);
            
            let current = 0;
            const increment = number / 30;
            const duration = 1000;
            const stepTime = duration / 30;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= number) {
                    current = number;
                    clearInterval(timer);
                }
                
                const formattedNumber = current.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                
                element.textContent = prefix + formattedNumber + suffix;
            }, stepTime);
        }
    }

    /**
     * Add success/error message animations
     */
    window.showNotification = function(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? 'linear-gradient(135deg, #4caf50, #45a049)' : 'linear-gradient(135deg, #f44336, #da190b)'};
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            transform: translateX(400px);
            transition: transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 10);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(400px)';
            setTimeout(() => notification.remove(), 400);
        }, 4000);
    };

    /**
     * Detect and handle slow network connections
     */
    if ('connection' in navigator) {
        const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
        
        if (connection && (connection.effectiveType === 'slow-2g' || connection.effectiveType === '2g')) {
            // Disable heavy animations for slow connections
            document.body.classList.add('reduced-motion');
            console.log('Slow connection detected - optimizing experience');
        }
    }

    /**
     * Add keyboard shortcuts
     */
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.querySelector('input[type="search"]');
            if (searchInput) searchInput.focus();
        }
        
        // ESC to close modals or go back
        if (e.key === 'Escape') {
            const modal = document.querySelector('.modal.show');
            if (modal) {
                const closeBtn = modal.querySelector('[data-bs-dismiss="modal"]');
                if (closeBtn) closeBtn.click();
            }
        }
    });

    console.log('🚀 Professional UI enhancements loaded successfully!');
})();
