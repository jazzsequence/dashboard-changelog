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
		$repository = 'jazzsequence/jazzsequence.com';
		$response = wp_remote_request( api_url( $repository ) );
		wp_cache_set( 'dc.api.cached_data', $response, null, DAY_IN_SECONDS );
	}

	return $response;
}
