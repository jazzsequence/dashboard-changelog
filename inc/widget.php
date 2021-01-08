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
	add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_styles' );
}

function enqueue_styles( string $pagenow ) {
	// Bail if we're not on the dashboard.
	if ( $pagenow !== 'index.php' ) {
		return;
	}

	wp_enqueue_style( 'dashboard-changelog', plugin_dir_url( dirname( __FILE__ ) ) . 'dist/css/style.css', [], '1.0.0', 'screen' );
}

function register_dashboard_widget() {
	add_meta_box(
		'js-dashboard-changelog',
		API\get_code() === 200 ? sprintf( __( '%s Updates', 'js-dashboard-changelog' ), API\get_name() ) : __( 'Error in Dashboard Changelog', 'js-dashboard-changelog' ),
		 __NAMESPACE__ . '\\render_dashboard_widget',
		 'dashboard',
		 'side',
		 'high'
	);
}

function render_dashboard_widget() {
	$updates = API\get_body();
	$body = '<ul>';
	$i = 0;

	if ( ! empty( $updates ) ) {
		foreach ( $updates as $update ) {
			// Only show the 3 most recent updates.
			if ( $i >= 3 ) {
				continue;
			}

			$title = $update->name;
			$version = $update->tag_name;
			$description = $update->body;
			$link = $update->html_url;

			$body .= '<li class="entry">';
			$body .= "<h3>$title</h3>";
			$body .= wpautop( $description );
			$body .= "<span class=\"version\"><a href=\"$link\">$version</a></span>";
			$body .= '</li>';

			$i++;
		}
	}

	$body .= '</ul>';

	echo wp_kses_post( $body );
}
