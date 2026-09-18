<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Rida_Widget_Products extends \Elementor\Widget_Base {
    public function get_name() { return 'rf_products'; }
    public function get_title() { return __( 'Rida Products', 'ridafarm' ); }
    public function get_icon() { return 'eicon-products'; }
    public function get_categories() { return [ 'general' ]; }

    protected function register_controls() {
        $this->start_controls_section('content_section', ['label' => __( 'Products Content', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('subheading', ['label' => __( 'Subheading', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'OUR PRODUCTS']);
        $this->add_control('title', ['label' => __( 'Title (H2)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Natural Nutrition<br />for a Better You']);
        $this->add_control('desc', ['label' => __( 'Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Delicious and nutritious goat milk and yogurt made with care at Rida Farm Bali. Pure, natural, and full of goodness for your family\'s daily health.']);
        $this->add_control('btn_label', ['label' => __( 'Button Label', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'View All Products']);
        $this->add_control('btn_url', ['label' => __( 'Button URL', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => ['url' => '#']]);
        
        $repeater = new \Elementor\Repeater();
        $repeater->add_control('icon_class', ['label' => __( 'Icon Class', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater->add_control('feature_label', ['label' => __( 'Feature Text', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $this->add_control('features', ['label' => __( 'Features', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater->get_controls(), 'default' => [
            ['icon_class' => 'fa-solid fa-leaf', 'feature_label' => '100% Natural'],
            ['icon_class' => 'fa-solid fa-shield-halved', 'feature_label' => 'Healthy Choice'],
            ['icon_class' => 'fa-solid fa-users', 'feature_label' => 'For the Whole Family'],
        ]]);

        $this->add_control('image', ['label' => __( 'Product Image', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::MEDIA, 'default' => ['url' => 'https://images.unsplash.com/photo-1517448931760-9bf4414148c5?auto=format&fit=crop&q=80&w=800']]);
        $this->add_control('handwritten', ['label' => __( 'Handwritten Text', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Rasa Alami<br />untuk Hari yang<br />Lebih Baik']);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <section class="products section-padding bg-white" id="products" aria-labelledby="products-heading">
            <div class="container">
                <div class="products-grid">
                    <div class="products-content">
                        <p class="subheading animate-fade-up"><span class="line"></span> <span><?php echo esc_html($settings['subheading']); ?></span></p>
                        <h2 class="animate-text" id="products-heading"><span><?php echo wp_kses_post($settings['title']); ?></span></h2>
                        <p class="animate-fade-up"><span><?php echo wp_kses_post($settings['desc']); ?></span></p>
                        <div class="animate-fade-up mt-4">
                            <a href="<?php echo esc_url($settings['btn_url']['url']); ?>" class="btn btn-primary magnetic">
                                <span><?php echo esc_html($settings['btn_label']); ?></span> <i class="fa-solid fa-arrow-right icon-right" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="product-features animate-fade-up">
                            <?php foreach($settings['features'] as $f): ?>
                            <div class="p-feature">
                                <div class="p-icon"><i class="<?php echo esc_attr($f['icon_class']); ?>" aria-hidden="true"></i></div>
                                <span><?php echo esc_html($f['feature_label']); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="products-visual">
                        <div class="products-img-wrap reveal-wrap">
                            <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="Products" class="parallax-img" loading="lazy" />
                            <p class="handwritten products-handwritten animate-scale" aria-hidden="true">
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
