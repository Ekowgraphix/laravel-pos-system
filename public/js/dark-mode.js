// Dark Mode Toggle for Laravel POS

(function() {
    'use strict';

    // Get saved theme from localStorage or default to light
    const savedTheme = localStorage.getItem('theme') || 'light';
    
    // Apply theme immediately to prevent flash
    document.documentElement.setAttribute('data-theme', savedTheme);

    // Initialize dark mode on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeDarkMode();
    });

    function initializeDarkMode() {
        // Create toggle button if it doesn't exist
        if (!document.querySelector('.dark-mode-toggle')) {
            createToggleButton();
        }

        // Update toggle button icon
        updateToggleIcon();

        // Add event listener to toggle button
        const toggleBtn = document.querySelector('.dark-mode-toggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleDarkMode);
        }

        // Listen for system theme changes
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                if (!localStorage.getItem('theme')) {
                    setTheme(e.matches ? 'dark' : 'light');
                }
            });
        }
    }

    function createToggleButton() {
        const button = document.createElement('button');
        button.className = 'dark-mode-toggle';
        button.setAttribute('aria-label', 'Toggle dark mode');
        button.innerHTML = '<i class="fa-solid fa-moon"></i>';
        document.body.appendChild(button);
    }

    function toggleDarkMode() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        setTheme(newTheme);
    }

    function setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        updateToggleIcon();
        
        // Dispatch custom event for theme change
        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));
    }

    function updateToggleIcon() {
        const toggleBtn = document.querySelector('.dark-mode-toggle');
        if (!toggleBtn) return;

        const currentTheme = document.documentElement.getAttribute('data-theme');
        const icon = toggleBtn.querySelector('i');

        if (currentTheme === 'dark') {
            icon.className = 'fa-solid fa-sun';
            toggleBtn.title = 'Switch to light mode';
        } else {
            icon.className = 'fa-solid fa-moon';
            toggleBtn.title = 'Switch to dark mode';
        }
    }

    // Public API
    window.DarkMode = {
        get current() {
            return document.documentElement.getAttribute('data-theme');
        },
        
        set(theme) {
            if (theme === 'dark' || theme === 'light') {
                setTheme(theme);
            }
        },
        
        toggle() {
            toggleDarkMode();
        },
        
        isDark() {
            return this.current === 'dark';
        },
        
        isLight() {
            return this.current === 'light';
        }
    };

    // Auto-detect system preference on first visit
    if (!localStorage.getItem('theme')) {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            setTheme('dark');
        }
    }
})();

// Example usage:
// DarkMode.set('dark');      // Set to dark mode
// DarkMode.set('light');     // Set to light mode
// DarkMode.toggle();         // Toggle between modes
// DarkMode.isDark();         // Check if dark mode
// console.log(DarkMode.current); // Get current theme

// Listen for theme changes
// window.addEventListener('themeChanged', function(e) {
//     console.log('Theme changed to:', e.detail.theme);
// });
