<?php
/**
 * PHPUnit bootstrap file
 *
 * @since   1.1.0
 * @package Dashboard-Changelog
 */

// Composer autoloader must be loaded before WP_PHPUNIT__DIR will be available
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

/**
 * Bootstrap WordPress
 */
defined( 'ABSPATH' ) or define( 'ABSPATH', __DIR__ . '/../wordpress/' );
defined( 'WPINC' ) or define( 'WPINC', 'wp-includes' );
defined( 'WP_PLUGIN_DIR' ) or define( 'WP_PLUGIN_DIR', '/../../' );
defined( 'WPMU_PLUGIN_DIR' ) or define( 'WPMU_PLUGIN_DIR', '/../../' );
require_once ABSPATH . WPINC . '/default-constants.php';
require_once ABSPATH . WPINC . '/formatting.php';
require_once ABSPATH . WPINC . '/functions.php';
require_once ABSPATH . WPINC . '/load.php';
require_once ABSPATH . WPINC . '/plugin.php';
wp_initial_constants();
$wp_plugin_paths = [];

require dirname( __DIR__ ) . '/plugin.php';
