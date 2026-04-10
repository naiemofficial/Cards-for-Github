<?php
if (! defined('ABSPATH')) {
    exit;
}



function github_card_github_card_fn_github_card_template($atts)
{
    ob_start();

    global $github_card_data_loading_icon, $github_card_counts_empty_placeholder, $github_card_data_empty_placeholder, $github_card_default_avatar;

    $is_skeleton = github_card_preloader_type('skeleton');
    $skeleton_class = $is_skeleton ? 'github-card-skeleton' : '';


    $repo = isset($atts['repo']) ? esc_attr($atts['repo']) : '/';
    $class = isset($atts['class']) ? esc_attr($atts['class']) : '';
    $heading = isset($atts['heading']) ? esc_attr($atts['heading']) : null;
    $description_text = isset($atts['description-text']) ? esc_attr($atts['description-text']) : null;

    // split by slash to get username and reponame
    $exploded_repo = explode('/', trim($repo, '/'));
    $username = isset($exploded_repo[0]) ? $exploded_repo[0] : $repo;
    $reponame = isset($exploded_repo[1]) ? $exploded_repo[1] : '';
    $repolink = "https://github.com/" . $username . "/" . $reponame;

    // Card 
    $uid = 'github-card-' . uniqid();
    $github_card_height = isset($atts['height']) && is_numeric($atts['height']) && intval($atts['height']) > 0 ? intval($atts['height']) : null;
    $github_card_width = isset($atts['width']) && is_numeric($atts['width']) && intval($atts['width']) > 0 ? intval($atts['width']) : null;
    $default_height = github_card_height();
    $default_width = github_card_width();
    $has_changed_dimensions = false;
    if((!empty($github_card_height) && $github_card_height !== $default_height) || (!empty($github_card_width) && $github_card_width !== $default_width)){
        $has_changed_dimensions = true;
    }


    // Shortcode Parameters
    $show_username = !isset($atts['username']) || $atts['username'] !== 'false';
    $show_slash = !isset($atts['slash']) || $atts['slash'] !== 'false';
    $show_dash = !isset($atts['dash']) || $atts['dash'] !== 'false';
    if (!$show_dash) {
        $reponame = str_replace('-', ' ', $reponame);
    }


    $show_avatar = !isset($atts['avatar']) || $atts['avatar'] !== 'false';
    $avatar_is_url = isset($atts['avatar']) && filter_var($atts['avatar'], FILTER_VALIDATE_URL);
    $avatar_url = $avatar_is_url ? esc_url($atts['avatar']) : $github_card_default_avatar;

    $description_words = isset($atts['description-words']) ? intval($atts['description-words']) : -1;
    $show_description = !isset($atts['description']) || $atts['description'] !== 'false';
    $show_contributors = !isset($atts['contributors']) || $atts['contributors'] !== 'false';
    $show_issues = !isset($atts['issues']) || $atts['issues'] !== 'false';
    $show_stars = !isset($atts['stars']) || $atts['stars'] !== 'false';
    $show_forks = !isset($atts['forks']) || $atts['forks'] !== 'false';

    $paratmeters = [
        'description-words' => $description_words
    ];
    if ($avatar_is_url) {
        $paratmeters['avatar-url'] = $avatar_url;
    }


    $error = false;
    $error_text = null;

    $is_loaded_via_php = github_card_load_with('php');
    $is_loaded_via_js = github_card_load_with('js');

    if ($is_loaded_via_php) {
        $repo_data = github_card_full_github_repo_data($atts);
        if (is_wp_error($repo_data) && github_card_error()) {
            $error = true;
            $error_text = json_encode($repo_data);
        }
        
        $description = !empty($description_text) ? $description_text : github_card_get_or_null($repo_data, 'description');

        $user = github_card_get_or_null($repo_data, 'user');
        $user_avatar_url = $avatar_is_url ? $avatar_url : github_card_get_or_null($user, 'avatar_url');

        $contributors = github_card_get_or_null($repo_data, 'contributors');
        $issues = github_card_get_or_null($repo_data, 'all_issues');
        $open_issues = github_card_get_or_null($repo_data, 'open_issues');
        $stars = github_card_get_or_null($repo_data, 'stars');
        $forks = github_card_get_or_null($repo_data, 'forks');

        $color_gradient = github_card_get_or_null($repo_data, 'color_gradient');
    }
?>

    <div class="github-card-wrapper"
        data-uid="<?php echo esc_attr($uid); ?>"
        <?php if (isset($atts['repo']) && !empty($atts['repo'])): ?>
        data-github-repo="<?php echo esc_attr($atts['repo']); ?>"
        <?php endif; ?>
        data-parameters='<?php echo json_encode($paratmeters); ?>
    '>
        <div class="github-card<?php echo (' ' . esc_attr($class)); ?>">
            <?php if (github_card_wrapper_preloader() && github_card_preloader_type('spinner')) { ?>
                <div class="github-card-wrapper-preloader">
                    <?php echo esc_html($github_card_data_loading_icon); ?>
                </div>
            <?php } ?>
            <div class="github-card-header">
                <div class="github-card-title">
                    <h3 class="repo-title <?php echo esc_attr($is_skeleton ? $skeleton_class : ''); ?>">
                        <a href="<?php echo esc_url($repolink); ?>" target="_blank" rel="noopener noreferrer">
                            <?php if ($show_username): ?><?php echo esc_html($username); ?><?php endif; ?><?php if ($show_slash): ?>/<?php endif; ?><strong><?php echo !empty($heading) ? esc_html($heading) : esc_html($reponame); ?></strong>
                        </a>
                    </h3>
                    <?php if ($show_description): ?>
                        <?php if ($is_loaded_via_php) { ?>
                            <p data-repo-description class="repo-description <?php echo esc_attr($error ? 'error' : ''); ?>">
                                <?php if ($error) { ?>
                                    <?php echo esc_html($error_text); ?>
                                <?php } else { ?>
                                    <?php if ($description_words < 0) {
                                        echo esc_html($description);
                                    } elseif ($description_words == 0) {
                                        echo '';
                                    } else {
                                        echo esc_html(wp_trim_words($description, $description_words));
                                    } ?>
                                <?php } ?>
                            </p>
                        <?php } else if ($is_loaded_via_js) { ?>
                            <p data-repo-description class="repo-description <?php echo esc_attr($is_skeleton ? $skeleton_class : ''); ?>">
                                <?php echo esc_html(github_card_data_preloader() ? $github_card_data_loading_icon : $github_card_data_empty_placeholder); ?>
                            </p>
                        <?php } ?>
                    <?php endif; ?>
                </div>
                <div class="github-card-user">
                    <div class="github-card-avatar repo-user-avatar <?php echo esc_attr($is_skeleton ?  $skeleton_class : ''); ?>">
                        <?php if($show_avatar){ ?>
                            <div class="inline-flex" data-repo-avatar>
                                <?php if ($is_loaded_via_php) { ?>
                                    <img src="<?php echo esc_url(empty($user_avatar_url) ? $github_card_default_avatar : $user_avatar_url); ?>" alt="<?php echo esc_attr($username); ?>" />
                                <?php } else if ($is_loaded_via_js) { ?>
                                    <img src="<?php echo esc_url($github_card_default_avatar); ?>" alt="<?php echo esc_attr($username); ?>" />
                                    <?php if (github_card_data_preloader()): ?>
                                        <span class="avatar-preloader">
                                            <?php echo esc_html($github_card_data_loading_icon); ?>
                                        </span>
                                    <?php endif; ?>
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <img src="<?php echo esc_url($github_card_default_avatar); ?>" alt="Hidden Avatar" />
                        <?php } ?>
                    </div>
                </div>
            </div>



            <!-- Stats and Counts -->
            <div class="github-card-footer">
                <div class="github-card-footer-wrapper">
                    <div class="github-card-stats">
                        <!-- Contributors -->
                        <?php if ($show_contributors): ?>
                            <div class="github-card-stat repo-contributors <?php echo esc_attr($is_skeleton ? $skeleton_class : ''); ?>">
                                <i class="far fa-users"></i>
                                <div class="github-card-stat-text">
                                    <span class="count" data-repo-contributors>
                                        <?php if ($is_loaded_via_php) { ?>
                                            <?php echo !empty($contributors) || $contributors === 0 ? esc_html(github_card_contributors_plus($contributors)) : esc_html($github_card_counts_empty_placeholder); ?>
                                        <?php } else if ($is_loaded_via_js) { ?>
                                            <?php echo esc_html(github_card_data_preloader() ? $github_card_data_loading_icon : $github_card_counts_empty_placeholder); ?>
                                        <?php } ?>
                                    </span>
                                    <span class="count-label" data-repo-contributors-label>
                                        <?php echo  esc_html(github_card_pluralize(
                                            $is_loaded_via_php ? (int)$contributors : 0,
                                            'Contributor',
                                            's'
                                        )); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>


                        <!-- Issues -->
                        <?php if ($show_issues): ?>
                            <div class="github-card-stat repo-issues <?php echo esc_attr($is_skeleton ? $skeleton_class : ''); ?>">
                                <i class="far fa-dot-circle"></i>
                                <div class="github-card-stat-text">
                                    <span class="count" data-repo-issues>
                                        <?php if ($is_loaded_via_php) { ?>
                                            <?php echo !empty($open_issues) || $open_issues === 0 ? esc_html(github_card_compact_number($open_issues)) : esc_html($github_card_counts_empty_placeholder); ?>
                                        <?php } else if ($is_loaded_via_js) { ?>
                                            <?php echo esc_html(github_card_data_preloader() ? $github_card_data_loading_icon : $github_card_counts_empty_placeholder); ?>
                                        <?php } ?>
                                    </span>
                                    <span class="count-label" data-repo-issues-label>
                                        <?php echo  esc_html(github_card_pluralize(
                                            $is_loaded_via_php ? (int)$open_issues : 0,
                                            'Issue',
                                            's'
                                        )); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>


                        <!-- Stars -->
                        <?php if ($show_stars): ?>
                            <div class="github-card-stat ml-12 repo-stars <?php echo esc_attr($is_skeleton ? $skeleton_class : ''); ?>">
                                <i class="far fa-star"></i>
                                <div class="github-card-stat-text">
                                    <span class="count" data-repo-stars>
                                        <?php if ($is_loaded_via_php) { ?>
                                            <?php echo !empty($stars) || $stars === 0 ? esc_html(github_card_compact_number($stars)) : esc_html($github_card_counts_empty_placeholder); ?>
                                        <?php } else if ($is_loaded_via_js) { ?>
                                            <?php echo esc_html(github_card_data_preloader() ? $github_card_data_loading_icon : $github_card_counts_empty_placeholder); ?>
                                        <?php } ?>
                                    </span>
                                    <span class="count-label" data-repo-stars-label>
                                        <?php echo  esc_html(github_card_pluralize(
                                            $is_loaded_via_php ? (int)$stars : 0,
                                            'Star',
                                            's'
                                        )); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>


                        <!-- Forks -->
                        <?php if ($show_forks): ?>
                            <div class="github-card-stat ml-10 repo-forks <?php echo esc_attr($is_skeleton ? $skeleton_class : ''); ?>">
                                <i class="far fa-code-branch"></i>
                                <div class="github-card-stat-text">
                                    <span class="count" data-repo-forks>
                                        <?php if ($is_loaded_via_php) { ?>
                                            <?php echo !empty($forks) || $forks === 0 ? esc_html(github_card_compact_number($forks)) : esc_html($github_card_counts_empty_placeholder); ?>
                                        <?php } else if ($is_loaded_via_js) { ?>
                                            <?php echo esc_html(github_card_data_preloader() ? $github_card_data_loading_icon : $github_card_counts_empty_placeholder); ?>
                                        <?php } ?>
                                    </span>
                                    <span class="count-label" data-repo-forks-label>
                                        <?php echo  esc_html(github_card_pluralize(
                                            $is_loaded_via_php ? (int)$forks : 0,
                                            'Fork',
                                            's'
                                        )); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="github-card-badges">
                        <div class="github-card-badge circle-skeleton <?php echo esc_attr($is_skeleton ? $skeleton_class : ''); ?>">
                            <a href="<?php echo esc_url($repolink); ?>" target="_blank" rel="noopener noreferrer">
                                <i class=" fab fa-github"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <?php if (github_card_footer_ribbon()) { ?>
                    <div
                        <?php if (github_card_language_ribbon()): ?>
                            data-language="true"
                            <?php if ($is_loaded_via_php && isset($color_gradient) && !empty($color_gradient)) { ?>
                            style="background: <?php echo esc_attr($color_gradient); ?>;"
                            <?php } else if ($is_loaded_via_js) { ?>
                            data-active="true"
                            <?php } ?>
                        <?php endif; ?>
                        class="language-ribbon <?php echo esc_attr($is_skeleton ? $skeleton_class : ''); ?>">
                    </div>
                <?php } else { ?>
                    <div class="ribbon-space"></div>
                <?php } ?>
            </div>
        </div>
        <?php if ($has_changed_dimensions): ?>
            <style>
                .github-card-wrapper[data-uid="<?php echo esc_attr($uid); ?>"] .github-card {
                    <?php if (!empty($github_card_height)) { ?>
                        height: <?php echo intval($github_card_height); ?>px;
                    <?php } ?>
                    <?php if (!empty($github_card_width)) { ?>
                        width: <?php echo intval($github_card_width); ?>px;
                    <?php } ?>
                }
            </style>
        <?php endif; ?>
    </div>

<?php
    return ob_get_clean();
}


function github_card_github_card_fn_github_card_mini($atts)
{
    $repo = isset($atts['repo']) ? esc_attr($atts['repo']) : '/';
    $gh_card_mini_url = "https://gh-card.dev/repos/{$repo}.svg";
    return '<img src="' . esc_url($gh_card_mini_url) . '" alt="' . esc_attr($repo) . ' GitHub Card" />';
}


function github_card_fn_github_card($args)
{
    if (isset($args['mini']) && $args['mini'] === 'true') {
        return github_card_github_card_fn_github_card_mini($args);
    }
    return github_card_github_card_fn_github_card_template($args);
}

add_shortcode('github_card', 'github_card_fn_github_card');
