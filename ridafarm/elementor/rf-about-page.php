<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Rida_Widget_About_Page extends \Elementor\Widget_Base {
    public function get_name() { return 'rf_about_page'; }
    public function get_title() { return __( 'Rida About Page (Full)', 'ridafarm' ); }
    public function get_icon() { return 'eicon-text-area'; }
    public function get_categories() { return [ 'general' ]; }

    protected function register_controls() {
        // Hero Section
        $this->start_controls_section('hero_section', ['label' => __( 'Hero Section', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('hero_subheading', ['label' => __( 'Subheading', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'OUR STORY']);
        $this->add_control('hero_title', ['label' => __( 'Title (H1)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Rooted in Nature, Grown with Love']);
        $this->add_control('hero_desc', ['label' => __( 'Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Discover the journey of Rida Farm Bali, where our passion for healthy living meets traditional, sustainable farming practices.']);
        $this->add_control('hero_image', ['label' => __( 'Hero Image', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::MEDIA, 'default' => ['url' => 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?auto=format&fit=crop&q=80&w=1200']]);
        $this->end_controls_section();

        // Mission & Vision
        $this->start_controls_section('mission_section', ['label' => __( 'Mission & Vision', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('mission_title', ['label' => __( 'Mission Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Our Mission']);
        $this->add_control('mission_desc', ['label' => __( 'Mission Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'To provide families with the purest, most nutritious goat milk products while maintaining the highest standards of animal welfare and environmental sustainability.']);
        $this->add_control('mission_quote', ['label' => __( 'Mission Quote (Handwritten)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => '"Pure goodness from our farm to your family."']);
        $this->add_control('vision_title', ['label' => __( 'Vision Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Our Vision']);
        $this->add_control('vision_desc', ['label' => __( 'Vision Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'We envision a future where natural, farm-fresh nutrition is accessible to everyone, inspiring healthier lifestyles and a deeper connection to nature across Bali and beyond.']);
        $this->add_control('mission_image', ['label' => __( 'Side Image (Optional)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::MEDIA, 'default' => ['url' => '']]);
        $this->end_controls_section();

        // Core Values
        $this->start_controls_section('values_section', ['label' => __( 'Core Values', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('values_subheading', ['label' => __( 'Subheading', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'OUR PILLARS']);
        $this->add_control('values_title', ['label' => __( 'Title (H2)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'What We Stand For']);
        
        $repeater = new \Elementor\Repeater();
        $repeater->add_control('icon', ['label' => __( 'Icon', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::ICONS, 'default' => ['value' => 'fa-solid fa-leaf', 'library' => 'solid']]);
        $repeater->add_control('title', ['label' => __( 'Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater->add_control('desc', ['label' => __( 'Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA]);
        
        $this->add_control('values_list', ['label' => __( 'Values', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater->get_controls(), 'default' => [
            ['title' => 'Sustainability', 'desc' => 'We farm in harmony with nature, ensuring our land remains fertile for generations.', 'icon' => ['value' => 'fa-solid fa-seedling', 'library' => 'solid']],
            ['title' => 'Animal Welfare', 'desc' => 'Happy goats give the best milk. They roam free and eat the freshest grass.', 'icon' => ['value' => 'fa-solid fa-heart', 'library' => 'solid']],
            ['title' => 'Purity', 'desc' => 'No additives, no preservatives. Just 100% pure, natural goodness.', 'icon' => ['value' => 'fa-solid fa-droplet', 'library' => 'solid']],
        ]]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="about-page-wrapper">
            <!-- Hero Section -->
            <section class="ap-hero section-padding">
                <div class="container text-center">
                    <p class="subheading animate-fade-up"><span class="line"></span> <span><?php echo esc_html($settings['hero_subheading']); ?></span> <span class="line"></span></p>
                    <h1 class="ap-hero-title animate-text"><?php echo wp_kses_post($settings['hero_title']); ?></h1>
                    <p class="ap-hero-desc animate-fade-up"><?php echo wp_kses_post($settings['hero_desc']); ?></p>
                </div>
                <div class="container animate-fade-up">
                    <div class="ap-hero-img-wrap reveal-wrap mt-5">
                        <img src="<?php echo esc_url($settings['hero_image']['url']); ?>" alt="About Rida Farm" class="parallax-img" loading="lazy" />
                    </div>
                </div>
            </section>

            <!-- Mission & Vision Section -->
            <section class="ap-mission section-padding bg-white">
                <div class="container">
                    <div class="ap-mission-grid">
                        <div class="ap-mission-content animate-fade-up">
                            <h2 class="ap-heading"><?php echo esc_html($settings['mission_title']); ?></h2>
                            <p class="ap-desc"><?php echo wp_kses_post($settings['mission_desc']); ?></p>
                            <div class="quote handwritten ap-quote"><?php echo wp_kses_post($settings['mission_quote']); ?></div>
                        </div>
                        <div class="ap-vision-content animate-fade-up">
                            <?php if(!empty($settings['mission_image']['url'])) : ?>
                                <img src="<?php echo esc_url($settings['mission_image']['url']); ?>" alt="Mission Image" class="ap-mission-img" />
                            <?php else : ?>
                                <h3 class="ap-heading-sm"><?php echo esc_html($settings['vision_title']); ?></h3>
                                <p class="ap-desc m-0"><?php echo wp_kses_post($settings['vision_desc']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Core Values Section -->
            <section class="ap-values section-padding">
                <div class="container text-center">
                    <p class="subheading animate-fade-up"><span class="line"></span> <span><?php echo esc_html($settings['values_subheading']); ?></span> <span class="line"></span></p>
                    <h2 class="ap-heading animate-text mb-5"><?php echo esc_html($settings['values_title']); ?></h2>
                    
                    <div class="ap-values-grid">
                        <?php foreach ($settings['values_list'] as $value) : ?>
                        <div class="ap-value-card animate-fade-up">
                            <div class="ap-value-icon">
                                <?php \Elementor\Icons_Manager::render_icon($value['icon'], [ 'aria-hidden' => 'true' ]); ?>
                            </div>
                            <h3 class="ap-heading-sm text-center"><?php echo esc_html($value['title']); ?></h3>
                            <p class="ap-desc text-center m-0"><?php echo wp_kses_post($value['desc']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

        </div>
        <?php
    }
}
