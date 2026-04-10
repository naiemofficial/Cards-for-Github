<?php
if (! defined('ABSPATH')) {
    exit;
}



// ------------------------- START - Load with JS --------------------------------- 
if (github_card_load_with('js')) {
    global $github_card_data_loading_icon, $github_card_counts_empty_placeholder;
?>
    <script type="text/javascript">
        function handleError(data, elements = {}) {
            const error = data.data;
            const message = error && error.message ? error.message : {
                message: 'Error fetching repository data.'
            };
            const errors = message ? message.errors : message;

            const {
                descriptionElement,
                contributorsCountElement,
                issuesCountElement,
                starsCountElement,
                forksCountElement
            } = elements;

            // Show error to description
            if (descriptionElement) {
                const show_github_card_error = <?php echo github_card_error() ? 'true' : 'false'; ?>;
                if (show_github_card_error) {
                    descriptionElement.textContent = JSON.stringify(message);
                    descriptionElement.classList.add('error');
                } else {
                    descriptionElement.innerHTML = null;
                }
            }

            // Replace counts with empty placeholder
            [contributorsCountElement, issuesCountElement, starsCountElement, forksCountElement].forEach(countElement => {
                if (countElement) {
                    countElement.innerHTML = <?php echo wp_json_encode($github_card_counts_empty_placeholder); ?>;
                }
            });
        }



        // Do AJAX
        document.addEventListener('DOMContentLoaded', function() {
            // Select all GitHub Repo placeholders
            document.querySelectorAll('[data-github-repo]').forEach(repoCard => {
                const repo = repoCard.dataset.githubRepo;
                if (!repo) return;

                const wrapperPreloaderElement = repoCard.querySelector('.github-card-wrapper-preloader');

                const parameters = JSON.parse(repoCard.dataset.parameters || '{}');
                const description_words = parameters['description-words'] || -1;
                const avatar_is_url = parameters['avatar-url'] && /^https?:\/\/.+/.test(parameters['avatar-url']);
                const avatar_url = avatar_is_url ? parameters['avatar-url'] : null;


                const descriptionElement = repoCard.querySelector('p[data-repo-description]');
                const avatarElement = repoCard.querySelector('[data-repo-avatar]');
                const imgElement = avatarElement ? avatarElement.querySelector('img') : null;

                const statsElement = repoCard.querySelector('.github-card-stats');
                const contributorsCountElement = repoCard.querySelector('[data-repo-contributors]');
                const issuesCountElement = repoCard.querySelector('[data-repo-issues]');
                const starsCountElement = repoCard.querySelector('[data-repo-stars]');
                const forksCountElement = repoCard.querySelector('[data-repo-forks]');

                // Fetch GitHub stats via AJAX
                fetch(`${githubCardRepo.ajax_url}?action=fetch_github_repo_data&repo=${encodeURIComponent(repo)}&nonce=${githubCardRepo.nonce}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.data) {
                            const $repo_data = data.data;
                            const $description = github_card_get_or_null($repo_data, 'description');

                            const $user = github_card_get_or_null($repo_data, 'user');
                            const $user_avatar_url = avatar_is_url ? avatar_url : github_card_get_or_null($user, 'avatar_url');

                            const $contributors = github_card_get_or_null($repo_data, 'contributors');
                            const $issues = github_card_get_or_null($repo_data, 'all_issues');
                            const $open_issues = github_card_get_or_null($repo_data, 'open_issues');
                            const $stars = github_card_get_or_null($repo_data, 'stars');
                            const $forks = github_card_get_or_null($repo_data, 'forks');

                            $color_gradient = github_card_get_or_null($repo_data, 'color_gradient');



                            // ------------------- START - Update the repoCard with fetched data ------------------- //

                            // Description
                            if (descriptionElement && $description) {
                                let descText = $description;
                                if (description_words > 0) {
                                    const wordsArray = descText.split(' ');
                                    if (wordsArray.length > description_words) {
                                        descText = wordsArray.slice(0, description_words).join(' ') + '...';
                                    }
                                }
                                descriptionElement.textContent = descText;
                            }

                            // User Avatar
                            if (imgElement && $user_avatar_url) {
                                imgElement.src = $user_avatar_url;
                            }


                            // Contributors
                            if (contributorsCountElement) {
                                contributorsCountElement.textContent = $contributors !== null ? github_card_contributors_plus($contributors) : '<?php echo esc_js($github_card_counts_empty_placeholder); ?>';
                                const contribLabel = repoCard.querySelector('[data-repo-contributor-label]');
                                if (contribLabel) {
                                    contribLabel.textContent = github_card_pluralize($contributors, 'Contributor', 's');
                                }
                            }

                            // Issues
                            if (issuesCountElement) {
                                issuesCountElement.textContent = $open_issues !== null ? github_card_compact_number($open_issues) : '<?php echo esc_js($github_card_counts_empty_placeholder); ?>';
                                const issuesLabel = repoCard.querySelector('[data-repo-issues-label]');
                                if (issuesLabel) {
                                    issuesLabel.textContent = github_card_pluralize($open_issues, 'Issue', 's');
                                }
                            }

                            // Stars
                            if (starsCountElement) {
                                starsCountElement.textContent = $stars !== null ? github_card_compact_number($stars) : '<?php echo esc_js($github_card_counts_empty_placeholder); ?>';
                                const starsLabel = repoCard.querySelector('[data-repo-stars-label]');
                                if (starsLabel) {
                                    starsLabel.textContent = github_card_pluralize($stars, 'Star', 's');
                                }
                            }

                            // Forks
                            if (forksCountElement) {
                                forksCountElement.textContent = $forks !== null ? github_card_compact_number($forks) : '<?php echo esc_js($github_card_counts_empty_placeholder); ?>';
                                const forksLabel = repoCard.querySelector('[data-repo-forks-label]');
                                if (forksLabel) {
                                    forksLabel.textContent = github_card_pluralize($forks, 'Fork', 's');
                                }
                            }


                            const languageRibbonElement = repoCard.querySelector('.language-ribbon[data-active="true"]');
                            if (languageRibbonElement && $color_gradient) {
                                languageRibbonElement.style.background = 'linear-gradient(to right, ' + $color_gradient + ')';
                            }
                            // ------------------- END - Update the repoCard with fetched data ------------------- //

                        } else {
                            handleError(data, {
                                descriptionElement,
                                contributorsCountElement,
                                issuesCountElement,
                                starsCountElement,
                                forksCountElement
                            });
                        }
                    })
                    .catch((data) => {
                        handleError(data, {
                            descriptionElement,
                            contributorsCountElement,
                            issuesCountElement,
                            starsCountElement,
                            forksCountElement
                        });
                    })
                    .finally(() => {
                        // Remove loading state
                        // Wrapper preloader
                        if (wrapperPreloaderElement) {
                            wrapperPreloaderElement.remove();
                        }

                        // Remove avatar preloader
                        if (avatarElement) {
                            const avatar_preloader = avatarElement.querySelector('.avatar-preloader');
                            if (avatar_preloader) {
                                avatar_preloader.remove();
                            }
                        }


                        // Remove Skeletons
                        const skeletons = repoCard?.querySelectorAll('.github-card-skeleton');
                        skeletons?.forEach(el => el.classList.remove('github-card-skeleton'));


                    });
            });
        });
    </script>
<?php }
// -------------------------------- END - Load with JS --------------------------- 
?>














<?php
// --------------------------------------- START - Auto Scale -------------------------------------- 
if (github_card_auto_scale()):
$github_card_height = github_card_height();
$github_card_width = github_card_width();
?>
    <style type="text/css">
        /* Scale */
        .github-card-wrapper {
            width: 100%;
            height: auto;
            position: relative;
            overflow: hidden;

            /* Set the max width to match original 1200px design */
            max-width: <?php echo !empty($github_card_width) ? intval($github_card_width) : '1200'; ?>px;
        }

        /* SCALE the card based on available wrapper width */
        .github-card-wrapper .github-card {
            transform-origin: top left;
            width: <?php echo !empty($github_card_width) ? intval($github_card_width) : '1200'; ?>px;
            height: <?php echo !empty($github_card_height) ? intval($github_card_height) : '600'; ?>px;
        }

        @media (max-width: 1200px) {
            .github-card-wrapper .github-card {
                transform: scale(calc(min(1, 100vw / <?php echo !empty($github_card_width) ? intval($github_card_width) : '1200'; ?>px)));
                width: <?php echo !empty($github_card_width) ? intval($github_card_width) : '1200'; ?>px;
                /* keep original width for scale calculation */
                height: auto;
            }
        }
    </style>

    <script type="text/javascript">
        (function() {
            const originalWidth = Number(<?php echo !empty($github_card_width) ? intval($github_card_width) : '1200'; ?>);
            const originalHeight = Number(<?php echo !empty($github_card_height) ? intval($github_card_height) : '600'; ?>);

            function scaleCard(wrapper) {
                const card = wrapper.querySelector(".github-card");
                if (!card) return;

                const scale = wrapper.clientWidth / originalWidth;

                card.style.transformOrigin = "top left";
                card.style.transform = `scale(${scale})`;

                wrapper.style.height = `${originalHeight * scale}px`;

                card.style.visibility = "visible";
            }

            function init(wrapper) {
                // avoid double initialization
                if (wrapper.dataset.scaled) return;
                wrapper.dataset.scaled = "1";

                // scale immediately
                scaleCard(wrapper);

                // update on resize
                new ResizeObserver(() => scaleCard(wrapper)).observe(wrapper);
            }

            // Watch entire DOM
            const mo = new MutationObserver(() => {
                // console.log("MutationObserver triggered");
                document.querySelectorAll(".github-card-wrapper").forEach(init);
            });

            // Start observing the whole document
            mo.observe(document.documentElement, {
                childList: true,
                subtree: true
            });

            // Also check immediately (if already exists)
            document.querySelectorAll(".github-card-wrapper").forEach(init);
        })();
    </script>
<?php endif;
// ------------------------------- END - Auto Scale -------------------------------------
?>






















<?php
// ------------------------------- START - Styles ------------------------------
$github_card_spinner_color = github_card_preloader_spinner_color();
$github_card_preloader_background_color = github_card_preloader_background_color();
$github_card_backdrop_enabled = github_card_enable_preloader_blur();
$github_card_blur_px = github_card_preloader_blur_px();
$github_card_skeleton_primary_color = github_card_skeleton_primary_color();
$github_card_skeleton_secondary_color = github_card_skeleton_secondary_color();
?>
<style type="text/css">
    /* Wrapper Preloder Loading Icon Color */
    .github-card-wrapper-preloader {
        <?php
        if (!empty($github_card_spinner_color)) echo 'color: ' . sanitize_hex_color($github_card_spinner_color) . '!important;';
        if (!empty($github_card_preloader_background_color)) echo 'background: ' . sanitize_hex_color($github_card_preloader_background_color) . '!important;';
        ?>
        backdrop-filter: <?php echo $github_card_backdrop_enabled ? esc_attr($github_card_blur_px) : 'none!important;'; ?> -webkit-backdrop-filter: <?php echo $github_card_backdrop_enabled ? esc_attr($github_card_blur_px) : 'none!important;'; ?>
    }

    /* Counts Laoding Color */
    .github-card-stat i {
        <?php if (!empty($github_card_spinner_color)): ?>color: <?php echo sanitize_hex_color($github_card_spinner_color); ?> !important;
        <?php endif; ?>
    }



    /* Skeleton Color */
    <?php if (!empty($github_card_skeleton_primary_color) && !empty($github_card_skeleton_secondary_color)): ?>.github-card .github-card-skeleton::before {
        background: linear-gradient(to right, <?php echo sanitize_hex_color($github_card_skeleton_primary_color); ?> 8%, <?php echo sanitize_hex_color($github_card_skeleton_secondary_color); ?> 18%, <?php echo sanitize_hex_color($github_card_skeleton_primary_color); ?> 33%);
        background-size: 1000px 100%;
    }

    <?php endif; ?>

    /* Footer Ribbon Color */
    <?php if (!empty(github_card_footer_ribbon_color())) { ?>.github-card .github-card-footer .language-ribbon {
        background: <?php echo sanitize_hex_color(github_card_footer_ribbon_color()); ?>;
    }

    <?php } ?>
</style>
<?php /*------------------------------- END - Styles ------------------------------------- */ ?>