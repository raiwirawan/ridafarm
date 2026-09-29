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
            'default' => 9,
        ]);

        $categories = get_categories(['hide_empty' => false]);
        $cat_options = ['all' => 'All Categories'];
        foreach($categories as $cat) {
            $cat_options[$cat->term_id] = $cat->name;
        }

        $repeater = new \Elementor\Repeater();
        $repeater->add_control('tab_label', [
            'label' => __( 'Tab Label', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Semua Berita'
        ]);
        $repeater->add_control('tab_category', [
            'label' => __( 'Select Category', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $cat_options,
            'default' => 'all'
        ]);

        $this->add_control('news_tabs', [
            'label' => __( 'Category Tabs', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['tab_label' => 'All News', 'tab_category' => 'all'],
            ],
            'title_field' => '{{{ tab_label }}}',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Collect category IDs from tabs to fetch only those posts
        $cat_ids = [];
        $has_all = false;
        if (!empty($settings['news_tabs'])) {
            foreach ($settings['news_tabs'] as $tab) {
                if ($tab['tab_category'] === 'all') {
                    $has_all = true;
                    break;
                }
                $cat_ids[] = $tab['tab_category'];
            }
        }

        $args = [
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $settings['posts_per_page'],
        ];
        if (!$has_all && !empty($cat_ids)) {
            $args['category__in'] = $cat_ids;
        }

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
                    <div class="news-header-right animate-fade-up">
                        <div role="group" aria-label="Slider navigation" class="news-nav-buttons">
                            <button class="btn btn-outline magnetic slider-btn prev-btn" id="newsPrev" disabled>
                                <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                            </button>
                            <button class="btn btn-outline magnetic slider-btn next-btn" id="newsNext">
                                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <?php if (!empty($settings['news_tabs'])) : ?>
                <div class="news-tabs-wrap animate-fade-up">
                    <div class="news-tabs">
                        <?php foreach ($settings['news_tabs'] as $index => $tab) : ?>
                            <button class="btn <?php echo $index === 0 ? 'btn-primary' : 'btn-outline'; ?> magnetic news-tab-btn" data-target-cat="<?php echo esc_attr($tab['tab_category']); ?>">
                                <?php echo esc_html($tab['tab_label']); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="news-slider-wrap mt-4">
                <div class="news-grid" id="newsSlider">
                    <?php 
                    if ($news_query->have_posts()) :
                        while ($news_query->have_posts()) : $news_query->the_post(); 
                        
                        // Get post categories
                        $post_categories = get_the_category();
                        $post_cat_ids = [];
                        if ($post_categories) {
                            foreach($post_categories as $c) {
                                $post_cat_ids[] = $c->term_id;
                            }
                        }
                        $cat_data_attr = implode(',', $post_cat_ids);
                    ?>
                    <a href="<?php the_permalink(); ?>" class="news-card animate-stagger" data-cat-ids="<?php echo esc_attr($cat_data_attr); ?>">
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
                        <p style="padding-left: 40px;" class="no-news-msg"><?php esc_html_e('No news found in this category.', 'ridafarm'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.news-tab-btn');
            const newsCards = document.querySelectorAll('.news-card');
            const noNewsMsg = document.querySelector('.no-news-msg');
            const slider = document.getElementById('newsSlider');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Update active class
                    tabBtns.forEach(b => {
                        b.classList.remove('btn-primary');
                        b.classList.add('btn-outline');
                    });
                    this.classList.remove('btn-outline');
                    this.classList.add('btn-primary');

                    const targetCat = this.getAttribute('data-target-cat');
                    let visibleCount = 0;

                    newsCards.forEach(card => {
                        if (targetCat === 'all') {
                            card.style.display = 'block';
                            visibleCount++;
                        } else {
                            const cats = card.getAttribute('data-cat-ids').split(',');
                            if (cats.includes(targetCat)) {
                                card.style.display = 'block';
                                visibleCount++;
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                    
                    if(slider) slider.scrollLeft = 0; // reset scroll
                    
                    // Dispatch custom event so main.js can update slider buttons logic
                    window.dispatchEvent(new Event('newsTabChanged'));
                });
            });
        });
        </script>
        <?php
    }
}
