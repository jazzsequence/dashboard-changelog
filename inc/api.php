<?php
/**
 * API
 *
 * Handles the GitHub API requests
 *
 * @package Dashboard-Changelog
 */

namespace jazzsequence\DashboardChangelog\API;

use jazzsequence\DashboardChangelog;

/**
 * Return the API url.
 *
 * @param string $path A custom path to request data from.
 *
 * @return string The full API URL.
 */
function api_url( string $path = '' ) : string {
	/**
	 * Allow the API URL to be filtered, so other APIs could be used besides GitHub, or so other endpoints could be used besides the repos endpoint.
	 *
	 * @param string $api_url The API URL to filter.
	 */
	$base_url = apply_filters( 'dc.api.url', 'https://api.github.com/repos' );

	return $base_url . '/' . $path;
}

/**
 * Get the API data.
 *
 * @return array An array of API response data.
 */
function get_data( string $endpoint = '/releases' ) : array {
	$response = wp_cache_get( 'dc.api.cached_data' );

	if ( ! $response ) {
		$repository = DashboardChangelog\get_repository_option( 'repository' );
		$response = wp_remote_request( api_url( $repository . $endpoint ) );
		$response_code = $response['response']['code'];

		if ( $response_code === 200 ) {
			wp_cache_set( 'dc.api.cached_data', $response, null, DAY_IN_SECONDS );
		}
	}

	return $response;
}

function get_code( array $response = [] ) {
	$response = empty( $response ) ? get_data() : $response;
	return wp_remote_retrieve_response_code( $response );
}

function get_body() {
	$body = wp_cache_get( 'dc.api.cached_body' );

	if ( ! $body ) {
		$response = get_data();
		$code     = get_code();

		if ( 200 !== $code ) {
			$body->code = $code;
			$body->error = $response['response']['message'];
			$body->message = sprintf( __( 'There was a problem fetching your repository. Check the %1$ssettings%2$s to make sure that you entered it in correctly.', 'js-dashboard-changelog' ), '<a href="options-general.php>', '</a>' );

			return $body;
		}

		$body = wp_remote_retrieve_body( $response );
	}

	return json_decode( $body );
}