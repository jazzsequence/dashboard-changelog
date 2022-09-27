<?php
/**
 * Base unit tests
 *
 * @since 1.2
 * @package Dashboard-Changelog
 */

namespace jazzsequence\DashboardChangelog\API;

use PHPUnit\Framework\TestCase;

class DC_Test_Api extends TestCase {
	public function test_api_url() {
		$this->assertEquals( 'https://api.github.com/repos/', api_url() );
		$this->assertEquals( 'https://api.github.com/repos/foo', api_url( 'foo' ) );
	}
}
