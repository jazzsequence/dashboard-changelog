<?php
/**
 * Registers the dashboard widget.
 *
 * @package Dashboard-Changelog
 */

namespace jazzsequence\DashboardChangelog\Widget;

use function jazzsequence\DashboardChangelog\parsedown_enabled;
use jazzsequence\DashboardChangelog\API;
use Parsedown;

/**
 * Initialize the Widget.
 */
function bootstrap() {
	add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\\register_dashboard_widget' );
	add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_styles' );
}

/**
 * Enqueue the styles, but only on the main WordPress dashboard page.
 *
 * @param string $pagenow
 */
function enqueue_styles( string $pagenow ) {
	// Bail if we're not on the dashboard.
	if ( $pagenow !== 'index.php' ) {
		return;
	}

	wp_enqueue_style( 'dashboard-changelog', plugin_dir_url( dirname( __FILE__ ) ) . 'dist/css/style.css', [], '1.0.0', 'screen' );
}

/**
 * Register the dashboard widget.
 */
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

/**
 * Display the dashboard widget.
 * Widget displays the 3 most recent GitHub releases.
 */
function render_dashboard_widget() {
	$parsedown = new Parsedown();
	$parsedown->setMarkupEscaped( true ); // Sanitize Markdown.
	$updates = API\get_body();

	// If there was an error, display the error message and bail.
	if ( isset( $updates['error'] ) ) {
		$error = wpautop( $updates['message'] );
		$error .= '<div class="error">';
		$error .= '<span class="error-code">' . sprintf( esc_html__( 'Error %d', 'js-dashboard-changelog' ), $updates['code'] ) . '</span>';
		$error .= '<span class="error-message">' . $updates['error'] . '</span>';
		$error .= '</div>';

		echo wp_kses_post( $error );
		return;
	}

	$body = '<ul>';
	$i = 0;

	/**
	 * Allow the maximum number of releases to display to be filtered.
	 *
	 * @param int $max_display The number of release updates to display.
	 */
	$max_display = apply_filters( 'dc.widget.max_display', 3 );

	if ( ! empty( $updates ) ) {
		foreach ( $updates as $update ) {
			// Only show the 3 most recent updates.
			if ( $i >= $max_display ) {
				continue;
			}

			$version = $update->tag_name;
			// If we have Parsedown, use it. Otherwise just use wpautop for basic parsing.
			$description = parsedown_enabled() ? $parsedown->text( $update->body ) : wpautop( $update->body );
			$link = $update->html_url;

			$body .= '<li class="entry">';
			$body .= $description;
			$body .= "<span class=\"version\"><a href=\"$link\">$version</a></span>";
			$body .= '</li>';

			$i++;
		}
	}

	$body .= '</ul>';

	echo wp_kses_post( $body );
}
