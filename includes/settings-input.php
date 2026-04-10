<?php
if (! defined('ABSPATH')) {
    exit;
}

// Default values for GitHub Card plugin
return [
    'github_card_load_with'        => [
        'type' => 'radio',
        'label' => 'Load With',
        'values' => [
            'php' => 'PHP',
            'js' => 'Javascript',
        ],
        'default' => 'js',
        'groups' => ['card-settings'],
    ],

    'github_card_wrapper_preloader' => [
        'type' => 'checkbox',
        'label' => 'Wrapper Preloader',
        'default' => 'on',
        'groups' => ['card-settings'],
    ],

    'github_card_preloader_type'   => [
        'type' => 'radio',
        'label' => 'Preloader Type',
        'values' => ['spinner', 'skeleton'],
        'default' => 'skeleton',
        'groups' => ['card-settings'],
    ],

    'github_card_data_preloader' => [
        'type' => 'checkbox',
        'label' => 'Repo Data Preloader',
        'default' => 'off',
        'groups' => ['card-settings'],
    ],

    'github_card_height'       => [
        'type' => 'number',
        'label' => 'Height',
        'placeholder' => 'Pixels',
        'default' => 600,
        'groups' => ['card-settings'],
    ],

    'github_card_width'       => [
        'type' => 'number',
        'label' => 'Width',
        'placeholder' => 'Pixels',
        'default' => 1200,
        'groups' => ['card-settings'],
    ],

    'github_card_auto_scale'       => [
        'type' => 'checkbox',
        'label' => 'Enable Auto Scale',
        'description' => "The card will scaled based on parent element width <br><small>(Default size 1200px/600px or the aspect ratio is 12/6)</small>",
        'default' => 'on',
        'groups' => ['card-settings'],
    ],

    'github_card_spinner'        => [
        'type' => 'radio',
        'label' => 'Spinner Style',
        'values' => [
            'fa-spinner',
            'fa-circle-notch',
            'fa-sync-alt',
            'fa-cog',
        ],
        'default' => 'fa-circle-notch',
        'groups' => ['card-settings'],
    ],


    'github_card_footer_ribbon'    => [
        'type' => 'checkbox',
        'label' => 'Footer Ribbon',
        'default' => 'on',
        'groups' => ['ribbon-settings'],
    ],


    'github_card_language_ribbon'    => [
        'type' => 'checkbox',
        'label' => 'Color Ribbon by Language(s)',
        'default' => 'on',
        'groups' => ['ribbon-settings'],
    ],


    'github_card_error'    => [
        'type' => 'checkbox',
        'label' => 'Show/Hide error',
        'description' => 'An error message will be visible to card desciption if failed to retrive data or any cause for any other reason',
        'default' => 'on',
        'groups' => ['card-settings'],
    ],


    'github_card_preloader_spinner_color' => [
        'type' => 'color',
        'label' => 'Spinner Color',
        'description' => 'Applies on wrapper preloader and loading icon color',
        'default' => '#8492AF',
        'groups' => ['color-settings'],
    ],

    'github_card_preloader_background_color' => [
        'type' => 'color',
        'label' => 'Background Color',
        'description' => 'Applies on wrapper preloader <br><small>To see backdrop blur effect, use alpha color/value</small>',
        'default' => '#ffffff42',
        'alpha' => true,
        'groups' => ['color-settings'],
    ],

    'github_card_enable_preloader_blur' => [
        'type' => 'checkbox',
        'label' => 'Enable Blur Effect',
        'description' => 'Wrapper background should have alpha value to apply this',
        'default' => 'on',
        'groups' => ['color-settings'],
    ],

    'github_card_preloader_blur_px' => [
        'type' => 'number',
        'label' => 'Blur Amount (px)',
        'placeholder' => 'Pixels',
        'default' => 15,
        'groups' => ['color-settings'],
    ],


    'github_card_skeleton_primary_color' => [
        'type' => 'color',
        'label' => 'Primary Color',
        'default' => '#dfe3eb',
        'groups' => ['card-settings'],
    ],

    'github_card_skeleton_secondary_color' => [
        'type' => 'color',
        'label' => 'Secondary Color',
        'description' => 'Wave effect <small>(Should be slightly different than primary color)</small>',
        'default' => '#eceff6',
        'groups' => ['card-settings'],
    ],


    'github_card_footer_ribbon_color' => [
        'type' => 'color',
        'label' => 'Footer Ribbon Color',
        'default' => '#8492AF',
        'groups' => ['ribbon-settings'],
    ],




    'github_card_fontawesome_support'    => [
        'type' => 'radio',
        'label' => 'Fontawesome Support',
        'version' => '7.1.0',
        'description' => '<small>Manage FontAwesome loading to avoid conflicts with existing FontAwesome from theme or other plugins</small>',
        'descriptions' => [
            'disable' => GITHUB_CARD_PLUGIN_NAME . ' will not load FontAwesome, but the card will use existing FontAwesome from the theme or other plugins',
            'enable' => 'Plugin will load FontAwesome from plugin itself',
            'replace' => 'Plugin will replace existing FontAwesome with its own FontAwesome',
        ],
        'values' => [
            'disable' => 'Disable', // Plugin will not load FontAwesome, but the card will use existing FontAwesome from the theme or other plugins
            'enable' => 'Enable',   // Plugin will load FontAwesome from plugin itself
            'replace' => 'Replace', // Plugin will replace existing FontAwesome with its own FontAwesome
        ],
        'default' => 'enable',
        'groups' => ['other-settings'],
    ],


    'github_card_cache_enabled'    => [
        'type' => 'checkbox',
        'label' => 'Cache',
        'default' => 'on',
        'groups' => ['cache-settings'],
    ],
    'github_card_cache_duration'   => [
        'type' => 'number',
        'label' => 'Cache Duration (seconds)',
        'placeholder' => 'Seconds',
        'default' => 3600,
        'groups' => ['cache-settings'],
    ],
    'github_card_clear_cache'      => [
        'type' => 'button',
        'default' => '',
        'label' => 'Clear Cache',
        'description' => 'Click to clear all cached GitHub data',
        'action' => 'github_card_clear_cache',
        'icons' => [
            'default' => 'fa-trash-alt',
            'loading' => 'fa-spinner fa-spin',
            'success' => 'fa-check',
            'error' => 'fa-exclamation-triangle',
        ],
        'text' => 'Clear Cache',
        'groups' => ['cache-settings'],
    ],
];
