<?php
if (! defined('ABSPATH')) {
    exit;
}


/** Register shortcode for GitHub profile data */


function github_user_cached($atts, $shortcode = true){
    if (!isset($atts['username']) || empty($atts['username'])) {
        return "Missing username attribute";
    }

    // Get user data
	$username = $atts['username'] ?? null;
    $user_data = github_card_get_user_data_cached($username);
    
    // Check errors
    if(!$user_data) return 'Error: empty data - (Ref: S>U)';
    if($user_data instanceof WP_Error) $shortcode ? json_encode($user_data) : $user_data;



    $get = $atts['get'] ?? '';
    if(empty($get)) {
        return $shortcode ? json_encode($user_data) : $user_data;
    }

    // return $user_data[$get] ?? '';
    return isset($user_data[$get]) ? $user_data[$get] : "Key '{$get}' not found. (Invalid key or invalid username or private account) See available keys at https://docs.github.com/en/rest/users/users#get-a-user";
}



function github_card_assign_user_data_to_repo($atts){
	$repo = isset($atts['repo']) ? esc_attr($atts['repo']) : '/';
	$exploded_repo = explode('/', trim($repo, '/'));
	$username = isset($exploded_repo[0]) ? $exploded_repo[0] : $repo;
	$user = github_user_cached(['username' => $username], false);
	
	return $user;
}


function github_card_fn_github_user($atts){
    return github_user_cached($atts);
}


add_shortcode('github_user', 'github_card_fn_github_user');