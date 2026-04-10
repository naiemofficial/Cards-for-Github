<?php
if (! defined('ABSPATH')) {
    exit;
}

$github_card_all_input_settings = include plugin_dir_path(__FILE__) . 'includes/settings-input.php';
$github_card_defaults = array_map(function ($setting) {
    return $setting['default'];
}, $github_card_all_input_settings);

/**
 * Safely fetch a GitHub API endpoint.
 * Returns decoded JSON array or WP_Error on failure.
 */
function github_safe_get($url) {
    $response = wp_remote_get($url, [
        'headers' => [
            'User-Agent'    => 'WordPress/1.0',
            'Accept'        => 'application/vnd.github+json',
            'X-GitHub-Api-Version' => '2022-11-28'
        ],
        'timeout' => 15
    ]);
    

    // WordPress HTTP error
    if (is_wp_error($response)) return $response;

    
    $code = wp_remote_retrieve_response_code($response);

    

    // GitHub forbidden / rate limited
    if ($code === 403) {
        return new WP_Error('github_forbidden', 'GitHub API returned 403 Forbidden');
    }

    if ($code !== 200) {
        return new WP_Error('github_error', "GitHub API returned status: $code");
    }

    // JSON decode
    return json_decode(wp_remote_retrieve_body($response), true);
}




// ------------------ SATRT - Option Getters ---------------- //
function github_card_load_with($requested_option = '', $other_input_dependency = true) {
    global $github_card_defaults;
    $key = 'github_card_load_with';
	$option = get_option($key, $github_card_defaults[$key]);
	if(!empty($requested_option)){
		return !empty($option) && $option === $requested_option;
    }
    return get_option($key, $github_card_defaults[$key]);
}

function github_card_wrapper_preloader($other_input_dependency = true) {
    if ($other_input_dependency && github_card_load_with() === 'php') {
        return null;
    }
    
    global $github_card_defaults;
    $key = 'github_card_wrapper_preloader';
    $value = filter_var(get_option($key, $github_card_defaults[$key]), FILTER_VALIDATE_BOOLEAN);
    if ($value) {
        update_option('github_card_data_preloader', false);
    }
    return $value;
}

function github_card_preloader_type($requested_option = '', $other_input_dependency = true){
    if($other_input_dependency && in_array(github_card_wrapper_preloader(), [null, false], true)){
        return !empty($requested_option) ? false : null;
    }

    global $github_card_defaults;
    $key = 'github_card_preloader_type';
	$option = get_option($key, $github_card_defaults[$key]);
	if(!empty($requested_option)){
		return !empty($option) && $option === $requested_option;
    }
    return get_option($key, $github_card_defaults[$key]);
}

function github_card_data_preloader($other_input_dependency = true) {
    global $github_card_defaults;
    $key = 'github_card_data_preloader';
    return filter_var(get_option($key, $github_card_defaults[$key]), FILTER_VALIDATE_BOOLEAN);
}

function github_card_width($other_input_dependency = true) {
    global $github_card_defaults;
    $key = 'github_card_width';
    return get_option($key, $github_card_defaults[$key]);
}

function github_card_height($other_input_dependency = true) {
    global $github_card_defaults;
    $key = 'github_card_height';
    return get_option($key, $github_card_defaults[$key]);
}

function github_card_auto_scale($other_input_dependency = true) {
    global $github_card_defaults;
    $key = 'github_card_auto_scale';
    return filter_var(get_option($key, $github_card_defaults[$key]), FILTER_VALIDATE_BOOLEAN);
}


function github_card_spinner($other_input_dependency = true) {
    global $github_card_defaults;
    $key = 'github_card_spinner';
    return get_option($key, $github_card_defaults[$key]);
}

function github_card_footer_ribbon($other_input_dependency = true) {
    global $github_card_defaults;
    $key = 'github_card_footer_ribbon';
    return filter_var(get_option($key, $github_card_defaults[$key]), FILTER_VALIDATE_BOOLEAN);
}

function github_card_language_ribbon($other_input_dependency = true) {
    if(!github_card_footer_ribbon()) {
        return false;
    }
    
    global $github_card_defaults;
    $key = 'github_card_language_ribbon';
    return filter_var(get_option($key, $github_card_defaults[$key]), FILTER_VALIDATE_BOOLEAN);
}


