<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Rida_Widget_News_Archive extends \Elementor\Widget_Base {
    public function get_name() { return 'rf_news_archive'; }
    public function get_title() { return __( 'Rida News Archive', 'ridafarm' ); }
    public function get_icon() { return 'eicon-archive'; }
    public function get_categories() { return [ 'general' ]; }

    protected function register_controls() {
        $this->start_controls_section('content_section', ['label' => __( 'Archive Settings', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('show_categories', [
            'label' => __( 'Show Categories Filter', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        $this->add_control('posts_per_page', [
            'label' => __( 'Posts Per Page', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 9,
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
        
        $current_cat = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
        
        $args = [
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $settings['posts_per_page'],
            'paged' => $paged,
        ];
        if ($current_cat > 0) {
            $args['cat'] = $current_cat;
        }
        
        $news_query = new \WP_Query($args);
        ?>
        <div class="news-archive-widget">
            <?php if ($settings['show_categories'] === 'yes') : 
                $categories = get_categories(['hide_empty' => true]);
                if (!empty($categories)) :
            ?>
                <div class="news-categories-filter animate-fade-up">
                    <a href="<?php echo esc_url(remove_query_arg('cat')); ?>" class="btn <?php echo ($current_cat === 0) ? 'btn-primary' : 'btn-outline'; ?> magnetic">
                        <?php esc_html_e('All', 'ridafarm'); ?>
                    </a>
                    <?php foreach ($categories as $category) : ?>
                        <a href="<?php echo esc_url(add_query_arg('cat', $category->term_id)); ?>" class="btn <?php echo ($current_cat === $category->term_id) ? 'btn-primary' : 'btn-outline'; ?> magnetic">
                            <?php echo esc_html($category->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php 
                endif;
            endif; 
            ?>
            
            <div class="news-archive-grid">
                <?php 
                if ($news_query->have_posts()) :
                    while ($news_query->have_posts()) : $news_query->the_post(); 
                ?>
                <a href="<?php the_permalink(); ?>" class="news-card animate-fade-up">
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
                else : 
                ?>
                    <p style="padding-left: 20px;"><?php esc_html_e('No news found.', 'ridafarm'); ?></p>
                <?php endif; ?>
            </div>
            
            <?php if ($news_query->max_num_pages > 1) : ?>
                <div class="news-pagination animate-fade-up">
                    <?php
                    echo paginate_links([
                        'total' => $news_query->max_num_pages,
                        'current' => $paged,
                        'prev_text' => '<i class="fa-solid fa-chevron-left" aria-hidden="true"></i>',
                        'next_text' => '<i class="fa-solid fa-chevron-right" aria-hidden="true"></i>',
                    ]);
                    ?>
                </div>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        <?php
    }
}
