<?php
/**
 * Main plugin namespace file.
 *
* @package Dashboard-Changelog
 */

namespace jazzsequence\DashboardChangelog;

/**
 * Kick it off!
 */
function bootstrap() {
	add_action( 'admin_init', __NAMESPACE__ . '\\add_setting' );
}

/**
 * Register the setting and add the field.
 */
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

/**
 * Get the Dashboard Changelog options.
 *
 * @param string $option  The option to retrieve.
 * @param string $default (Optional) A default value.
 */
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

/**
 * Display the input field for the GitHub repository.
 */
function render_settings_field() {
	$repo = get_repository_option( 'repository' );

	?>
	<input type="text" id="dc-repo" class="regular-text" name="dashboard_changelog[repository]" value="<?php echo esc_attr( $repo ); ?>" />

	<p class="description">
		<?php esc_html_e( 'Add the repository user and name, e.g. jazzsequence/dashboard-changelog.', 'js-dashboard-changelog' ); ?>
	</p>
	<?php
}
