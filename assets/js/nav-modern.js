/**
 * Modern Navigation Bar - JavaScript Enhancements
 * Handles smooth animations, interactions, and dynamic effects
 */

(function() {
    'use strict';

    // =====================================================================
    // INITIALIZATION
    // =====================================================================

    class ModernNavigation {
        constructor() {
            this.nav = document.querySelector('.modern-nav');
            this.navMenu = document.getElementById('navMenu');
            this.navToggle = document.getElementById('navToggle');
            this.navLinks = document.querySelectorAll('.nav-link');
            this.themeToggle = document.querySelector('.theme-toggle');
            this.searchBtn = document.querySelector('.nav-actions .nav-action-btn:not(.theme-toggle)');
            this.navSearchInput = document.getElementById('navSearchInput');
            this.navSearchBtn = document.querySelector('.nav-search-btn');
            
            this.isMenuOpen = false;
            this.isScrolling = false;
            
            if (this.nav) {
                this.init();
            }
        }

        init() {
            this.attachEventListeners();
            this.handleActiveLink();
            this.setupScrollEffects();
            this.setupRippleEffect();
        }

        // ===================================================================
        // EVENT LISTENERS
        // ===================================================================

        attachEventListeners() {
            // Mobile menu toggle
            if (this.navToggle) {
                this.navToggle.addEventListener('click', () => this.toggleMobileMenu());
            }

            // Close menu when link is clicked
            this.navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (this.isMenuOpen) {
                        this.closeMobileMenu();
                    }
                    this.updateActiveLink(link);
                });
            });

            // Close menu on outside click
            document.addEventListener('click', (e) => {
                if (this.isMenuOpen && !this.nav.contains(e.target)) {
                    this.closeMobileMenu();
                }
            });

            // Theme toggle
            if (this.themeToggle) {
                this.themeToggle.addEventListener('click', () => this.toggleTheme());
            }

            // Search button
            if (this.searchBtn) {
                this.searchBtn.addEventListener('click', () => this.openSearch());
            }

            // Close mobile menu on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isMenuOpen) {
                    this.closeMobileMenu();
                }
            });
            
            // Nav search box functionality
            if (this.navSearchBtn) {
                this.navSearchBtn.addEventListener('click', () => this.performSearch());
            }
            
            if (this.navSearchInput) {
                this.navSearchInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        this.performSearch();
                    }
                });
                
                // Add focus animation
                this.navSearchInput.addEventListener('focus', () => {
                    this.navSearchInput.parentElement.style.transform = 'translateY(-1px)';
                });
                
                this.navSearchInput.addEventListener('blur', () => {
                    this.navSearchInput.parentElement.style.transform = 'translateY(0)';
                });
            }
        }

        // ===================================================================
        // MOBILE MENU FUNCTIONS
        // ===================================================================

        toggleMobileMenu() {
            if (this.isMenuOpen) {
                this.closeMobileMenu();
            } else {
                this.openMobileMenu();
            }
        }

        openMobileMenu() {
            this.isMenuOpen = true;
            this.navMenu.classList.add('active');
            this.navToggle.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Animate menu items
            this.animateMenuItems('in');
        }

        closeMobileMenu() {
            this.isMenuOpen = false;
            this.navMenu.classList.remove('active');
            this.navToggle.classList.remove('active');
            document.body.style.overflow = 'auto';
            
            // Animate menu items
            this.animateMenuItems('out');
        }

        animateMenuItems(direction) {
            const items = this.navMenu.querySelectorAll('.nav-item');
            const isIn = direction === 'in';
            
            items.forEach((item, index) => {
                const delay = isIn ? index * 50 : (items.length - index) * 50;
                
                setTimeout(() => {
                    if (isIn) {
                        item.style.animation = `slideInLeft 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards`;
                    } else {
                        item.style.animation = `slideOutLeft 0.3s ease forwards`;
                    }
                }, delay);
            });
        }

        // ===================================================================
        // ACTIVE LINK HANDLING
        // ===================================================================

        handleActiveLink() {
            const currentPath = window.location.pathname;
            
            this.navLinks.forEach(link => {
                const href = link.getAttribute('href');
                
                // Check if the current page matches the link
                if (href && currentPath.includes(href.split('/').pop())) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }

        updateActiveLink(clickedLink) {
            this.navLinks.forEach(link => {
                link.classList.remove('active');
            });
            clickedLink.classList.add('active');
        }

        // ===================================================================
        // SCROLL EFFECTS
        // ===================================================================

        setupScrollEffects() {
            let scrollTimeout;
            let lastScrollTop = 0;

            window.addEventListener('scroll', () => {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                // Hide/show nav on scroll
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // Scrolling down
                    if (!this.isScrolling) {
                        this.isScrolling = true;
                        this.nav.style.transform = 'translateY(-100%)';
                        this.nav.style.transition = 'transform 0.3s ease';
                    }
                } else {
                    // Scrolling up or at top
                    if (this.isScrolling) {
                        this.isScrolling = false;
                        this.nav.style.transform = 'translateY(0)';
                        this.nav.style.transition = 'transform 0.3s ease';
                    }
                }

                lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;

                // Add shadow on scroll
                if (scrollTop > 10) {
                    this.nav.style.boxShadow = '0 10px 40px rgba(0, 0, 0, 0.15)';
                } else {
                    this.nav.style.boxShadow = 'var(--glass-shadow)';
                }

                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    // Subtle effect when scroll stops
                }, 150);
            });
        }

        // ===================================================================
        // THEME TOGGLE
        // ===================================================================

        toggleTheme() {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const currentTheme = localStorage.getItem('theme') || (prefersDark ? 'dark' : 'light');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            localStorage.setItem('theme', newTheme);
            this.applyTheme(newTheme);
            
            // Animate theme toggle button
            this.themeToggle.style.animation = 'spin 0.6s ease';
            setTimeout(() => {
                this.themeToggle.style.animation = '';
            }, 600);
        }

        applyTheme(theme) {
            const html = document.documentElement;
            
            if (theme === 'dark') {
                html.style.colorScheme = 'dark';
                document.body.classList.add('dark-mode');
            } else {
                html.style.colorScheme = 'light';
                document.body.classList.remove('dark-mode');
            }
        }

        // ===================================================================
        // SEARCH FUNCTIONALITY
        // ===================================================================
        
        performSearch() {
            if (!this.navSearchInput) return;
            
            const searchQuery = this.navSearchInput.value.trim();
            
            if (searchQuery) {
                // Animate search button
                if (this.navSearchBtn) {
                    this.navSearchBtn.style.animation = 'pulse 0.4s ease';
                    setTimeout(() => {
                        this.navSearchBtn.style.animation = '';
                    }, 400);
                }
                
                // Redirect to products page with search query
                const searchUrl = `${window.location.origin}/public/products.php?search=${encodeURIComponent(searchQuery)}`;
                window.location.href = searchUrl;
            }
        }

        openSearch() {
            // Create a simple search modal or dispatch custom event
            const searchEvent = new CustomEvent('nav-search-opened');
            document.dispatchEvent(searchEvent);
            
            // Animate search button
            this.searchBtn.style.animation = 'pulse 0.6s ease';
            setTimeout(() => {
                this.searchBtn.style.animation = '';
            }, 600);
        }

        // ===================================================================
        // RIPPLE EFFECT
        // ===================================================================

        setupRippleEffect() {
            const links = document.querySelectorAll('.nav-link, .nav-action-btn');
            
            links.forEach(link => {
                link.addEventListener('click', (e) => {
                    this.createRipple(e, link);
                });
            });
        }

        createRipple(e, element) {
            const rect = element.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const ripple = document.createElement('span');
            ripple.style.position = 'absolute';
            ripple.style.width = '20px';
            ripple.style.height = '20px';
            ripple.style.background = 'rgba(255, 71, 87, 0.5)';
            ripple.style.borderRadius = '50%';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.style.animation = 'expandRipple 0.6s ease-out';
            ripple.style.pointerEvents = 'none';
            ripple.style.transform = 'translate(-50%, -50%)';
            
            // Only add ripple to elements with position relative
            if (element.style.position !== 'absolute') {
                element.style.position = 'relative';
            }
            
            element.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        }
    }

    // =====================================================================
    // KEYBOARD NAVIGATION
    // =====================================================================

    class KeyboardNavigation {
        constructor() {
            this.navLinks = document.querySelectorAll('.nav-link, .nav-action-btn');
            this.currentIndex = 0;
            
            if (this.navLinks.length > 0) {
                this.init();
            }
        }

        init() {
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Tab') {
                    // Allow native tab behavior
                    return;
                }
                
                // Arrow key navigation (optional)
                if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    this.focusNext();
                } else if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    this.focusPrev();
                }
            });
        }

        focusNext() {
            this.currentIndex = (this.currentIndex + 1) % this.navLinks.length;
            this.navLinks[this.currentIndex].focus();
        }

        focusPrev() {
            this.currentIndex = (this.currentIndex - 1 + this.navLinks.length) % this.navLinks.length;
            this.navLinks[this.currentIndex].focus();
        }
    }

    // =====================================================================
    // PARALLAX EFFECT FOR BACKGROUND
    // =====================================================================

    class ParallaxBackground {
        constructor() {
            this.glassEffect = document.querySelector('.nav-glass-effect');
            
            if (this.glassEffect) {
                this.init();
            }
        }

        init() {
            window.addEventListener('mousemove', (e) => {
                const x = (e.clientX / window.innerWidth) * 100;
                const y = (e.clientY / window.innerHeight) * 100;
                
                this.glassEffect.style.right = (50 - x * 0.2) + '%';
                this.glassEffect.style.top = (50 - y * 0.2) + '%';
            });
        }
    }

    // =====================================================================
    // INITIALIZATION ON DOM READY
    // =====================================================================

    document.addEventListener('DOMContentLoaded', () => {
        new ModernNavigation();
        new KeyboardNavigation();
        new ParallaxBackground();
    });

    // Fallback for older browsers
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            new ModernNavigation();
            new KeyboardNavigation();
            new ParallaxBackground();
        });
    }

})();

// =====================================================================
// ADDITIONAL ANIMATIONS (CSS-in-JS)
// =====================================================================

const style = document.createElement('style');
style.textContent = `
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideOutLeft {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(-20px);
        }
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }

    @keyframes expandRipple {
        from {
            width: 20px;
            height: 20px;
            opacity: 1;
        }
        to {
            width: 200px;
            height: 200px;
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
