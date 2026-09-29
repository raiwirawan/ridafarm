<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Rida_Widget_News_Archive extends \Elementor\Widget_Base {
    public function get_name() { return 'rf_news_archive'; }
    public function get_title() { return __( 'Rida News Archive', 'ridafarm' ); }
    public function get_icon() { return 'eicon-archive'; }
    public function get_categories() { return [ 'general' ]; }

    protected function register_controls() {
        // Hero Section
        $this->start_controls_section('hero_section', ['label' => __( 'Hero Settings', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('show_hero', [
            'label' => __( 'Show Hero', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        $this->add_control('hero_image', [
            'label' => __( 'Background Image', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'condition' => ['show_hero' => 'yes'],
        ]);
        $this->add_control('hero_eyebrow', [
            'label' => __( 'Eyebrow', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'RIDA FARM INSIGHTS',
            'condition' => ['show_hero' => 'yes'],
        ]);
        $this->add_control('hero_title', [
            'label' => __( 'Title', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'News & Insights',
            'condition' => ['show_hero' => 'yes'],
        ]);
        $this->add_control('hero_subtitle', [
            'label' => __( 'Subtitle', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'default' => 'Stay updated with the latest trends in organic farming, goat milk products, and community events from Rida Farm Bali.',
            'condition' => ['show_hero' => 'yes'],
        ]);
        $this->end_controls_section();

        // Archive Settings
        $this->start_controls_section('content_section', ['label' => __( 'Archive Settings', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('posts_per_page', [
            'label' => __( 'Posts Per Page', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 9, 
        ]);
        $this->add_control('show_all_tab', [
            'label' => __( 'Show "All" Tab', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $categories = get_categories(['hide_empty' => false]);
        $cat_options = [];
        foreach($categories as $cat) {
            $cat_options[$cat->term_id] = $cat->name;
        }

        $repeater = new \Elementor\Repeater();
        $repeater->add_control('cat_label', [
            'label' => __( 'Tab Label (Optional)', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'description' => 'Leave empty to use original category name.',
        ]);
        $repeater->add_control('cat_id', [
            'label' => __( 'Select Category', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $cat_options,
        ]);

        $this->add_control('visible_categories', [
            'label' => __( 'Visible Category Tabs', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'title_field' => '{{{ cat_label || "Category ID: " + cat_id }}}',
            'description' => 'If empty, all categories will be shown.',
        ]);
        $this->end_controls_section();
    }

    private function get_reading_time($content) {
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200);
        return $reading_time;
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
        
        $grid_class = ($paged == 1 && $current_cat == 0) ? 'is-featured-grid' : 'is-standard-grid';
        ?>
        <div class="news-archive-widget">
            
            <?php if ($settings['show_hero'] === 'yes') : ?>
            <div class="news-hero" style="background-image: url('<?php echo esc_url($settings['hero_image']['url'] ?? ''); ?>');">
                <div class="news-hero-overlay"></div>
                <div class="news-hero-content container">
                    <p class="news-hero-eyebrow"><?php echo esc_html($settings['hero_eyebrow']); ?></p>
                    <h1 class="news-hero-title"><?php echo esc_html($settings['hero_title']); ?></h1>
                    <p class="news-hero-subtitle"><?php echo esc_html($settings['hero_subtitle']); ?></p>
                </div>
            </div>
            <?php endif; ?>

            <div class="container section-padding">
                <div class="news-categories-filter flex-start animate-fade-up">
                    <?php if ($settings['show_all_tab'] === 'yes') : ?>
                        <a href="<?php echo esc_url(remove_query_arg('cat')); ?>" class="btn <?php echo ($current_cat === 0) ? 'btn-primary' : 'btn-outline'; ?> magnetic">
                            <?php esc_html_e('All', 'ridafarm'); ?>
                        </a>
                    <?php endif; ?>

                    <?php 
                    if (!empty($settings['visible_categories'])) {
                        foreach ($settings['visible_categories'] as $item) {
                            $cat_id = (int)$item['cat_id'];
                            $cat_obj = get_category($cat_id);
                            if ($cat_obj) {
                                $label = !empty($item['cat_label']) ? $item['cat_label'] : $cat_obj->name;
                                ?>
                                <a href="<?php echo esc_url(add_query_arg('cat', $cat_id)); ?>" class="btn <?php echo ($current_cat === $cat_id) ? 'btn-primary' : 'btn-outline'; ?> magnetic">
                                    <?php echo esc_html($label); ?>
                                </a>
                                <?php
                            }
                        }
                    } else {
                        $categories = get_categories(['hide_empty' => true]);
                        foreach ($categories as $category) {
                            ?>
                            <a href="<?php echo esc_url(add_query_arg('cat', $category->term_id)); ?>" class="btn <?php echo ($current_cat === $category->term_id) ? 'btn-primary' : 'btn-outline'; ?> magnetic">
                                <?php echo esc_html($category->name); ?>
                            </a>
                            <?php
                        }
                    }
                    ?>
                </div>
                
                <div class="news-archive-grid <?php echo esc_attr($grid_class); ?>">
                    <?php 
                    if ($news_query->have_posts()) :
                        while ($news_query->have_posts()) : $news_query->the_post(); 
                        
                        $cats = get_the_category();
                        $cat_name = !empty($cats) ? $cats[0]->name : 'Uncategorized';
                        $read_time = $this->get_reading_time(get_the_content());
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
                            <span class="news-cat-badge"><?php echo esc_html($cat_name); ?></span>
                            <h3 class="news-title"><span><?php the_title(); ?></span></h3>
                            <p class="news-excerpt"><span><?php echo wp_trim_words(get_the_excerpt(), 18); ?></span></p>
                            <div class="news-meta">
                                <span class="news-date"><?php echo get_the_date(); ?></span>
                                <span class="news-read-time"><?php echo esc_html($read_time); ?> min read</span>
                            </div>
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
        </div>
        <?php
    }
}
