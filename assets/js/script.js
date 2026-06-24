(function () {
    const body = document.body;
    const themeKey = 'portfolio-theme';
    const savedTheme = localStorage.getItem(themeKey);
    const toggles = document.querySelectorAll('[data-theme-toggle]');
    const navToggle = document.querySelector('[data-nav-toggle]');
    const navMenu = document.querySelector('[data-nav-menu]');
    const siteHeader = document.querySelector('.site-header');

    if (savedTheme === 'dark') {
        body.classList.add('dark');
    }

    function syncThemeText() {
        const isDark = body.classList.contains('dark');
        toggles.forEach((button) => {
            const label = button.querySelector('[data-theme-label]');
            const text = isDark ? 'Terang' : 'Gelap';
            if (label) {
                label.textContent = text;
            } else {
                button.textContent = `Mode ${text}`;
            }
            button.setAttribute('aria-label', isDark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap');
        });
    }

    function closeMobileMenu() {
        if (!navMenu || !navToggle) {
            return;
        }

        navMenu.classList.remove('open');
        navToggle.classList.remove('open');
        navToggle.setAttribute('aria-expanded', 'false');
    }

    function syncHeaderState() {
        if (!siteHeader) {
            return;
        }

        siteHeader.classList.toggle('is-scrolled', window.scrollY > 12);
    }

    toggles.forEach((button) => {
        button.addEventListener('click', () => {
            body.classList.toggle('dark');
            localStorage.setItem(themeKey, body.classList.contains('dark') ? 'dark' : 'light');
            syncThemeText();
            closeMobileMenu();
        });
    });

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', () => {
            const isOpen = navMenu.classList.toggle('open');
            navToggle.classList.toggle('open', isOpen);
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        navMenu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', closeMobileMenu);
        });
    }

    document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach((link) => {
        link.addEventListener('click', (event) => {
            const target = document.querySelector(link.getAttribute('href'));
            if (!target) {
                return;
            }

            event.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            closeMobileMenu();
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeMobileMenu();
        }
    });

    window.addEventListener('scroll', syncHeaderState, { passive: true });
    window.addEventListener('resize', () => {
        if (window.innerWidth > 992) {
            closeMobileMenu();
        }
    });

    document.querySelectorAll('[data-confirm]').forEach((link) => {
        link.addEventListener('click', (event) => {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                event.preventDefault();
            }
        });
    });

    const animatedItems = document.querySelectorAll(
        '.section-heading, .page-hero, .capability-grid article, .hero-metrics div, .skill-card, .project-card, .timeline-item, .profile-summary, .bio-card, .form-card, .contact-info-card, .focus-grid article, .stat-card, .admin-panel'
    );

    if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        animatedItems.forEach((item, index) => {
            item.classList.add('reveal-on-scroll');
            item.style.setProperty('--reveal-delay', `${Math.min(index % 6, 5) * 70}ms`);
        });

        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        }, {
            rootMargin: '0px 0px -12% 0px',
            threshold: 0.12,
        });

        animatedItems.forEach((item) => revealObserver.observe(item));
    } else {
        animatedItems.forEach((item) => item.classList.add('is-visible'));
    }

    syncThemeText();
    syncHeaderState();
})();
