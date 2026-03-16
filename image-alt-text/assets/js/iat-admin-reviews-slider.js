/**
 * Image Alt Text - Reviews Carousel Slider
 * Handles the testimonials slider functionality on the Get Pro page
 */
(function () {
    'use strict';

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initReviewsSlider);
    } else {
        initReviewsSlider();
    }

    function initReviewsSlider() {
        const slider = document.getElementById('iatReviewsSlider');
        const dotsContainer = document.getElementById('iatSliderDots');

        if (!slider) return;

        const cards = slider.querySelectorAll('.iat-testimonial');
        const totalCards = cards.length;

        if (totalCards === 0) return;

        let currentSlide = 0;
        let cardsPerView = 3;

        /**
         * Calculate cards per view based on screen width
         */
        function updateCardsPerView() {
            const width = window.innerWidth;
            if (width < 768) {
                cardsPerView = 1;
            } else if (width < 1024) {
                cardsPerView = 2;
            } else {
                cardsPerView = 3;
            }
        }

        /**
         * Get total number of slides
         * @returns {number} Total slides
         */
        function getTotalSlides() {
            return Math.ceil(totalCards / cardsPerView);
        }

        /**
         * Create dot indicators
         */
        function createDots() {
            dotsContainer.innerHTML = '';
            const totalSlides = getTotalSlides();

            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('span');
                dot.className = 'iat-dot';
                dot.setAttribute('role', 'button');
                dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));

                if (i === 0) {
                    dot.classList.add('active');
                    dot.setAttribute('aria-current', 'true');
                }

                dot.onclick = function () {
                    goToSlide(i);
                };

                dotsContainer.appendChild(dot);
            }
        }

        /**
         * Update dot indicators
         */
        function updateDots() {
            const dots = dotsContainer.querySelectorAll('.iat-dot');
            dots.forEach(function (dot, index) {
                const isActive = index === currentSlide;
                dot.classList.toggle('active', isActive);

                if (isActive) {
                    dot.setAttribute('aria-current', 'true');
                } else {
                    dot.removeAttribute('aria-current');
                }
            });
        }

        /**
         * Update slider position with smooth animation
         */
        function updateSlider() {
            const cardWidth = cards[0].offsetWidth;
            const gap = 20;
            const offset = currentSlide * cardsPerView * (cardWidth + gap);

            slider.style.transform = 'translateX(-' + offset + 'px)';
            updateDots();
            updateButtons();
        }

        /**
         * Update navigation button states
         */
        function updateButtons() {
            const prevBtn = document.querySelector('.iat-slider-prev');
            const nextBtn = document.querySelector('.iat-slider-next');
            const totalSlides = getTotalSlides();

            if (prevBtn) {
                prevBtn.disabled = currentSlide === 0;
                prevBtn.setAttribute('aria-disabled', currentSlide === 0 ? 'true' : 'false');
            }

            if (nextBtn) {
                nextBtn.disabled = currentSlide >= totalSlides - 1;
                nextBtn.setAttribute('aria-disabled', currentSlide >= totalSlides - 1 ? 'true' : 'false');
            }
        }

        /**
         * Move to next/previous slide
         * @param {number} direction - Direction to move (-1 for prev, 1 for next)
         */
        window.iatMoveSlide = function (direction) {
            const totalSlides = getTotalSlides();
            currentSlide += direction;

            // Boundary checks
            if (currentSlide < 0) {
                currentSlide = 0;
            }
            if (currentSlide >= totalSlides) {
                currentSlide = totalSlides - 1;
            }

            updateSlider();
        };

        /**
         * Go to specific slide
         * @param {number} index - Slide index to go to
         */
        function goToSlide(index) {
            const totalSlides = getTotalSlides();

            if (index >= 0 && index < totalSlides) {
                currentSlide = index;
                updateSlider();
            }
        }

        /**
         * Handle keyboard navigation
         * @param {KeyboardEvent} e - Keyboard event
         */
        function handleKeyboard(e) {
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                iatMoveSlide(-1);
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                iatMoveSlide(1);
            }
        }

        /**
         * Initialize slider
         */
        function init() {
            // Set initial cards per view
            updateCardsPerView();

            // Add smooth transition
            slider.style.transition = 'transform 0.5s ease-in-out';

            // Create dots and update buttons
            createDots();
            updateButtons();

            // Add keyboard navigation
            const prevBtn = document.querySelector('.iat-slider-prev');
            const nextBtn = document.querySelector('.iat-slider-next');

            if (prevBtn) {
                prevBtn.addEventListener('keydown', handleKeyboard);
            }
            if (nextBtn) {
                nextBtn.addEventListener('keydown', handleKeyboard);
            }
        }

        /**
         * Handle window resize with debouncing
         */
        let resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                const oldCardsPerView = cardsPerView;
                updateCardsPerView();

                // Reset to first slide if cards per view changed
                if (oldCardsPerView !== cardsPerView) {
                    currentSlide = 0;
                    createDots();
                    updateSlider();
                }
            }, 250);
        });

        // Initialize the slider
        init();
    }
})();
