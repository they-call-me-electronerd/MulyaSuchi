/**
 * Counter Animation
 * Smooth count-up animation for statistics
 * Triggered when element enters viewport
 */

(function () {
    'use strict';

    /**
     * Animate a number from start to end
     */
    function animateCounter(element, start, end, duration, suffix = '') {
        const startTime = performance.now();
        const range = end - start;

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Easing function (ease-out)
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(start + (range * easeOut));

            // Format number with commas
            const formatted = current.toLocaleString('en-US');
            element.textContent = formatted + suffix;

            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                // Ensure final value is exact
                element.textContent = end.toLocaleString('en-US') + suffix;
            }
        }

        requestAnimationFrame(update);
    }

    /**
     * Initialize counter animations
     */
    function initCounters() {
        const counters = document.querySelectorAll('[data-counter]');

        if (counters.length === 0) return;

        // Check if IntersectionObserver is supported
        if (!('IntersectionObserver' in window)) {
            // Fallback: show final values immediately
            counters.forEach(counter => {
                const endValue = parseInt(counter.dataset.counter) || 0;
                const suffix = counter.dataset.suffix || '';
                counter.textContent = endValue.toLocaleString('en-US') + suffix;
            });
            return;
        }

        // Create observer
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.dataset.animated) {
                    const element = entry.target;
                    const endValue = parseInt(element.dataset.counter) || 0;
                    const startValue = parseInt(element.dataset.start) || 0;
                    const duration = parseInt(element.dataset.duration) || 2000;
                    const suffix = element.dataset.suffix || '';

                    // Mark as animated
                    element.dataset.animated = 'true';

                    // Start animation
                    animateCounter(element, startValue, endValue, duration, suffix);

                    // Unobserve after animation starts
                    observer.unobserve(element);
                }
            });
        }, {
            threshold: 0.5
        });

        // Observe all counters
        counters.forEach(counter => observer.observe(counter));
    }

    /**
     * Initialize on DOM ready
     */
    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCounters);
        } else {
            initCounters();
        }
    }

    // Initialize
    init();

    // Export for external use
    window.CounterAnimation = {
        init: initCounters,
        animate: animateCounter
    };

})();
