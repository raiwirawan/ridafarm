<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php wp_head(); ?>
</head>
<body <?php body_class('loading'); ?>>
    <?php wp_body_open(); ?>
    <!-- Custom Cursor -->
    <div class="cursor-dot"></div>
    <div class="cursor-outline"></div>

    <!-- Page Loader -->
    <div class="loader" role="status" aria-live="polite">
        <div class="loader-text">Rida Farm Bali</div>
        <div class="loader-progress"></div>
    </div>
