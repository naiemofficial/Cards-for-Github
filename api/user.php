<?php
if (! defined('ABSPATH')) {
    exit;
}



function github_card_get_user_data($username){
    $api = "https://api.github.com/users/{$username}";

    // Fetch user data
    $response = wp_remote_get($api);

    // If error, don't process data; just return
    if (is_wp_error($response)) return $response;

    

    // ----------- START/END - Porcess data -----------
    return json_decode(wp_remote_retrieve_body($response), true);
}


function github_card_load_get_user_data($username) {
    return github_card_get_user_data($username);
}