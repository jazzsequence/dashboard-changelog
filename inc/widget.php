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

function register_dashboard_widget() {
	wp_add_dashboard_widget(
		'js-dashboard-changelog',
		API\get_code() === 200 ? sprintf( __( '%s Updates', 'js-dashboard-changelog' ), API\get_name() ) : __( 'Error in Dashboard Changelog', 'js-dashboard-changelog' ),
		 __NAMESPACE__ . '\\render_dashboard_widget',
		 null,
		 null,
		 'side',
		 'high'
	);
}