document.addEventListener('DOMContentLoaded', () => {
    const themeToggleBtns = document.querySelectorAll('.theme-toggle-btn');

    // Check local storage or default to light
    const currentTheme = localStorage.getItem('theme') || 'light';

    // Initial icon state (Theme is already applied in <head>)
    if (currentTheme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
        updateIcons('dark');
    } else {
        document.documentElement.removeAttribute('data-theme');
        updateIcons('light');
    }

    // Toggle event
    themeToggleBtns.forEach(btn => {
        if (btn.dataset.themeBound === 'true') {
            return;
        }
        btn.dataset.themeBound = 'true';

        btn.addEventListener('click', () => {
            let theme = document.documentElement.getAttribute('data-theme');
            if (theme === 'dark') {
                document.documentElement.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
                updateIcons('light');
            } else {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                updateIcons('dark');
            }
        });
    });

    function updateIcons(theme) {
        themeToggleBtns.forEach(btn => {
            const icon = btn.querySelector('i');
            if (icon) {
                if (theme === 'dark') {
                    icon.classList.remove('ti-moon');
                    icon.classList.add('ti-sun');
                } else {
                    icon.classList.remove('ti-sun');
                    icon.classList.add('ti-moon');
                }
            }
        });
    }
});

// Delegated UI helpers (CSP-compliant replacement for inline event attributes).
document.addEventListener('submit', (e) => {
    const form = e.target.closest('form[data-confirm]');
    if (form && !window.confirm(form.dataset.confirm)) {
        e.preventDefault();
    }
});

document.addEventListener('change', (e) => {
    const autoSubmit = e.target.closest('[data-auto-submit]');
    if (autoSubmit && autoSubmit.form) {
        autoSubmit.form.submit();
    }
});

document.addEventListener('click', (e) => {
    if (e.target.closest('[data-stop-propagation]')) {
        e.stopPropagation();
        return;
    }

    const submitTrigger = e.target.closest('[data-submit-form]');
    if (submitTrigger) {
        e.preventDefault();
        const form = document.getElementById(submitTrigger.dataset.submitForm);
        if (form) {
            form.submit();
        }
        return;
    }

    const thumb = e.target.closest('[data-gallery-target][data-gallery-src]');
    if (thumb) {
        const img = document.getElementById(thumb.dataset.galleryTarget);
        if (img) {
            img.src = thumb.dataset.gallerySrc;
        }
        return;
    }

    const navEl = e.target.closest('[data-href]');
    if (navEl && !e.target.closest('a, button, form, input, select, textarea, label')) {
        window.location.href = navEl.dataset.href;
    }
});
