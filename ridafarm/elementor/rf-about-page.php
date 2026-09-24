<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Rida_Widget_About_Page extends \Elementor\Widget_Base {
    public function get_name() { return 'rf_about_page'; }
    public function get_title() { return __( 'Rida About Page (Full)', 'ridafarm' ); }
    public function get_icon() { return 'eicon-text-area'; }
    public function get_categories() { return [ 'general' ]; }

    protected function register_controls() {
        // 1. Founder Section
        $this->start_controls_section('founder_sec', ['label' => __( 'Our Founder', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('founder_image', ['label' => __( 'Founder Image', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::MEDIA]);
        $this->add_control('founder_script', ['label' => __( 'Image Script Text', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => "[Nama Pemilik]<br>Pendiri Rida Farm Bali"]);
        $this->add_control('founder_eyebrow', ['label' => __( 'Eyebrow', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Sang Pemilik']);
        $this->add_control('founder_title', ['label' => __( 'Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Halo, Saya [Nama Pemilik]']);
        $this->add_control('founder_desc', ['label' => __( 'Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::WYSIWYG, 'default' => '<p>Rida Farm Bali lahir dari kecintaan saya pada makanan yang jujur dan alami. Sejak [tahun], saya memulai peternakan ini di [lokasi], Bali, dengan beberapa ekor kambing dan satu tujuan: menghadirkan susu dan yoghurt kambing yang bermanfaat bagi keluarga.</p><p>Bagi saya, kambing yang bahagia dan sehat adalah awal dari semua produk yang baik. Karena itu setiap hari kami merawat mereka dengan penuh perhatian, dari pakan hingga kebersihan kandang.</p>']);
        $this->add_control('founder_quote', ['label' => __( 'Quote', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '“Makanan yang baik mendekatkan orang pada hari esok yang lebih cerah.”']);
        $this->end_controls_section();

        // 2. Journey Section
        $this->start_controls_section('journey_sec', ['label' => __( 'Our Journey', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('journey_eyebrow', ['label' => __( 'Eyebrow', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Perjalanan Kami']);
        $this->add_control('journey_title', ['label' => __( 'Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Dari Kandang Kecil ke Meja Keluarga']);
        
        $repeater_tl = new \Elementor\Repeater();
        $repeater_tl->add_control('year', ['label' => __( 'Year', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater_tl->add_control('text', ['label' => __( 'Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA]);
        $this->add_control('journey_list', ['label' => __( 'Timeline', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater_tl->get_controls(), 'default' => [
            ['year' => '[Tahun]', 'text' => 'Memulai peternakan dengan [jumlah] ekor kambing di [lokasi].'],
            ['year' => '[Tahun]', 'text' => 'Meluncurkan susu kambing segar dan yoghurt Rida Farm.'],
            ['year' => '[Tahun]', 'text' => 'Membuka Farm Tour agar keluarga bisa melihat langsung proses kami.'],
            ['year' => '2026', 'text' => 'Membuka peluang investasi untuk tumbuh bersama mitra.'],
        ]]);

        $repeater_st = new \Elementor\Repeater();
        $repeater_st->add_control('num', ['label' => __( 'Number', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater_st->add_control('label', ['label' => __( 'Label', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $this->add_control('stats_list', ['label' => __( 'Stats', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater_st->get_controls(), 'default' => [
            ['num' => '[00]+', 'label' => 'Ekor kambing'],
            ['num' => '100%', 'label' => 'Alami'],
            ['num' => '[00]+', 'label' => 'Keluarga terlayani'],
            ['num' => '[0]', 'label' => 'Tahun berdiri'],
        ]]);
        $this->end_controls_section();

        // 3. Business Section
        $this->start_controls_section('business_sec', ['label' => __( 'Our Business', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('business_eyebrow', ['label' => __( 'Eyebrow', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Usaha Kami']);
        $this->add_control('business_title', ['label' => __( 'Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Peternakan, Produk, dan Pengalaman']);
        $this->add_control('business_desc', ['label' => __( 'Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::WYSIWYG, 'default' => '<p>Rida Farm Bali adalah peternakan kambing yang menghasilkan susu kambing segar dan yoghurt alami. Selain produk, kami membuka pintu bagi keluarga lewat Farm Tour, dan bagi mitra yang ingin tumbuh bersama lewat program Investasi.</p>']);
        $this->add_control('business_btn_label', ['label' => __( 'Button Label', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Lihat Semua Produk']);
        $this->add_control('business_btn_url', ['label' => __( 'Button URL', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => ['url' => '#']]);
        $this->add_control('business_image', ['label' => __( 'Image', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::MEDIA]);
        $this->add_control('business_script', ['label' => __( 'Image Script Text', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => "Care<br>Nurture<br>Better Lives"]);
        $this->end_controls_section();

        // 4. Philosophy Section
        $this->start_controls_section('phil_sec', ['label' => __( 'Philosophy', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('phil_eyebrow', ['label' => __( 'Eyebrow', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Filosofi Kami']);
        $this->add_control('phil_title', ['label' => __( 'Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Nilai Sederhana, Dunia yang Lebih Sehat']);
        
        $repeater_ph = new \Elementor\Repeater();
        $repeater_ph->add_control('icon', ['label' => __( 'Icon/Emoji', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater_ph->add_control('title', ['label' => __( 'Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater_ph->add_control('text', ['label' => __( 'Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA]);
        $this->add_control('phil_list', ['label' => __( 'Cards', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater_ph->get_controls(), 'default' => [
            ['icon' => '🌿', 'title' => 'Perawatan Hewan', 'text' => 'Kambing yang sehat dan bahagia adalah jantung dari semua yang kami kerjakan.'],
            ['icon' => '♥', 'title' => 'Kesegaran', 'text' => 'Dari kandang ke meja Anda, kami menjaga produk tetap alami, segar, dan berkualitas.'],
            ['icon' => '👪', 'title' => 'Untuk Keluarga', 'text' => 'Nutrisi yang mendekatkan keluarga, hari ini dan untuk generasi mendatang.'],
        ]]);
        $this->end_controls_section();

        // 5. CTA Section
        $this->start_controls_section('cta_sec', ['label' => __( 'Call To Action', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
        $this->add_control('cta_eyebrow', ['label' => __( 'Eyebrow', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Mari Terhubung']);
        $this->add_control('cta_title', ['label' => __( 'Title', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Datang, Lihat, dan Rasakan Sendiri']);
        $this->add_control('cta_desc', ['label' => __( 'Description', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Kunjungi peternakan kami atau bergabung sebagai mitra investasi.']);
        $this->add_control('cta_btn1_label', ['label' => __( 'Button 1 Label', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Pesan Farm Tour']);
        $this->add_control('cta_btn1_url', ['label' => __( 'Button 1 URL', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => ['url' => '#']]);
        $this->add_control('cta_btn2_label', ['label' => __( 'Button 2 Label', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Investasi']);
        $this->add_control('cta_btn2_url', ['label' => __( 'Button 2 URL', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::URL, 'default' => ['url' => '#']]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="about-page-wrapper">
            
            <!-- Founder Section -->
            <section class="ap-new-section">
                <div class="ap-new-wrap">
                    <div class="ap-new-grid">
                        <div class="ap-new-ph">
                            <?php if(!empty($settings['founder_image']['url'])): ?>
                                <img src="<?php echo esc_url($settings['founder_image']['url']); ?>" alt="Founder" loading="lazy" />
                            <?php else: ?>
                                <div style="background:var(--primary);width:100%;height:100%;position:absolute;inset:0;"></div>
                            <?php endif; ?>
                            <span class="ap-new-script"><?php echo wp_kses_post($settings['founder_script']); ?></span>
                        </div>
                        <div>
                            <div class="ap-new-eyebrow"><?php echo esc_html($settings['founder_eyebrow']); ?></div>
                            <h2><?php echo esc_html($settings['founder_title']); ?></h2>
                            <?php echo wp_kses_post($settings['founder_desc']); ?>
                            <p class="ap-new-quote"><?php echo esc_html($settings['founder_quote']); ?></p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Journey Section -->
            <section class="ap-new-section ap-new-alt">
                <div class="ap-new-wrap">
                    <div class="ap-new-center">
                        <div class="ap-new-eyebrow"><?php echo esc_html($settings['journey_eyebrow']); ?></div>
                        <h2><?php echo esc_html($settings['journey_title']); ?></h2>
                    </div>
                    <div class="ap-new-tl" style="max-width:720px;margin-inline:auto">
                        <?php foreach($settings['journey_list'] as $j): ?>
                            <div><b><?php echo esc_html($j['year']); ?></b><br><span><?php echo esc_html($j['text']); ?></span></div>
                        <?php endforeach; ?>
                    </div>
                    <div class="ap-new-stats">
                        <?php foreach($settings['stats_list'] as $s): ?>
                            <div class="ap-new-stat"><b><?php echo esc_html($s['num']); ?></b><span><?php echo esc_html($s['label']); ?></span></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <!-- Business Section -->
            <section class="ap-new-section">
                <div class="ap-new-wrap">
                    <div class="ap-new-grid">
                        <div>
                            <div class="ap-new-eyebrow"><?php echo esc_html($settings['business_eyebrow']); ?></div>
                            <h2><?php echo esc_html($settings['business_title']); ?></h2>
                            <?php echo wp_kses_post($settings['business_desc']); ?>
                            <?php if(!empty($settings['business_btn_label'])): ?>
                                <a class="ap-new-btn" href="<?php echo esc_url($settings['business_btn_url']['url']); ?>"><?php echo esc_html($settings['business_btn_label']); ?></a>
                            <?php endif; ?>
                        </div>
                        <div class="ap-new-ph">
                            <?php if(!empty($settings['business_image']['url'])): ?>
                                <img src="<?php echo esc_url($settings['business_image']['url']); ?>" alt="Business" loading="lazy" />
                            <?php else: ?>
                                <div style="background:var(--primary);width:100%;height:100%;position:absolute;inset:0;"></div>
                            <?php endif; ?>
                            <span class="ap-new-script"><?php echo wp_kses_post($settings['business_script']); ?></span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Philosophy Section -->
            <section class="ap-new-section ap-new-alt">
                <div class="ap-new-wrap ap-new-center">
                    <div class="ap-new-eyebrow"><?php echo esc_html($settings['phil_eyebrow']); ?></div>
                    <h2><?php echo esc_html($settings['phil_title']); ?></h2>
                    <div class="ap-new-cards">
                        <?php foreach($settings['phil_list'] as $p): ?>
                            <div class="ap-new-card">
                                <div class="ap-new-ic"><?php echo esc_html($p['icon']); ?></div>
                                <h3><?php echo esc_html($p['title']); ?></h3>
                                <p><?php echo esc_html($p['text']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="ap-new-section">
                <div class="ap-new-wrap">
                    <div class="ap-new-cta ap-new-center">
                        <div class="ap-new-eyebrow" style="color:var(--accent)"><?php echo esc_html($settings['cta_eyebrow']); ?></div>
                        <h2><?php echo esc_html($settings['cta_title']); ?></h2>
                        <p><?php echo esc_html($settings['cta_desc']); ?></p>
                        <?php if(!empty($settings['cta_btn1_label'])): ?>
                            <a class="ap-new-btn" href="<?php echo esc_url($settings['cta_btn1_url']['url']); ?>"><?php echo esc_html($settings['cta_btn1_label']); ?></a>
                        <?php endif; ?>
                        <?php if(!empty($settings['cta_btn2_label'])): ?>
                            <a class="ap-new-btn o" href="<?php echo esc_url($settings['cta_btn2_url']['url']); ?>"><?php echo esc_html($settings['cta_btn2_label']); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

        </div>
        <?php
    }
}
