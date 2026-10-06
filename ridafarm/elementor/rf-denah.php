<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Rida_Widget_Denah extends \Elementor\Widget_Base {
    public function get_name() { return 'rf_denah'; }
    public function get_title() { return __( 'Rida Denah Interaktif', 'ridafarm' ); }
    public function get_icon() { return 'eicon-image-hotspot'; }
    public function get_categories() { return [ 'general' ]; }
    public function get_style_depends() { return [ 'rf-denah' ]; }
    public function get_script_depends() { return [ 'rf-denah' ]; }

    protected function register_controls() {
        // Section: Judul
        $this->start_controls_section('sec_title', ['label' => __( 'Judul', 'ridafarm' )]);
        $this->add_responsive_control('wrap_pad', [
            'label' => __( 'Padding Keseluruhan', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => ['{{WRAPPER}} .rfd-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;']
        ]);
        $this->add_control('show_title', ['label' => __( 'Tampilkan Judul', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes']);
        $this->add_control('title_text', ['label' => __( 'Teks Judul', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Prototipe Interaktif: Denah Ridafarm', 'condition' => ['show_title' => 'yes']]);
        $this->add_control('desc_text', ['label' => __( 'Teks Deskripsi', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Klik area bangunan untuk memindahkan karakter dan melihat opsi.', 'condition' => ['show_title' => 'yes']]);
        $this->end_controls_section();

        // Section: Gambar & Karakter
        $this->start_controls_section('sec_images', ['label' => __( 'Gambar & Karakter', 'ridafarm' )]);
        $this->add_control('bg_image', [
            'label' => __( 'Gambar Denah (Background)', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => ['url' => get_template_directory_uri() . '/assets/denah/denah-ridafarm.webp']
        ]);
        $this->add_control('char_image', [
            'label' => __( 'Gambar Karakter', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => ['url' => get_template_directory_uri() . '/assets/denah/pak-wayan-artana.png']
        ]);
        $this->add_responsive_control('char_w', [
            'label' => __( 'Lebar Karakter (%)', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['%' => ['min' => 1, 'max' => 50]],
            'default' => ['unit' => '%', 'size' => 5],
            'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-char-w: {{SIZE}}{{UNIT}};']
        ]);
        $this->add_responsive_control('char_min_w', [
            'label' => __( 'Min Width Karakter (px)', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 10, 'max' => 200]],
            'default' => ['unit' => 'px', 'size' => 40],
            'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-char-min-w: {{SIZE}}{{UNIT}};']
        ]);
        $this->add_control('char_start_top', ['label' => __( 'Posisi Awal Top (%)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 88]);
        $this->add_control('char_start_left', ['label' => __( 'Posisi Awal Left (%)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 88]);
        $this->add_control('anim_dur', [
            'label' => __( 'Durasi Jalan (detik)', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 1,
            'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-dur: {{VALUE}}s;']
        ]);
        $this->add_control('debug_mode', [
            'label' => __( 'Tampilkan Outline Hotspot', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'description' => 'Outline merah untuk memudahkan pengaturan posisi. Di frontend akan otomatis mati kecuali ini dinyalakan.'
        ]);
        $this->end_controls_section();

        // Section: Responsif & Mobile
        $this->start_controls_section('sec_responsive', ['label' => __( 'Responsif & Interaksi', 'ridafarm' )]);
        
        $this->add_control('mobile_mode', [
            'label' => __( 'Mode Tampilan Mobile', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => ['pan' => 'Pan (Geser) - Direkomendasikan', 'scale' => 'Scale (Muat Layar)'],
            'default' => 'pan',
            'description' => 'Di mode geser, peta tetap besar dan bisa digeser. Mode scale akan mengecilkan peta sampai muat layar.'
        ]);
        
        $this->add_control('pan_min_width', [
            'label' => __( 'Lebar Minimum Peta (px)', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 400, 'max' => 1200]],
            'default' => ['unit' => 'px', 'size' => 720],
            'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-pan-min-w: {{SIZE}}{{UNIT}};'],
            'condition' => ['mobile_mode' => 'pan']
        ]);
        
        $this->add_control('hint_text', [
            'label' => __( 'Teks Petunjuk Geser', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Geser untuk menjelajah',
            'condition' => ['mobile_mode' => 'pan']
        ]);

        $this->add_control('show_pulse', [
            'label' => __( 'Indikator Titik Berdenyut', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => ['auto' => 'Auto (Sentuh & Awal Desktop)', 'always' => 'Selalu Tampil', 'none' => 'Sembunyikan'],
            'default' => 'auto',
            'description' => 'Petunjuk di setiap hotspot. Penting untuk layar sentuh.'
        ]);

        $this->add_control('show_chips', [
            'label' => __( 'Navigasi Cepat (Chips)', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => ['mobile' => 'Mobile Saja', 'tablet_mobile' => 'Tablet & Mobile', 'all' => 'Semua Layar', 'none' => 'Sembunyikan'],
            'default' => 'mobile',
            'description' => 'Tombol akses cepat di bawah peta berdasarkan judul hotspot.'
        ]);
        $this->end_controls_section();

        // Section: Hotspots
        $this->start_controls_section('sec_hotspots', ['label' => __( 'Hotspot (Area Klik)', 'ridafarm' )]);
        $repeater_hs = new \Elementor\Repeater();
        $repeater_hs->add_control('key', ['label' => __( 'Key Unik', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'hs-1', 'description' => 'ID unik untuk dihubungkan dengan Opsi Popup.']);
        $repeater_hs->add_control('title', ['label' => __( 'Judul Popup', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Judul Area']);
        $repeater_hs->add_control('tooltip', ['label' => __( 'Tooltip (Title atribut)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT]);
        $repeater_hs->add_control('h_top', ['label' => __( 'Area Top (%)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 10]);
        $repeater_hs->add_control('h_left', ['label' => __( 'Area Left (%)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 10]);
        $repeater_hs->add_control('h_w', ['label' => __( 'Area Width (%)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 10]);
        $repeater_hs->add_control('h_h', ['label' => __( 'Area Height (%)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 10]);
        $repeater_hs->add_control('c_top', ['label' => __( 'Tujuan Karakter Top (%)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 15]);
        $repeater_hs->add_control('c_left', ['label' => __( 'Tujuan Karakter Left (%)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 15]);
        $repeater_hs->add_control('popup_pos', [
            'label' => __( 'Posisi Popup', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => ['auto' => 'Otomatis', 'top' => 'Atas', 'right' => 'Kanan', 'left' => 'Kiri'],
            'default' => 'auto'
        ]);
        $repeater_hs->add_control('hide_chip', [
            'label' => __( 'Sembunyikan dari Navigasi Chips', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::SWITCHER
        ]);
        
        $this->add_control('hotspots', [
            'label' => __( 'Daftar Hotspot', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater_hs->get_controls(),
            'default' => [
                ['key' => 'kandang-kiri', 'title' => 'Kandang Kambing (Kiri)', 'tooltip' => 'Kandang Kambing (Kiri)', 'h_top' => 18, 'h_left' => 20, 'h_w' => 16, 'h_h' => 23, 'c_top' => 41, 'c_left' => 28, 'popup_pos' => 'auto'],
                ['key' => 'kandang-kanan', 'title' => 'Kandang Kambing (Kanan)', 'tooltip' => 'Kandang Kambing (Kanan)', 'h_top' => 18, 'h_left' => 62, 'h_w' => 22, 'h_h' => 23, 'c_top' => 41, 'c_left' => 73, 'popup_pos' => 'auto'],
                ['key' => 'papan-pengumuman', 'title' => 'Papan Pengumuman', 'tooltip' => 'Papan Pengumuman', 'h_top' => 43, 'h_left' => 41, 'h_w' => 17, 'h_h' => 18, 'c_top' => 61, 'c_left' => 50, 'popup_pos' => 'auto'],
                ['key' => 'bale-bengong', 'title' => 'Bale Bengong', 'tooltip' => 'Bale Bengong', 'h_top' => 48, 'h_left' => 12, 'h_w' => 14, 'h_h' => 30, 'c_top' => 78, 'c_left' => 19, 'popup_pos' => 'auto'],
                ['key' => 'picnic-ground', 'title' => 'Picnic Ground', 'tooltip' => 'Picnic Ground', 'h_top' => 17, 'h_left' => 43, 'h_w' => 14, 'h_h' => 6, 'c_top' => 23, 'c_left' => 50, 'popup_pos' => 'auto'],
                ['key' => 'sungai', 'title' => 'Area Sungai', 'tooltip' => 'Sungai', 'h_top' => 4, 'h_left' => 44, 'h_w' => 12, 'h_h' => 7, 'c_top' => 11, 'c_left' => 50, 'popup_pos' => 'auto'],
            ]
        ]);
        $this->end_controls_section();

        // Section: Opsi Popup
        $this->start_controls_section('sec_options', ['label' => __( 'Opsi Popup (Tombol)', 'ridafarm' )]);
        $repeater_opt = new \Elementor\Repeater();
        $repeater_opt->add_control('key', ['label' => __( 'Key Hotspot', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'description' => 'Sesuaikan dengan Key Unik di atas.']);
        $repeater_opt->add_control('label', ['label' => __( 'Label Tombol', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Lihat Detail']);
        $repeater_opt->add_control('type', [
            'label' => __( 'Tipe Aksi', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => ['modal' => 'Modal (Tengah Layar)', 'link' => 'Link / Anchor / WA', 'close' => 'Tutup Popup'],
            'default' => 'modal'
        ]);
        
        // Modal Specific
        $repeater_opt->add_control('m_title', ['label' => __( 'Judul Modal', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'condition' => ['type' => 'modal']]);
        $repeater_opt->add_control('m_img', ['label' => __( 'Gambar Modal', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::MEDIA, 'condition' => ['type' => 'modal']]);
        $repeater_opt->add_control('m_content', ['label' => __( 'Konten Modal', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::WYSIWYG, 'condition' => ['type' => 'modal']]);
        $repeater_opt->add_control('m_btn_text', ['label' => __( 'Teks Tombol CTA', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::TEXT, 'condition' => ['type' => 'modal'], 'description' => 'Kosongkan jika tidak butuh tombol.']);
        $repeater_opt->add_control('m_btn_link', ['label' => __( 'Link Tombol CTA', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::URL, 'condition' => ['type' => 'modal']]);
        
        // Link Specific
        $repeater_opt->add_control('l_url', ['label' => __( 'Link URL', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::URL, 'condition' => ['type' => 'link']]);

        $this->add_control('options', [
            'label' => __( 'Daftar Tombol', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater_opt->get_controls(),
            'default' => [
                ['key' => 'kandang-kiri', 'label' => '🔍 Lihat Detail Kandang', 'type' => 'modal', 'm_title' => 'Detail Kandang Kiri', 'm_content' => '<p>Membuka Detail Kandang Kiri...</p>'],
                ['key' => 'kandang-kiri', 'label' => '🐐 Cek Kondisi Ternak', 'type' => 'modal', 'm_title' => 'Kondisi Ternak', 'm_content' => '<p>Kondisi Ternak: Sehat</p>'],
                ['key' => 'kandang-kanan', 'label' => '🔍 Lihat Detail Kandang', 'type' => 'modal', 'm_title' => 'Detail Kandang Kanan', 'm_content' => '<p>Membuka Detail Kandang Kanan...</p>'],
                ['key' => 'kandang-kanan', 'label' => '🌾 Jadwal Makan', 'type' => 'modal', 'm_title' => 'Jadwal Makan', 'm_content' => '<p>Jadwal Makan: Pagi & Sore</p>'],
                ['key' => 'papan-pengumuman', 'label' => '📰 Baca Pengumuman', 'type' => 'modal', 'm_title' => 'Pengumuman', 'm_content' => '<p>Membaca pengumuman terbaru...</p>'],
                ['key' => 'papan-pengumuman', 'label' => '📅 Event Mendatang', 'type' => 'modal', 'm_title' => 'Event Mendatang', 'm_content' => '<p>Event Mendatang: Festival Panen (Desember)</p>'],
                ['key' => 'bale-bengong', 'label' => '☕ Istirahat & Bersantai', 'type' => 'modal', 'm_title' => 'Bale Bengong', 'm_content' => '<p>Duduk santai sambil minum kopi...</p>'],
                ['key' => 'bale-bengong', 'label' => 'ℹ️ Pusat Informasi', 'type' => 'modal', 'm_title' => 'Informasi', 'm_content' => '<p>Menampilkan Profil Ridafarm...</p>'],
                ['key' => 'picnic-ground', 'label' => '⛺ Booking Area', 'type' => 'modal', 'm_title' => 'Booking Area', 'm_content' => '<p>Membuka form reservasi tempat...</p>'],
                ['key' => 'picnic-ground', 'label' => '📸 Lihat Galeri', 'type' => 'modal', 'm_title' => 'Galeri', 'm_content' => '<p>Membuka galeri foto piknik...</p>'],
                ['key' => 'sungai', 'label' => '🎣 Aktivitas Air', 'type' => 'modal', 'm_title' => 'Aktivitas Air', 'm_content' => '<p>Melihat aktivitas memancing dan susur sungai...</p>'],
                ['key' => 'sungai', 'label' => '💧 Cek Kualitas Air', 'type' => 'modal', 'm_title' => 'Kualitas Air', 'm_content' => '<p>Kualitas Air: Jernih & Aman</p>'],
            ]
        ]);
        $this->end_controls_section();

        // TAB STYLE
        $this->start_controls_section('style_hotspot', ['label' => __( 'Hotspot', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE]);
        $this->add_control('hs_border', ['label' => __( 'Warna Outline', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-hotspot-border: 2px solid {{VALUE}};']]);
        $this->add_control('hs_bg', ['label' => __( 'Warna Latar', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-hotspot-bg: {{VALUE}};']]);
        $this->add_control('hs_hover_border', ['label' => __( 'Warna Outline (Hover)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-hotspot-hover-border: {{VALUE}};']]);
        $this->add_control('hs_hover_bg', ['label' => __( 'Warna Latar (Hover)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-hotspot-hover-bg: {{VALUE}};']]);
        $this->add_control('hs_active_border', ['label' => __( 'Warna Outline (Aktif)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-hotspot-active-border: {{VALUE}};']]);
        $this->add_control('hs_active_bg', ['label' => __( 'Warna Latar (Aktif)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-hotspot-active-bg: {{VALUE}};']]);
        
        $this->add_control('pulse_head', [
            'label' => __( 'Indikator Titik Berdenyut', 'ridafarm' ),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        $this->add_control('pulse_color', ['label' => __( 'Warna Titik', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-pulse-color: {{VALUE}};']]);
        $this->add_control('pulse_size', ['label' => __( 'Ukuran Titik (px)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => ['px' => ['max' => 50]], 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-pulse-size: {{SIZE}}{{UNIT}};']]);
        
        $this->end_controls_section();

        $this->start_controls_section('style_popup', ['label' => __( 'Popup Aksi', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE]);
        $this->add_control('pop_head_bg', ['label' => __( 'Latar Header', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-popup-head-bg: {{VALUE}};']]);
        $this->add_control('pop_head_col', ['label' => __( 'Teks Header', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-popup-head-color: {{VALUE}};']]);
        $this->add_control('pop_body_bg', ['label' => __( 'Latar Body', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-popup-body-bg: {{VALUE}};']]);
        $this->add_control('pop_radius', ['label' => __( 'Radius', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-popup-radius: {{SIZE}}{{UNIT}};']]);
        $this->add_control('pop_btn_col', ['label' => __( 'Teks Tombol', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-popup-btn-color: {{VALUE}};']]);
        $this->add_control('pop_btn_hbg', ['label' => __( 'Latar Tombol (Hover)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-popup-btn-hover-bg: {{VALUE}};']]);
        $this->end_controls_section();

        $this->start_controls_section('style_modal', ['label' => __( 'Modal', 'ridafarm' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE]);
        $this->add_control('mod_anim', ['label' => __( 'Animasi Masuk', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::SELECT, 'options' => ['zoom' => 'Zoom In', 'slide' => 'Slide Up', 'fade' => 'Fade'], 'default' => 'zoom']);
        $this->add_control('mod_overlay', ['label' => __( 'Warna Overlay', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-modal-overlay: {{VALUE}};']]);
        $this->add_control('mod_blur', ['label' => __( 'Efek Blur Overlay', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => ['px' => ['max' => 20]], 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-modal-blur: {{SIZE}}{{UNIT}};']]);
        $this->add_control('mod_bg', ['label' => __( 'Latar Konten', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-modal-bg: {{VALUE}};']]);
        $this->add_control('mod_w', ['label' => __( 'Lebar Maksimum', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => ['px' => ['max' => 1000]], 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-modal-w: {{SIZE}}{{UNIT}};']]);
        $this->add_control('mod_pad', ['label' => __( 'Padding Dalam', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-modal-pad: {{SIZE}}{{UNIT}};']]);
        $this->add_control('mod_radius', ['label' => __( 'Radius', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-modal-radius: {{SIZE}}{{UNIT}};']]);
        $this->add_control('mod_title_col', ['label' => __( 'Warna Judul', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-modal-title-color: {{VALUE}};']]);
        $this->add_control('mod_text_col', ['label' => __( 'Warna Teks Konten', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-modal-text: {{VALUE}};']]);
        $this->add_control('mod_close_col', ['label' => __( 'Warna Tombol Tutup', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-modal-close-color: {{VALUE}};']]);
        $this->add_control('mod_cta_bg', ['label' => __( 'Latar Tombol CTA', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-modal-cta-bg: {{VALUE}};']]);
        $this->add_control('mod_cta_hover', ['label' => __( 'Latar Tombol CTA (Hover)', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-modal-cta-hover: {{VALUE}};']]);
        $this->add_control('mod_cta_col', ['label' => __( 'Teks Tombol CTA', 'ridafarm' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .rfd-wrapper' => '--rfd-modal-cta-color: {{VALUE}};']]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        
        $config = [
            'dur' => $s['anim_dur'],
            'charW' => $s['char_w']['size'],
            'hotspots' => [],
            'options' => []
        ];

        foreach ($s['hotspots'] as $hs) {
            $config['hotspots'][] = [
                'key' => $hs['key'],
                'title' => $hs['title'],
                'top' => $hs['h_top'] . '%',
                'left' => $hs['h_left'] . '%',
                'w' => $hs['h_w'] . '%',
                'h' => $hs['h_h'] . '%',
                'cTop' => $hs['c_top'] . '%',
                'cLeft' => $hs['c_left'] . '%',
                'popupPos' => $hs['popup_pos'],
            ];
        }

        foreach ($s['options'] as $idx => $o) {
            $opt = [
                'key' => $o['key'],
                'label' => $o['label'],
                'type' => $o['type'],
                'index' => $idx
            ];
            if ($o['type'] === 'link' && !empty($o['l_url']['url'])) {
                $opt['url'] = $o['l_url']['url'];
                $opt['blank'] = !empty($o['l_url']['is_external']);
                $opt['nofollow'] = !empty($o['l_url']['nofollow']);
            }
            $config['options'][] = $opt;
        }

        $is_edit = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $debug_class = ($is_edit || $s['debug_mode'] === 'yes') ? ' rfd-debug' : '';
        $mode_class = ' rfd-mode-' . $s['mobile_mode'];
        $pulse_attr = ' data-rfd-pulse="' . esc_attr($s['show_pulse']) . '"';
        $chips_class = ' rfd-chips-show-' . $s['show_chips'];
        ?>
        <div class="rfd-wrapper<?php echo $debug_class . $mode_class . $chips_class; ?>" data-rfd-id="<?php echo esc_attr($this->get_id()); ?>" data-rfd-modal-anim="<?php echo esc_attr($s['mod_anim']); ?>" data-rfd-config='<?php echo wp_json_encode($config); ?>'<?php echo $pulse_attr; ?>>
            
            <?php if ($s['show_title'] === 'yes'): ?>
            <div class="rfd-head">
                <h2 class="rfd-title"><?php echo esc_html($s['title_text']); ?></h2>
                <p class="rfd-desc"><?php echo esc_html($s['desc_text']); ?></p>
            </div>
            <?php endif; ?>

            <?php if ($s['show_chips'] !== 'none'): ?>
            <nav class="rfd-chips" role="tablist" aria-label="Navigasi Peta">
                <?php foreach($config['hotspots'] as $h): 
                    // cek hide_chip
                    $ht = array_filter($s['hotspots'], function($v) use ($h) { return $v['key'] === $h['key']; });
                    $ht = reset($ht);
                    if (!empty($ht['hide_chip']) && $ht['hide_chip'] === 'yes') continue;
                ?>
                    <button type="button" class="rfd-chip" data-key="<?php echo esc_attr($h['key']); ?>" role="tab" aria-selected="false"><?php echo esc_html($h['title']); ?></button>
                <?php endforeach; ?>
            </nav>
            <?php endif; ?>

            <div class="rfd-viewport">
                <div class="rfd-map">
                    <?php if(!empty($s['bg_image']['url'])): ?>
                        <img class="rfd-bg" src="<?php echo esc_url($s['bg_image']['url']); ?>" alt="Denah">
                    <?php endif; ?>
                    
                    <div class="rfd-layer">
                        <?php foreach($config['hotspots'] as $h): ?>
                            <button type="button" class="rfd-hotspot" data-key="<?php echo esc_attr($h['key']); ?>" 
                                    title="<?php 
                                        $ht = array_filter($s['hotspots'], function($v) use ($h) { return $v['key'] === $h['key']; });
                                        $ht = reset($ht);
                                        echo esc_attr($ht['tooltip'] ?? $h['title']); 
                                    ?>"
                                    aria-label="<?php echo esc_attr($h['title']); ?>"
                                    style="top:<?php echo esc_attr($h['top']); ?>; left:<?php echo esc_attr($h['left']); ?>; width:<?php echo esc_attr($h['w']); ?>; height:<?php echo esc_attr($h['h']); ?>;"></button>
                        <?php endforeach; ?>
                    </div>

                    <?php if(!empty($s['char_image']['url'])): ?>
                        <img class="rfd-char" src="<?php echo esc_url($s['char_image']['url']); ?>" alt="Character" aria-hidden="true"
                             style="top:<?php echo esc_attr($s['char_start_top']); ?>%; left:<?php echo esc_attr($s['char_start_left']); ?>%;">
                    <?php endif; ?>

                    <!-- Desktop/Tablet Popup -->
                    <div class="rfd-popup" role="dialog" aria-live="polite">
                        <div class="rfd-popup-head"></div>
                        <div class="rfd-popup-body"></div>
                    </div>
                </div>
            </div>

            <?php if ($s['mobile_mode'] === 'pan' && !empty($s['hint_text'])): ?>
            <div class="rfd-hint" aria-hidden="true">
                <span><?php echo esc_html($s['hint_text']); ?></span>
            </div>
            <?php endif; ?>

            <!-- Mobile Bottom Sheet -->
            <div class="rfd-sheet-overlay" aria-hidden="true"></div>
            <div class="rfd-sheet" role="dialog" aria-modal="true" aria-live="polite">
                <div class="rfd-sheet-handle"></div>
                <div class="rfd-sheet-head"></div>
                <div class="rfd-sheet-body"></div>
                <button type="button" class="rfd-sheet-close" aria-label="Tutup"><i class="fas fa-times"></i></button>
            </div>

            <!-- Modal Templates -->
            <?php foreach ($s['options'] as $idx => $o): 
                if ($o['type'] === 'modal'): ?>
                <template class="rfd-modal-tpl" data-opt="<?php echo esc_attr($idx); ?>">
                    <div class="rfd-modal-overlay"></div>
                    <div class="rfd-modal-content">
                        <button type="button" class="rfd-modal-close" aria-label="Tutup"><i class="fas fa-times"></i></button>
                        <?php if (!empty($o['m_img']['url'])): ?>
                            <img class="rfd-modal-img" src="<?php echo esc_url($o['m_img']['url']); ?>" alt="Modal Image">
                        <?php endif; ?>
                        
                        <?php if (!empty($o['m_title'])): ?>
                            <h3 class="rfd-modal-title"><?php echo esc_html($o['m_title']); ?></h3>
                        <?php endif; ?>
                        
                        <div class="rfd-modal-body">
                            <?php echo wp_kses_post($o['m_content']); ?>
                        </div>

                        <?php if (!empty($o['m_btn_text'])): ?>
                            <a href="<?php echo esc_url($o['m_btn_link']['url']); ?>" class="rfd-modal-cta"
                               <?php echo !empty($o['m_btn_link']['is_external']) ? 'target="_blank"' : ''; ?>
                               <?php echo !empty($o['m_btn_link']['nofollow']) ? 'rel="nofollow"' : ''; ?>>
                               <?php echo esc_html($o['m_btn_text']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </template>
            <?php endif; endforeach; ?>
        </div>
        <?php
    }
}
