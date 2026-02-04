/**
 * RFPlugin Animations
 * 
 * Powered by GSAP.
 */

document.addEventListener('DOMContentLoaded', () => {
    // Check if gsap is loaded (via CDN or local)
    if (typeof gsap === 'undefined') return;

    // Register ScrollTrigger if available
    if (typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);
    }

    // Default Fade Up animation for components
    // Dashboard Entrance Stagger (for grid layouts)
    const dashboardGrids = document.querySelectorAll('.rf-grid');
    dashboardGrids.forEach(grid => {
        const items = grid.querySelectorAll('.rf-glass-card, .stat-card');
        if (items.length > 0) {
            gsap.to(items, {
                y: 0,
                opacity: 1,
                autoAlpha: 1,
                duration: 0.8,
                stagger: 0.08,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: grid,
                    start: "top 90%",
                }
            });
        }
    });

    // Button Interaction Feedback
    const allButtons = document.querySelectorAll('.rf-btn, .rf-btn-premium');
    allButtons.forEach(btn => {
        btn.addEventListener('mousedown', () => {
            gsap.to(btn, { scale: 0.96, duration: 0.1 });
        });
        btn.addEventListener('mouseup', () => {
            gsap.to(btn, { scale: 1, duration: 0.1 });
        });
        btn.addEventListener('mouseleave', () => {
            gsap.to(btn, { scale: 1, duration: 0.2 });
        });
    });

    // Header Entrance
    const headers = document.querySelectorAll('.rf-dashboard-header');
    headers.forEach(header => {
        gsap.from(header, {
            x: -20,
            opacity: 0,
            duration: 1,
            ease: "power4.out"
        });
    });

    // Default Fade Up for other elements
    const fadeUpElements = document.querySelectorAll('.rf-animate-up:not(.rf-grid *)');
    fadeUpElements.forEach((el, index) => {
        gsap.to(el, {
            y: 0,
            opacity: 1,
            autoAlpha: 1,
            duration: 0.8,
            ease: "power2.out",
            delay: index * 0.05,
            scrollTrigger: {
                trigger: el,
                start: "top 95%",
            }
        });
    });

    // Glass Card hover effects
    const glassCards = document.querySelectorAll('.rf-glass-card');
    glassCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            gsap.to(card, { y: -6, scale: 1.01, duration: 0.4, ease: "power2.out" });
        });
        card.addEventListener('mouseleave', () => {
            gsap.to(card, { y: 0, scale: 1, duration: 0.4, ease: "power2.inOut" });
        });
    });
});
