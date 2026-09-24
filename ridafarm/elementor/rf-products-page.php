<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Rida_Widget_Products_Page extends \Elementor\Widget_Base {
    public function get_name() { return 'rf_products_page'; }
    public function get_title() { return __( 'Rida Products Page (Cards)', 'ridafarm' ); }
    public function get_icon() { return 'eicon-products'; }
    public function get_categories() { return [ 'general' ]; }

    protected function register_controls() {
        $this->start_controls_section('header_sec', ['label' => __( 'Header', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('subheading', ['label' => __( 'Subheading', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'OUR PRODUCTS']);
        $this->add_control('title', ['label' => __( 'Title (H2)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Featured Products']);
        $this->add_control('desc', ['label' => __( 'Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'We offer a selection of fresh and healthy products, directly from our farm to your table.']);
        $this->end_controls_section();

        $this->start_controls_section('products_sec', ['label' => __( 'Products', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $repeater = new \Elementor\Repeater();
        $repeater->add_control('image', ['label' => __( 'Image', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::MEDIA]);
        $repeater->add_control('category', ['label' => __( 'Category', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater->add_control('title', ['label' => __( 'Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater->add_control('desc', ['label' => __( 'Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA]);
        $repeater->add_control('price', ['label' => __( 'Price', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater->add_control('unit', ['label' => __( 'Unit (e.g. / 1 Liter)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater->add_control('btn_label', ['label' => __( 'Button Label', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Order Now']);
        $repeater->add_control('btn_url', ['label' => __( 'Button URL', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => ['url' => '#']]);
        
        $this->add_control('products_list', ['label' => __( 'Products List', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater->get_controls(), 'default' => [
            ['category' => 'DAIRY PRODUCT', 'title' => 'Fresh Milk', 'desc' => '100% natural cow\'s milk, rich in nutrients and free from preservatives. Perfect for your family\'s daily needs.', 'price' => 'Rp 15.000', 'unit' => '/ 1 Liter'],
            ['category' => 'DAIRY PRODUCT', 'title' => 'Yogurt', 'desc' => 'Creamy, delicious, and healthy yogurt made from fresh milk. Available in plain and fruit variants.', 'price' => 'Rp 12.000', 'unit' => '/ 200 ml'],
            ['category' => 'LIVESTOCK PRODUCT', 'title' => 'Fresh Beef', 'desc' => 'Premium quality beef from our own farm. Naturally raised, hormone-free, and full of flavor.', 'price' => 'Rp 110.000', 'unit' => '/ kg'],
        ]]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <section class="pp-section section-padding">
            <div class="container">
                <div class="pp-header text-left">
                    <p class="pp-eyebrow"><span class="line"></span> <span><?php echo esc_html($settings['subheading']); ?></span></p>
                    <h2 class="pp-title-main"><?php echo esc_html($settings['title']); ?></h2>
                    <p class="pp-desc-main"><?php echo wp_kses_post($settings['desc']); ?></p>
                </div>
            </div>
            
            <div class="pp-slider-wrapper mt-5">
                <div class="pp-slider" data-slider="">
                    <?php foreach($settings['products_list'] as $p): ?>
                        <div class="pp-card">
                            <div class="pp-img-wrap">
                                <?php if(!empty($p['image']['url'])): ?>
                                    <img src="<?php echo esc_url($p['image']['url']); ?>" alt="<?php echo esc_attr($p['title']); ?>" loading="lazy" />
                                <?php else: ?>
                                    <div class="pp-img-placeholder"></div>
                                <?php endif; ?>
                            </div>
                            <div class="pp-content">
                                <p class="pp-cat"><?php echo esc_html($p['category']); ?></p>
                                <h3 class="pp-title"><?php echo esc_html($p['title']); ?></h3>
                                <p class="pp-desc"><?php echo wp_kses_post($p['desc']); ?></p>
                                <div class="pp-price-wrap">
                                    <span class="pp-price"><?php echo esc_html($p['price']); ?></span>
                                    <?php if(!empty($p['unit'])): ?>
                                        <span class="pp-unit"><?php echo esc_html($p['unit']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if(!empty($p['btn_label'])): ?>
                                    <a href="<?php echo esc_url($p['btn_url']['url']); ?>" class="btn pp-btn">
                                        <?php echo esc_html($p['btn_label']); ?> <i class="fa-solid fa-arrow-right icon-right" aria-hidden="true"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}
