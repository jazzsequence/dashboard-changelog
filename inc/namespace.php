<?php
/**
 * Main plugin namespace file.
 *
* @package Dashboard-Changelog
 */

namespace jazzsequence\DashboardChangelog;

use jazzsequence\DashboardChangelog\API;

function bootstrap() {
	add_action( 'admin_init', __NAMESPACE__ . '\\add_setting' );
}

function add_setting() {
	register_setting( 'general', 'dashboard_changelog', [
		'sanitize_callback' => 'sanitize_text_field',
		'default' => null,
	] );

	add_settings_field(
		'dc-repo',
		__( 'GitHub Repo', 'js-dashboard-changelog' ),
		__NAMESPACE__ . '\\render_settings_field',
		'general'
	);
}

function get_repository_option( $option = '', $default = '' ) {
	$options = get_option( 'dashboard_changelog' );

	if ( empty( $option ) ) {
		return $options;
	}

	if ( ! isset( $options[ $option ] ) ) {
		return $default;
	}

	return $options[ $option ];
}

function render_settings_field() {
	$repo = get_repository_option( 'repository' );

	?>
	<input type="text" id="dc-repo" class="regular-text" name="dashboard_changelog[repository]" value="<?php echo esc_attr( $repo ); ?>" />

	<p class="description">
		<?php esc_html_e( 'Add the repository user and name, e.g. jazzsequence/dashboard-changelog.', 'js-dashboard-changelog' ); ?>
	</p>
	<?php
}
