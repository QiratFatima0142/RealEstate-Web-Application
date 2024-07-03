/* ==========================================================================
   EstateEase - static contact form
   Client-side validation + in-memory "send" simulation for the Pages preview.
   ========================================================================== */

(() => {
    'use strict';

    const form  = document.getElementById('contact-form');
    const flash = document.getElementById('contact-flash');
    if (!form || !flash) return;

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        flash.innerHTML = '';

        const data = new FormData(form);
        const name    = String(data.get('name') || '').trim();
        const email   = String(data.get('email') || '').trim();
        const message = String(data.get('message') || '').trim();
        const errors  = [];

        if (name.length < 2 || name.length > 100) errors.push('Please provide your name.');
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errors.push('Please provide a valid email.');
        if (message.length < 10) errors.push('Message must be at least 10 characters.');
        if (message.length > 2000) errors.push('Message must be under 2000 characters.');

        if (errors.length) {
            flash.innerHTML = '<div class="flash flash--error">' + errors.join(' ') + '</div>';
            return;
        }

        flash.innerHTML =
            '<div class="flash flash--success">' +
                'Thanks, ' + escapeHtml(name) + '! Your message has been queued. ' +
                'When the full PHP app is running, it will be stored in the <code>contact_message</code> table.' +
            '</div>';
        form.reset();
        flash.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });

    function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, (c) => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        }[c]));
    }
})();
