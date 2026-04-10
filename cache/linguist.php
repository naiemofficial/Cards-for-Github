<?php
if (! defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . '../api/linguist.php';



function github_card_get_linguist_cached() {
    $cache_key = 'github_card_linguist';

    // Check if cached
    $cache_enabled = github_card_cache_enabled();
    if($cache_enabled){
        $cached_linguist_colors = get_transient($cache_key);
        if ($cached_linguist_colors !== false) {
            return $cached_linguist_colors;
        }
    }

    // Load from API
    $linguist_colors = github_card_load_linguist_data();

    // Don't cache if any error
    if ($linguist_colors instanceof WP_Error) return $linguist_colors;

    // Cache it
    if($cache_enabled){
        $cache_duration = github_card_cache_duration();
        set_transient($cache_key, $linguist_colors, $cache_duration);
    }

    return $linguist_colors ?? [];
}