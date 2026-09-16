document.addEventListener('DOMContentLoaded', () => {
    
    // --- Page Loader ---
    const loader = document.querySelector('.loader');
    window.addEventListener('load', () => {
        setTimeout(() => {
            loader.classList.add('hidden');
            document.body.classList.remove('loading');
            initAnimations(); // Trigger initial animations
        }, 1500); // Fake loading time for effect
    });

    // --- Custom Cursor ---
    const cursorDot = document.querySelector('.cursor-dot');
    const cursorOutline = document.querySelector('.cursor-outline');
    let mouseX = 0, mouseY = 0;
    let outlineX = 0, outlineY = 0;

    window.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
        
        // Dot follows instantly
        cursorDot.style.left = `${mouseX}px`;
        cursorDot.style.top = `${mouseY}px`;
    });

    // Outline follows with delay (lerp)
    function animateCursor() {
        let distX = mouseX - outlineX;
        let distY = mouseY - outlineY;
        
        outlineX += distX * 0.15;
        outlineY += distY * 0.15;
        
        cursorOutline.style.left = `${outlineX}px`;
        cursorOutline.style.top = `${outlineY}px`;
        
        requestAnimationFrame(animateCursor);
    }
    animateCursor();

    // Hover effect on links and buttons
    const interactives = document.querySelectorAll('a, button, .magnetic');
    interactives.forEach(el => {
        el.addEventListener('mouseenter', () => document.body.classList.add('hovering'));
        el.addEventListener('mouseleave', () => document.body.classList.remove('hovering'));
    });


    // --- Intersection Observer for Entry Animations ---
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                // Optional: stop observing once animated
                // observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    function initAnimations() {
        const animatedElements = document.querySelectorAll('.animate-fade-up, .animate-text, .reveal-wrap, .animate-scale');
        animatedElements.forEach(el => observer.observe(el));

        // Handle stagger animations specifically
        const staggerGroups = [
            document.querySelectorAll('.philosophy-grid .animate-stagger'),
            document.querySelectorAll('.news-grid .animate-stagger')
        ];

        staggerGroups.forEach(group => {
            group.forEach((el, index) => {
                el.style.transitionDelay = `${index * 0.2}s`;
                observer.observe(el);
            });
        });
    }

    // --- Parallax Effect ---
    const parallaxElements = document.querySelectorAll('.parallax-img');
    
    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;
        
        parallaxElements.forEach(el => {
            const speed = el.getAttribute('data-speed') || 0.1;
            // Only parallax if element is roughly in view
            const rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight && rect.bottom > 0) {
                const yPos = -(rect.top * speed);
                el.style.transform = `translateY(${yPos}px) scale(1.1)`; // scale slightly to avoid clipping edges
            }
        });
        
        // Navbar Scrolled State
        const navbar = document.querySelector('.navbar');
        if (scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // --- Magnetic Buttons ---
    const magneticBtns = document.querySelectorAll('.magnetic');
    magneticBtns.forEach(btn => {
        btn.addEventListener('mousemove', (e) => {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            
            btn.style.transform = `translate(${x * 0.3}px, ${y * 0.3}px)`;
        });
        
        btn.addEventListener('mouseleave', () => {
            btn.style.transform = `translate(0px, 0px)`;
        });
    });

    // --- Sticky Investor Banner ---
    const banner = document.querySelector('.investor-banner');
    const closeBanner = document.querySelector('.banner-close');
    
    // Show banner after scroll down
    window.addEventListener('scroll', () => {
        if (window.scrollY > 500 && !banner.classList.contains('closed')) {
            banner.classList.add('show');
        }
    });

    closeBanner.addEventListener('click', () => {
        banner.classList.remove('show');
        banner.classList.add('closed');
    });

});
