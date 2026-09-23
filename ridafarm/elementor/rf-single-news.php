<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Rida_Widget_Single_News extends \Elementor\Widget_Base {
    public function get_name() { return 'rf_single_news'; }
    public function get_title() { return __( 'Rida Single News Page', 'ridafarm' ); }
    public function get_icon() { return 'eicon-single-post'; }
    public function get_categories() { return [ 'general' ]; }

    protected function register_controls() {
        $this->start_controls_section('content_section', ['label' => __( 'Article Content', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        
        $this->add_control('title', [
            'label' => __( 'Article Title', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => get_the_title(),
            'description' => 'Leave blank to use the current WordPress post title.'
        ]);
        
        $this->add_control('date', [
            'label' => __( 'Date', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => get_the_date(),
        ]);
        
        $this->add_control('image', [
            'label' => __( 'Featured Image', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'description' => 'If left empty, it will try to use the Post Featured Image.'
        ]);
        
        $this->add_control('content', [
            'label' => __( 'Article Description / Content', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::WYSIWYG,
            'default' => __( 'Write your news or story content here...', 'ridafarm' ),
        ]);
        
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $title = !empty($settings['title']) ? $settings['title'] : get_the_title();
        $date = !empty($settings['date']) ? $settings['date'] : get_the_date();
        
        $image_url = '';
        if (!empty($settings['image']['url'])) {
            $image_url = $settings['image']['url'];
        } elseif (has_post_thumbnail()) {
            $image_url = get_the_post_thumbnail_url(null, 'full');
        } else {
            $image_url = get_template_directory_uri() . '/assets/ridafarm-logo.jpg';
        }
        ?>
        <article class="single-news-article section-padding">
            <div class="container">
                <header class="single-news-header animate-fade-up">
                    <span class="news-date"><?php echo esc_html($date); ?></span>
                    <h1 class="single-news-title"><?php echo wp_kses_post($title); ?></h1>
                </header>
                
                <div class="single-news-image-wrap animate-fade-up" style="animation-delay: 0.2s;">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(strip_tags($title)); ?>" class="single-news-img" />
                </div>
                
                <div class="single-news-content animate-fade-up" style="animation-delay: 0.4s;">
                    <?php echo wp_kses_post($settings['content']); ?>
                </div>
            </div>
        </article>
        <?php
    }
}
