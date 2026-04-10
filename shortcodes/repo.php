<?php
if (! defined('ABSPATH')) {
    exit;
}



/**
 * Generic shortcode builder for a specific field.
 */
function github_repo_data_cached($atts, $field, $shortcode = true){
    $a = shortcode_atts([
        'repo' => '',
    ], $atts);

    if (empty($a['repo'])) return 'Missing repo=""';

    // Get repo repo_data
    $repo_data = github_card_get_repo_data_cached($a['repo']);

    // Check errors
    if (!$repo_data) return 'Error: empty data - (Ref: S>S)';
    if ($repo_data instanceof WP_Error) return $shortcode ? json_encode($repo_data) : $repo_data;



    // Specic fields
    if ($field === 'languages') {
        return github_languages_cached($atts, $repo_data, $shortcode);
    } else if($field === 'full_info'){
		return $repo_data;
    }

    // Default behavior for stars, forks, issues, contributors
    return isset($repo_data[$field]) ? $repo_data[$field] : '';
}


function github_languages_cached($atts, $repo_data, $shortcode = true){
    if(empty($repo_data['languages']))  return 'Languages not found';

    // Check if 'array' key exists and is truthy
    if (!empty($atts['array']))  return $repo_data['languages']; // return associative array

    // return as formatted string: "PHP 30%, HTML 20.3%"
    $text_formatted = [];
    $color_formatted = [];
    foreach ($repo_data['languages'] as $lang => $data) {
        $percentage = $data['percentage'];
        $color = $data['color'] ?? 'N/A';

        $text_formatted[] = "{$lang} {$percentage}%";
        $color_formatted[] = "{$color} {$percentage}%";
    }

    if(!empty($atts['colors']) && $atts['colors'] === 'true'){
        return implode(', ', $color_formatted);
    }
    return implode(', ', $text_formatted);
}











function github_card_fn_github_repo_data($atts, $field){
    return github_repo_data_cached($atts, $field, true);
}


function github_card_full_github_repo_data($atts){
	$repo_data = github_repo_data_cached($atts, 'full_info', false);

    if(github_card_language_ribbon()){
	    github_card_assign_color_to_repo_languages($repo_data);
    }
    
	if(!is_wp_error($repo_data) && is_array($repo_data)){
		$repo_data['user'] = github_card_assign_user_data_to_repo($atts);
	}
	
	return $repo_data;
}


/** Register shortcodes */


add_shortcode('github_stars_count', function($atts){
    return github_card_fn_github_repo_data($atts, 'stars');
});

add_shortcode('github_forks_count', function($atts){
    return github_card_fn_github_repo_data($atts, 'forks');
});

add_shortcode('github_issues_count', function($atts){
    return github_card_fn_github_repo_data($atts, 'all_issues');
});

add_shortcode('github_contributors_count', function($atts){
    return github_card_fn_github_repo_data($atts, 'contributors');
});

add_shortcode('github_languages', function($atts){
    return github_card_fn_github_repo_data($atts, 'languages');
});