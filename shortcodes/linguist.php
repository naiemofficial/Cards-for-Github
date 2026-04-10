<?php
if (! defined('ABSPATH')) {
	exit;
}



function github_card_fn_github_linguist($shortcode = true){
    $linguist_colors = github_card_get_linguist_cached();
    if(!$linguist_colors) return 'Error: empty data - (Ref: S>L)';
    if($linguist_colors instanceof WP_Error) return $shortcode ? json_encode($linguist_colors) : $linguist_colors;

    return $shortcode ? json_encode($linguist_colors) : $linguist_colors;
}


function github_card_assign_color_to_repo_languages(&$repo_data){
	if (is_wp_error($repo_data)){
		return $repo_data;
	}
		
	$linguist_data = github_card_get_linguist_cached(); //
	if (is_wp_error($linguist_data)) {
		$repo_data = $linguist_data;
		return $repo_data;
	}
	
	
	$repo_languages = $repo_data['languages'];
	if(is_array($repo_languages) && is_array($linguist_data)){
		$languages = [];
		$color_gradient = '';
		$current_start = 0.0;
		
		$total_bytes = array_sum($repo_languages);
		if ($total_bytes > 0) {
			$prev_percentage = 0;
			foreach ($repo_languages as $lang => $bytes) {
				$percentage = round(($bytes / $total_bytes) * 100, 1);
				$color = $linguist_data[$lang] ?? null;
				$languages[$lang] = [
					'percentage' => $percentage,
					'bytes' => $bytes,
					'color' => $color,
				];
				
				[$color_gradient, $current_start] = github_card_add_gradient_stop( $color, $percentage, $current_start, $color_gradient );
				if ($current_start >= 100.0) {
					break;
				}
			}
		}
		
		$repo_data['languages'] = $languages;
		$repo_data['color_gradient'] = $color_gradient;
	}
	
	return $repo_data;
}

add_shortcode('github_linguist', 'github_card_fn_github_linguist');












function github_card_add_gradient_stop(string $color, float $percentage, float $current_start, string $color_gradient): array {
	if ($percentage <= 0) {
		// Return original values if no block is added
		return [$color_gradient, $current_start];
	}
	
	$current_end = $current_start + $percentage;
	
	// Safety check against rounding errors
	if ($current_end > 100.0) {
		$current_end = 100.0;
	}
	
	// 1. Add the color stop for the start of the current color block
	if ($current_start > 0.0) {
		if (strlen($color_gradient) > 0) {
			$color_gradient .= ', ';
		}
		$color_gradient .= $color . ' ' . number_format($current_start, 1) . '%';
	} else {
		// Explicitly start the first block at 0%
		if (strlen($color_gradient) > 0) {
			$color_gradient .= ', ';
		}
		$color_gradient .= $color . ' 0%';
	}
	
	// 2. Add the color stop for the end of the current color block
	$color_gradient .= ', ' . $color . ' ' . number_format($current_end, 1) . '%';
	
	// 3. Return the updated string and the new starting position
	return [$color_gradient, $current_end];
}