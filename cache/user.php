<?php
if (! defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . '../api/user.php';



function github_card_get_user_data_cached($username) {
    $transient_key = 'github_card_user_' . md5($username);

    // Check if cached
    $cache_enabled = github_card_cache_enabled();
    if ($cache_enabled) {
        $cached_user = get_transient($transient_key);
        if ($cached_user !== false) {
            return $cached_user;
        }
    }

    // Load from API
    $user_data = github_card_load_get_user_data($username);

    // Don't cache if any error
    if ($user_data instanceof WP_Error) return $user_data;

    // Cache it
    if($cache_enabled){
        $cache_duration = github_card_cache_duration();
        set_transient($transient_key, $user_data, $cache_duration);
    }
    
    return $user_data;
}


