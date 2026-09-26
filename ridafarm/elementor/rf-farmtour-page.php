<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Rida_Widget_FarmTour_Page extends \Elementor\Widget_Base {
    public function get_name() { return 'rf_farmtour_page'; }
    public function get_title() { return __( 'Rida Farm Tour Page', 'ridafarm' ); }
    public function get_icon() { return 'eicon-map-pin'; }
    public function get_categories() { return [ 'general' ]; }

    protected function register_controls() {
        // 1. Hero Section
        $this->start_controls_section('hero_sec', ['label' => __( 'Hero Section', 'ridafarm' )]);
        $this->add_control('hero_image', ['label' => __( 'Background Image', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::MEDIA]);
        $this->add_control('hero_eyebrow', ['label' => __( 'Eyebrow', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'FARM TOUR']);
        $this->add_control('hero_title', ['label' => __( 'Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'A Return To Roots']);
        $this->end_controls_section();

        // 2. Intro Section
        $this->start_controls_section('intro_sec', ['label' => __( 'Intro Section', 'ridafarm' )]);
        $this->add_control('intro_image', ['label' => __( 'Image', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::MEDIA]);
        $this->add_control('intro_title', ['label' => __( 'Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'A HALF-DAY EXPLORATION OF GOAT FARMING']);
        $this->add_control('intro_desc', ['label' => __( 'Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::WYSIWYG, 'default' => '<p>Immerse yourself in authentic farm life and learn the roots of our all-natural goat dairy farm at Rida Farm, Bali.</p><p>Surrounded by the beauty of nature, discover the story behind our fresh milk and yogurt. Experience the daily routine of caring for our goats, feeding them, and witnessing the milking process — a way of life that is meaningful and deeply connected to nature.</p>']);
        $this->add_control('intro_btn', ['label' => __( 'Button Text', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'BOOK TOUR']);
        $this->add_control('intro_link', ['label' => __( 'Button Link', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => ['url' => '#booking-form']]);
        $this->end_controls_section();

        // 3. Gallery Section
        $this->start_controls_section('gallery_sec', ['label' => __( 'Gallery Section', 'ridafarm' )]);
        $repeater_gal = new \Elementor\Repeater();
        $repeater_gal->add_control('image', ['label' => __( 'Image', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::MEDIA]);
        $this->add_control('gallery', ['label' => __( 'Gallery Images', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater_gal->get_controls()]);
        $this->end_controls_section();

        // 4. Info/Tabs Section
        $this->start_controls_section('info_sec', ['label' => __( 'Info & Tabs', 'ridafarm' )]);
        $repeater_tabs = new \Elementor\Repeater();
        $repeater_tabs->add_control('tab_title', ['label' => __( 'Tab Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater_tabs->add_control('tab_content', ['label' => __( 'Tab Content', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::WYSIWYG]);
        $this->add_control('tabs', ['label' => __( 'Tabs', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater_tabs->get_controls(), 'default' => [
            ['tab_title' => 'TOUR PURCHASE', 'tab_content' => '<h4 style="text-align:center;font-family:var(--font-heading);color:var(--primary);margin-bottom:10px;">ADULTS: RP 150.000</h4><h4 style="text-align:center;font-family:var(--font-heading);color:var(--primary);">CHILDREN UNDER AGE 12: RP 75.000</h4><p style="text-align:center;font-size:14px;margin-top:10px;">Includes fresh milk tasting and farm activities.</p>'],
            ['tab_title' => 'OVERVIEW', 'tab_content' => '<p style="text-align:center;">Enjoy a guided tour around our beautiful goat farm. Learn about sustainable farming practices and interact with our friendly goats.</p>'],
            ['tab_title' => 'ITINERARY', 'tab_content' => '<p style="text-align:center;">09:00 AM - Welcome Drink<br>09:30 AM - Farm Tour & Goat Feeding<br>10:30 AM - Milking Demonstration<br>11:00 AM - Yogurt & Milk Tasting</p>'],
        ]]);
        $this->end_controls_section();
        
        // 5. Booking Form Section
        $this->start_controls_section('form_sec', ['label' => __( 'Booking Form', 'ridafarm' )]);
        $this->add_control('form_title', ['label' => __( 'Form Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'BOOK A TOUR']);
        $this->add_control('form_info', ['label' => __( 'Price Info Block', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::WYSIWYG, 'default' => '<ul><li><strong>Morning Session:</strong> 09:00 AM - 12:00 PM</li><li><strong>Afternoon Session:</strong> 02:00 PM - 05:00 PM</li></ul>']);
        $this->add_control('form_btn', ['label' => __( 'Submit Button Text', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'CHECK AVAILABILITY']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="ft-wrapper">
            <!-- Hero -->
            <section class="ft-hero" style="background-image: url('<?php echo esc_url($settings['hero_image']['url'] ?? ''); ?>');">
                <div class="ft-hero-overlay"></div>
                <div class="ft-hero-content">
                    <p class="ft-hero-eyebrow"><?php echo esc_html($settings['hero_eyebrow']); ?></p>
                    <h1 class="ft-hero-title"><?php echo esc_html($settings['hero_title']); ?></h1>
                </div>
            </section>

            <!-- Intro -->
            <section class="ft-intro container">
                <div class="ft-intro-grid">
                    <div class="ft-intro-img">
                        <?php if(!empty($settings['intro_image']['url'])): ?>
                            <img src="<?php echo esc_url($settings['intro_image']['url']); ?>" alt="Intro" loading="lazy">
                        <?php else: ?>
                            <div class="ft-placeholder"></div>
                        <?php endif; ?>
                    </div>
                    <div class="ft-intro-text-box">
                        <div class="ft-intro-inner">
                            <h2 class="ft-intro-title"><?php echo esc_html($settings['intro_title']); ?></h2>
                            <div class="ft-intro-desc"><?php echo wp_kses_post($settings['intro_desc']); ?></div>
                            <?php if(!empty($settings['intro_btn'])): ?>
                                <a href="<?php echo esc_url($settings['intro_link']['url']); ?>" class="btn pp-btn ft-btn"><?php echo esc_html($settings['intro_btn']); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Gallery -->
            <?php if(!empty($settings['gallery'])): ?>
            <section class="ft-gallery container">
                <div class="ft-gallery-grid">
                    <?php foreach($settings['gallery'] as $img): ?>
                        <div class="ft-gallery-item">
                            <img src="<?php echo esc_url($img['image']['url']); ?>" alt="Gallery Image" loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Info Tabs -->
            <?php if(!empty($settings['tabs'])): ?>
            <section class="ft-info container">
                <div class="ft-info-box">
                    <div class="ft-tabs-nav">
                        <?php foreach($settings['tabs'] as $i => $tab): ?>
                            <button class="ft-tab-btn <?php echo $i===0?'active':''; ?>" data-target="ft-tab-<?php echo $i; ?>"><?php echo esc_html($tab['tab_title']); ?></button>
                        <?php endforeach; ?>
                    </div>
                    <div class="ft-tabs-content">
                        <?php foreach($settings['tabs'] as $i => $tab): ?>
                            <div class="ft-tab-pane <?php echo $i===0?'active':''; ?>" id="ft-tab-<?php echo $i; ?>">
                                <?php echo wp_kses_post($tab['tab_content']); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            <?php endif; ?>

            <!-- Booking Form -->
            <section class="ft-booking container" id="booking-form">
                <div class="ft-form-box">
                    <h3 class="ft-form-title"><?php echo esc_html($settings['form_title']); ?></h3>
                    <form class="ft-form" onsubmit="event.preventDefault();">
                        <div class="ft-form-group">
                            <input type="text" placeholder="Name *" required>
                        </div>
                        <div class="ft-form-group">
                            <input type="email" placeholder="Email *" required>
                        </div>
                        <div class="ft-form-group">
                            <input type="text" placeholder="WhatsApp *" required>
                        </div>
                        <div class="ft-form-group">
                            <select required>
                                <option value="" disabled selected>Tour requested *</option>
                                <option value="morning">Morning Session (09:00 AM)</option>
                                <option value="afternoon">Afternoon Session (02:00 PM)</option>
                            </select>
                        </div>
                        
                        <div class="ft-form-info-block">
                            <div class="ft-fib-left">Price Information</div>
                            <div class="ft-fib-right">
                                <?php echo wp_kses_post($settings['form_info']); ?>
                            </div>
                        </div>

                        <div class="ft-form-row">
                            <div class="ft-form-group">
                                <input type="number" placeholder="Number of Adults *" min="1" required>
                            </div>
                            <div class="ft-form-group">
                                <input type="number" placeholder="Children" min="0">
                            </div>
                            <div class="ft-form-group ft-btn-group">
                                <button type="submit" class="btn pp-btn ft-btn w-100 justify-center"><?php echo esc_html($settings['form_btn']); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.ft-tab-btn');
            const tabPanes = document.querySelectorAll('.ft-tab-pane');
            
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active from all
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabPanes.forEach(p => p.classList.remove('active'));
                    
                    // Add active to clicked
                    this.classList.add('active');
                    const targetId = this.getAttribute('data-target');
                    document.getElementById(targetId).classList.add('active');
                });
            });
        });
        </script>
        <?php
    }
}
