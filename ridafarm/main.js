// Mark HTML element immediately so CSS animations are enabled
document.documentElement.classList.add('js');

function initRidaFarm() {

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
            document.querySelectorAll('.news-grid .animate-stagger'),
            document.querySelectorAll('.ap-team-grid .animate-stagger')
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
    const hideLoader = () => {
        setTimeout(() => {
            if(loader) loader.classList.add('hidden');
            document.body.classList.remove('loading');
        }, 1000);
    };

    if (document.readyState === 'complete') {
        hideLoader();
    } else {
        window.addEventListener('load', hideLoader);
    }

    // --- Custom Cursor ---
    const cursorDot = document.querySelector('.cursor-dot');
    const cursorOutline = document.querySelector('.cursor-outline');
    let mouseX = 0, mouseY = 0;
    let outlineX = 0, outlineY = 0;

    if (!cursorDot || !cursorOutline) {
        document.body.style.cursor = 'auto';
    } else {
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
    }

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
        if (navbar) {
            navbar.classList.toggle('scrolled', scrollY > 50);
        }
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

    if (banner && closeBanner) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 500 && !banner.classList.contains('closed')) {
                banner.classList.add('show');
            }
        });

        closeBanner.addEventListener('click', () => {
            banner.classList.remove('show');
            banner.classList.add('closed');
        });
    }

    // --- Mobile Menu Toggle ---
    const menuBtn = document.querySelector('.menu-btn');
    const navLinks = document.querySelectorAll('.nav-link');

    if (menuBtn) {
        menuBtn.addEventListener('click', () => {
            document.body.classList.toggle('menu-open');
        });

        // Close menu when a link is clicked
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                document.body.classList.remove('menu-open');
                
                // Set active class if it's a section link
                if (link.getAttribute('href').startsWith('#')) {
                    navLinks.forEach(l => l.classList.remove('active'));
                    link.classList.add('active');
                }
            });
        });
    }

    // --- News Slider Logic ---
    const newsSlider = document.getElementById('newsSlider');
    const newsPrev = document.getElementById('newsPrev');
    const newsNext = document.getElementById('newsNext');

    if (newsSlider && newsPrev && newsNext) {
        const updateSliderButtons = () => {
            // Disable prev if at start
            if (newsSlider.scrollLeft <= 10) {
                newsPrev.disabled = true;
            } else {
                newsPrev.disabled = false;
            }

            // Disable next if at end
            // scrollWidth is total width, clientWidth is visible width
            if (newsSlider.scrollLeft + newsSlider.clientWidth >= newsSlider.scrollWidth - 10) {
                newsNext.disabled = true;
            } else {
                newsNext.disabled = false;
            }
        };

        // Initialize state
        updateSliderButtons();

        // Listen for scrolling
        newsSlider.addEventListener('scroll', updateSliderButtons);

        // Button clicks
        const scrollAmount = 350 + 32; // card width + gap (approx)

        newsPrev.addEventListener('click', () => {
            newsSlider.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });

        newsNext.addEventListener('click', () => {
            newsSlider.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });

        // --- Mouse Drag to Scroll ---
        let isDown = false;
        let startX;
        let scrollLeft;
        let isDragging = false;
        let snapTimeout;

        newsSlider.addEventListener('pointerdown', (e) => {
            if (e.pointerType !== 'mouse' || e.button !== 0) return;
            isDown = true;
            isDragging = false;
            newsSlider.style.cursor = 'grabbing';
            // Disable scroll-snap and CSS smooth-behavior for 1-to-1 immediate drag updates
            newsSlider.style.scrollSnapType = 'none';
            newsSlider.style.scrollBehavior = 'auto';
            clearTimeout(snapTimeout); // clear any pending snap restores
            
            startX = e.pageX - newsSlider.offsetLeft;
            scrollLeft = newsSlider.scrollLeft;
        });

        const handlePointerUp = (e) => {
            if (!isDown || e.pointerType !== 'mouse') return;
            isDown = false;
            newsSlider.style.cursor = 'grab';
            
            // Re-enable smooth scrolling for the snap animation
            newsSlider.style.scrollBehavior = 'smooth';
            
            // Find closest card to snap to
            const paddingLeft = parseFloat(window.getComputedStyle(newsSlider).paddingLeft) || 0;
            const currentScroll = newsSlider.scrollLeft;
            const newsCards = Array.from(newsSlider.querySelectorAll('.news-card'));
            
            let closestCard = newsCards[0];
            let minDiff = Infinity;

            newsCards.forEach(card => {
                const targetScroll = card.offsetLeft - paddingLeft;
                const diff = Math.abs(currentScroll - targetScroll);
                if (diff < minDiff) {
                    minDiff = diff;
                    closestCard = card;
                }
            });

            // Trigger smooth snap manual correction
            newsSlider.scrollLeft = closestCard.offsetLeft - paddingLeft;
            
            // Wait for smooth scroll to finish before giving control back to CSS
            snapTimeout = setTimeout(() => {
                if (!isDown) { 
                    newsSlider.style.scrollSnapType = '';
                    newsSlider.style.scrollBehavior = '';
                }
            }, 600);
        };

        newsSlider.addEventListener('pointerleave', handlePointerUp);
        newsSlider.addEventListener('pointerup', handlePointerUp);

        newsSlider.addEventListener('pointermove', (e) => {
            if (!isDown || e.pointerType !== 'mouse') return;
            e.preventDefault();
            const x = e.pageX - newsSlider.offsetLeft;
            const walk = (x - startX) * 1.5; // smoother multiplier (1.5x)
            if (Math.abs(walk) > 5) {
                isDragging = true;
            }
            newsSlider.scrollLeft = scrollLeft - walk;
        });

        // Prevent clicking links when dragging
        const newsCards = newsSlider.querySelectorAll('.news-card');
        newsCards.forEach(card => {
            card.addEventListener('click', (e) => {
                if (isDragging) {
                    e.preventDefault();
                }
            });
        });
    }

    


    // --- Philosophy Slider Logic ---
    const philSlider = document.getElementById('philosophySlider');
    const philDotsContainer = document.getElementById('philosophyDots');

    if (philSlider && philDotsContainer) {
        const philCards = philSlider.querySelectorAll('.feature-card');
        
        philCards.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.classList.add('philosophy-dot');
            if (index === 0) dot.classList.add('active');
            
            dot.addEventListener('click', () => {
                const cardWidth = philCards[0].offsetWidth;
                const scrollTarget = cardWidth * index;
                philSlider.scrollTo({ left: scrollTarget, behavior: 'smooth' });
                resetPhilInterval();
            });
            philDotsContainer.appendChild(dot);
        });

        const philDots = philDotsContainer.querySelectorAll('.philosophy-dot');
        let currentPhilIndex = 0;
        let philAutoPlay;

        philSlider.addEventListener('scroll', () => {
            const cardWidth = philCards[0].offsetWidth;
            const index = Math.round(philSlider.scrollLeft / cardWidth);
            if (index !== currentPhilIndex && index >= 0 && index < philCards.length) {
                if(philDots[currentPhilIndex]) philDots[currentPhilIndex].classList.remove('active');
                currentPhilIndex = index;
                if(philDots[currentPhilIndex]) philDots[currentPhilIndex].classList.add('active');
            }
        });

        const nextPhilSlide = () => {
            if (window.getComputedStyle(philDotsContainer).display !== 'none') {
                let nextIndex = currentPhilIndex + 1;
                if (nextIndex >= philCards.length) {
                    nextIndex = 0;
                }
                const cardWidth = philCards[0].offsetWidth;
                philSlider.scrollTo({ left: cardWidth * nextIndex, behavior: 'smooth' });
            }
        };

        const resetPhilInterval = () => {
            clearInterval(philAutoPlay);
            philAutoPlay = setInterval(nextPhilSlide, 3000);
        };

        resetPhilInterval();

        philSlider.addEventListener('touchstart', () => clearInterval(philAutoPlay), {passive: true});
        philSlider.addEventListener('touchend', resetPhilInterval, {passive: true});
        philSlider.addEventListener('mouseenter', () => clearInterval(philAutoPlay));
        philSlider.addEventListener('mouseleave', resetPhilInterval);
    }

    // --- Team Slider Logic (About Page) ---
    const teamSlider = document.getElementById('teamSlider');
    const teamPrev = document.getElementById('teamPrev');
    const teamNext = document.getElementById('teamNext');

    if (teamSlider && teamPrev && teamNext) {
        const updateTeamSliderButtons = () => {
            if (teamSlider.scrollLeft <= 10) {
                teamPrev.disabled = true;
            } else {
                teamPrev.disabled = false;
            }
            if (teamSlider.scrollLeft + teamSlider.clientWidth >= teamSlider.scrollWidth - 10) {
                teamNext.disabled = true;
            } else {
                teamNext.disabled = false;
            }
        };

        updateTeamSliderButtons();
        teamSlider.addEventListener('scroll', updateTeamSliderButtons);

        const scrollAmountTeam = 320 + 32;

        teamPrev.addEventListener('click', () => {
            teamSlider.scrollBy({ left: -scrollAmountTeam, behavior: 'smooth' });
        });
        teamNext.addEventListener('click', () => {
            teamSlider.scrollBy({ left: scrollAmountTeam, behavior: 'smooth' });
        });

        // Mouse Drag to Scroll for Team Slider
        let isDownTeam = false;
        let startXTeam;
        let scrollLeftTeam;
        let isDraggingTeam = false;
        let snapTimeoutTeam;

        teamSlider.addEventListener('pointerdown', (e) => {
            if (e.pointerType !== 'mouse' || e.button !== 0) return;
            isDownTeam = true;
            isDraggingTeam = false;
            teamSlider.style.cursor = 'grabbing';
            teamSlider.style.scrollSnapType = 'none';
            teamSlider.style.scrollBehavior = 'auto';
            clearTimeout(snapTimeoutTeam);
            
            startXTeam = e.pageX - teamSlider.offsetLeft;
            scrollLeftTeam = teamSlider.scrollLeft;
        });

        const handlePointerUpTeam = (e) => {
            if (!isDownTeam || e.pointerType !== 'mouse') return;
            isDownTeam = false;
            teamSlider.style.cursor = 'grab';
            teamSlider.style.scrollBehavior = 'smooth';
            
            const paddingLeft = parseFloat(window.getComputedStyle(teamSlider).paddingLeft) || 0;
            const currentScroll = teamSlider.scrollLeft;
            const teamCards = Array.from(teamSlider.querySelectorAll('.ap-team-card'));
            
            if (teamCards.length === 0) return;
            let closestCard = teamCards[0];
            let minDiff = Infinity;

            teamCards.forEach(card => {
                const targetScroll = card.offsetLeft - paddingLeft;
                const diff = Math.abs(currentScroll - targetScroll);
                if (diff < minDiff) {
                    minDiff = diff;
                    closestCard = card;
                }
            });

            teamSlider.scrollLeft = closestCard.offsetLeft - paddingLeft;
            
            snapTimeoutTeam = setTimeout(() => {
                if (!isDownTeam) { 
                    teamSlider.style.scrollSnapType = '';
                    teamSlider.style.scrollBehavior = '';
                }
            }, 600);
        };

        teamSlider.addEventListener('pointerleave', handlePointerUpTeam);
        teamSlider.addEventListener('pointerup', handlePointerUpTeam);

        teamSlider.addEventListener('pointermove', (e) => {
            if (!isDownTeam || e.pointerType !== 'mouse') return;
            e.preventDefault();
            const x = e.pageX - teamSlider.offsetLeft;
            const walk = (x - startXTeam) * 1.5;
            if (Math.abs(walk) > 5) {
                isDraggingTeam = true;
            }
            teamSlider.scrollLeft = scrollLeftTeam - walk;
        });
    }

    // --- Draggable Slider (Products Page) ---
    const sliders = document.querySelectorAll('[data-slider]');
    sliders.forEach(slider => {
        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('pointerdown', (e) => {
            if (e.pointerType !== 'mouse') return;
            if (slider.scrollWidth <= slider.clientWidth) return;
            isDown = true;
            slider.style.cursor = 'grabbing';
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
            slider.style.scrollSnapType = 'none';
        });

        slider.addEventListener('pointerleave', (e) => {
            if (e.pointerType !== 'mouse') return;
            isDown = false;
            slider.style.cursor = 'grab';
            slider.style.scrollSnapType = 'x mandatory';
        });

        slider.addEventListener('pointerup', (e) => {
            if (e.pointerType !== 'mouse') return;
            isDown = false;
            slider.style.cursor = 'grab';
            slider.style.scrollSnapType = 'x mandatory';
        });

        slider.addEventListener('pointermove', (e) => {
            if (!isDown || e.pointerType !== 'mouse') return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;
            slider.scrollLeft = scrollLeft - walk;
        });
        
        // Prevent default drag behavior on images to allow slider dragging
        slider.querySelectorAll('img').forEach(img => {
            img.addEventListener('dragstart', (e) => e.preventDefault());
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initRidaFarm);
} else {
    initRidaFarm();
}

