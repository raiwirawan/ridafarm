<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Rida_Widget_InvestorBanner extends \Elementor\Widget_Base {
    public function get_name() { return 'rf_investor_banner'; }
    public function get_title() { return __( 'Rida Investor Banner', 'ridafarm' ); }
    public function get_icon() { return 'eicon-banner'; }
    public function get_categories() { return [ 'general' ]; }

    protected function register_controls() {
        $this->start_controls_section('content_section', ['label' => __( 'Banner Content', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('title', ['label' => __( 'Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Welcoming Investors']);
        $this->add_control('desc', ['label' => __( 'Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Invest now and grow with ridafarmbali — get more value later.']);
        $this->add_control('btn_label', ['label' => __( 'Button Label', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Invest Now']);
        $this->add_control('btn_url', ['label' => __( 'Button URL', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => ['url' => '#']]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <aside class="investor-banner" role="complementary">
            <div class="container banner-inner">
                <div class="banner-left">
                    <i class="fa-solid fa-chart-line text-green" aria-hidden="true"></i>
                    <span class="banner-title"><?php echo esc_html($settings['title']); ?></span>
                </div>
                <div class="banner-center">
                    <span><?php echo esc_html($settings['desc']); ?></span>
                </div>
                <div class="banner-right">
                    <a href="<?php echo esc_url($settings['btn_url']['url']); ?>" class="btn btn-primary btn-sm magnetic">
                        <i class="fa-solid fa-chart-line" aria-hidden="true"></i> <span><?php echo esc_html($settings['btn_label']); ?></span>
                        <i class="fa-solid fa-arrow-right icon-right" aria-hidden="true"></i>
                    </a>
                    <button class="banner-close" aria-label="Close banner">
                        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </aside>
        <?php
    }
}
