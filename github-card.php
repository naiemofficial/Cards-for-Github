<?php

/**
 * Plugin Name: Github Cards
 * Plugin URI: http://github.com/naiemofficial/Github-Cards
 * Description: Showcase GitHub repositories as social-media-style cards.
 * Version: 1.0.0
 * Author: Abdullah Al Naiem
 * Author URI: https://naiem.info
 * Text Domain: github-cards
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

define('GITHUB_CARD_PLUGIN_NAME', 'Github Card');

require_once __DIR__ . '/vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;


require_once plugin_dir_path(__FILE__) . 'index.php';
