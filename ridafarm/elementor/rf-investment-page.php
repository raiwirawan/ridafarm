<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Rida_Widget_Investment_Page extends \Elementor\Widget_Base {
    public function get_name() { return 'rf_investment_page'; }
    public function get_title() { return __( 'Rida Farm Investment Page', 'ridafarm' ); }
    public function get_icon() { return 'eicon-chart-pie'; }
    public function get_categories() { return [ 'general' ]; }

    protected function register_controls() {
        // 1. Hero Section
        $this->start_controls_section('hero_sec', ['label' => __( 'Hero Section', 'ridafarm' )]);
        $this->add_control('hero_image', ['label' => __( 'Background Image', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::MEDIA]);
        $this->add_control('hero_eyebrow', ['label' => __( 'Eyebrow', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'INVESTOR RELATIONS']);
        $this->add_control('hero_title', ['label' => __( 'Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Grow With Us']);
        $this->add_control('hero_subtitle', ['label' => __( 'Subtitle', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Invest in your future with our sustainable organic goat farm']);
        $this->add_control('hero_btn_text', ['label' => __( 'Button Text', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'VIEW INVESTMENT PACKAGES']);
        $this->add_control('hero_btn_link', ['label' => __( 'Button Link', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => ['url' => '#packages']]);
        
        $this->add_control('hero_eyebrow_color', [
            'label' => __( 'Eyebrow Color', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .inv-hero-eyebrow' => 'color: {{VALUE}} !important;'],
        ]);
        $this->add_control('hero_title_color', [
            'label' => __( 'Title Inner Color', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .inv-hero-title' => 'color: {{VALUE}} !important;'],
        ]);
        $this->add_control('hero_outline_color', [
            'label' => __( 'Title Outline Color', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .inv-hero-title' => '-webkit-text-stroke: 1.5px {{VALUE}}; text-shadow: -1px -1px 0 {{VALUE}}, 1px -1px 0 {{VALUE}}, -1px 1px 0 {{VALUE}}, 1px 1px 0 {{VALUE}};',
            ],
        ]);
        $this->end_controls_section();

        // 2. Why Invest Section
        $this->start_controls_section('why_sec', ['label' => __( 'Why Invest Section', 'ridafarm' )]);
        $this->add_control('why_title', ['label' => __( 'Section Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Why Invest in Rida Farm?']);
        
        $repeater_why = new \Elementor\Repeater();
        $repeater_why->add_control('icon', ['label' => __( 'Icon (Emoji/Text)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater_why->add_control('title', ['label' => __( 'Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater_why->add_control('desc', ['label' => __( 'Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA]);
        $this->add_control('why_cards', ['label' => __( 'Cards', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater_why->get_controls(), 'default' => [
            ['icon' => '🌱', 'title' => 'High Market Demand', 'desc' => 'The demand for fresh organic goat milk products continues to grow in Bali.'],
            ['icon' => '📈', 'title' => 'Proven Track Record', 'desc' => 'Stable growth and loyal customers from five-star restaurants.'],
            ['icon' => '♻️', 'title' => 'Sustainable Farming', 'desc' => 'Eco-friendly business model focused on animal welfare.'],
        ]]);
        $this->end_controls_section();

        // 3. Use of Funds Section
        $this->start_controls_section('funds_sec', ['label' => __( 'Use of Funds Section', 'ridafarm' )]);
        $this->add_control('funds_title', ['label' => __( 'Section Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Use of Funds']);
        $repeater_funds = new \Elementor\Repeater();
        $repeater_funds->add_control('name', ['label' => __( 'Allocation Name', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater_funds->add_control('percent', ['label' => __( 'Percentage (number only)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'min' => 0, 'max' => 100]);
        $this->add_control('funds_items', ['label' => __( 'Allocations', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater_funds->get_controls(), 'default' => [
            ['name' => 'Livestock expansion', 'percent' => 40],
            ['name' => 'Farm infrastructure', 'percent' => 25],
            ['name' => 'Dairy production equipment', 'percent' => 20],
            ['name' => 'Marketing & distribution', 'percent' => 10],
            ['name' => 'Operational reserves', 'percent' => 5],
        ]]);
        $this->end_controls_section();

        // 4. Investment Packages Section
        $this->start_controls_section('packages_sec', ['label' => __( 'Investment Packages', 'ridafarm' )]);
        $this->add_control('packages_title', ['label' => __( 'Section Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Investment Packages']);
        
        $repeater_pkg = new \Elementor\Repeater();
        $repeater_pkg->add_control('pkg_name', ['label' => __( 'Package Name', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater_pkg->add_control('pkg_price', ['label' => __( 'Investment Amount (IDR)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater_pkg->add_control('pkg_duration', ['label' => __( 'Duration', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater_pkg->add_control('pkg_roi', ['label' => __( 'ROI / Year (%)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'min' => 0, 'max' => 100]);
        $repeater_pkg->add_control('pkg_return_total', ['label' => __( 'Return Total (Text)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater_pkg->add_control('pkg_report', ['label' => __( 'Reporting', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater_pkg->add_control('pkg_visit', ['label' => __( 'Farm Visit', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater_pkg->add_control('pkg_highlight', ['label' => __( 'Highlight Card?', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::SWITCHER]);
        
        $this->add_control('packages', ['label' => __( 'Packages', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater_pkg->get_controls(), 'default' => [
            ['pkg_name' => 'Starter', 'pkg_price' => '5.000.000', 'pkg_duration' => '12 months', 'pkg_roi' => 12, 'pkg_return_total' => 'IDR 600.000', 'pkg_report' => 'Quarterly', 'pkg_visit' => 'None', 'pkg_highlight' => ''],
            ['pkg_name' => 'Growth', 'pkg_price' => '15.000.000', 'pkg_duration' => '24 months', 'pkg_roi' => 18, 'pkg_return_total' => 'IDR 5.400.000', 'pkg_report' => 'Monthly', 'pkg_visit' => '1x / year', 'pkg_highlight' => 'yes'],
            ['pkg_name' => 'Premium', 'pkg_price' => '50.000.000', 'pkg_duration' => '36 months', 'pkg_roi' => 25, 'pkg_return_total' => 'IDR 37.500.000', 'pkg_report' => 'Weekly', 'pkg_visit' => 'Unlimited', 'pkg_highlight' => ''],
        ]]);
        $this->end_controls_section();

        // 5. ROI Calculator Section
        $this->start_controls_section('calc_sec', ['label' => __( 'ROI Calculator', 'ridafarm' )]);
        $this->add_control('calc_title', ['label' => __( 'Section Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'ROI Calculator']);
        $this->add_control('calc_desc', ['label' => __( 'Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Simulate your returns. (ROI rates are based on the packages above)']);
        $this->end_controls_section();

        // 6. Transparency Section
        $this->start_controls_section('trans_sec', ['label' => __( 'Transparency & Legality', 'ridafarm' )]);
        $this->add_control('trans_title', ['label' => __( 'Section Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Transparency & Legality']);
        
        $repeater_acc = new \Elementor\Repeater();
        $repeater_acc->add_control('acc_title', ['label' => __( 'Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater_acc->add_control('acc_content', ['label' => __( 'Content', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::WYSIWYG]);
        $this->add_control('accordions', ['label' => __( 'Items', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater_acc->get_controls(), 'default' => [
            ['acc_title' => 'Investment Agreement', 'acc_content' => 'We use legally binding cooperation agreements (MOU).'],
            ['acc_title' => 'Business Licenses & Certifications', 'acc_content' => 'Rida Farm holds complete and certified farming licenses.'],
            ['acc_title' => 'Reporting Process', 'acc_content' => 'Investors receive transparent periodic reports.'],
        ]]);
        $this->end_controls_section();

        // 7. Booking Interest Form
        $this->start_controls_section('form_sec', ['label' => __( 'Interest Form', 'ridafarm' )]);
        $this->add_control('form_title', ['label' => __( 'Form Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'SEND INVESTMENT INTEREST']);
        $this->add_control('form_wa_number', [
            'label' => __( 'WhatsApp Number', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '6281936663738',
            'description' => 'Use country code without +, e.g. 6281936663738',
        ]);
        $this->add_control('form_btn', ['label' => __( 'Submit Button Text', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'SUBMIT MY INTEREST']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="inv-wrapper">
            <!-- 1. Hero -->
            <section class="inv-hero" style="background-image: url('<?php echo esc_url($settings['hero_image']['url'] ?? ''); ?>');">
                <div class="inv-hero-overlay"></div>
                <div class="inv-hero-content container">
                    <p class="inv-hero-eyebrow"><?php echo esc_html($settings['hero_eyebrow']); ?></p>
                    <h1 class="inv-hero-title"><i><?php echo esc_html($settings['hero_title']); ?></i></h1>
                    <p class="inv-hero-subtitle"><?php echo esc_html($settings['hero_subtitle']); ?></p>
                    <?php if(!empty($settings['hero_btn_text'])): ?>
                        <a href="<?php echo esc_url($settings['hero_btn_link']['url']); ?>" class="btn pp-btn mt-4"><?php echo esc_html($settings['hero_btn_text']); ?></a>
                    <?php endif; ?>
                </div>
            </section>

            <!-- 2. Why Invest -->
            <section class="inv-why section-padding">
                <div class="container">
                    <h2 class="inv-section-title text-center"><?php echo esc_html($settings['why_title']); ?></h2>
                    <div class="inv-why-grid">
                        <?php if(!empty($settings['why_cards'])): foreach($settings['why_cards'] as $card): ?>
                            <div class="inv-why-card">
                                <div class="inv-why-icon"><?php echo esc_html($card['icon']); ?></div>
                                <h3 class="inv-why-card-title"><?php echo esc_html($card['title']); ?></h3>
                                <p class="inv-why-desc"><?php echo esc_html($card['desc']); ?></p>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            </section>

            <!-- 3. Use of Funds -->
            <section class="inv-funds section-padding">
                <div class="container">
                    <h2 class="inv-section-title text-center"><?php echo esc_html($settings['funds_title']); ?></h2>
                    <div class="inv-funds-layout">
                        <?php if(!empty($settings['funds_items'])): foreach($settings['funds_items'] as $item): ?>
                            <div class="inv-fund-item">
                                <div class="inv-fund-header">
                                    <span class="inv-fund-name"><?php echo esc_html($item['name']); ?></span>
                                    <span class="inv-fund-pct"><?php echo esc_html($item['percent']); ?>%</span>
                                </div>
                                <div class="inv-bar-row">
                                    <div class="inv-bar-fill" style="width: <?php echo esc_attr($item['percent']); ?>%;"></div>
                                </div>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            </section>

            <!-- 4. Packages -->
            <section class="inv-packages section-padding" id="packages">
                <div class="container">
                    <h2 class="inv-section-title text-center"><?php echo esc_html($settings['packages_title']); ?></h2>
                    <div class="inv-pkg-grid">
                        <?php 
                        $pkg_data = []; // Save for calculator
                        if(!empty($settings['packages'])): foreach($settings['packages'] as $idx => $pkg): 
                            $pkg_data[] = ['name' => $pkg['pkg_name'], 'roi' => $pkg['pkg_roi'], 'dur' => (int)filter_var($pkg['pkg_duration'], FILTER_SANITIZE_NUMBER_INT) ];
                            $hl = $pkg['pkg_highlight'] === 'yes' ? 'inv-pkg-highlight' : '';
                        ?>
                            <div class="inv-pkg-card <?php echo $hl; ?>">
                                <h3 class="inv-pkg-name"><?php echo esc_html($pkg['pkg_name']); ?></h3>
                                <div class="inv-pkg-price">IDR <?php echo esc_html($pkg['pkg_price']); ?></div>
                                
                                <ul class="inv-pkg-features">
                                    <li><span>Duration</span> <strong><?php echo esc_html($pkg['pkg_duration']); ?></strong></li>
                                    <li><span>ROI / Year</span> <strong><?php echo esc_html($pkg['pkg_roi']); ?>%</strong></li>
                                    <li><span>Total Return</span> <strong><?php echo esc_html($pkg['pkg_return_total']); ?></strong></li>
                                    <li><span>Reporting</span> <strong><?php echo esc_html($pkg['pkg_report']); ?></strong></li>
                                    <li><span>Farm Visit</span> <strong><?php echo esc_html($pkg['pkg_visit']); ?></strong></li>
                                </ul>
                                
                                <a href="#inv-form" class="btn pp-btn w-100 justify-center">START INVESTING</a>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            </section>

            <!-- 5. ROI Calculator -->
            <section class="inv-calc section-padding">
                <div class="container">
                    <div class="inv-calc-box">
                        <h2 class="inv-section-title text-center"><?php echo esc_html($settings['calc_title']); ?></h2>
                        <p class="text-center mb-4"><?php echo esc_html($settings['calc_desc']); ?></p>
                        
                        <div class="inv-calc-grid">
                            <div class="inv-calc-input">
                                <div class="inv-calc-group">
                                    <label for="calcAmount">Investment Amount (IDR)</label>
                                    <input type="number" id="calcAmount" value="10000000" min="1000000" step="1000000">
                                </div>
                                
                                <div class="inv-calc-group mt-group">
                                    <label for="calcPkg">Select Package</label>
                                    <select id="calcPkg">
                                        <?php foreach($pkg_data as $p): ?>
                                            <option value="<?php echo esc_attr($p['roi']); ?>" data-dur="<?php echo esc_attr($p['dur']); ?>"><?php echo esc_html($p['name']); ?> (<?php echo esc_html($p['roi']); ?>% / yr)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="inv-calc-result">
                                <div class="inv-res-item">
                                    <span>Estimated Yearly Return</span>
                                    <strong id="resYearly">IDR 0</strong>
                                </div>
                                <div class="inv-res-item">
                                    <span>Total Return (<span id="resDur">0</span> months)</span>
                                    <strong id="resTotal">IDR 0</strong>
                                </div>
                                <div class="inv-res-item inv-res-final">
                                    <span>Total Amount Received</span>
                                    <strong id="resFinal">IDR 0</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 6. Transparency -->
            <section class="inv-trans section-padding">
                <div class="container">
                    <h2 class="inv-section-title text-center"><?php echo esc_html($settings['trans_title']); ?></h2>
                    <div class="inv-acc-list">
                        <?php if(!empty($settings['accordions'])): foreach($settings['accordions'] as $acc): ?>
                            <div class="inv-acc-item">
                                <button class="inv-acc-btn"><?php echo esc_html($acc['acc_title']); ?> <i class="fas fa-chevron-down"></i></button>
                                <div class="inv-acc-content">
                                    <div class="inv-acc-inner">
                                        <?php echo wp_kses_post($acc['acc_content']); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            </section>

            <!-- 7. Booking Form -->
            <section class="inv-booking section-padding" id="inv-form">
                <div class="container">
                    <div class="inv-form-box">
                        <h3 class="inv-form-title"><?php echo esc_html($settings['form_title']); ?></h3>
                        <form class="inv-form" id="invBookingForm" data-wa-number="<?php echo esc_attr($settings['form_wa_number']); ?>" onsubmit="event.preventDefault();">
                            <div class="inv-form-group">
                                <input type="text" id="invName" placeholder="Full Name *" required>
                            </div>
                            <div class="inv-form-group">
                                <input type="email" id="invEmail" placeholder="Email *" required>
                            </div>
                            <div class="inv-form-group">
                                <input type="text" id="invWA" placeholder="WhatsApp Number *" required>
                            </div>
                            <div class="inv-form-group">
                                <select id="invPkg" required>
                                    <option value="" disabled selected>Select Investment Package *</option>
                                    <?php foreach($pkg_data as $p): ?>
                                        <option value="<?php echo esc_attr($p['name']); ?>"><?php echo esc_html($p['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="inv-form-group">
                                <input type="number" id="invAmount" placeholder="Planned Investment Amount (IDR)" min="1000000">
                            </div>
                            <div class="inv-form-group">
                                <textarea id="invNote" placeholder="Questions / Notes" rows="3"></textarea>
                            </div>
                            <div class="inv-form-group mt-4">
                                <button type="submit" id="invSubmitBtn" class="btn pp-btn w-100 justify-center" disabled style="opacity:0.5; cursor:not-allowed;"><?php echo esc_html($settings['form_btn']); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Calculator Logic
            const calcAmount = document.getElementById('calcAmount');
            const calcPkg = document.getElementById('calcPkg');
            const resYearly = document.getElementById('resYearly');
            const resTotal = document.getElementById('resTotal');
            const resFinal = document.getElementById('resFinal');
            const resDur = document.getElementById('resDur');

            const updateCalc = () => {
                if(!calcAmount || !calcPkg) return;
                const amount = parseFloat(calcAmount.value) || 0;
                const roiPct = parseFloat(calcPkg.value) || 0;
                const selectedOpt = calcPkg.options[calcPkg.selectedIndex];
                const durMonths = parseFloat(selectedOpt.getAttribute('data-dur')) || 12;

                const yearly = amount * (roiPct / 100);
                const totalRet = yearly * (durMonths / 12);
                const finalAmt = amount + totalRet;

                const fmt = (num) => 'IDR ' + num.toLocaleString('id-ID');
                
                resYearly.textContent = fmt(yearly);
                resTotal.textContent = fmt(totalRet);
                resFinal.textContent = fmt(finalAmt);
                resDur.textContent = durMonths;
            };

            if(calcAmount && calcPkg) {
                calcAmount.addEventListener('input', updateCalc);
                calcPkg.addEventListener('change', updateCalc);
                updateCalc();
            }

            // Accordion Logic
            const accBtns = document.querySelectorAll('.inv-acc-btn');
            accBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    this.classList.toggle('active');
                    const content = this.nextElementSibling;
                    if (content.style.maxHeight) {
                        content.style.maxHeight = null;
                    } else {
                        content.style.maxHeight = content.scrollHeight + "px";
                    } 
                });
            });

            // Form Logic
            const invForm = document.getElementById('invBookingForm');
            if(invForm) {
                const submitBtn = document.getElementById('invSubmitBtn');
                const reqInputs = invForm.querySelectorAll('[required]');
                
                const checkValid = () => {
                    let valid = true;
                    reqInputs.forEach(i => { if(!i.value.trim() || !i.checkValidity()) valid = false; });
                    submitBtn.disabled = !valid;
                    submitBtn.style.opacity = valid ? '1' : '0.5';
                    submitBtn.style.cursor = valid ? 'pointer' : 'not-allowed';
                };

                reqInputs.forEach(i => {
                    i.addEventListener('input', checkValid);
                    i.addEventListener('change', checkValid);
                });

                invForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const name = document.getElementById('invName').value.trim();
                    const email = document.getElementById('invEmail').value.trim();
                    const wa = document.getElementById('invWA').value.trim();
                    const pkg = document.getElementById('invPkg').value;
                    const amt = document.getElementById('invAmount').value;
                    const note = document.getElementById('invNote').value.trim();

                    const amtText = amt ? 'IDR ' + parseFloat(amt).toLocaleString('id-ID') : 'Not specified';
                    
                    const msg = `Hello Rida Farm! I am interested in your investment program.
Here are my details:

*Name:* ${name}
*Email:* ${email}
*WhatsApp No:* ${wa}
*Selected Package:* ${pkg}
*Planned Investment:* ${amtText}
*Notes:* ${note || '-'}

Please provide more information, thank you!`;

                    const target = invForm.getAttribute('data-wa-number') || '6281936663738';
                    window.open(`https://wa.me/${target}?text=${encodeURIComponent(msg)}`, '_blank');
                });
            }
        });
        </script>
        <?php
    }
}
