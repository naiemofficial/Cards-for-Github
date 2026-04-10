<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;


/**
 * =====================================================
 * SANITIZATION FUNCTIONS
 * =====================================================
 */

// Checkbox sanitize
function github_card_sanitize_checkbox($value)
{
    return ($value === 'on') ? 'on' : '';
}

// Text sanitize
function github_card_sanitize_text($value)
{
    return sanitize_text_field($value);
}

// Integer sanitize
function github_card_sanitize_int($value)
{
    return isset($value) ? absint($value) : 0;
}

// Hex color sanitize
function github_card_sanitize_color($value)
{
    return sanitize_hex_color($value);
}

// Preloader type whitelist
function github_card_sanitize_preloader_type($value)
{
    $allowed = ['spinner', 'skeleton', 'none'];
    return in_array($value, $allowed, true) ? $value : 'spinner';
}


/**
 * =====================================================
 * REGISTER SETTINGS (FIXED FOR PLUGIN CHECK)
 * =====================================================
 */
function github_card_register_settings()
{

    register_setting('github_card_settings_group', 'github_card_load_with', [
        'sanitize_callback' => 'github_card_sanitize_text',
    ]);

    register_setting('github_card_settings_group', 'github_card_preloader_type', [
        'sanitize_callback' => 'github_card_sanitize_preloader_type',
    ]);

    register_setting('github_card_settings_group', 'github_card_wrapper_preloader', [
        'sanitize_callback' => 'github_card_sanitize_checkbox',
    ]);

    register_setting('github_card_settings_group', 'github_card_data_preloader', [
        'sanitize_callback' => 'github_card_sanitize_checkbox',
    ]);

    register_setting('github_card_settings_group', 'github_card_auto_scale', [
        'sanitize_callback' => 'github_card_sanitize_checkbox',
    ]);

    register_setting('github_card_settings_group', 'github_card_cache_enabled', [
        'sanitize_callback' => 'github_card_sanitize_checkbox',
    ]);

    register_setting('github_card_settings_group', 'github_card_cache_duration', [
        'sanitize_callback' => 'github_card_sanitize_int',
    ]);

    register_setting('github_card_settings_group', 'github_card_width', [
        'sanitize_callback' => 'github_card_sanitize_int',
    ]);

    register_setting('github_card_settings_group', 'github_card_height', [
        'sanitize_callback' => 'github_card_sanitize_int',
    ]);

    register_setting('github_card_settings_group', 'github_card_preloader_spinner_color', [
        'sanitize_callback' => 'github_card_sanitize_color',
    ]);

    register_setting('github_card_settings_group', 'github_card_preloader_background_color', [
        'sanitize_callback' => 'github_card_sanitize_color',
    ]);

    register_setting('github_card_settings_group', 'github_card_enable_preloader_blur', [
        'sanitize_callback' => 'github_card_sanitize_checkbox',
    ]);

    register_setting('github_card_settings_group', 'github_card_preloader_blur_px', [
        'sanitize_callback' => 'github_card_sanitize_int',
    ]);

    register_setting('github_card_settings_group', 'github_card_skeleton_primary_color', [
        'sanitize_callback' => 'github_card_sanitize_color',
    ]);

    register_setting('github_card_settings_group', 'github_card_skeleton_secondary_color', [
        'sanitize_callback' => 'github_card_sanitize_color',
    ]);

    register_setting('github_card_settings_group', 'github_card_footer_ribbon', [
        'sanitize_callback' => 'github_card_sanitize_checkbox',
    ]);

    register_setting('github_card_settings_group', 'github_card_language_ribbon', [
        'sanitize_callback' => 'github_card_sanitize_checkbox',
    ]);

    register_setting('github_card_settings_group', 'github_card_footer_ribbon_color', [
        'sanitize_callback' => 'github_card_sanitize_color',
    ]);

    register_setting('github_card_settings_group', 'github_card_fontawesome_support', [
        'sanitize_callback' => 'github_card_sanitize_text',
    ]);

    register_setting('github_card_settings_group', 'github_card_cache_enabled', [
        'sanitize_callback' => 'github_card_sanitize_checkbox',
    ]);
}
add_action('admin_init', 'github_card_register_settings');


