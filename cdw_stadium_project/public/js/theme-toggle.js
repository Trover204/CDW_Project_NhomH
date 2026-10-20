document.addEventListener('DOMContentLoaded', () => {
    const themeToggleBtns = document.querySelectorAll('.theme-toggle-btn');

    // Check local storage or default to light
    const currentTheme = localStorage.getItem('theme') || 'light';

    // Initial icon state (Theme is already applied in <head>)
    if (currentTheme === 'dark') {
        updateIcons('dark');
    } else {
        updateIcons('light');
    }

    // Toggle event
    themeToggleBtns.forEach(btn => {
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
