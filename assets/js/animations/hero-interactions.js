/**
 * Hero Interactions
 * Mouse parallax and interactive effects for hero section
 * Optimized for smooth performance
 */

(function () {
    'use strict';

    let mouseX = 0;
    let mouseY = 0;
    let currentX = 0;
    let currentY = 0;

    /**
     * Initialize mouse parallax effect
     */
    function initMouseParallax() {
        const illustration = document.querySelector('.hero-illustration');

        if (!illustration) return;

        // Add data attribute for parallax
        illustration.dataset.parallaxMouse = 'true';

        // Track mouse movement
        document.addEventListener('mousemove', (e) => {
            mouseX = (e.clientX / window.innerWidth - 0.5) * 2;
            mouseY = (e.clientY / window.innerHeight - 0.5) * 2;
        });

        // Smooth animation loop
        function animate() {
            // Smooth interpolation
            currentX += (mouseX - currentX) * 0.1;
            currentY += (mouseY - currentY) * 0.1;

            // Apply transform
            const translateX = currentX * 20;
            const translateY = currentY * 20;
            illustration.style.transform = `translate(${translateX}px, ${translateY}px)`;

            requestAnimationFrame(animate);
        }

        animate();
    }

    /**
     * Add ripple effect to buttons
     */
    function initRippleEffect() {
        const buttons = document.querySelectorAll('.hero-search-btn, .nav-btn');

        buttons.forEach(button => {
            button.addEventListener('click', function (e) {
                const ripple = document.createElement('span');
                ripple.classList.add('ripple-effect');

                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';

                this.appendChild(ripple);

                setTimeout(() => ripple.remove(), 600);
            });
        });

        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            .ripple-effect {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple-animation 0.6s ease-out;
                pointer-events: none;
            }
            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }

    /**
     * Animate SVG elements on hover
     */
    function initSVGAnimations() {
        const svg = document.querySelector('.illustration-img');

        if (!svg) return;

        const elements = svg.querySelectorAll('circle, ellipse, rect, path');

        elements.forEach((element, index) => {
            // Add subtle floating animation with different delays
            element.style.animation = `float 3s ease-in-out ${index * 0.1}s infinite`;
        });

        // Add hover effect
        svg.addEventListener('mouseenter', () => {
            elements.forEach(element => {
                element.style.animationPlayState = 'paused';
                element.style.transform = 'scale(1.05)';
                element.style.transition = 'transform 0.3s ease';
            });
        });

        svg.addEventListener('mouseleave', () => {
            elements.forEach(element => {
                element.style.animationPlayState = 'running';
                element.style.transform = 'scale(1)';
            });
        });
    }

    /**
     * Typing effect for search placeholder
     */
    function initTypingEffect() {
        const searchInput = document.querySelector('.hero-search-input');

        if (!searchInput) return;

        const placeholders = [
            'Search for rice, tomatoes, mobile phones...',
            'Try: potatoes, milk, laptops...',
            'Find: vegetables, fruits, electronics...',
            'Search: dal, cooking oil, headphones...'
        ];

        let currentIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let typingSpeed = 100;

        function type() {
            const current = placeholders[currentIndex];

            if (isDeleting) {
                searchInput.placeholder = current.substring(0, charIndex - 1);
                charIndex--;
                typingSpeed = 50;
            } else {
                searchInput.placeholder = current.substring(0, charIndex + 1);
                charIndex++;
                typingSpeed = 100;
            }

            if (!isDeleting && charIndex === current.length) {
                // Pause at end
                typingSpeed = 2000;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                currentIndex = (currentIndex + 1) % placeholders.length;
                typingSpeed = 500;
            }

            setTimeout(type, typingSpeed);
        }

        // Start typing effect after a delay
        setTimeout(type, 1000);
    }

    /**
     * Add magnetic effect to buttons
     */
    function initMagneticButtons() {
        const buttons = document.querySelectorAll('.nav-btn, .hero-search-btn');

        buttons.forEach(button => {
            button.addEventListener('mousemove', (e) => {
                const rect = button.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;

                button.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px)`;
            });

            button.addEventListener('mouseleave', () => {
                button.style.transform = 'translate(0, 0)';
            });
        });
    }

    /**
     * Initialize all hero interactions
     */
    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                initMouseParallax();
                initRippleEffect();
                initSVGAnimations();
                initTypingEffect();
                initMagneticButtons();
            });
        } else {
            initMouseParallax();
            initRippleEffect();
            initSVGAnimations();
            initTypingEffect();
            initMagneticButtons();
        }
    }

    // Initialize
    init();

    // Export for external use
    window.HeroInteractions = {
        init: init
    };

})();
