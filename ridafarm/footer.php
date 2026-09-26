    <?php 
    $wa_url = get_theme_mod('rf_wa_url', 'https://wa.me/6281234567890');
    $wa_img = get_theme_mod('rf_wa_image', get_template_directory_uri() . '/assets/whatsapp-logo.png');
    if (!empty($wa_url) && !empty($wa_img)):
    ?>
    <a href="<?php echo esc_url($wa_url); ?>" class="rf-floating-wa" target="_blank" rel="noopener noreferrer" aria-label="Chat on WhatsApp">
        <img src="<?php echo esc_url($wa_img); ?>" alt="WhatsApp" loading="lazy" />
    </a>
    <?php endif; ?>

    <?php wp_footer(); ?>
</body>
</html>
