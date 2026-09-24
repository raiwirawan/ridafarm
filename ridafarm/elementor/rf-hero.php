<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Rida_Widget_Hero extends \Elementor\Widget_Base {
    public function get_name() { return 'rf_hero'; }
    public function get_title() { return __( 'Rida Hero', 'ridafarm' ); }
    public function get_icon() { return 'eicon-image-rollover'; }
    public function get_categories() { return [ 'general' ]; }

    protected function register_controls() {
        $this->start_controls_section('content_section', ['label' => __( 'Hero Content', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('bg_image', ['label' => __( 'Background Image', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::MEDIA, 'default' => ['url' => 'https://images.unsplash.com/photo-1524024973431-2ad916746881?auto=format&fit=crop&q=80&w=1920']]);
        $this->add_control('title', ['label' => __( 'Title (H1)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Pure Goodness<br />from Our Farm<br />to Your Family']);
        $this->add_control('desc', ['label' => __( 'Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Fresh goat milk and yogurt from Rida Farm <br />Natural nutrition, healthier tomorrow.']);
        $this->add_control('btn1_label', ['label' => __( 'Button 1 Label', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Book a Farm Tour']);
        $this->add_control('btn1_url', ['label' => __( 'Button 1 URL', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => ['url' => '#']]);
        $this->add_control('btn2_label', ['label' => __( 'Button 2 Label', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Explore Products']);
        $this->add_control('btn2_url', ['label' => __( 'Button 2 URL', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => ['url' => '#']]);
        $this->add_control('play_url', ['label' => __( 'Play Button URL', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => ['url' => '#']]);
        $this->add_control('play_text', ['label' => __( 'Play Button Text', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Watch<br />Farm Tour']);
        $this->add_control('handwritten', ['label' => __( 'Handwritten Text', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Healthy Goats<br />Happier Families']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $bg_url = !empty($settings['bg_image']['url']) ? $settings['bg_image']['url'] : '';
        ?>
        <section class="hero" id="home" aria-labelledby="home-heading">
            <div class="hero-bg-wrap">
                <img src="<?php echo esc_url($bg_url); ?>" alt="Hero Background" class="hero-bg-img" fetchpriority="high" />
                <div class="hero-overlay"></div>
            </div>
            <div class="hero-content">
                <h1 class="hero-title" id="home-heading"><?php echo wp_kses_post($settings['title']); ?></h1>
                <p class="hero-desc"><?php echo wp_kses_post($settings['desc']); ?></p>
                <div class="hero-buttons">
                    <a href="<?php echo esc_url($settings['btn1_url']['url']); ?>" class="btn btn-primary magnetic">
                        <i class="fa-regular fa-calendar" aria-hidden="true"></i> <?php echo esc_html($settings['btn1_label']); ?>
                        <i class="fa-solid fa-arrow-right icon-right" aria-hidden="true"></i>
                    </a>
                    <a href="<?php echo esc_url($settings['btn2_url']['url']); ?>" class="btn btn-outline-white magnetic">
                        <span><?php echo esc_html($settings['btn2_label']); ?></span>
                        <i class="fa-solid fa-arrow-right icon-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <div class="hero-play">
                <a href="<?php echo esc_url($settings['play_url']['url']); ?>" class="play-btn magnetic">
                    <i class="fa-solid fa-play" aria-hidden="true"></i>
                </a>
                <span class="play-text"><?php echo wp_kses_post($settings['play_text']); ?></span>
            </div>
            <p class="hero-handwritten handwritten"><?php echo wp_kses_post($settings['handwritten']); ?></p>
        </section>
        <?php
    }
}
