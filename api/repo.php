<?php
if (! defined('ABSPATH')) {
    exit;
}


function github_card_get_repo_data($repo_full) {
    // ----------------------------
    // Build API endpoints
    // ----------------------------
    $repo_api         = "https://api.github.com/repos/{$repo_full}";
    $contributors_api = "https://api.github.com/repos/{$repo_full}/contributors?per_page=100";
    $languages_api    = "https://api.github.com/repos/{$repo_full}/languages";

    // ----------------------------
    // Fetch repo basic info
    // ----------------------------
    $repo_data = github_safe_get($repo_api);
    if (is_wp_error($repo_data)) return $repo_data;

    // ----------------------------
    // Contributors
    // ----------------------------
    $contributors_data = github_safe_get($contributors_api);
    if (is_wp_error($contributors_data)) return $contributors_data;

    $contributors_total = is_array($contributors_data) ? count($contributors_data) : 0;

    // ----------------------------
    // Count ALL issues (open + closed) using Search API
    // ----------------------------
    $issues_total = 0;
    $search_api = "https://api.github.com/search/issues?q=repo:{$repo_full}+type:issue";
    $issues_data = github_safe_get($search_api);

    if (!is_wp_error($issues_data) && isset($issues_data['total_count'])) {
        $issues_total = intval($issues_data['total_count']);
    }

    // ----------------------------
    // Programming languages (%)
    // ----------------------------
    $languages_data = github_safe_get($languages_api);
    if (is_wp_error($languages_data)) $languages_data = [];

    // ----------------------------
    // Final return array
    // ----------------------------
    
    $repo_data = array_merge($repo_data, [
        'stars'        => $repo_data['stargazers_count'] ?? 0,
        'forks'        => $repo_data['forks_count'] ?? 0,
        'open_issues'  => $repo_data['open_issues_count'] ?? 0,
        'all_issues'   => $issues_total,
        'contributors' => $contributors_total,
        'languages'    => $languages_data,
    ]);

    return $repo_data;
}


function github_card_load_repo_data($repo_full) {
    return github_card_get_repo_data($repo_full);
}
