<?php

/**
 * Plugin Name: Cards for Github
 * Plugin URI: https://github.com/naiemofficial/Cards-for-Github
 * Description: Showcase Github repositories as social-media-style cards.
 * Version: 1.0.0
 * Author: Abdullah Al Naiem
 * Author URI: https://naiem.info
 * Text Domain: cards-for-github
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (! defined('ABSPATH')) {
    exit;
}

define('GITHUB_CARD_PLUGIN_NAME', 'Cards for Github');

require_once __DIR__ . '/vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;


require_once plugin_dir_path(__FILE__) . 'index.php';
