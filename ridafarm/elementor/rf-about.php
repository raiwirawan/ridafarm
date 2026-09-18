<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Rida_Widget_About extends \Elementor\Widget_Base {
    public function get_name() { return 'rf_about'; }
    public function get_title() { return __( 'Rida About', 'ridafarm' ); }
    public function get_icon() { return 'eicon-image-box'; }
    public function get_categories() { return [ 'general' ]; }

    protected function register_controls() {
        $this->start_controls_section('content_section', ['label' => __( 'About Content', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('subheading', ['label' => __( 'Subheading', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'OUR STORY']);
        $this->add_control('title', ['label' => __( 'Title (H2)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'About Us']);
        $this->add_control('h3_text', ['label' => __( 'Subtitle (H3)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'More Than a Farm,<br />A Healthier Tomorrow']);
        $this->add_control('desc', ['label' => __( 'Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Rida Farm Bali is built on a simple belief: healthy goats create wholesome food for healthier families...']);
        $this->add_control('btn_label', ['label' => __( 'Button Label', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Our Story']);
        $this->add_control('btn_url', ['label' => __( 'Button URL', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => ['url' => '#']]);
        $this->add_control('quote', ['label' => __( 'Quote Text', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => '"Good food brings people closer to a brighter tomorrow."']);
        $this->add_control('image', ['label' => __( 'Main Image', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::MEDIA, 'default' => ['url' => 'https://images.unsplash.com/photo-1580271920379-1436b1715545?auto=format&fit=crop&q=80&w=800']]);
        $this->add_control('handwritten', ['label' => __( 'Handwritten Text', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Care<br />Nurture<br />Better Lives']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <section class="about section-padding bg-white" id="about" aria-labelledby="about-heading">
            <div class="container">
                <div class="about-grid">
                    <div class="about-content">
                        <p class="subheading animate-fade-up"><span class="line"></span> <?php echo esc_html($settings['subheading']); ?></p>
                        <h2 class="animate-text" id="about-heading"><?php echo esc_html($settings['title']); ?></h2>
                        <h3 class="animate-text"><?php echo wp_kses_post($settings['h3_text']); ?></h3>
                        <p class="animate-fade-up"><?php echo wp_kses_post($settings['desc']); ?></p>
                        <div class="animate-fade-up mt-4">
                            <a href="<?php echo esc_url($settings['btn_url']['url']); ?>" class="btn btn-primary magnetic">
                                <?php echo esc_html($settings['btn_label']); ?> <i class="fa-solid fa-arrow-right icon-right" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="quote handwritten animate-fade-up"><?php echo wp_kses_post($settings['quote']); ?></div>
                    </div>
                    <div class="about-visual">
                        <div class="about-img-main reveal-wrap">
                            <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="About" class="parallax-img" data-speed="0.05" loading="lazy" />
                            <p class="handwritten about-handwritten animate-fade-up" aria-hidden="true">
                                <?php echo wp_kses_post($settings['handwritten']); ?>
                                <i class="fa-regular fa-heart" aria-hidden="true"></i>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}
