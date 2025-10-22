// PWA Installation Handler for Laravel POS

(function() {
    'use strict';

    let deferredPrompt;
    let isInstalled = false;

    // Check if already installed
    if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone) {
        isInstalled = true;
        console.log('[PWA] Already installed');
    }

    // Register service worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker
                .register('/service-worker.js')
                .then((registration) => {
                    console.log('[PWA] Service Worker registered:', registration.scope);
                    
                    // Check for updates
                    registration.addEventListener('updatefound', () => {
                        const newWorker = registration.installing;
                        console.log('[PWA] New Service Worker found');
                        
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                showUpdateNotification();
                            }
                        });
                    });
                })
                .catch((error) => {
                    console.error('[PWA] Service Worker registration failed:', error);
                });
        });
    }

    // Listen for install prompt
    window.addEventListener('beforeinstallprompt', (e) => {
        console.log('[PWA] Install prompt triggered');
        e.preventDefault();
        deferredPrompt = e;
        
        if (!isInstalled) {
            showInstallPrompt();
        }
    });

    // Listen for app installed
    window.addEventListener('appinstalled', () => {
        console.log('[PWA] App installed successfully');
        isInstalled = true;
        hideInstallPrompt();
        showNotification('App installed successfully!', 'success');
    });

    // Show install prompt banner
    function showInstallPrompt() {
        // Check if user dismissed before
        if (localStorage.getItem('pwa-install-dismissed')) {
            return;
        }

        const banner = document.createElement('div');
        banner.id = 'pwa-install-banner';
        banner.className = 'pwa-install-banner';
        banner.innerHTML = `
            <div class="pwa-banner-content">
                <div class="pwa-banner-icon">
                    <i class="fa-solid fa-download"></i>
                </div>
                <div class="pwa-banner-text">
                    <strong>Install Laravel POS</strong>
                    <p>Install our app for faster access and offline support</p>
                </div>
                <div class="pwa-banner-actions">
                    <button class="btn btn-primary btn-sm" id="pwa-install-btn">
                        Install
                    </button>
                    <button class="btn btn-secondary btn-sm" id="pwa-dismiss-btn">
                        Not Now
                    </button>
                </div>
            </div>
        `;

        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            .pwa-install-banner {
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: white;
                border-radius: 10px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                padding: 15px 20px;
                z-index: 9999;
                max-width: 500px;
                width: 90%;
                animation: slideUp 0.3s ease;
            }
            
            [data-theme="dark"] .pwa-install-banner {
                background: #232730;
                border: 1px solid #495057;
            }
            
            @keyframes slideUp {
                from { bottom: -100px; opacity: 0; }
                to { bottom: 20px; opacity: 1; }
            }
            
            .pwa-banner-content {
                display: flex;
                align-items: center;
                gap: 15px;
            }
            
            .pwa-banner-icon {
                font-size: 32px;
                color: #0d6efd;
            }
            
            .pwa-banner-text {
                flex: 1;
            }
            
            .pwa-banner-text p {
                margin: 0;
                font-size: 14px;
                color: #6c757d;
            }
            
            .pwa-banner-actions {
                display: flex;
                gap: 10px;
            }
        `;
        document.head.appendChild(style);
        document.body.appendChild(banner);

        // Add event listeners
        document.getElementById('pwa-install-btn').addEventListener('click', installApp);
        document.getElementById('pwa-dismiss-btn').addEventListener('click', dismissInstallPrompt);
    }

    function hideInstallPrompt() {
        const banner = document.getElementById('pwa-install-banner');
        if (banner) {
            banner.style.animation = 'slideUp 0.3s ease reverse';
            setTimeout(() => banner.remove(), 300);
        }
    }

    function dismissInstallPrompt() {
        localStorage.setItem('pwa-install-dismissed', Date.now());
        hideInstallPrompt();
    }

    // Install app
    async function installApp() {
        if (!deferredPrompt) {
            console.log('[PWA] No install prompt available');
            return;
        }

        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        
        console.log('[PWA] User choice:', outcome);
        
        if (outcome === 'accepted') {
            hideInstallPrompt();
        }
        
        deferredPrompt = null;
    }

    // Show update notification
    function showUpdateNotification() {
        if (confirm('A new version is available! Reload to update?')) {
            navigator.serviceWorker.getRegistration().then((registration) => {
                registration.waiting.postMessage({ action: 'skipWaiting' });
                window.location.reload();
            });
        }
    }

    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} pwa-notification`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            min-width: 300px;
            animation: slideIn 0.3s ease;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideIn 0.3s ease reverse';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Check online/offline status
    window.addEventListener('online', () => {
        showNotification('Back online!', 'success');
        
        // Trigger background sync if available
        if ('sync' in navigator.serviceWorker) {
            navigator.serviceWorker.ready.then((registration) => {
                return registration.sync.register('sync-orders');
            }).catch((err) => {
                console.error('[PWA] Background sync registration failed:', err);
            });
        }
    });

    window.addEventListener('offline', () => {
        showNotification('You are offline. Some features may be limited.', 'warning');
    });

    // Public API
    window.PWA = {
        isInstalled() {
            return isInstalled;
        },
        
        canInstall() {
            return !!deferredPrompt;
        },
        
        install() {
            return installApp();
        },
        
        isOnline() {
            return navigator.onLine;
        },
        
        async requestNotificationPermission() {
            if ('Notification' in window) {
                const permission = await Notification.requestPermission();
                console.log('[PWA] Notification permission:', permission);
                return permission;
            }
            return 'denied';
        }
    };

    console.log('[PWA] Initialized successfully');
})();
