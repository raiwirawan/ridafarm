/* --- rf-denah.js --- */
(function() {
    'use strict';

    function initRfdMap(wrapper) {
        if (wrapper.hasAttribute('data-rfd-ready')) return;
        wrapper.setAttribute('data-rfd-ready', 'true');

        const configRaw = wrapper.getAttribute('data-rfd-config');
        if (!configRaw) return;
        
        let config;
        try {
            config = JSON.parse(configRaw);
        } catch (e) {
            console.error('RF Denah config error:', e);
            return;
        }

        const mapEl = wrapper.querySelector('.rfd-map');
        const viewportEl = wrapper.querySelector('.rfd-viewport');
        const charEl = wrapper.querySelector('.rfd-char');
        const popupEl = wrapper.querySelector('.rfd-popup');
        const popupHead = popupEl.querySelector('.rfd-popup-head');
        const popupBody = popupEl.querySelector('.rfd-popup-body');
        const hotspots = wrapper.querySelectorAll('.rfd-hotspot');
        const chips = wrapper.querySelectorAll('.rfd-chip');
        const chipsContainer = wrapper.querySelector('.rfd-chips');
        
        const sheetEl = wrapper.querySelector('.rfd-sheet');
        const sheetOverlay = wrapper.querySelector('.rfd-sheet-overlay');
        const sheetHead = wrapper.querySelector('.rfd-sheet-head');
        const sheetBody = wrapper.querySelector('.rfd-sheet-body');
        const sheetClose = wrapper.querySelector('.rfd-sheet-close');
        
        const hintEl = wrapper.querySelector('.rfd-hint');

        let isMobile = window.matchMedia('(max-width: 767px)').matches;
        let isPanMode = wrapper.classList.contains('rfd-mode-pan');

        let currentTimeout = null;
        let activeTransitionListener = null;

        // Hover Integration
        const applyHoverState = (el) => {
            el.addEventListener('mouseenter', () => document.body.classList.add('hovering'));
            el.addEventListener('mouseleave', () => document.body.classList.remove('hovering'));
        };
        hotspots.forEach(applyHoverState);

        const closePopup = () => {
            popupEl.classList.remove('rfd-show');
            if (sheetEl) {
                sheetEl.classList.remove('rfd-show');
                sheetOverlay.classList.remove('rfd-show');
                document.documentElement.classList.remove('rfd-sheet-open');
            }
            hotspots.forEach(h => h.classList.remove('rfd-active'));
            chips.forEach(c => c.classList.remove('rfd-active'));
        };

        const clampPopup = () => {
            const pRect = popupEl.getBoundingClientRect();
            const mRect = mapEl.getBoundingClientRect();
            
            if (mRect.width === 0) return;

            // horizontal clamp
            let xOffset = 0;
            if (pRect.left < mRect.left) xOffset = mRect.left - pRect.left + 8;
            else if (pRect.right > mRect.right) xOffset = mRect.right - pRect.right - 8;
            
            const currTransform = window.getComputedStyle(popupEl).transform;
            if(xOffset !== 0 && currTransform !== 'none') {
                popupEl.style.setProperty('margin-left', `${xOffset}px`, 'important');
            } else {
                popupEl.style.setProperty('margin-left', '0px', 'important');
            }
        };

        const handleArrival = (top, left, hsData) => {
            if (charEl) {
                charEl.classList.remove('rfd-moving');
                charEl.classList.add('rfd-arrived');
            }
            
            const targetHead = isMobile && sheetEl ? sheetHead : popupHead;
            const targetBody = isMobile && sheetEl ? sheetBody : popupBody;
            
            targetHead.textContent = hsData.title || '';
            targetBody.innerHTML = '';
            popupEl.style.setProperty('margin-left', '0px', 'important');
            
            const opts = config.options.filter(o => o.key === hsData.key);
            opts.forEach(opt => {
                let btn;
                if (opt.type === 'link') {
                    btn = document.createElement('a');
                    btn.href = opt.url || '#';
                    if(opt.blank) btn.target = '_blank';
                    if(opt.nofollow) btn.rel = 'nofollow';
                } else {
                    btn = document.createElement('button');
                    btn.type = 'button';
                }
                
                btn.className = 'rfd-popup-btn';
                btn.textContent = opt.label;
                applyHoverState(btn);
                
                if (opt.type === 'close') {
                    btn.addEventListener('click', closePopup);
                } else if (opt.type === 'modal') {
                    btn.addEventListener('click', () => openModal(opt.index, wrapper));
                }
                
                targetBody.appendChild(btn);
            });

            if (isMobile && sheetEl) {
                sheetOverlay.classList.add('rfd-show');
                sheetEl.classList.add('rfd-show');
                document.documentElement.classList.add('rfd-sheet-open');
                if (sheetClose) sheetClose.focus();
            } else {
                let topVal = parseFloat(top);
                let leftVal = parseFloat(left);
                
                // Positioning Logic
                let popPos = hsData.popupPos || 'auto';
                if (popPos === 'auto') {
                    popPos = topVal < 25 ? 'right' : 'top';
                }

                if (popPos === 'right') {
                    popupEl.style.setProperty('top', topVal + '%', 'important');
                    popupEl.style.setProperty('left', (leftVal + (parseFloat(config.charW)/2 || 2.5) + 2) + '%', 'important');
                    popupEl.style.setProperty('transform', 'translate(0, -50%)', 'important');
                } else if (popPos === 'left') {
                    popupEl.style.setProperty('top', topVal + '%', 'important');
                    popupEl.style.setProperty('left', (leftVal - (parseFloat(config.charW)/2 || 2.5) - 2) + '%', 'important');
                    popupEl.style.setProperty('transform', 'translate(-100%, -50%)', 'important');
                } else {
                    // top
                    popupEl.style.setProperty('top', (topVal - 6) + '%', 'important');
                    popupEl.style.setProperty('left', leftVal + '%', 'important');
                    popupEl.style.setProperty('transform', 'translate(-50%, -100%)', 'important');
                }
                
                popupEl.classList.add('rfd-show');
                setTimeout(clampPopup, 50); // wait for display
            }
        };

        const moveCharacter = (hsData) => {
            closePopup();
            
            if (isMobile && isPanMode && viewportEl && hintEl) {
                hintEl.classList.remove('rfd-show');
            }
            
            if (!charEl) {
                // Fallback if character is disabled
                handleArrival(hsData.cTop, hsData.cLeft, hsData);
                return;
            }

            const currentTop = charEl.style.top;
            const currentLeft = charEl.style.left;
            
            if (currentTop === hsData.cTop && currentLeft === hsData.cLeft) {
                handleArrival(hsData.cTop, hsData.cLeft, hsData);
                return;
            }

            if (activeTransitionListener) {
                charEl.removeEventListener('transitionend', activeTransitionListener);
            }
            clearTimeout(currentTimeout);

            charEl.classList.remove('rfd-arrived');
            void charEl.offsetWidth; // flush css
            charEl.classList.add('rfd-moving');
            
            charEl.style.setProperty('top', hsData.cTop, 'important');
            charEl.style.setProperty('left', hsData.cLeft, 'important');

            if (isMobile && isPanMode && viewportEl) {
                // Scroll viewport so character target is in center
                const mapWidth = mapEl.offsetWidth;
                const viewWidth = viewportEl.clientWidth;
                const leftPercent = parseFloat(hsData.cLeft) / 100;
                const targetPixel = mapWidth * leftPercent;
                const scrollTarget = targetPixel - (viewWidth / 2);
                viewportEl.scrollTo({ left: scrollTarget, behavior: 'smooth' });
            }

            const onTransitionEnd = (e) => {
                if (e.propertyName === 'top' || e.propertyName === 'left') {
                    charEl.removeEventListener('transitionend', onTransitionEnd);
                    clearTimeout(currentTimeout);
                    handleArrival(hsData.cTop, hsData.cLeft, hsData);
                }
            };
            
            activeTransitionListener = onTransitionEnd;
            charEl.addEventListener('transitionend', onTransitionEnd);
            
            const dur = parseFloat(config.dur) * 1000 || 1000;
            currentTimeout = setTimeout(() => {
                 charEl.removeEventListener('transitionend', onTransitionEnd);
                 handleArrival(hsData.cTop, hsData.cLeft, hsData);
            }, dur + 100); 
        };

        hotspots.forEach(hs => {
            hs.addEventListener('click', function(e) {
                e.stopPropagation();
                const key = this.getAttribute('data-key');
                const data = config.hotspots.find(h => h.key === key);
                if(data) {
                    hotspots.forEach(h => h.classList.remove('rfd-active'));
                    this.classList.add('rfd-active');
                    
                    chips.forEach(c => c.classList.remove('rfd-active'));
                    const activeChip = Array.from(chips).find(c => c.getAttribute('data-key') === key);
                    if (activeChip) {
                        activeChip.classList.add('rfd-active');
                        activeChip.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                    }
                    
                    wrapper.classList.remove('rfd-pulse-on'); // turn off auto pulse
                    moveCharacter(data);
                }
            });
        });

        chips.forEach(chip => {
            chip.addEventListener('click', function(e) {
                const key = this.getAttribute('data-key');
                const hs = Array.from(hotspots).find(h => h.getAttribute('data-key') === key);
                if (hs) hs.click();
            });
        });

        wrapper.addEventListener('click', function(e) {
            if (!e.target.closest('.rfd-hotspot') && !e.target.closest('.rfd-popup') && !e.target.closest('.rfd-chip') && !e.target.closest('.rfd-sheet')) {
                closePopup();
            }
        });

        if (sheetOverlay) sheetOverlay.addEventListener('click', closePopup);
        if (sheetClose) sheetClose.addEventListener('click', closePopup);

        // Swipe to close sheet
        if (sheetEl) {
            let startY = 0;
            let currentY = 0;
            const handle = sheetEl.querySelector('.rfd-sheet-handle');
            const head = sheetEl.querySelector('.rfd-sheet-head');
            
            const onTouchStart = (e) => {
                startY = e.touches[0].clientY;
                currentY = startY;
                sheetEl.style.transition = 'none';
            };
            const onTouchMove = (e) => {
                currentY = e.touches[0].clientY;
                const delta = currentY - startY;
                if (delta > 0) {
                    sheetEl.style.transform = `translateY(${delta}px)`;
                }
            };
            const onTouchEnd = () => {
                sheetEl.style.transition = '';
                sheetEl.style.transform = '';
                if (currentY - startY > 50) {
                    closePopup();
                }
            };

            if (handle) {
                handle.addEventListener('touchstart', onTouchStart, {passive: true});
                handle.addEventListener('touchmove', onTouchMove, {passive: true});
                handle.addEventListener('touchend', onTouchEnd);
            }
            if (head) {
                head.addEventListener('touchstart', onTouchStart, {passive: true});
                head.addEventListener('touchmove', onTouchMove, {passive: true});
                head.addEventListener('touchend', onTouchEnd);
            }
        }

        wrapper.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && popupEl.classList.contains('rfd-show')) {
                closePopup();
            }
            if (e.key === 'Escape' && sheetEl && sheetEl.classList.contains('rfd-show')) {
                closePopup();
            }
        });

        // Resize detection
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                const wasMobile = isMobile;
                isMobile = window.matchMedia('(max-width: 767px)').matches;
                if (wasMobile !== isMobile) {
                    closePopup();
                } else if (!isMobile && popupEl.classList.contains('rfd-show')) {
                    clampPopup();
                }
            }, 100);
        });
        
        // --- Modal Logic ---
        function openModal(optIndex, wrapperEl) {
            const tpl = wrapperEl.querySelector(`.rfd-modal-tpl[data-opt="${optIndex}"]`);
            if (!tpl) return;
            
            const computed = window.getComputedStyle(wrapperEl);
            const vars = ['--rfd-modal-overlay', '--rfd-modal-blur', '--rfd-modal-bg', '--rfd-modal-w', '--rfd-modal-radius', '--rfd-modal-pad', '--rfd-modal-close-color', '--rfd-modal-title-color', '--rfd-modal-text', '--rfd-modal-cta-bg', '--rfd-modal-cta-color', '--rfd-modal-cta-hover'];
            
            let modalStyle = '';
            vars.forEach(v => {
                const val = computed.getPropertyValue(v);
                if(val) modalStyle += `${v}: ${val.trim()}; `;
            });

            const animType = wrapperEl.getAttribute('data-rfd-modal-anim') || 'zoom';
            const wId = wrapperEl.getAttribute('data-rfd-id');
            const existing = document.querySelector(`.rfd-modal-portal[data-rfd-owner="${wId}"]`);
            if(existing) existing.remove();

            const portal = document.createElement('div');
            portal.className = 'rfd-modal-portal';
            portal.setAttribute('data-rfd-owner', wId);
            portal.setAttribute('data-anim', animType);
            portal.setAttribute('role', 'dialog');
            portal.setAttribute('aria-modal', 'true');
            if (modalStyle) portal.style.cssText = modalStyle;
            
            portal.innerHTML = tpl.innerHTML;
            document.body.appendChild(portal);

            document.documentElement.classList.add('rfd-modal-open');
            
            const overlay = portal.querySelector('.rfd-modal-overlay');
            const closeBtn = portal.querySelector('.rfd-modal-close');
            const ctaBtn = portal.querySelector('.rfd-modal-cta');
            
            [closeBtn, ctaBtn].forEach(el => {
                if(el) applyHoverState(el);
            });

            void portal.offsetWidth;
            portal.classList.add('rfd-show');
            if(closeBtn) closeBtn.focus();

            const closeModal = () => {
                portal.classList.remove('rfd-show');
                document.documentElement.classList.remove('rfd-modal-open');
                setTimeout(() => portal.remove(), 300);
            };

            if(overlay) overlay.addEventListener('click', closeModal);
            if(closeBtn) closeBtn.addEventListener('click', closeModal);
            portal.addEventListener('keydown', (e) => {
                if(e.key === 'Escape') closeModal();
            });
        }
        
        // --- Pulse & Hint Logic ---
        if ('IntersectionObserver' in window) {
            let hasShownPulse = false;
            let pulseTimeout = null;
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !hasShownPulse) {
                        hasShownPulse = true;
                        
                        // Desktop Pulse
                        if (wrapper.getAttribute('data-rfd-pulse') === 'auto') {
                            wrapper.classList.add('rfd-pulse-on');
                            pulseTimeout = setTimeout(() => {
                                wrapper.classList.remove('rfd-pulse-on');
                            }, 5000);
                        }
                        
                        // Hint
                        if (hintEl && isMobile && isPanMode) {
                            hintEl.classList.add('rfd-show');
                            const hideHint = () => hintEl.classList.remove('rfd-show');
                            
                            // Hide hint on user interaction
                            if (viewportEl) viewportEl.addEventListener('scroll', hideHint, {once: true});
                            setTimeout(hideHint, 4000); // Or hide after 4s
                        }
                        
                        observer.disconnect();
                    }
                });
            }, { threshold: 0.2 });
            
            observer.observe(wrapper);
        }
    }

    const runInit = () => {
        document.querySelectorAll('.rfd-wrapper:not([data-rfd-ready])').forEach(initRfdMap);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runInit);
    } else {
        runInit();
    }

    if (window.elementorFrontend) {
        window.elementorFrontend.hooks.addAction('frontend/element_ready/rf_denah.default', function($scope) {
            if ($scope[0]) {
                const wrap = $scope[0].querySelector('.rfd-wrapper');
                if (wrap) {
                    const wId = wrap.getAttribute('data-rfd-id');
                    const old = document.querySelector(`.rfd-modal-portal[data-rfd-owner="${wId}"]`);
                    if(old) old.remove();
                    initRfdMap(wrap);
                }
            }
        });
    }

})();
