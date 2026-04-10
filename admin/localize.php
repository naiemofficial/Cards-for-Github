<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue admin CSS and Script
 */
function github_card_admin_style_scripts($hook){

    if ($hook !== 'toplevel_page_github-card-settings') return;

    // if (isset($_GET['page']) && sanitize_key(wp_unslash($_GET['page'])) === 'github-card-settings') {
        wp_enqueue_style('font-awesome', plugin_dir_url(__FILE__) . '../assets/libs/fontawesome/css/fontawesome.min.css', [], '7.1.0');

        wp_enqueue_style(
            'github-card-admin-style',
            plugin_dir_url(__DIR__) . 'admin/assets/css/styles.css',
            [],
            '1.0'
        );

        wp_enqueue_style(
            'tailwind',
            plugin_dir_url(__DIR__) . 'admin/assets/css/tailwind.min.css',
            [],
            '2.2.19'
        );

        wp_enqueue_style(
            'font-alliance-no-1',
            plugin_dir_url(__DIR__) . './assets/css/fonts.css',
            [],
            '1.0'
        );

        wp_enqueue_script(
            'github-card-admin-script',
            plugin_dir_url(__DIR__) . 'admin/assets/js/github-card-admin.js',
            ['jquery'],
            '1.0',
            true
        );

        wp_localize_script('github-card-admin-script', 'githubCardAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('github_card_admin'),
        ]);


        // WordPress built-in color picker
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    // }
}
add_action('admin_enqueue_scripts', 'github_card_admin_style_scripts');
