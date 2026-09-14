// Initialize Lenis for smooth, buttery scrolling (Awwwards staple)
const lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), // https://www.desmos.com/calculator/brs54l4xou
    direction: 'vertical',
    gestureDirection: 'vertical',
    smooth: true,
    mouseMultiplier: 1,
    smoothTouch: false,
    touchMultiplier: 2,
    infinite: false,
})

// Integrate Lenis with GSAP ScrollTrigger
function raf(time) {
    lenis.raf(time)
    requestAnimationFrame(raf)
}
requestAnimationFrame(raf)

// Make ScrollTrigger aware of Lenis
gsap.registerPlugin(ScrollTrigger);

// Update ScrollTrigger on Lenis scroll
lenis.on('scroll', ScrollTrigger.update)

gsap.ticker.add((time)=>{
  lenis.raf(time * 1000)
})

gsap.ticker.lagSmoothing(0)

document.addEventListener('DOMContentLoaded', () => {
    // --- Mobile Menu Toggle ---
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }

    const links = document.querySelectorAll('.nav-links a');
    links.forEach(link => {
        link.addEventListener('click', () => {
            if (navLinks.classList.contains('active')) {
                navLinks.classList.remove('active');
            }
        });
    });

    // --- Navbar Scroll Effect ---
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // --- GSAP ANIMATIONS ---
    
    // 1. Hero Reveal Animation
    const tlHero = gsap.timeline();
    
    // Parallax effect on hero background
    gsap.to('.hero', {
        backgroundPosition: `50% 100%`,
        ease: "none",
        scrollTrigger: {
            trigger: '.hero',
            start: "top top",
            end: "bottom top",
            scrub: true
        }
    });

    tlHero.fromTo('.hero-content h1', 
        { y: 100, opacity: 0, clipPath: 'inset(100% 0 0 0)' },
        { y: 0, opacity: 1, clipPath: 'inset(0% 0 0 0)', duration: 1.5, ease: 'power4.out', delay: 0.2 }
    )
    .fromTo('.hero-content p',
        { y: 50, opacity: 0 },
        { y: 0, opacity: 1, duration: 1.2, ease: 'power3.out' },
        '-=1'
    )
    .fromTo('.hero-content .btn',
        { y: 30, opacity: 0 },
        { y: 0, opacity: 1, duration: 1, ease: 'power3.out' },
        '-=0.8'
    );

    // 2. Sections Reveal (Titles & Subtitles)
    gsap.utils.toArray('.section-title').forEach(title => {
        gsap.fromTo(title,
            { y: 80, opacity: 0, rotationX: -20 },
            { 
                y: 0, opacity: 1, rotationX: 0, 
                duration: 1.2, 
                ease: 'expo.out',
                scrollTrigger: {
                    trigger: title,
                    start: 'top 85%',
                }
            }
        );
    });

    // 3. Cards Stagger Animation (Products)
    gsap.fromTo('.card',
        { y: 100, opacity: 0 },
        {
            y: 0, opacity: 1,
            duration: 1,
            stagger: 0.15,
            ease: 'back.out(1.2)',
            scrollTrigger: {
                trigger: '.cards-grid',
                start: 'top 80%'
            }
        }
    );

    // 4. Map Timeline Animation
    const timelineItems = gsap.utils.toArray('.timeline-item');
    gsap.fromTo(timelineItems,
        { x: -50, opacity: 0 },
        {
            x: 0, opacity: 1,
            duration: 0.8,
            stagger: 0.2,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: '.map-timeline',
                start: 'top 75%'
            }
        }
    );

    gsap.fromTo('.map-placeholder',
        { scale: 0.9, opacity: 0 },
        {
            scale: 1, opacity: 1,
            duration: 1.2,
            ease: 'expo.out',
            scrollTrigger: {
                trigger: '.map-container',
                start: 'top 80%'
            }
        }
    );

    // 5. Pricing Cards Animation
    gsap.fromTo('.pricing-card',
        { y: 80, opacity: 0, rotateY: 15 },
        {
            y: 0, opacity: 1, rotateY: 0,
            duration: 1.2,
            stagger: 0.2,
            ease: 'power4.out',
            scrollTrigger: {
                trigger: '.pricing-grid',
                start: 'top 80%'
            }
        }
    );

    // --- Hover Magnet Effect for Buttons (Awwwards Style) ---
    const magnetBtns = document.querySelectorAll('.btn, .logo, .nav-links a');
    magnetBtns.forEach(btn => {
        btn.addEventListener('mousemove', (e) => {
            const rect = btn.getBoundingClientRect();
            const x = (e.clientX - rect.left) - rect.width / 2;
            const y = (e.clientY - rect.top) - rect.height / 2;
            
            // Subtle movement
            gsap.to(btn, {
                x: x * 0.2,
                y: y * 0.2,
                duration: 0.4,
                ease: 'power2.out'
            });
        });

        btn.addEventListener('mouseleave', () => {
            gsap.to(btn, {
                x: 0,
                y: 0,
                duration: 0.7,
                ease: 'elastic.out(1, 0.3)'
            });
        });
    });

    // Smooth scroll for anchor links using Lenis
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            lenis.scrollTo(targetId, {
                offset: -80, // Navbar height compensation
                duration: 1.5,
                easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t))
            });
        });
    });
});
