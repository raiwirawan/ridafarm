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
        
        $repeater = new \Elementor\Repeater();
        $repeater->add_control('image', ['label' => __( 'Image', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::MEDIA]);
        $repeater->add_control('date', ['label' => __( 'Date', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater->add_control('title', ['label' => __( 'Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater->add_control('excerpt', ['label' => __( 'Excerpt', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA]);
        $repeater->add_control('url', ['label' => __( 'URL', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::URL]);
        
        $this->add_control('cards', ['label' => __( 'News Cards', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater->get_controls(), 'default' => [
            ['date' => 'May 15, 2024', 'title' => 'Happy Goats, Healthier Lives', 'excerpt' => 'A closer look at how we care for our goats every day.', 'url' => ['url' => '#'], 'image' => ['url' => 'https://images.unsplash.com/photo-1499115421298-dc3b4fe66c58?auto=format&fit=crop&q=80&w=600']],
            ['date' => 'Apr 20, 2024', 'title' => 'People, Goats, and a Brighter Tomorrow', 'excerpt' => 'Meet the hands behind Rida Farm Bali.', 'url' => ['url' => '#'], 'image' => ['url' => 'https://images.unsplash.com/photo-1596426924463-983524bf0085?auto=format&fit=crop&q=80&w=600']],
            ['date' => 'Apr 15, 2024', 'title' => 'The Goodness of Goat Milk', 'excerpt' => 'Why goat milk is a natural choice for your family.', 'url' => ['url' => '#'], 'image' => ['url' => 'https://images.unsplash.com/photo-1523473827533-2a64d0d36748?auto=format&fit=crop&q=80&w=600']],
            ['date' => 'Mar 10, 2024', 'title' => 'From Farm to Table', 'excerpt' => 'How our products reach you fresh everyday.', 'url' => ['url' => '#'], 'image' => ['url' => 'https://images.unsplash.com/photo-1517448931760-9bf4414148c5?auto=format&fit=crop&q=80&w=600']],
        ]]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
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
                    <?php foreach($settings['cards'] as $card): ?>
                    <a href="<?php echo esc_url($card['url']['url']); ?>" class="news-card animate-stagger">
                        <div class="news-img-wrap">
                            <img src="<?php echo esc_url($card['image']['url']); ?>" alt="News Image" loading="lazy" />
                        </div>
                        <div class="news-content">
                            <span class="news-date"><?php echo esc_html($card['date']); ?></span>
                            <h3 class="news-title"><span><?php echo esc_html($card['title']); ?></span></h3>
                            <p class="news-excerpt"><span><?php echo wp_kses_post($card['excerpt']); ?></span></p>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}