function github_card_error($other_input_dependency = true) {
    global $github_card_defaults;
    $key = 'github_card_error';
    return filter_var(get_option($key, $github_card_defaults[$key]), FILTER_VALIDATE_BOOLEAN);
}

function github_card_preloader_spinner_color($other_input_dependency = true) {
    global $github_card_defaults;
    $key = 'github_card_preloader_spinner_color';
    return get_option($key, $github_card_defaults[$key]);
}

function github_card_preloader_background_color($other_input_dependency = true) {
    if ($other_input_dependency && !github_card_preloader_type('spinner')) {
        return null;
    }
    global $github_card_defaults;
    $key = 'github_card_preloader_background_color';
    return get_option($key, $github_card_defaults[$key]);
}

function github_card_enable_preloader_blur($other_input_dependency = true) {
    if($other_input_dependency && !github_card_preloader_type('spinner')){
        return false;
    }
    global $github_card_defaults;
    $key = 'github_card_enable_preloader_blur';
    return filter_var(get_option($key, $github_card_defaults[$key]), FILTER_VALIDATE_BOOLEAN);
}

function github_card_preloader_blur_px($other_input_dependency = true) {
    if(github_card_enable_preloader_blur()){
        return null;
    }
    global $github_card_defaults;
    $key = 'github_card_preloader_blur_px';
    return get_option($key, $github_card_defaults[$key]);
}


function github_card_skeleton_primary_color($other_input_dependency = true) {
    if($other_input_dependency && !github_card_preloader_type('skeleton')){
        return null;
    }
    global $github_card_defaults;
    $key = 'github_card_skeleton_primary_color';
    return get_option($key, $github_card_defaults[$key]);
}


function github_card_skeleton_secondary_color($other_input_dependency = true) {
    if ($other_input_dependency && !github_card_preloader_type('skeleton')) {
        return null;
    }
    global $github_card_defaults;
    $key = 'github_card_skeleton_secondary_color';
    return get_option($key, $github_card_defaults[$key]);
}

function github_card_footer_ribbon_color($other_input_dependency = true) {
    if($other_input_dependency && !github_card_footer_ribbon()){
        return null;
    }
    global $github_card_defaults;
    $key = 'github_card_footer_ribbon_color';
    return get_option($key, $github_card_defaults[$key]);
}

function github_card_fontawesome_support($other_input_dependency = true) {
    global $github_card_defaults;
    $key = 'github_card_fontawesome_support';
    $option = get_option($key, $github_card_defaults[$key]);
    if(empty($option)){
        return 'enable';
    }
    return $option;
}


function github_card_cache_enabled($other_input_dependency = true) {
    global $github_card_defaults;
    $key = 'github_card_cache_enabled';
    return filter_var(get_option($key, $github_card_defaults[$key]), FILTER_VALIDATE_BOOLEAN);
}

function github_card_cache_duration($other_input_dependency = true) {
    if($other_input_dependency && !github_card_cache_enabled()){
        return 0;
    }
    
    global $github_card_defaults;
    $key = 'github_card_cache_duration';
    return intval(get_option($key, $github_card_defaults[$key]));
}
// ------------------ END - Option Getters ---------------- //
















function github_card_get_or_null($array, $key){
	return is_array($array) && isset($array[$key]) ? $array[$key] : null;
}



function github_card_compact_number($number) {
    if (!is_numeric($number)) return $number;

    $absNumber = abs($number);
    if ($absNumber < 1000) {
        return (string)$number;
    }

    $units = ['', 'K', 'M', 'B', 'T']; // Thousand, Million, Billion, Trillion
    $power = floor(log($absNumber, 1000)); // Determine which unit to use
    $value = $number / pow(1000, $power);

    // Remove .0 if whole number
    $value = ($value == floor($value)) ? floor($value) : round($value, 1);

    return $value . $units[$power];
}




function github_card_contributors_plus($number){
    if($number >= 100){
        return $number . '+';
    }
    return (string)$number;
}




function github_card_pluralize($count, $singular, $plural_suffix){
    return $count <= 1 ? $singular : $singular . $plural_suffix;
}