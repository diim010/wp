/**
 * RFPlugin Corporate Animation System
 *
 * Mobile-optimized GSAP animations for intuitive, native-feeling UX.
 * Respects reduced motion preferences for accessibility.
 *
 * @package RFPlugin
 * @version 2.0.0
 * @requires gsap ^3.14.0
 * @requires gsap/ScrollTrigger
 */

(function() {
    'use strict';

    // ============================================
    // CONFIGURATION
    // ============================================
    const CONFIG = {
        // Animation durations (seconds)
        duration: {
            instant: 0.1,
            fast: 0.2,
            normal: 0.4,
            slow: 0.6,
            dramatic: 1.0
        },
        // Easing presets (mobile-optimized, native iOS/Android feel)
        ease: {
            // iOS-like spring
            spring: 'elastic.out(1, 0.5)',
            // Native scroll deceleration
            decelerate: 'power2.out',
            // Smooth entrance
            entrance: 'power3.out',
            // Quick response
            snappy: 'power4.out',
            // Bounce effect
            bounce: 'back.out(1.4)',
            // Smooth in-out
            smooth: 'power2.inOut',
            // Exit animations
            exit: 'power2.in'
        },
        // Stagger delays
        stagger: {
            fast: 0.04,
            normal: 0.08,
            slow: 0.12
        },
        // ScrollTrigger defaults
        scroll: {
            start: 'top 85%',
            markers: false
        }
    };

    // ============================================
    // UTILITY FUNCTIONS
    // ============================================

    /**
     * Check if user prefers reduced motion
     */
    function prefersReducedMotion() {
        return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }

    /**
     * Check if device is touch-capable
     */
    function isTouchDevice() {
        return ('ontouchstart' in window) ||
               (navigator.maxTouchPoints > 0) ||
               (navigator.msMaxTouchPoints > 0);
    }

    /**
     * Check if device is mobile/tablet
     */
    function isMobile() {
        return window.innerWidth <= 1024;
    }

    /**
     * Get safe animation values (respects reduced motion)
     */
    function safeAnimation(props) {
        if (prefersReducedMotion()) {
            // Only allow opacity changes for reduced motion
            return {
                opacity: props.opacity ?? 1,
                duration: CONFIG.duration.instant
            };
        }
        return props;
    }

    /**
     * Debounce function for resize events
     */
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // ============================================
    // CORE ANIMATION CLASS
    // ============================================
    class RFAnimations {
        constructor() {
            this.isInitialized = false;
            this.scrollTriggers = [];
            this.observers = [];

            this.init();
        }

        init() {
            // Wait for GSAP
            if (typeof gsap === 'undefined') {
                console.warn('RFAnimations: GSAP not loaded');
                return;
            }

            // Register plugins
            if (typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);
            }

            // Set GSAP defaults for performance
            gsap.defaults({
                overwrite: 'auto',
                force3D: true
            });

            // Initialize all animations
            this.setupInitialStates();
            this.initScrollAnimations();
            this.initInteractionAnimations();
            this.initMobileOptimizations();
            this.initIntersectionObserver();

            this.isInitialized = true;

            // Handle resize
            window.addEventListener('resize', debounce(() => {
                this.refresh();
            }, 250));
        }

        /**
         * Set initial states for animated elements (prevents FOUC)
         */
        setupInitialStates() {
            // Elements that will animate in
            const animateUpElements = document.querySelectorAll('.rf-animate-up, .rf-corp-animate-slide-up');
            gsap.set(animateUpElements, {
                y: 30,
                opacity: 0,
                visibility: 'visible'
            });

            const animateFadeElements = document.querySelectorAll('.rf-animate-fade, .rf-corp-animate-fade-in');
            gsap.set(animateFadeElements, {
                opacity: 0,
                visibility: 'visible'
            });

            const animateScaleElements = document.querySelectorAll('.rf-animate-scale, .rf-corp-animate-scale-in');
            gsap.set(animateScaleElements, {
                scale: 0.9,
                opacity: 0,
                visibility: 'visible'
            });
        }

        /**
         * Scroll-triggered animations
         */
        initScrollAnimations() {
            if (typeof ScrollTrigger === 'undefined') return;

            // Hero sections - dramatic entrance
            document.querySelectorAll('.rf-hero, .rf-header, .rf-premium-ui .rf-header').forEach(hero => {
                const tl = gsap.timeline({
                    scrollTrigger: {
                        trigger: hero,
                        start: 'top 95%',
                        once: true
                    }
                });

                const title = hero.querySelector('.rf-title, h1');
                const subtitle = hero.querySelector('.rf-subtitle, .rf-text');
                const badge = hero.querySelector('.rf-badge');
                const cta = hero.querySelector('.rf-btn, .rf-btn-premium');

                if (badge) {
                    tl.from(badge, safeAnimation({
                        y: -20,
                        opacity: 0,
                        duration: CONFIG.duration.normal,
                        ease: CONFIG.ease.entrance
                    }));
                }

                if (title) {
                    tl.from(title, safeAnimation({
                        y: 40,
                        opacity: 0,
                        duration: CONFIG.duration.slow,
                        ease: CONFIG.ease.entrance
                    }), '-=0.2');
                }

                if (subtitle) {
                    tl.from(subtitle, safeAnimation({
                        y: 30,
                        opacity: 0,
                        duration: CONFIG.duration.normal,
                        ease: CONFIG.ease.decelerate
                    }), '-=0.3');
                }

                if (cta) {
                    tl.from(cta, safeAnimation({
                        y: 20,
                        opacity: 0,
                        duration: CONFIG.duration.normal,
                        ease: CONFIG.ease.entrance
                    }), '-=0.2');
                }

                this.scrollTriggers.push(tl.scrollTrigger);
            });

            // Card grids - staggered entrance
            document.querySelectorAll('.rf-grid, .rf-results-grid, .rf-corp-grid').forEach(grid => {
                const cards = grid.querySelectorAll('.rf-card, .rf-glass-card, .rf-corp-card');

                if (cards.length > 0) {
                    const trigger = ScrollTrigger.create({
                        trigger: grid,
                        start: CONFIG.scroll.start,
                        once: true,
                        onEnter: () => {
                            gsap.to(cards, safeAnimation({
                                y: 0,
                                opacity: 1,
                                duration: CONFIG.duration.slow,
                                stagger: {
                                    each: CONFIG.stagger.fast,
                                    from: 'start',
                                    grid: 'auto'
                                },
                                ease: CONFIG.ease.entrance
                            }));
                        }
                    });
                    this.scrollTriggers.push(trigger);
                }
            });

            // Individual animate-up elements
            document.querySelectorAll('.rf-animate-up:not(.rf-grid *)').forEach(el => {
                const trigger = ScrollTrigger.create({
                    trigger: el,
                    start: 'top 90%',
                    once: true,
                    onEnter: () => {
                        gsap.to(el, safeAnimation({
                            y: 0,
                            opacity: 1,
                            duration: CONFIG.duration.normal,
                            ease: CONFIG.ease.entrance
                        }));
                    }
                });
                this.scrollTriggers.push(trigger);
            });

            // Parallax effects (desktop only)
            if (!isMobile()) {
                document.querySelectorAll('.rf-parallax').forEach(el => {
                    gsap.to(el, {
                        yPercent: -20,
                        ease: 'none',
                        scrollTrigger: {
                            trigger: el,
                            start: 'top bottom',
                            end: 'bottom top',
                            scrub: 1
                        }
                    });
                });
            }
        }

        /**
         * Interactive element animations (buttons, cards, inputs)
         */
        initInteractionAnimations() {
            const isTouch = isTouchDevice();

            // Button interactions
            document.querySelectorAll('.rf-btn, .rf-corp-btn, .rf-btn-premium, .rf-corp-btn--primary').forEach(btn => {
                // Touch feedback (mobile-native feel)
                if (isTouch) {
                    btn.addEventListener('touchstart', (e) => {
                        gsap.to(btn, {
                            scale: 0.95,
                            duration: CONFIG.duration.instant,
                            ease: CONFIG.ease.snappy
                        });
                    }, { passive: true });

                    btn.addEventListener('touchend', () => {
                        gsap.to(btn, {
                            scale: 1,
                            duration: CONFIG.duration.fast,
                            ease: CONFIG.ease.spring
                        });
                    }, { passive: true });

                    btn.addEventListener('touchcancel', () => {
                        gsap.to(btn, {
                            scale: 1,
                            duration: CONFIG.duration.fast,
                            ease: CONFIG.ease.decelerate
                        });
                    }, { passive: true });
                }

                // Mouse feedback (desktop)
                btn.addEventListener('mousedown', () => {
                    gsap.to(btn, {
                        scale: 0.97,
                        duration: CONFIG.duration.instant,
                        ease: CONFIG.ease.snappy
                    });
                });

                btn.addEventListener('mouseup', () => {
                    gsap.to(btn, {
                        scale: 1,
                        duration: CONFIG.duration.fast,
                        ease: CONFIG.ease.spring
                    });
                });

                btn.addEventListener('mouseleave', () => {
                    gsap.to(btn, {
                        scale: 1,
                        duration: CONFIG.duration.fast,
                        ease: CONFIG.ease.decelerate
                    });
                });
            });

            // Card hover effects (desktop only)
            if (!isTouch) {
                document.querySelectorAll('.rf-card, .rf-glass-card, .rf-corp-card').forEach(card => {
                    card.addEventListener('mouseenter', () => {
                        gsap.to(card, {
                            y: -8,
                            scale: 1.02,
                            duration: CONFIG.duration.normal,
                            ease: CONFIG.ease.decelerate
                        });
                    });

                    card.addEventListener('mouseleave', () => {
                        gsap.to(card, {
                            y: 0,
                            scale: 1,
                            duration: CONFIG.duration.normal,
                            ease: CONFIG.ease.smooth
                        });
                    });
                });
            }

            // Input focus animations
            document.querySelectorAll('.rf-input-premium, .rf-corp-input, input[class*="rf-"]').forEach(input => {
                input.addEventListener('focus', () => {
                    gsap.to(input, {
                        scale: 1.01,
                        duration: CONFIG.duration.fast,
                        ease: CONFIG.ease.decelerate
                    });

                    // Animate label if it exists
                    const label = input.closest('.rf-form-group, .rf-corp-form-group')?.querySelector('label');
                    if (label) {
                        gsap.to(label, {
                            color: 'var(--rf-corp-primary)',
                            duration: CONFIG.duration.fast
                        });
                    }
                });

                input.addEventListener('blur', () => {
                    gsap.to(input, {
                        scale: 1,
                        duration: CONFIG.duration.fast,
                        ease: CONFIG.ease.smooth
                    });

                    const label = input.closest('.rf-form-group, .rf-corp-form-group')?.querySelector('label');
                    if (label) {
                        gsap.to(label, {
                            color: 'var(--rf-corp-text)',
                            duration: CONFIG.duration.fast
                        });
                    }
                });
            });

            // Tab/navigation animations
            document.querySelectorAll('.rf-tab, .rf-nav-item').forEach(tab => {
                tab.addEventListener('click', function() {
                    // Animate indicator
                    const activeIndicator = this.closest('.rf-tabs')?.querySelector('.rf-tab-indicator');
                    if (activeIndicator) {
                        gsap.to(activeIndicator, {
                            x: this.offsetLeft,
                            width: this.offsetWidth,
                            duration: CONFIG.duration.normal,
                            ease: CONFIG.ease.snappy
                        });
                    }
                });
            });
        }

        /**
         * Mobile-specific optimizations
         */
        initMobileOptimizations() {
            if (!isMobile()) return;

            // Reduce animation complexity on mobile for performance
            gsap.defaults({
                duration: CONFIG.duration.normal * 0.8 // Slightly faster on mobile
            });

            // Pull-to-refresh style header animation
            let lastScrollY = 0;
            let ticking = false;

            const header = document.querySelector('.rf-sticky-header, .site-header');
            if (header) {
                window.addEventListener('scroll', () => {
                    if (!ticking) {
                        window.requestAnimationFrame(() => {
                            const currentScrollY = window.scrollY;

                            if (currentScrollY > lastScrollY && currentScrollY > 100) {
                                // Scrolling down - hide header
                                gsap.to(header, {
                                    y: -header.offsetHeight,
                                    duration: CONFIG.duration.normal,
                                    ease: CONFIG.ease.exit
                                });
                            } else {
                                // Scrolling up - show header
                                gsap.to(header, {
                                    y: 0,
                                    duration: CONFIG.duration.normal,
                                    ease: CONFIG.ease.entrance
                                });
                            }

                            lastScrollY = currentScrollY;
                            ticking = false;
                        });
                        ticking = true;
                    }
                }, { passive: true });
            }

            // Bottom sheet animation helper
            document.querySelectorAll('.rf-bottom-sheet').forEach(sheet => {
                const handle = sheet.querySelector('.rf-sheet-handle');
                if (handle) {
                    let startY = 0;
                    let currentY = 0;

                    handle.addEventListener('touchstart', (e) => {
                        startY = e.touches[0].clientY;
                    }, { passive: true });

                    handle.addEventListener('touchmove', (e) => {
                        currentY = e.touches[0].clientY;
                        const deltaY = currentY - startY;

                        if (deltaY > 0) { // Only allow dragging down
                            gsap.set(sheet, { y: deltaY });
                        }
                    }, { passive: true });

                    handle.addEventListener('touchend', () => {
                        const deltaY = currentY - startY;

                        if (deltaY > 100) { // Dismiss threshold
                            gsap.to(sheet, {
                                y: '100%',
                                duration: CONFIG.duration.normal,
                                ease: CONFIG.ease.exit,
                                onComplete: () => {
                                    sheet.classList.remove('is-open');
                                }
                            });
                        } else {
                            gsap.to(sheet, {
                                y: 0,
                                duration: CONFIG.duration.normal,
                                ease: CONFIG.ease.spring
                            });
                        }
                    }, { passive: true });
                }
            });
        }

        /**
         * Intersection Observer for lazy animation initialization
         */
        initIntersectionObserver() {
            if (!('IntersectionObserver' in window)) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const el = entry.target;

                        // Trigger animation based on data attribute
                        const animation = el.dataset.rfAnimate;

                        switch(animation) {
                            case 'fade-up':
                                gsap.to(el, safeAnimation({
                                    y: 0,
                                    opacity: 1,
                                    duration: CONFIG.duration.normal,
                                    ease: CONFIG.ease.entrance
                                }));
                                break;
                            case 'fade':
                                gsap.to(el, safeAnimation({
                                    opacity: 1,
                                    duration: CONFIG.duration.normal,
                                    ease: CONFIG.ease.decelerate
                                }));
                                break;
                            case 'scale':
                                gsap.to(el, safeAnimation({
                                    scale: 1,
                                    opacity: 1,
                                    duration: CONFIG.duration.normal,
                                    ease: CONFIG.ease.spring
                                }));
                                break;
                            case 'slide-left':
                                gsap.to(el, safeAnimation({
                                    x: 0,
                                    opacity: 1,
                                    duration: CONFIG.duration.normal,
                                    ease: CONFIG.ease.entrance
                                }));
                                break;
                            case 'slide-right':
                                gsap.to(el, safeAnimation({
                                    x: 0,
                                    opacity: 1,
                                    duration: CONFIG.duration.normal,
                                    ease: CONFIG.ease.entrance
                                }));
                                break;
                        }

                        observer.unobserve(el);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            // Observe elements with data-rf-animate
            document.querySelectorAll('[data-rf-animate]').forEach(el => {
                // Set initial state based on animation type
                const animation = el.dataset.rfAnimate;
                switch(animation) {
                    case 'fade-up':
                        gsap.set(el, { y: 30, opacity: 0 });
                        break;
                    case 'fade':
                        gsap.set(el, { opacity: 0 });
                        break;
                    case 'scale':
                        gsap.set(el, { scale: 0.9, opacity: 0 });
                        break;
                    case 'slide-left':
                        gsap.set(el, { x: 50, opacity: 0 });
                        break;
                    case 'slide-right':
                        gsap.set(el, { x: -50, opacity: 0 });
                        break;
                }
                observer.observe(el);
            });

            this.observers.push(observer);
        }

        /**
         * Refresh all ScrollTriggers (call after DOM changes)
         */
        refresh() {
            if (typeof ScrollTrigger !== 'undefined') {
                ScrollTrigger.refresh();
            }
        }

        /**
         * Clean up all animations
         */
        destroy() {
            this.scrollTriggers.forEach(st => st.kill());
            this.observers.forEach(obs => obs.disconnect());
            this.scrollTriggers = [];
            this.observers = [];
        }
    }

    // ============================================
    // PUBLIC API
    // ============================================
    window.RFAnimations = {
        instance: null,
        config: CONFIG,

        /**
         * Initialize animations
         */
        init() {
            if (!this.instance) {
                this.instance = new RFAnimations();
            }
            return this.instance;
        },

        /**
         * Animate element with preset
         */
        animate(element, preset = 'fadeUp', options = {}) {
            if (typeof gsap === 'undefined') return;

            const presets = {
                fadeUp: { y: 0, opacity: 1, duration: CONFIG.duration.normal, ease: CONFIG.ease.entrance },
                fadeDown: { y: 0, opacity: 1, duration: CONFIG.duration.normal, ease: CONFIG.ease.entrance },
                fadeIn: { opacity: 1, duration: CONFIG.duration.normal, ease: CONFIG.ease.decelerate },
                fadeOut: { opacity: 0, duration: CONFIG.duration.fast, ease: CONFIG.ease.exit },
                scaleIn: { scale: 1, opacity: 1, duration: CONFIG.duration.normal, ease: CONFIG.ease.spring },
                scaleOut: { scale: 0.9, opacity: 0, duration: CONFIG.duration.fast, ease: CONFIG.ease.exit },
                slideLeft: { x: 0, opacity: 1, duration: CONFIG.duration.normal, ease: CONFIG.ease.entrance },
                slideRight: { x: 0, opacity: 1, duration: CONFIG.duration.normal, ease: CONFIG.ease.entrance },
                bounce: { y: 0, duration: CONFIG.duration.slow, ease: CONFIG.ease.bounce }
            };

            const animProps = { ...presets[preset], ...options };
            return gsap.to(element, safeAnimation(animProps));
        },

        /**
         * Animate staggered group
         */
        stagger(elements, preset = 'fadeUp', options = {}) {
            if (typeof gsap === 'undefined') return;

            return this.animate(elements, preset, {
                stagger: CONFIG.stagger.normal,
                ...options
            });
        },

        /**
         * Create timeline
         */
        timeline(options = {}) {
            if (typeof gsap === 'undefined') return null;
            return gsap.timeline(options);
        },

        /**
         * Refresh ScrollTriggers
         */
        refresh() {
            if (this.instance) {
                this.instance.refresh();
            }
        }
    };

    // Auto-initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            window.RFAnimations.init();
        });
    } else {
        window.RFAnimations.init();
    }

})();
