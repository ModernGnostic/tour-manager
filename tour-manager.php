<?php
/**
 * Plugin Name: Tour Manager
 * Description: Manage tour packages with categories, custom fields, and a frontend listing shortcode.
 * Version: 1.0.0
 * Author: Ayangbola Samuel
 * License: GPL-2.0-or-later
 * Text Domain: tour-manager
 * Domain Path: /languages
 */
if (!defined('ABSPATH')) exit;

define('TM_VER', '1.0.0');

/** i18n */
add_action('plugins_loaded', function () {
    load_plugin_textdomain('tour-manager', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

/** Includes */
require_once __DIR__ . '/includes/cpt-tax.php';
require_once __DIR__ . '/includes/meta-boxes.php';
require_once __DIR__ . '/includes/admin-columns.php';
require_once __DIR__ . '/includes/shortcode.php';

/** Assets */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('tm-style', plugins_url('css/style.css', __FILE__), [], TM_VER);
});

/** Uninstall is handled in uninstall.php */
