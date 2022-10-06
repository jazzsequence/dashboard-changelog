<?php
/**
 * Main plugin namespace file.
 *
* @package Dashboard-Changelog
 */

namespace jazzsequence\DashboardChangelog;

use jazzsequence\DashboardChangelog\Widget;

/**
 * Kick it off!
 */
function bootstrap() {
	Widget\bootstrap();

	if ( ! defined( 'JSDC_REPOSITORY' ) ) {
		add_action( 'admin_init', __NAMESPACE__ . '\\add_repo_setting' );
	}

	if ( ! defined( 'JSDC_PAT' ) ) {
		add_action( 'admin_init', __NAMESPACE__ . '\\add_pat_setting' );
	}

	if ( ! defined( 'JSDC_ADMIN_NOTICE' ) ) {
		add_action( 'admin_init', __NAMESPACE__ . '\\add_admin_notice_setting' );
	}

	add_action( 'init', __NAMESPACE__ . '\\maybe_show_new_release_notice' );
}

/**
 * Show an admin notice if there is a new release since user's last visit.
 *
 * @return void
 */
function maybe_show_new_release_notice() {
	global $current_user;

	$user_id = $current_user->ID;
	$releases = API\get_body();
	$notices = new Notices();
	$last_viewed_release = get_user_meta( $user_id, 'jsdc_last_viewed_release', true );
	$last_release = $releases[0]->tag_name;
	$notice_dismissed = get_user_meta( $user_id, 'jsdc_new_feature', true );
	// display the notice only if there is a new release since last visit,
	// or if the last notice shown was not dismissed.
	if ( $last_release !== $last_viewed_release ||
	 ( $last_release === $last_viewed_release && ! $notice_dismissed ) ) {
		$notices->add(
			'new_feature',
			sprintf( __( 'Version %s Released!', 'js-dashboard-changelog' ),
			$last_release ),
			sprintf( esc_html__( 'See the changes in the "Updates" widget on %s your dashboard%s.', 'js-dashboard-changelog' ),
			'<a href="/wp-admin">', '</a>' ),
			[
				'scope'         => 'user',
				'type'          => 'success',
				'alt_style'     => true,
				'option_prefix' => 'jsdc',
			]
		);
	}
	update_user_meta( $user_id, 'jsdc_last_viewed_release', $releases[0]->tag_name );
	$notices->boot();
}

/**
 * Determines whether Parsedown should be enabled.
 *
 * @return bool
 */
function parsedown_enabled() : bool {
	if ( ! class_exists( 'Parsedown' ) ) {
		error_log( __( 'Parsedown class does not exist.', 'dashboard-changelog' ) );
		return false;
	}

	return true;
}

/**
 * Get the cache expiration time.
 * We default to one day, but this can be filtered.
 *
 * @return int The cache expiration time.
 */
function get_cache_expiration() : int {
	/**
	 * Allow the expiration time to be filtered. Default to DAY_IN_SECONDS.
	 *
	 * @param int $expire The length to retain cached response codes.
	 */
	return apply_filters( 'dc.api_expiration', DAY_IN_SECONDS );
}

/**
 * Register the repo setting and add the field.
 */
function add_repo_setting() {
	add_settings_field(
		'dc-repo',
		__( 'GitHub Repo', 'js-dashboard-changelog' ),
		__NAMESPACE__ . '\\render_repo_settings_field',
		'general',
		'default',
		[
			'label_for' => 'dc-repo'
		]
	);

	register_setting( 'general', 'dc-repo', [
		'sanitize_callback' => 'sanitize_text_field',
		'default' => null,
	] );
}

/**
 * Register the PAT setting and add the field.
 */
function add_pat_setting() {
	add_settings_field(
		'dc-pat',
		__( 'GitHub Personal Access Token', 'js-dashboard-changelog' ),
		__NAMESPACE__ . '\\render_pat_settings_field',
		'general',
		'default',
		[
			'label_for' => 'dc-pat'
		]
	);

	register_setting( 'general', 'dc-pat', [
		'sanitize_callback' => 'sanitize_text_field',
		'default' => null,
	] );
}

/**
 * Register the admin notice setting and add the field.
 */
function add_admin_notice_setting() {
	add_settings_field(
		'dc-admin-notice',
		__( 'Admin notice', 'js-dashboard-changelog' ),
		__NAMESPACE__ . '\\render_admin_notice_settings_field',
		'general',
		'default',
		[
			'label_for' => 'dc-admin-notice',
		]
	);

	register_setting(
		'general',
		'dc-admin-notice',
		[
			'sanitize_callback' => __NAMESPACE__ . '\\sanitize_checkbox',
			'default'           => '',
		]
	);
}

/**
 * Display the input field for the GitHub repository.
 */
function render_repo_settings_field() {
	$repo = get_option( 'dc-repo' );

	?>
	<input type="text" id="dc-repo" class="regular-text" name="dc-repo" value="<?php echo esc_attr( $repo ); ?>" />

	<p class="description">
		<?php esc_html_e( 'Add the repository user and name, e.g. jazzsequence/dashboard-changelog.', 'js-dashboard-changelog' ); ?>
	</p>
	<?php
}

/**
 * Display the input field for the GitHub personal acess token.
 */
function render_pat_settings_field() {
	$pat = get_option( 'dc-pat' );

	?>
	<input type="password" id="dc-pat" class="regular-text" name="dc-pat" value="<?php echo esc_attr( $pat ); ?>" />

	<p class="description">
		<?php esc_html_e( 'Add a personal access token with read scope on your private repo.', 'js-dashboard-changelog' ); ?>
	</p>
	<?php
}


/**
 * Display the checkbox field for the admin notices.
 */
function render_admin_notice_settings_field() {
	$admin_notice = get_option( 'dc-admin-notice' );

	?>
	<fieldset>
		<legend class="screen-reader-text"><span>Changelog translation</span></legend>
		<label for="dc-admin-notice">
			<input type="checkbox" id="dc-admin-notice" name="dc-admin-notice" value="1" <?php checked( 1, $admin_notice, true ); ?> />
			<?php esc_html_e( 'Show a dismissable notice on new releases.', 'js-dashboard-changelog' ); ?>
		</label>
	</fieldset>
	<?php
}

/**
 * Get the repository to fetch updates from.
 */
function get_repository() {
	if ( defined( 'JSDC_REPOSITORY' ) ) {
		return \JSDC_REPOSITORY;
	}

	return get_option( 'dc-repo' );
}

/**
 * Get the personal access token to access private GH repo.
 */
function get_pat() {
	if ( defined( 'JSDC_PAT' ) ) {
		return \JSDC_PAT;
	}

	return get_option( 'dc-pat' );
}