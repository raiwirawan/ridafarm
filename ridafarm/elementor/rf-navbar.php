<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Rida_Widget_Navbar extends \Elementor\Widget_Base {

    public function get_name() { return 'rf_navbar'; }
    public function get_title() { return __( 'Rida Navbar', 'ridafarm' ); }
    public function get_icon() { return 'eicon-header'; }
    public function get_categories() { return [ 'general' ]; }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'ridafarm' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'logo_image',
            [
                'label' => __( 'Logo Image', 'ridafarm' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => get_template_directory_uri() . '/assets/ridafarm-logo.jpg',
                ],
            ]
        );

        $this->add_control(
            'brand_name',
            [
                'label' => __( 'Brand Name', 'ridafarm' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Rida Farm Bali', 'ridafarm' ),
            ]
        );

        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'link_text',
            [
                'label' => __( 'Link Text', 'ridafarm' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Link', 'ridafarm' ),
            ]
        );
        $repeater->add_control(
            'link_url',
            [
                'label' => __( 'Link URL', 'ridafarm' ),
                'type' => \Elementor\Controls_Manager::URL,
                'default' => [ 'url' => '#' ],
            ]
        );
        $this->add_control(
            'nav_links',
            [
                'label' => __( 'Navigation Links', 'ridafarm' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'link_text' => 'Home', 'link_url' => [ 'url' => '#home' ] ],
                    [ 'link_text' => 'About Us', 'link_url' => [ 'url' => '#about' ] ],
                    [ 'link_text' => 'Our Products', 'link_url' => [ 'url' => '#products' ] ],
                    [ 'link_text' => 'News', 'link_url' => [ 'url' => '#news' ] ],
                    [ 'link_text' => 'Contact', 'link_url' => [ 'url' => '#' ] ],
                    [ 'link_text' => 'Farm Tour', 'link_url' => [ 'url' => '#' ] ],
                    [ 'link_text' => 'Investment', 'link_url' => [ 'url' => '#' ] ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $logo_url = !empty($settings['logo_image']['url']) ? $settings['logo_image']['url'] : '';
        ?>
        <nav class="navbar">
            <div class="nav-container">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="logo" aria-label="Rida Farm Bali — Homepage">
                    <?php if($logo_url): ?>
                        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($settings['brand_name']); ?>" class="logo-img">
                    <?php endif; ?>
                    <div class="logo-text">
                        <span class="brand-name"><?php echo esc_html($settings['brand_name']); ?></span>
                    </div>
                </a>
                <div class="nav-links">
                    <?php if ( $settings['nav_links'] ) : ?>
                        <?php foreach ( $settings['nav_links'] as $index => $item ) : 
                            $target = $item['link_url']['is_external'] ? ' target="_blank"' : '';
                            $nofollow = $item['link_url']['nofollow'] ? ' rel="nofollow"' : '';
                            // Make first item active by default for styling
                            $active_class = ($index === 0) ? ' active' : '';
                        ?>
                            <a href="<?php echo esc_url($item['link_url']['url']); ?>" class="nav-link<?php echo $active_class; ?>" <?php echo $target . $nofollow; ?>>
                                <?php echo esc_html($item['link_text']); ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <div class="lang-switcher">
                    <?php 
                    // Polylang Language Switcher Integration
                    if ( function_exists('pll_the_languages') ) {
                        $langs = pll_the_languages(array('raw' => 1));
                        if (!empty($langs)) {
                            $output = [];
                            foreach ($langs as $lang) {
                                if ($lang['current_lang']) {
                                    $output[] = '<span class="lang-btn active">' . esc_html(strtoupper($lang['slug'])) . '</span>';
                                } else {
                                    $output[] = '<a href="' . esc_url($lang['url']) . '" class="lang-btn" aria-label="Switch to ' . esc_attr($lang['name']) . '">' . esc_html(strtoupper($lang['slug'])) . '</a>';
                                }
                            }
                            echo implode('<span class="lang-sep">|</span>', $output);
                        } else {
                            echo '<span class="lang-btn active">EN</span><span class="lang-sep">|</span><a href="#" class="lang-btn">ID</a>';
                        }
                    } else {
                        // Fallback if Polylang is not installed
                        echo '<span class="lang-btn active">EN</span><span class="lang-sep">|</span><a href="#" class="lang-btn">ID</a>';
                    }
                    ?>
                </div>
                <div class="nav-actions">
                    <button class="menu-btn" aria-label="Toggle menu">
                        <span class="hamburger" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </nav>
        <?php
    }
}
