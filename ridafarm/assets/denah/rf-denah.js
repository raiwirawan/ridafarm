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
        const charEl = wrapper.querySelector('.rfd-char');
        const popupEl = wrapper.querySelector('.rfd-popup');
        const popupHead = popupEl.querySelector('.rfd-popup-head');
        const popupBody = popupEl.querySelector('.rfd-popup-body');
        const hotspots = wrapper.querySelectorAll('.rfd-hotspot');

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
            hotspots.forEach(h => h.classList.remove('rfd-active'));
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
            
            popupHead.textContent = hsData.title || '';
            popupBody.innerHTML = '';
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
                
                popupBody.appendChild(btn);
            });

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
        };

        const moveCharacter = (hsData) => {
            closePopup();
            
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
                    moveCharacter(data);
                }
            });
        });

        wrapper.addEventListener('click', function(e) {
            if (!e.target.closest('.rfd-hotspot') && !e.target.closest('.rfd-popup')) {
                closePopup();
            }
        });

        wrapper.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && popupEl.classList.contains('rfd-show')) {
                closePopup();
            }
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
