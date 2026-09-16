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


    // --- Language Switcher ---
        const i18n = {
        en: {
            '.nav-links a:nth-child(1)': 'Home',
            '.nav-links a:nth-child(2)': 'About Us',
            '.nav-links a:nth-child(3)': 'Our Products',
            '.nav-links a:nth-child(4)': 'News',
            '.nav-links a:nth-child(5)': 'Contact',
            '.nav-links a:nth-child(6)': 'Farm Tour',
            '.nav-links a:nth-child(7)': 'Investment',
            '.hero-title': 'Pure Goodness<br />from Our Farm<br />to Your Family',
            '.hero-desc': 'Fresh goat milk and yogurt from Rida Farm <br />Natural nutrition, healthier tomorrow.',
            '.hero-buttons .btn-primary': '<i class="fa-regular fa-calendar"></i> Book a Farm Tour <i class="fa-solid fa-arrow-right icon-right"></i>',
            '.hero-buttons .btn-outline-white': 'Explore Products <i class="fa-solid fa-arrow-right icon-right"></i>',
            '.hero-handwritten': 'Healthy Goats<br />Happier Families <i class="fa-regular fa-heart"></i>',
            
            '.about .subheading': '<span class="line"></span> OUR STORY',
            '.about h2.animate-text': 'About Us',
            '.about h3.animate-text': 'More Than a Farm,<br />A Healthier Tomorrow',
            '.about-content > p': 'Rida Farm Bali is built on a simple belief: healthy goats create wholesome food for healthier families. We nurture our goats with care, respect, and a natural environment, producing fresh and nutritious milk and yogurt for your everyday life.',
            '.about .btn-primary': 'Read Our Story <i class="fa-solid fa-arrow-right icon-right"></i>',
            '.about-handwritten': 'Care<br />Nurture<br />Better Lives <i class="fa-regular fa-heart"></i>',
            
            '.philosophy .subheading': '<span class="line"></span> OUR PHILOSOPHY <span class="line"></span>',
            '.philosophy .section-title': 'Simple Values, A Healthier World',
            '.philosophy-grid .feature-card:nth-child(1) h3': 'Animal Care',
            '.philosophy-grid .feature-card:nth-child(1) p': 'Happy and healthy goats are the heart of everything we do.',
            '.philosophy-grid .feature-card:nth-child(2) h3': 'Freshness',
            '.philosophy-grid .feature-card:nth-child(2) p': 'From our farm to your table, we ensure natural, fresh, and high-quality dairy products.',
            '.philosophy-grid .feature-card:nth-child(3) h3': 'From Farm to Family',
            '.philosophy-grid .feature-card:nth-child(3) p': 'Nutritious food that brings families closer, today and for generations to come.',
            
            '.products .subheading': '<span class="line"></span> OUR PRODUCTS',
            '.products-content > h2.animate-text': 'Natural Nutrition<br />for a Better You',
            '.products-content > p.animate-fade-up': 'Delicious and nutritious goat milk and yogurt made with care at Rida Farm Bali. Pure, natural, and full of goodness for your family\'s daily health.',
            '.products-content .btn-primary': 'View All Products <i class="fa-solid fa-arrow-right icon-right"></i>',
            '.product-features .p-feature:nth-child(1) span': '100% Natural',
            '.product-features .p-feature:nth-child(2) span': 'Healthy Choice',
            '.product-features .p-feature:nth-child(3) span': 'For the Whole Family',
            '.products-handwritten': 'Rasa Alami<br />untuk Hari yang<br />Lebih Baik <i class="fa-regular fa-heart"></i>',
            
            '.news-header-left .subheading': '<span class="line"></span> NEWS & STORIES',
            '.news-header-left h2': 'Latest from the Farm',
            '.news-header-left p': 'Stories, updates, and inspiration from our journey at Rida Farm Bali.',
            '#newsSlider .news-card:nth-child(1) .news-title': 'Happy Goats, Healthier Lives',
            '#newsSlider .news-card:nth-child(1) .news-excerpt': 'A closer look at how we care for our goats every day.',
            '#newsSlider .news-card:nth-child(2) .news-title': 'People, Goats, and a Brighter Tomorrow',
            '#newsSlider .news-card:nth-child(2) .news-excerpt': 'Meet the hands behind Rida Farm Bali.',
            '#newsSlider .news-card:nth-child(3) .news-title': 'The Goodness of Goat Milk',
            '#newsSlider .news-card:nth-child(3) .news-excerpt': 'Why goat milk is a natural choice for your family.',
            '#newsSlider .news-card:nth-child(4) .news-title': 'From Farm to Table',
            '#newsSlider .news-card:nth-child(4) .news-excerpt': 'How our products reach you fresh everyday.',
            
            '.banner-title': 'Welcoming Investors',
            '.banner-center': 'Invest now and grow with ridafarmbali — get more value later.',
            '.banner-right .btn': '<i class="fa-solid fa-chart-line"></i> Invest Now <i class="fa-solid fa-arrow-right icon-right"></i>'
        },
        id: {
            '.nav-links a:nth-child(1)': 'Beranda',
            '.nav-links a:nth-child(2)': 'Tentang Kami',
            '.nav-links a:nth-child(3)': 'Produk Kami',
            '.nav-links a:nth-child(4)': 'Berita',
            '.nav-links a:nth-child(5)': 'Kontak',
            '.nav-links a:nth-child(6)': 'Tur Peternakan',
            '.nav-links a:nth-child(7)': 'Investasi',
            '.hero-title': 'Kebaikan Murni<br />dari Peternakan<br />untuk Keluarga Anda',
            '.hero-desc': 'Susu kambing segar dan yogurt dari Rida Farm <br />Nutrisi alami, hari esok yang lebih sehat.',
            '.hero-buttons .btn-primary': '<i class="fa-regular fa-calendar"></i> Pesan Tur Peternakan <i class="fa-solid fa-arrow-right icon-right"></i>',
            '.hero-buttons .btn-outline-white': 'Jelajahi Produk <i class="fa-solid fa-arrow-right icon-right"></i>',
            '.hero-handwritten': 'Kambing Sehat<br />Keluarga Bahagia <i class="fa-regular fa-heart"></i>',
            
            '.about .subheading': '<span class="line"></span> KISAH KAMI',
            '.about h2.animate-text': 'Tentang Kami',
            '.about h3.animate-text': 'Lebih Dari Sekadar Peternakan,<br />Masa Depan yang Lebih Sehat',
            '.about-content > p': 'Rida Farm Bali dibangun atas satu keyakinan sederhana: kambing yang sehat akan menghasilkan makanan bergizi untuk keluarga yang lebih sehat. Kami merawat kambing kami dengan penuh kasih sayang, rasa hormat, dan lingkungan alami, menghasilkan susu dan yogurt segar nan bergizi untuk keseharian Anda.',
            '.about .btn-primary': 'Baca Kisah Kami <i class="fa-solid fa-arrow-right icon-right"></i>',
            '.about-handwritten': 'Peduli<br />Merawat<br />Hidup Lebih Baik <i class="fa-regular fa-heart"></i>',
            
            '.philosophy .subheading': '<span class="line"></span> FILOSOFI KAMI <span class="line"></span>',
            '.philosophy .section-title': 'Nilai Sederhana, Dunia Lebih Sehat',
            '.philosophy-grid .feature-card:nth-child(1) h3': 'Perawatan Hewan',
            '.philosophy-grid .feature-card:nth-child(1) p': 'Kambing yang sehat dan bahagia adalah jantung dari setiap hal yang kami lakukan.',
            '.philosophy-grid .feature-card:nth-child(2) h3': 'Kesegaran',
            '.philosophy-grid .feature-card:nth-child(2) p': 'Dari peternakan kami ke meja Anda, kami memastikan produk olahan susu yang alami, segar, dan berkualitas.',
            '.philosophy-grid .feature-card:nth-child(3) h3': 'Dari Peternakan ke Keluarga',
            '.philosophy-grid .feature-card:nth-child(3) p': 'Makanan bergizi yang mendekatkan keluarga, untuk hari ini dan generasi mendatang.',
            
            '.products .subheading': '<span class="line"></span> PRODUK KAMI',
            '.products-content > h2.animate-text': 'Nutrisi Alami<br />untuk Anda yang Lebih Baik',
            '.products-content > p.animate-fade-up': 'Susu kambing dan yogurt lezat nan bergizi yang dibuat sepenuh hati di Rida Farm Bali. Murni, alami, dan penuh kebaikan untuk kesehatan harian keluarga Anda.',
            '.products-content .btn-primary': 'Lihat Semua Produk <i class="fa-solid fa-arrow-right icon-right"></i>',
            '.product-features .p-feature:nth-child(1) span': '100% Alami',
            '.product-features .p-feature:nth-child(2) span': 'Pilihan Sehat',
            '.product-features .p-feature:nth-child(3) span': 'Untuk Seluruh Keluarga',
            '.products-handwritten': 'Rasa Alami<br />untuk Hari yang<br />Lebih Baik <i class="fa-regular fa-heart"></i>',
            
            '.news-header-left .subheading': '<span class="line"></span> BERITA & CERITA',
            '.news-header-left h2': 'Kabar Terbaru dari Peternakan',
            '.news-header-left p': 'Cerita, perkembangan terbaru, dan inspirasi dari perjalanan Rida Farm Bali.',
            '#newsSlider .news-card:nth-child(1) .news-title': 'Kambing Bahagia, Hidup Lebih Sehat',
            '#newsSlider .news-card:nth-child(1) .news-excerpt': 'Melihat lebih dekat cara kami merawat kambing-kambing kami setiap hari.',
            '#newsSlider .news-card:nth-child(2) .news-title': 'Orang-orang, Kambing, dan Masa Depan Cerah',
            '#newsSlider .news-card:nth-child(2) .news-excerpt': 'Kenali mereka yang berada di balik Rida Farm Bali.',
            '#newsSlider .news-card:nth-child(3) .news-title': 'Kebaikan Susu Kambing',
            '#newsSlider .news-card:nth-child(3) .news-excerpt': 'Mengapa susu kambing adalah pilihan alami yang tepat bagi keluarga Anda.',
            '#newsSlider .news-card:nth-child(4) .news-title': 'Dari Peternakan ke Meja Makan',
            '#newsSlider .news-card:nth-child(4) .news-excerpt': 'Bagaimana produk kami sampai di tangan Anda dalam keadaan segar setiap hari.',
            
            '.banner-title': 'Menyambut Investor',
            '.banner-center': 'Investasi sekarang dan kembang bersama ridafarmbali — dapatkan nilai lebih nanti.',
            '.banner-right .btn': '<i class="fa-solid fa-chart-line"></i> Investasi Sekarang <i class="fa-solid fa-arrow-right icon-right"></i>'
        }
    };

    const langBtns = document.querySelectorAll('.lang-btn');
    langBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            // Update active state
            langBtns.forEach(b => b.classList.remove('active'));
            e.target.classList.add('active');
            
            const lang = e.target.getAttribute('data-lang');
            const dictionary = i18n[lang];
            
            // Apply translations
            for (const selector in dictionary) {
                const el = document.querySelector(selector);
                if (el) {
                    el.innerHTML = dictionary[selector];
                }
            }
            
            // Save language preference
            localStorage.setItem('rida_lang', lang);
        });
    });

    // Check saved language or default to en
    const savedLang = localStorage.getItem('rida_lang') || 'en';
    if (savedLang === 'id') {
        const idBtn = document.querySelector('.lang-btn[data-lang="id"]');
        if (idBtn) idBtn.click();
    }

});
