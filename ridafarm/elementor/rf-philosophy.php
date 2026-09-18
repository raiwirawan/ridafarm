<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Rida_Widget_Philosophy extends \Elementor\Widget_Base {
    public function get_name() { return 'rf_philosophy'; }
    public function get_title() { return __( 'Rida Philosophy', 'ridafarm' ); }
    public function get_icon() { return 'eicon-info-box'; }
    public function get_categories() { return [ 'general' ]; }

    protected function register_controls() {
        $this->start_controls_section('content_section', ['label' => __( 'Philosophy Content', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('subheading', ['label' => __( 'Subheading', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'OUR PHILOSOPHY']);
        $this->add_control('title', ['label' => __( 'Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Simple Values, A Healthier World']);
        
        $repeater = new \Elementor\Repeater();
        $repeater->add_control('icon_class', ['label' => __( 'FontAwesome Icon Class', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'fa-solid fa-leaf']);
        $repeater->add_control('card_title', ['label' => __( 'Card Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Animal Care']);
        $repeater->add_control('card_desc', ['label' => __( 'Card Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Happy and healthy goats are the heart of everything we do.']);
        
        $this->add_control('cards', [
            'label' => __( 'Cards', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['icon_class' => 'fa-solid fa-leaf', 'card_title' => 'Animal Care', 'card_desc' => 'Happy and healthy goats are the heart of everything we do.'],
                ['icon_class' => 'fa-solid fa-heart', 'card_title' => 'Freshness', 'card_desc' => 'From our farm to your table, we ensure natural, fresh, and high-quality dairy products.'],
                ['icon_class' => 'fa-solid fa-users', 'card_title' => 'From Farm to Family', 'card_desc' => 'Nutritious food that brings families closer, today and for generations to come.'],
            ],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <section class="philosophy section-padding" id="philosophy" aria-labelledby="philosophy-heading">
            <div class="container text-center">
                <p class="subheading justify-center animate-fade-up"><span class="line"></span> <span><?php echo esc_html($settings['subheading']); ?></span> <span class="line"></span></p>
                <h2 class="animate-text section-title" id="philosophy-heading"><span><?php echo esc_html($settings['title']); ?></span></h2>
                <div class="philosophy-slider-wrap">
                    <div class="philosophy-grid" id="philosophySlider">
                    <?php foreach ( $settings['cards'] as $card ) : ?>
                    <div class="feature-card animate-stagger">
                        <div class="feature-icon"><i class="<?php echo esc_attr($card['icon_class']); ?>" aria-hidden="true"></i></div>
                        <h3><?php echo esc_html($card['card_title']); ?></h3>
                        <p><span><?php echo wp_kses_post($card['card_desc']); ?></span></p>
                    </div>
                    <?php endforeach; ?>
                    </div>
                    <div class="philosophy-dots" id="philosophyDots"></div>
                </div>
            </div>
        </section>
        <?php
    }
}