/**
 * =====================================================
 * ADMIN BODY CLASS
 * =====================================================
 */
function github_card_admin_body_class($classes)
{
    $screen = get_current_screen();
    if ($screen && $screen->id === 'toplevel_page_github-card-settings') {
        $classes .= ' github-card-admin-page';
    }
    return $classes;
}
add_filter('admin_body_class', 'github_card_admin_body_class');


/**
 * =====================================================
 * MENU PAGE
 * =====================================================
 */
function github_card_add_menu_page()
{
    $icon_path = plugin_dir_path(__DIR__) . 'admin/assets/icons/square-github.svg';

    $svg = '';
    if (file_exists($icon_path)) {
        $svg = file_get_contents($icon_path);
    }

    $icon_url = 'data:image/svg+xml;base64,' . base64_encode($svg);

    add_menu_page(
        'Github Card',
        'Github Card',
        'manage_options',
        'github-card-settings',
        'github_card_render_admin_page',
        $icon_url,
        56
    );
}
add_action('admin_menu', 'github_card_add_menu_page');



/**
 * Render admin page
 */
function github_card_render_admin_page()
{
    global $github_card_all_input_settings, $github_card_defaults;
?>

    <div class="github-card-admin wrap max-w-4xl mx-auto py-8 animate-fade-in">

        <h1 class="github-card-admin-heading">Github Card Settings</h1>
        <div class="github-card-banner-wrapper">
            <img src="<?php echo esc_url(plugin_dir_url(__DIR__)); ?>admin/assets/banners/github-card.svg" alt="Github Card Banner" class="admin-settings-banner" />
        </div>
        <form method="post" action="options.php" class="space-y-10">
            <?php settings_fields('github_card_settings_group'); ?>

            <!-- SECTION: Card Settings -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-6">
                <h2 class=" text-xl font-semibold border-b pb-2">Card Settings</h2>

                <!-- Load With -->
                <?php
                $key = 'github_card_load_with';
                $input = $github_card_all_input_settings[$key];
                $label = $input['label'];
                $load_with = github_card_load_with();
                $values = $input['values'];
                ?>
                <div class="p-4 rounded-lg border border-white flex items-center justify-between animate-fade-in-up">
                    <label class="font-medium"><?php echo esc_html($label); ?></label>
                    <div class="flex gap-6">
                        <?php foreach ($values as $value_key => $text) { ?>
                            <label class="flex items-center gap-2 cursor-pointer animate-fade-in">
                                <input
                                    type="radio"
                                    name="<?php echo esc_attr($key); ?>"
                                    value="<?php echo esc_attr($value_key); ?>"
                                    class="h-4 w-4 text-[#141414] border-[#141414] checked:bg-[#141414] checked:border-[#141414]"
                                    <?php checked($load_with, $value_key); ?> />
                                <span><?php echo esc_html($text); ?></span>
                            </label>
                        <?php } ?>
                    </div>
                </div>

                <!-- Conditional Section -->
                <div class="space-y-6 animate-fade-in-up">
                    <div class="space-y-6" data-condition="js">
                        <div class="flex flex-col space-y-6 bg-gray-50 p-4 rounded-lg border border-gray-200" data-condition="data-preloader-off">
                            <!-- Wrapper Preloader  -->
                            <?php
                            $key = 'github_card_wrapper_preloader';
                            $input = $github_card_all_input_settings[$key];
                            $label = $input['label'];
                            $value = github_card_wrapper_preloader(other_input_dependency: false);
                            ?>
                            <div class="flex items-center justify-between animate-fade-in-up">
                                <label class="font-medium"><?php echo esc_html($label); ?></label>
                                <label class="relative inline-flex items-center cursor-pointe checkbox-label">
                                    <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" class="sr-only peer" <?php checked($value); ?> />
                                    <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300 peer-checked"></div>
                                    <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                                </label>
                            </div>



                            <!-- Preloader Type -->
                            <?php
                            $key = 'github_card_preloader_type';
                            $input = $github_card_all_input_settings[$key];
                            $label = $input['label'];
                            $preloader_type = github_card_preloader_type(other_input_dependency: false);
                            $values = $input['values'];
                            ?>
                            <div class="animate-fade-in-up flex items-center justify-between animate-scale-up" data-condition="wrapper-preloader-on">
                                <label class="block font-medium mb-2"><?php echo esc_html($label); ?></label>
                                <select
                                    name="github_card_preloader_type"
                                    class="border rounded-lg px-3 py-2 w-48 focus:ring-[#141414] focus:border-[#141414] text-xs">
                                    <?php foreach ($values as $value) { ?>
                                        <option value="<?php echo esc_attr($value); ?>" <?php selected($preloader_type, $value); ?>>
                                            <?php echo esc_html(ucfirst($value)); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>


                        <div class="flex flex-col space-y-6 bg-gray-50 p-4 rounded-lg border border-gray-200" data-condition="wrapper-preloader-off">
                            <!-- Data Preloader  -->
                            <?php
                            $key = 'github_card_data_preloader';
                            $input = $github_card_all_input_settings[$key];
                            $label = $input['label'];
                            $value = github_card_data_preloader(other_input_dependency: false);
                            ?>
                            <div class="flex items-center justify-between animate-fade-in-up">
                                <label class="font-medium"><?php echo esc_html($label); ?></label>
                                <label class="relative inline-flex items-center cursor-pointe checkbox-label">
                                    <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" class="sr-only peer" <?php checked($value); ?> />
                                    <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300 peer-checked"></div>
                                    <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Width X Height -->
                <div class="flex flex-col space-y-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between animate-fade-in-up">
                        <div>
                            <label class="font-medium">Card Dimensions</label>
                        </div>
                        <div class="inline-flex flex-row items-center gap-4">
                            <?php
                            $key = 'github_card_width';
                            $input = $github_card_all_input_settings[$key];
                            $label = $input['label'];
                            $placeholder = $input['placeholder'];
                            $value = github_card_width(other_input_dependency: false);
                            ?>
                            <div class="inline-flex flex-col items-center gap-1">
                                <label class="mr-2 font-medium"><?php echo esc_html($label); ?></label>
                                <input type="number" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>" min="200" placeholder="<?php echo esc_attr($placeholder); ?>" class="border rounded-lg px-3 py-2 w-24 focus:ring-[#141414] focus:border-[#141414]" />
                            </div>

                            x

                            <?php
                            $key = 'github_card_height';
                            $input = $github_card_all_input_settings[$key];
                            $label = $input['label'];
                            $placeholder = $input['placeholder'];
                            $value = github_card_height(other_input_dependency: false);
                            ?>
                            <div class="inline-flex flex-col items-center gap-1">
                                <label class="mr-2 font-medium"><?php echo esc_html($label); ?></label>
                                <input type="number" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>" min="100" placeholder="<?php echo esc_attr($placeholder); ?>" class="border rounded-lg px-3 py-2 w-24 focus:ring-[#141414] focus:border-[#141414]" />
                            </div>
                        </div>
                    </div>





                    <!-- Auto Scale -->
                    <?php
                    $key = 'github_card_auto_scale';
                    $input = $github_card_all_input_settings[$key];
                    $label = $input['label'];
                    $description = $input['description'];
                    $value = github_card_auto_scale(other_input_dependency: false);
                    ?>
                    <div class="flex items-center justify-between animate-fade-in-up">
                        <div>
                            <label class="font-medium"><?php echo esc_html($label); ?></label>
                            <p><?php echo esc_html($description); ?></p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer checkbox-label m-0">
                            <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" class="sr-only peer" <?php checked($value); ?> />
                            <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300 peer-checked"></div>
                            <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                        </label>
                    </div>
                </div>



                <!-- Spinner -->
                <?php
                $key = 'github_card_spinner';
                $input = $github_card_all_input_settings[$key];
                $label = $input['label'];
                $values = $input['values'];
                $spinner = github_card_spinner(other_input_dependency: false);
                ?>
                <div class="github_card_spinner p-4 rounded-lg border border-white flex items-center justify-between animate-fade-in-up">
                    <label class="font-medium"><?php echo esc_html($label); ?></label>
                    <div class="flex gap-6 options">
                        <?php foreach ($values as $value) { ?>
                            <label class="flex items-center gap-2 cursor-pointer animate-fade-in">
                                <input
                                    type="radio"
                                    name="<?php echo esc_attr($key); ?>"
                                    value="<?php echo esc_attr($value); ?>"
                                    <?php checked($spinner, $value); ?>>
                                <span class="spinner-selection">
                                    <i class="fa-solid <?php echo esc_attr($value); ?> fa-spin"></i>
                                </span>
                            </label>
                        <?php } ?>
                    </div>
                </div>

                <div class="flex flex-col space-y-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <!-- Footer Ribbon  -->
                    <?php
                    $key = 'github_card_footer_ribbon';
                    $input = $github_card_all_input_settings[$key];
                    $label = $input['label'];
                    $value = github_card_footer_ribbon(other_input_dependency: false);
                    ?>
                    <div class="flex items-center justify-between animate-fade-in-up">
                        <label class="font-medium"><?php echo esc_html($label); ?></label>
                        <label class="relative inline-flex items-center cursor-pointe checkbox-label">
                            <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" class="sr-only peer" <?php checked($value); ?> />
                            <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300 peer-checked"></div>
                            <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                        </label>
                    </div>


                    <!-- Language Ribbon  -->
                    <?php
                    $key = 'github_card_language_ribbon';
                    $input = $github_card_all_input_settings[$key];
                    $label = $input['label'];
                    $value = github_card_language_ribbon(other_input_dependency: false);
                    ?>
                    <div class="flex items-center justify-between animate-fade-in-up" data-condition="data-footer-ribbon-on">
                        <label class="font-medium"><?php echo esc_html($label); ?></label>
                        <label class="relative inline-flex items-center cursor-pointe checkbox-label">
                            <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" class="sr-only peer" <?php checked($value); ?> />
                            <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300 peer-checked"></div>
                            <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                        </label>
                    </div>
                </div>



                <!-- Error -->
                <?php
                $key = 'github_card_error';
                $input = $github_card_all_input_settings[$key];
                $label = $input['label'];
                $description = $input['description'];
                $value = github_card_error(other_input_dependency: false);
                ?>
                <div class="p-4 rounded-lg border border-white flex items-center gap-1 justify-between animate-fade-in-up">
                    <div class="flex flex-col">
                        <div class="flex flex-row items-center gap-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            <label class=" font-medium"><?php echo esc_html($label); ?></label>
                        </div>
                        <p class="text-small"><?php echo esc_html($description); ?></p>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer checkbox-label m-0">
                        <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" class="sr-only peer" <?php checked($value); ?> />
                        <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300 peer-checked"></div>
                        <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                    </label>
                </div>
            </div>

            <!-- SECTION: Color Settings -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-6">
                <h2 class=" text-xl font-semibold border-b pb-2">Color Settings</h2>

                <div class="flex flex-col space-y-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <!-- Spinner Color  -->
                    <?php
                    $key = 'github_card_preloader_spinner_color';
                    $input = $github_card_all_input_settings[$key];
                    $label = $input['label'];
                    $description = $input['description'];
                    $value = github_card_preloader_spinner_color(other_input_dependency: false);
                    ?>
                    <div class="flex items-center justify-between animate-fade-in-up">
                        <div class="flex flex-col">
                            <label class="font-medium"><?php echo esc_html($label); ?></label>
                            <?php if (!empty($description)): ?>
                                <p class="text-small"><?php echo esc_html($description); ?></p>
                            <?php endif; ?>
                        </div>
                        <input type="text" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>" class="github-card-color-field" />
                    </div>

                    <div class="flex items-center animate-fade-in-up gap-6">
                        <div>
                            <label class="font-medium">Spinner</label>
                            <p><small>(Wrapper Preloader)</small></p>
                        </div>
                        <div class="flex flex-col items-end gap-4 flex-1 pl-5" style="border-left: 3px dashed #e5e7eb;">
                            <!-- Wrapper Preloader Background  -->
                            <?php
                            $key = 'github_card_preloader_background_color';
                            $input = $github_card_all_input_settings[$key];
                            $label = $input['label'];
                            $description = $input['description'];
                            $value = github_card_preloader_spinner_color(other_input_dependency: false);
                            ?>
                            <div class="flex items-center justify-between animate-fade-in-up w-full">
                                <div class="flex flex-col">
                                    <label class="font-medium"><?php echo esc_html($label); ?></label>
                                    <?php if (!empty($description)): ?>
                                        <p class="text-small"><?php echo esc_html($description); ?></p>
                                    <?php endif; ?>
                                </div>
                                <input type="text" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>" class="github-card-color-field" />
                            </div>


                            <!-- Preloader Blur -->
                            <?php
                            $key_enable_preloader_blur = 'github_card_enable_preloader_blur';
                            $input_enable_preloader_blur = $github_card_all_input_settings[$key_enable_preloader_blur];
                            $label_enable_preloader_blur = $input_enable_preloader_blur['label'];
                            $description_enable_preloader_blur = $input_enable_preloader_blur['description'];
                            $value_enable_preloader_blur = github_card_enable_preloader_blur(other_input_dependency: false);

                            $key_preloader_blur_px = 'github_card_preloader_blur_px';
                            $value_preloader_blur_px = github_card_preloader_blur_px(other_input_dependency: false);
                            ?>
                            <div class="rounded-lg flex items-center justify-between animate-fade-in-up w-full" data-condition="preloader-type-spinner">
                                <div>
                                    <div>
                                        <label class="font-medium"><?php echo esc_html($label_enable_preloader_blur); ?></label>
                                        <?php if (!empty($description_enable_preloader_blur)): ?>
                                            <p class="text-small"><?php echo esc_html($description_enable_preloader_blur); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="inline-flex items-center gap-4">
                                    <div data-condition="enable-preloader-blur-on" class="flex items-center" data-condition="enable-preloader-blur-on">
                                        <input type="number" name="<?php echo esc_attr($key_preloader_blur_px); ?>" value="<?php echo esc_attr($value_preloader_blur_px); ?>" min="0" placeholder="Pixels" class="border rounded-lg px-3 py-2 w-16 ml-4 focus:ring-[#141414] focus:border-[#141414]" />
                                    </div>
                                    <div class="min-h-32px inline-flex items-center">
                                        <label class="relative inline-flex items-center cursor-pointer checkbox-label">
                                            <input type="checkbox" name="<?php echo esc_attr($key_enable_preloader_blur); ?>" value="on" class="sr-only peer" <?php checked($value_enable_preloader_blur); ?> />
                                            <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300 peer-checked"></div>
                                            <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col space-y-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <!-- Skeleton Color  -->
                    <div class="flex items-center animate-fade-in-up gap-6" data-condition="preloader-type-skeleton">
                        <label class="font-medium">Skeleton</label>
                        <div class="flex flex-col items-end gap-4 flex-1 pl-5" style="border-left: 3px dashed #e5e7eb;">
                            <?php
                            $key_primary = 'github_card_skeleton_primary_color';
                            $input_primary = $github_card_all_input_settings[$key_primary];
                            $label_primary = $input_primary['label'];
                            $value_primary = github_card_skeleton_primary_color(other_input_dependency: false);

                            $key_secondary = 'github_card_skeleton_secondary_color';
                            $input_secondary = $github_card_all_input_settings[$key_secondary];
                            $label_secondary = $input_secondary['label'];
                            $description_secondary = $input_secondary['description'];
                            $value_secondary = github_card_skeleton_secondary_color(other_input_dependency: false);
                            ?>
                            <div class="flex flex-row items-center gap-4 justify-between flex-1 w-full">
                                <label class="mb-2 font-medium"><?php echo esc_html($label_primary); ?></label>
                                <input type="text" name="<?php echo esc_attr($key_primary); ?>" value="<?php echo esc_attr($value_primary); ?>" class="github-card-color-field" />
                            </div>
                            <div class="flex flex-row items-center gap-4 justify-between flex-1 w-full">
                                <div>
                                    <label class="mb-2 font-medium"><?php echo esc_html($label_secondary); ?></label>
                                    <?php if (!empty($description_secondary)): ?>
                                        <p class="text-small"><?php echo esc_html($description_secondary); ?></p>
                                    <?php endif; ?>
                                </div>
                                <input type="text" name="<?php echo esc_attr($key_secondary); ?>" value="<?php echo esc_attr($value_secondary); ?>" class="github-card-color-field" />
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Footer Ribbon Color -->
                <?php
                $key = 'github_card_footer_ribbon_color';
                $input = $github_card_all_input_settings[$key];
                $label = $input['label'];
                $value = github_card_footer_ribbon_color(other_input_dependency: false);
                ?>
                <div class="p-4 rounded-lg border border-white flex items-center justify-between animate-fade-in-up" data-condition="footer-ribbon-on">
                    <label class="font-medium"><?php echo esc_html($label); ?></label>
                    <input type="text" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>" class="github-card-color-field" />
                </div>
            </div>



            <!-- Other Settings -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-6">
                <h2 class="text-xl font-semibold border-b pb-2">Other Settings</h2>
                <?php
                $key = 'github_card_fontawesome_support';
                $input = $github_card_all_input_settings[$key];
                $label = $input['label'];
                $version = $input['version'];
                $description = $input['description'];
                $descriptions = $input['descriptions'];
                $load_with = github_card_fontawesome_support();
                $values = $input['values'];
                ?>
                <div class="p-4 rounded-lg border border-white flex items-center justify-between animate-fade-in-up gap-4">
                    <div class="pr-5">
                        <div>
                            <label class="font-medium"><?php echo esc_html($label); ?></label>
                            <span class="ml-1 inline-block bg-gray-200 text-gray-800 text-xs font-semibold px-2 py-1 rounded"><?php echo esc_html($version); ?></span>
                        </div>
                        <p><?php echo esc_html($description); ?></p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <div class="flex gap-6">
                            <?php foreach ($values as $value_key => $text) { ?>
                                <label class="flex items-center gap-2 cursor-pointer animate-fade-in tooltip-wrapper">
                                    <tooltip class="github-card-tooltip" data-tooltip="<?php echo esc_attr($descriptions[$value_key]); ?>"></tooltip>
                                    <input
                                        type="radio"
                                        name="<?php echo esc_attr($key); ?>"
                                        value="<?php echo esc_attr($value_key); ?>"
                                        class="h-4 w-4 text-[#141414] border-[#141414] checked:bg-[#141414] checked:border-[#141414]"
                                        <?php checked($load_with, $value_key); ?> />
                                    <span><?php echo esc_html($text); ?></span>
                                </label>
                            <?php } ?>
                        </div>
                        <style>
                            .github-card-tooltip {
                                --tooltip-bg-color: #141414;
                                --tooltip-text-color: #ffffff;
                                --tooltip-padding: 6px 10px;
                                --tooltip-font-size: 12px;
                                --tooltip-border-radius: 4px;
                                --tooltip-arrow-size: 6px;
                            }
                        </style>
                    </div>
                </div>
            </div>



            <!-- Cache Section -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-6">
                <h2 class="text-xl font-semibold border-b pb-2">Cache Settings</h2>

                <?php
                $key = 'github_card_cache_enabled';
                $input = $github_card_all_input_settings[$key];
                $label = $input['label'];
                $cache_enabled = github_card_cache_enabled(other_input_dependency: false);
                ?>
                <div class="flex items-center justify-between animate-fade-in">
                    <label class="font-medium"><?php echo esc_html($label); ?></label>
                    <label class="relative inline-flex items-center cursor-pointer checkbox-label">
                        <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" class="sr-only peer" <?php checked($cache_enabled); ?> />
                        <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300 peer-checked"></div>
                        <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                    </label>
                </div>

                <?php
                $key = 'github_card_cache_duration';
                $input = $github_card_all_input_settings[$key];
                $label = $input['label'];
                $placeholder = $input['placeholder'] ?? '';
                $cache_duration = github_card_cache_duration(other_input_dependency: false);
                ?>
                <div class="flex items-center justify-between animate-fade-in" data-condition="cache-enabled-on">
                    <label class="block font-medium mb-2"><?php echo esc_html($label); ?></label>
                    <input type="number" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($cache_duration); ?>" min="1" placeholder="<?php echo esc_attr($placeholder); ?>" class="border rounded-lg px-3 py-2 w-32 focus:ring-[#141414] focus:border-[#141414]">
                </div>


                <?php
                $key = 'github_card_clear_cache';
                $input = $github_card_all_input_settings[$key];
                $type = $input['type'];
                $label = $input['label'];
                $description = $input['description'];
                $action = $input['action'];
                $icons = $input['icons']; // default, loading, success, error
                $text = $input['text'];
                ?>
                <div class="flex items-center justify-between animate-fade-in" data-condition="cache-enabled-on">
                    <div>
                        <label class="font-medium"><?php echo esc_html($label); ?></label>
                        <p class="text-small"><?php echo esc_html($description); ?></p>
                    </div>
                    <div>
                        <button
                            type="button"
                            id="<?php echo esc_attr($key); ?>"
                            class="border border-gray-300 rounded-lg px-4 py-2 hover:bg-gray-100 github-card-admin-button clear-github-card-cache"
                            data-action="<?php echo esc_attr($action); ?>"
                            data-loading="<?php esc_attr_e('Clearing...', 'cards-for-github'); ?>"
                            data-success="<?php esc_attr_e('Cleared!', 'cards-for-github'); ?>">
                            <i class="fa-solid <?php echo esc_attr($icons['default']); ?> gc-icon-default"></i>
                            <i class="fa-solid <?php echo esc_attr($icons['loading']); ?> gc-icon-loading" style="display:none;"></i>
                            <i class="fa-solid <?php echo esc_attr($icons['success']); ?> gc-icon-success" style="display:none; color:#2ecc71;"></i>
                            <i class="fa-solid <?php echo esc_attr($icons['error']); ?> gc-icon-error" style="display:none; color:#e74c3c;"></i>
                            <span class="gc-btn-text"><?php echo esc_html($text); ?></span>
                        </button>
                    </div>
                </div>
            </div>



            <div class="github-card-admin-settings submit-button-wrapper flex space-x-4 animate-fade-in-up justify-between">
                <button
                    type="button" id="github_card_save_settings"
                    class="github-card-admin-button save-github-card-settings"
                    data-loading="<?php esc_attr_e('Saving...', 'cards-for-github'); ?>"
                    data-success="<?php esc_attr_e('Saved!', 'cards-for-github'); ?>">
                    <i class="fa-solid fa-floppy-disk gc-icon-default"></i>
                    <i class="fa-solid fa-spinner fa-spin gc-icon-loading" style="display:none;"></i>
                    <i class="fa-solid fa-circle-check gc-icon-success" style="display:none; color:#2ecc71;"></i>
                    <span class="gc-btn-text">Save Settings</span>
                </button>

                <div class="reset-button-wrapper flex items-center space-x-2">

                    <label class="relative inline-flex items-center cursor-pointer checkbox-label" style="transform: scale(0.8)">
                        <input type="checkbox" id="gc-reset-confirm-checkbox" class="sr-only peer" />
                        <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300" bis_skin_checked="1"></div>
                        <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                    </label>
                    <button type="reset" id="github_card_reset_settings"
                        class="github-card-admin-button reset-github-card-settings"
                        data-loading="<?php esc_attr_e('Resetting...', 'cards-for-github'); ?>"
                        data-success="<?php esc_attr_e('Reset!', 'cards-for-github'); ?>"
                        disabled>
                        <i class="fa-solid fa-rotate-left gc-icon-default"></i>
                        <i class="fa-solid fa-spinner fa-spin gc-icon-loading" style="display:none;"></i>
                        <i class="fa-solid fa-circle-check gc-icon-success" style="display:none; color:#2ecc71;"></i>
                        <span class="gc-btn-text">Reset</span>
                    </button>
                </div>
            </div>

        </form>
    </div>

<?php }
