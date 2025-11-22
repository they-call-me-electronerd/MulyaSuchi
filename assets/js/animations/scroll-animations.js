/**
 * Scroll Animations
 * Intersection Observer implementation for smooth scroll-triggered animations
 * Optimized for performance with lazy loading
 */

(function () {
    'use strict';

    // Configuration
    const config = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px',
        animationDelay: 100
    };

    /**
     * Initialize Intersection Observer for scroll animations
     */
    function initScrollAnimations() {
        // Check if IntersectionObserver is supported
        if (!('IntersectionObserver' in window)) {
            // Fallback: reveal all elements immediately
            revealAllElements();
            return;
        }

        // Create observer
        const observer = new IntersectionObserver(handleIntersection, {
            threshold: config.threshold,
            rootMargin: config.rootMargin
        });

        // Observe all scroll-reveal elements
        const elements = document.querySelectorAll(
            '.scroll-reveal, .scroll-reveal-left, .scroll-reveal-right, .scroll-reveal-scale'
        );

        elements.forEach((element, index) => {
            // Add staggered delay
            if (element.dataset.delay === undefined) {
                element.style.transitionDelay = `${index * 50}ms`;
            }
            observer.observe(element);
        });
    }

    /**
     * Handle intersection events
     */
    function handleIntersection(entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Add revealed class
                entry.target.classList.add('revealed');

                // Unobserve after revealing (one-time animation)
                observer.unobserve(entry.target);
            }
        });
    }

    /**
     * Fallback: Reveal all elements immediately
     */
    function revealAllElements() {
        const elements = document.querySelectorAll(
            '.scroll-reveal, .scroll-reveal-left, .scroll-reveal-right, .scroll-reveal-scale'
        );
        elements.forEach(element => {
            element.classList.add('revealed');
        });
    }

    /**
     * Parallax effect on scroll
     */
    function initParallax() {
        const parallaxElements = document.querySelectorAll('[data-parallax]');

        if (parallaxElements.length === 0) return;

        let ticking = false;

        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    updateParallax(parallaxElements);
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }

    /**
     * Update parallax positions
     */
    function updateParallax(elements) {
        const scrolled = window.pageYOffset;

        elements.forEach(element => {
            const speed = parseFloat(element.dataset.parallax) || 0.5;
            const yPos = -(scrolled * speed);
            element.style.transform = `translate3d(0, ${yPos}px, 0)`;
        });
    }

    /**
     * Smooth scroll to anchor links
     */
    function initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');

        links.forEach(link => {
            link.addEventListener('click', (e) => {
                const href = link.getAttribute('href');

                // Skip if href is just "#"
                if (href === '#') return;

                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    /**
     * Add scroll progress indicator
     */
    function initScrollProgress() {
        // Create progress bar element
        const progressBar = document.createElement('div');
        progressBar.className = 'scroll-progress-bar';
        progressBar.innerHTML = '<div class="scroll-progress-fill"></div>';
        document.body.appendChild(progressBar);

        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            .scroll-progress-bar {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 3px;
                background: rgba(0, 0, 0, 0.1);
                z-index: 9999;
                pointer-events: none;
            }
            .scroll-progress-fill {
                height: 100%;
                background: linear-gradient(90deg, #f97316, #ea580c);
                width: 0%;
                transition: width 0.1s ease;
            }
            [data-theme="dark"] .scroll-progress-bar {
                background: rgba(255, 255, 255, 0.1);
            }
        `;
        document.head.appendChild(style);

        const fill = progressBar.querySelector('.scroll-progress-fill');
        let ticking = false;

        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    updateScrollProgress(fill);
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }

    /**
     * Update scroll progress
     */
    function updateScrollProgress(fill) {
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollPercent = (scrollTop / (documentHeight - windowHeight)) * 100;

        fill.style.width = `${Math.min(scrollPercent, 100)}%`;
    }

    /**
     * Stagger animation for grid items
     */
    function initStaggeredAnimations() {
        const grids = document.querySelectorAll('[data-stagger]');

        grids.forEach(grid => {
            const items = grid.children;
            const delay = parseInt(grid.dataset.stagger) || 100;

            Array.from(items).forEach((item, index) => {
                item.style.animationDelay = `${index * delay}ms`;
            });
        });
    }

    /**
     * Initialize all scroll animations
     */
    function init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                initScrollAnimations();
                initParallax();
                initSmoothScroll();
                initScrollProgress();
                initStaggeredAnimations();
            });
        } else {
            initScrollAnimations();
            initParallax();
            initSmoothScroll();
            initScrollProgress();
            initStaggeredAnimations();
        }
    }

    // Initialize
    init();

    // Export for external use
    window.ScrollAnimations = {
        init: initScrollAnimations,
        reveal: revealAllElements
    };

})();
