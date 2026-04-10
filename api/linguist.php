<?php
if (! defined('ABSPATH')) {
    exit;
}


use Symfony\Component\Yaml\Yaml;

function github_card_get_linguist_data() {
    $cache_key = 'github_linguist_colors';
    $linguist_colors = get_transient($cache_key);
    if ($linguist_colors !== false) return $linguist_colors;

    // Fetch YAML file from GitHub
    $url = 'https://raw.githubusercontent.com/github/linguist/master/lib/linguist/languages.yml';
    $response = wp_remote_get($url, [
        'headers' => ['User-Agent' => 'WordPress/1.0'],
        'timeout' => 15
    ]);

    if (is_wp_error($response)) return [];
    $yaml = wp_remote_retrieve_body($response);
    if (!$yaml) return [];

    // Parse YAML using Symfony YAML parser
    try {
        $yaml_data = Yaml::parse($yaml);
    } catch (\Exception $e) {
        return [];
    }

    // Convert to language => color array
    $linguist_colors = [];
    foreach ($yaml_data as $lang => $data) {
        $linguist_colors[$lang] = $data['color'] ?? null;
    }

    return $linguist_colors;
}




function github_card_load_linguist_data() {
    return github_card_get_linguist_data();
}