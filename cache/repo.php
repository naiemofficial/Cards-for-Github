<?php
if (! defined('ABSPATH')) {
    exit;
}





require_once plugin_dir_path(__FILE__).'../api/repo.php';












// Get GitHub repo repo_data with caching
function github_card_get_repo_data_cached($repo_full) {
    $transient_key = 'github_card_repo_data_' . md5($repo_full);

    // Check if cached
    $cache_enabled = github_card_cache_enabled();
    if($cache_enabled){
        $cached_repo_data = get_transient($transient_key);
        if ($cached_repo_data !== false) {
            return $cached_repo_data;
        }
    }

    // Load from API
    $repo_data = github_card_load_repo_data($repo_full);

    // Don't cache if any error
    if ($repo_data instanceof WP_Error) return $repo_data;

    // Cache it
    if($cache_enabled){
        $cache_duration = github_card_cache_duration();
        set_transient($transient_key, $repo_data, $cache_duration);
    }

    return $repo_data;
}