<?php
/**
 * Registers the dashboard widget.
 *
 * @package Dashboard-Changelog
 */

namespace jazzsequence\DashboardChangelog\Widget;

use jazzsequence\DashboardChangelog\API;

function bootstrap() {
	add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\\register_dashboard_widget' );
}