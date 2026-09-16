// Mark HTML element immediately so CSS animations are enabled
document.documentElement.classList.add('js');

document.addEventListener('DOMContentLoaded', () => {

    // --- Intersection Observer for Entry Animations ---
    // Called IMMEDIATELY on DOMContentLoaded — no delay, no dependency on loader
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                observer.unobserve(entry.target); // stop observing once visible
            }
        });
    }, observerOptions);

    function initAnimations() {
        const animatedElements = document.querySelectorAll(
            '.animate-fade-up, .animate-text, .reveal-wrap, .animate-scale'
        );
        animatedElements.forEach(el => observer.observe(el));

        // Stagger animations
        const staggerGroups = [
            document.querySelectorAll('.philosophy-grid .animate-stagger'),
            document.querySelectorAll('.news-grid .animate-stagger')
        ];
        staggerGroups.forEach(group => {
            group.forEach((el, index) => {
                el.style.transitionDelay = `${index * 0.15}s`;
                observer.observe(el);
            });
        });
    }

    // Run immediately — don't wait for loader
    initAnimations();

    // --- Page Loader (visual only, doesn't block content) ---
    const loader = document.querySelector('.loader');
    window.addEventListener('load', () => {
        setTimeout(() => {
            loader.classList.add('hidden');
            document.body.classList.remove('loading');
        }, 1000);
    });

    // --- Custom Cursor ---
    const cursorDot = document.querySelector('.cursor-dot');
    const cursorOutline = document.querySelector('.cursor-outline');
    let mouseX = 0, mouseY = 0;
    let outlineX = 0, outlineY = 0;

    window.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
        cursorDot.style.left = `${mouseX}px`;
        cursorDot.style.top = `${mouseY}px`;
    });

    function animateCursor() {
        outlineX += (mouseX - outlineX) * 0.15;
        outlineY += (mouseY - outlineY) * 0.15;
        cursorOutline.style.left = `${outlineX}px`;
        cursorOutline.style.top = `${outlineY}px`;
        requestAnimationFrame(animateCursor);
    }
    animateCursor();

    // Hover effect on interactive elements
    const interactives = document.querySelectorAll('a, button, .magnetic');
    interactives.forEach(el => {
        el.addEventListener('mouseenter', () => document.body.classList.add('hovering'));
        el.addEventListener('mouseleave', () => document.body.classList.remove('hovering'));
    });

    // --- Parallax Effect ---
    const parallaxElements = document.querySelectorAll('.parallax-img');
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;

        parallaxElements.forEach(el => {
            const speed = parseFloat(el.getAttribute('data-speed')) || 0.1;
            const rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight && rect.bottom > 0) {
                el.style.transform = `translateY(${-(rect.top * speed)}px) scale(1.1)`;
            }
        });

        // Navbar scrolled state
        navbar.classList.toggle('scrolled', scrollY > 50);
    });

    // --- Magnetic Buttons ---
    document.querySelectorAll('.magnetic').forEach(btn => {
        btn.addEventListener('mousemove', (e) => {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            btn.style.transform = `translate(${x * 0.3}px, ${y * 0.3}px)`;
        });
        btn.addEventListener('mouseleave', () => {
            btn.style.transform = 'translate(0px, 0px)';
        });
    });

    // --- Sticky Investor Banner ---
    const banner = document.querySelector('.investor-banner');
    const closeBanner = document.querySelector('.banner-close');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 500 && !banner.classList.contains('closed')) {
            banner.classList.add('show');
        }
    });

    closeBanner.addEventListener('click', () => {
        banner.classList.remove('show');
        banner.classList.add('closed');
    });

    // --- Mobile Menu Toggle ---
    const menuBtn = document.querySelector('.menu-btn');
    const navLinks = document.querySelectorAll('.nav-link');

    if (menuBtn) {
        menuBtn.addEventListener('click', () => {
            document.body.classList.toggle('menu-open');
        });

        // Close menu when a link is clicked
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                document.body.classList.remove('menu-open');
            });
        });
    }

});
