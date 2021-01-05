<?php
/**
 * Main plugin namespace file.
 *
* @package Dashboard-Changelog
 */

namespace jazzsequence\DashboardChangelog;

use jazzsequence\DashboardChangelog\API;

function bootstrap() {
	API\bootstrap();
}

function api_url( string $path = '' ) {
	$base_url = apply_filters( 'dc.api.url', '://api.github.com/repos' );

	return $base_url . '/' . $path;
}

function get_data() {
	$repository = 'jazzsequence/jazzsequence.com';
	$response = wp_remote_request( api_url( "repos/$repository" ) );

	var_dump( $response );
}