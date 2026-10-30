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

            const badge = btn.querySelector('.theme-status-badge');
            if (badge) {
                const lightText = badge.dataset.lightText || 'Sáng';
                const darkText = badge.dataset.darkText || 'Tối';
                badge.textContent = theme === 'dark' ? darkText : lightText;
            }
        });
    }

    // Settings Gear Dropdown toggle
    const settingsDropdown = document.querySelector('.settings-dropdown');
    const settingsToggleBtn = document.getElementById('settingsDropdownBtn');
    const settingsDropdownMenu = document.getElementById('settingsDropdownMenu');

    if (settingsToggleBtn && settingsDropdown && settingsDropdownMenu) {
        settingsToggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = settingsDropdown.classList.toggle('open');
            settingsDropdownMenu.classList.toggle('show', isOpen);
            settingsToggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', (e) => {
            if (!settingsDropdown.contains(e.target)) {
                settingsDropdown.classList.remove('open');
                settingsDropdownMenu.classList.remove('show');
                settingsToggleBtn.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                settingsDropdown.classList.remove('open');
                settingsDropdownMenu.classList.remove('show');
                settingsToggleBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Testimonial Carousel
    const testimonialTrack = document.getElementById('testimonialTrack');
    const testimonialViewport = document.getElementById('testimonialViewport');
    const testimonialSlides = testimonialTrack
        ? Array.from(testimonialTrack.querySelectorAll('.testimonial-slide'))
        : [];
    const testimonialDots = Array.from(document.querySelectorAll('[data-testimonial-dot]'));
    const prevBtn = document.querySelector('[data-testimonial-prev]');
    const nextBtn = document.querySelector('[data-testimonial-next]');

    if (testimonialTrack && testimonialSlides.length > 0) {
        let currentSlide = 0;
        let autoPlayTimer = null;

        function getSlidesPerView() {
            if (window.innerWidth <= 640) {
                return 1;
            }
            if (window.innerWidth <= 960) {
                return Math.min(2, testimonialSlides.length);
            }
            return Math.min(3, testimonialSlides.length);
        }

        function getMaxIndex() {
            return Math.max(0, testimonialSlides.length - getSlidesPerView());
        }

        function updateCarousel(index) {
            const maxIdx = getMaxIndex();
            if (index > maxIdx) {
                currentSlide = 0;
            } else if (index < 0) {
                currentSlide = maxIdx;
            } else {
                currentSlide = index;
            }

            const stepPercent = 100 / getSlidesPerView();
            testimonialTrack.style.transform = `translateX(-${currentSlide * stepPercent}%)`;

            testimonialDots.forEach((dot, idx) => {
                dot.classList.toggle('active', idx === currentSlide);
            });
        }

        function startAutoPlay() {
            stopAutoPlay();
            if (testimonialSlides.length > 1) {
                autoPlayTimer = window.setInterval(() => {
                    updateCarousel(currentSlide + 1);
                }, 4500);
            }
        }

        function stopAutoPlay() {
            if (autoPlayTimer) {
                window.clearInterval(autoPlayTimer);
                autoPlayTimer = null;
            }
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                updateCarousel(currentSlide - 1);
                startAutoPlay();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                updateCarousel(currentSlide + 1);
                startAutoPlay();
            });
        }

        testimonialDots.forEach((dot) => {
            dot.addEventListener('click', () => {
                const targetIdx = parseInt(dot.dataset.testimonialDot || '0', 10);
                updateCarousel(targetIdx);
                startAutoPlay();
            });
        });

        if (testimonialViewport) {
            testimonialViewport.addEventListener('mouseenter', stopAutoPlay);
            testimonialViewport.addEventListener('mouseleave', startAutoPlay);
        }

        window.addEventListener('resize', () => {
            updateCarousel(currentSlide);
        });

        updateCarousel(0);
        startAutoPlay();
    }

    // Pre-footer simulated contact form (validates types only, does not save to DB)
    const prefooterForm = document.getElementById('prefooterContactForm');
    const prefooterFeedback = document.getElementById('prefooterFeedback');

    if (prefooterForm && prefooterFeedback) {
        prefooterForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const nameInput = prefooterForm.querySelector('input[name="name"]');
            const emailInput = prefooterForm.querySelector('input[name="email"]');
            const messageInput = prefooterForm.querySelector('textarea[name="message"]');

            [nameInput, emailInput, messageInput].forEach((el) => {
                if (el) {
                    el.classList.remove('is-invalid');
                }
            });

            const nameVal = nameInput ? String(nameInput.value).trim() : '';
            const emailVal = emailInput ? String(emailInput.value).trim() : '';
            const messageVal = messageInput ? String(messageInput.value).trim() : '';

            const isNameValid =
                typeof nameVal === 'string' &&
                nameVal.length >= 2 &&
                nameVal.length <= 100 &&
                !/^\d+$/.test(nameVal);
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
            const isEmailValid = typeof emailVal === 'string' && emailRegex.test(emailVal);
            const isMessageValid =
                typeof messageVal === 'string' &&
                messageVal.length >= 5 &&
                messageVal.length <= 1000;

            if (!isNameValid || !isEmailValid || !isMessageValid) {
                if (!isNameValid && nameInput) {
                    nameInput.classList.add('is-invalid');
                }
                if (!isEmailValid && emailInput) {
                    emailInput.classList.add('is-invalid');
                }
                if (!isMessageValid && messageInput) {
                    messageInput.classList.add('is-invalid');
                }

                prefooterFeedback.hidden = false;
                prefooterFeedback.className = 'prefooter-feedback is-error';
                prefooterFeedback.textContent =
                    prefooterFeedback.dataset.errorMsg ||
                    'Vui lòng nhập đầy đủ họ tên hợp lệ, đúng định dạng Email và nội dung tối thiểu 5 ký tự.';
                return;
            }

            prefooterFeedback.hidden = false;
            prefooterFeedback.className = 'prefooter-feedback is-success';
            prefooterFeedback.textContent =
                prefooterFeedback.dataset.successMsg ||
                'Cảm ơn bạn! Thông tin liên hệ đã được kiểm tra và gửi thành công.';
            prefooterForm.reset();
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
