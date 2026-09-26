<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

// 1. Enqueue Assets
function ridafarm_enqueue_assets() {
    wp_enqueue_style('ridafarm-fonts', 'https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap');
    wp_enqueue_style('ridafarm-fa', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
    wp_enqueue_style('ridafarm-style', get_stylesheet_uri(), [], filemtime(get_stylesheet_directory() . '/style.css'));
    wp_enqueue_script('ridafarm-main', get_template_directory_uri() . '/main.js', [], filemtime(get_template_directory() . '/main.js'), true);
}
add_action('wp_enqueue_scripts', 'ridafarm_enqueue_assets');

// 2. Register Custom Elementor Widgets
function ridafarm_register_widgets($widgets_manager) {
    require_once get_template_directory() . '/elementor/rf-navbar.php';
    require_once get_template_directory() . '/elementor/rf-hero.php';
    require_once get_template_directory() . '/elementor/rf-about.php';
    require_once get_template_directory() . '/elementor/rf-philosophy.php';
    require_once get_template_directory() . '/elementor/rf-products.php';
    require_once get_template_directory() . '/elementor/rf-news.php';
    require_once get_template_directory() . '/elementor/rf-news-archive.php';
    require_once get_template_directory() . '/elementor/rf-single-news.php';
    require_once get_template_directory() . '/elementor/rf-about-page.php';
    require_once get_template_directory() . '/elementor/rf-products-page.php';
    require_once get_template_directory() . '/elementor/rf-farmtour-page.php';
    require_once get_template_directory() . '/elementor/rf-investor-banner.php';

    $widgets_manager->register(new \Rida_Widget_Navbar());
    $widgets_manager->register(new \Rida_Widget_Hero());
    $widgets_manager->register(new \Rida_Widget_About());
    $widgets_manager->register(new \Rida_Widget_Philosophy());
    $widgets_manager->register(new \Rida_Widget_Products());
    $widgets_manager->register(new \Rida_Widget_News());
    $widgets_manager->register(new \Rida_Widget_News_Archive());
    $widgets_manager->register(new \Rida_Widget_Single_News());
    $widgets_manager->register(new \Rida_Widget_About_Page());
    $widgets_manager->register(new \Rida_Widget_Products_Page());
    $widgets_manager->register(new \Rida_Widget_FarmTour_Page());
    $widgets_manager->register(new \Rida_Widget_InvestorBanner());
}
add_action('elementor/widgets/register', 'ridafarm_register_widgets');

// 3. Theme Supports
function ridafarm_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
}
add_action('after_setup_theme', 'ridafarm_theme_setup');

// 4. Customizer Settings
function ridafarm_customize_register($wp_customize) {
    $wp_customize->add_section('rf_floating_wa', [
        'title' => __('Floating WhatsApp', 'ridafarm'),
        'priority' => 100,
    ]);
    
    $wp_customize->add_setting('rf_wa_url', [
        'default' => 'https://wa.me/6281234567890',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('rf_wa_url', [
        'label' => __('WhatsApp Link URL', 'ridafarm'),
        'section' => 'rf_floating_wa',
        'type' => 'url',
    ]);

    $wp_customize->add_setting('rf_wa_image', [
        'default' => get_template_directory_uri() . '/assets/whatsapp-logo.png',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'rf_wa_image', [
        'label' => __('WhatsApp Icon Image', 'ridafarm'),
        'section' => 'rf_floating_wa',
    ]));
}
add_action('customize_register', 'ridafarm_customize_register');
