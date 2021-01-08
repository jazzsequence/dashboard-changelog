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
		$repository = DashboardChangelog\get_repository();
		$response = wp_remote_request( api_url( $repository . $endpoint ) );
		$response_code = $response['response']['code'];
		$expire = DashboardChangelog\get_cache_expiration();

		if ( $response_code === 200 ) {
			wp_cache_set( 'dc.api.cached_data', $response, null, $expire );
		}
	}

	return $response;
}

/**
 * Get the API response code.
 *
 * @param array $response
 *
 * @return int The HTTP API Response code.
 */
function get_code( array $response = [] ) : int {
	$response = empty( $response ) ? get_data() : $response;
	return wp_remote_retrieve_response_code( $response );
}

/**
 * Get the API body content.
 * If no content was able to be retrieved, return an error with an error message.
 *
 * @return object The body of the API response, or an object containing an error message and information about what went wrong.
 */
function get_body() : array {
	$body = wp_cache_get( 'dc.api.cached_body' );

	if ( ! $body ) {
		$response = get_data();
		$code = get_code();
		$expire = DashboardChangelog\get_cache_expiration();

		if ( 200 !== $code ) {
			$body['code'] = $code;
			$body['error'] = $response['response']['message'];
			$body['message'] = sprintf( __( 'There was a problem fetching your repository. Check the %1$ssettings%2$s to make sure that you entered it in correctly.', 'js-dashboard-changelog' ), '<a href="options-general.php>', '</a>' );

			return $body;
		}

		$body = wp_remote_retrieve_body( $response );
		wp_cache_set( 'dc.api.cached_body', $body, null, $expire );
	}

	return json_decode( $body );
}

/**
 * Get the repository name from the API.
 *
 * This name is cached indefinitely as repository names should never change.
 *
 * @return string The repository name, or an error.
 */
function get_name() : string {
	$name = wp_cache_get( 'dc.api.cached_name' ) ?? '';

	if ( ! $name ) {
		$response = get_data( '' );
		$code = get_code( $response );

		if ( 200 !== $code ) {
			return __( 'Error', 'js-dashboard-changelog' );
		}

		$body = json_decode( wp_remote_retrieve_body( $response ) );

		// Check if the name is set. If it is, cache it.
		if ( isset( $body['name'] ) ) {
			$name = $body['name'];

			// Don't expire the name. This should never change.
			wp_cache_set( 'dc.api.cached_name', $name );
		}
	}

	return $name;
}
