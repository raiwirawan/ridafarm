<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Rida_Widget_News extends \Elementor\Widget_Base {
    public function get_name() { return 'rf_news'; }
    public function get_title() { return __( 'Rida News', 'ridafarm' ); }
    public function get_icon() { return 'eicon-post-slider'; }
    public function get_categories() { return [ 'general' ]; }

    protected function register_controls() {
        $this->start_controls_section('content_section', ['label' => __( 'News Content', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('subheading', ['label' => __( 'Subheading', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'NEWS & STORIES']);
        $this->add_control('title', ['label' => __( 'Title (H2)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Latest from the Farm']);
        $this->add_control('desc', ['label' => __( 'Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Stories, updates, and inspiration from our journey at Rida Farm Bali.']);
        
        $this->add_control('posts_per_page', [
            'label' => __( 'Number of Posts', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 6,
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $args = [
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $settings['posts_per_page'],
        ];
        $news_query = new \WP_Query($args);
        ?>
        <section class="news section-padding" id="news" aria-labelledby="news-heading">
            <div class="container">
                <div class="news-header">
                    <div class="news-header-left">
                        <p class="subheading animate-fade-up"><span class="line"></span> <span><?php echo esc_html($settings['subheading']); ?></span></p>
                        <h2 class="animate-text" id="news-heading"><?php echo esc_html($settings['title']); ?></h2>
                        <p class="animate-fade-up"><span><?php echo wp_kses_post($settings['desc']); ?></span></p>
                    </div>
                    <div class="news-header-right animate-fade-up" style="display: flex; gap: 10px;" role="group" aria-label="Slider navigation">
                        <button class="btn btn-outline magnetic slider-btn prev-btn" id="newsPrev" disabled>
                            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                        </button>
                        <button class="btn btn-outline magnetic slider-btn next-btn" id="newsNext">
                            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="news-slider-wrap">
                <div class="news-grid" id="newsSlider">
                    <?php 
                    if ($news_query->have_posts()) :
                        while ($news_query->have_posts()) : $news_query->the_post(); 
                    ?>
                    <a href="<?php the_permalink(); ?>" class="news-card animate-stagger">
                        <div class="news-img-wrap">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium_large', ['alt' => esc_attr(get_the_title()), 'loading' => 'lazy']); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/ridafarm-logo.jpg'); ?>" alt="News Image" loading="lazy" />
                            <?php endif; ?>
                        </div>
                        <div class="news-content">
                            <span class="news-date"><?php echo get_the_date(); ?></span>
                            <h3 class="news-title"><span><?php the_title(); ?></span></h3>
                            <p class="news-excerpt"><span><?php echo wp_trim_words(get_the_excerpt(), 15); ?></span></p>
                        </div>
                    </a>
                    <?php 
                        endwhile;
                        wp_reset_postdata();
                    else : 
                    ?>
                        <p style="padding-left: 20px;"><?php esc_html_e('No news found.', 'ridafarm'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
    }
}
