<?php
if (! defined('ABSPATH')) {
    exit;
}

// Core Functions
require_once plugin_dir_path(__FILE__) . '/functions.php';
require_once plugin_dir_path(__FILE__) . '/includes/settings-frontend.php';

require_once plugin_dir_path(__FILE__) . '/cache/index.php';
require_once plugin_dir_path(__FILE__) . '/shortcodes/index.php';



// Enque Scripts and Styles
require_once plugin_dir_path(__FILE__) . '/localize.php';






// Admin Page
require_once plugin_dir_path(__FILE__) . '/admin/index.php';

// Ajax
require_once plugin_dir_path(__FILE__) . '/includes/ajax.php';