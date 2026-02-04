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
    const fadeUpElements = document.querySelectorAll('.rf-animate-up');

    fadeUpElements.forEach((el, index) => {
        gsap.to(el, {
            y: 0,
            opacity: 1,
            display: 'block', // Ensure it's not display: none if that was used
            autoAlpha: 1,    // Best for visibility toggles
            duration: 0.8,
            ease: "power2.out",
            delay: index * 0.05,
            scrollTrigger: {
                trigger: el,
                start: "top 90%",
                toggleActions: "play none none none"
            }
        });
    });

    // Glass Card hover effects (subtle tilt or scale if needed)
    const glassCards = document.querySelectorAll('.rf-glass-card');
    glassCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            gsap.to(card, { scale: 1.02, duration: 0.3, ease: "power1.out" });
        });
        card.addEventListener('mouseleave', () => {
            gsap.to(card, { scale: 1, duration: 0.3, ease: "power1.in" });
        });
    });
});
