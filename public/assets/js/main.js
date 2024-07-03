/* ==========================================================================
   EstateEase - shared client-side script
   Works for both the static site and the PHP app (served as assets/js/app.js
   for PHP and assets/js/main.js for the static site; they share source).
   ========================================================================== */

(() => {
    'use strict';

    // --------- Mobile navigation toggle ---------
    const toggle = document.querySelector('[data-nav-toggle]');
    const nav    = document.getElementById('primary-nav');
    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const open = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', String(open));
        });
    }

    // --------- Smooth reveal for cards (intersection observer) ---------
    const revealTargets = document.querySelectorAll('.card, .tile, .stat');
    if ('IntersectionObserver' in window && revealTargets.length) {
        revealTargets.forEach((el) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(12px)';
            el.style.transition = 'opacity 400ms ease, transform 400ms ease';
        });
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        revealTargets.forEach((el) => io.observe(el));
    }

    // --------- Active link highlighting (for static pages using same header) ---------
    const path = location.pathname.split('/').pop() || 'index.html';
    document.querySelectorAll('.nav__links a').forEach((a) => {
        const href = a.getAttribute('href');
        if (href && href === path) a.classList.add('is-active');
    });
})();
