<?php
/**
 * Base unit tests
 *
 * @since 1.1
 * @package Dashboard-Changelog
 */

namespace jazzsequence\DashboardChangelog;

use PHPUnit\Framework\TestCase;

class DC_Test_Main extends TestCase {
	function test_get_vendor_dir() {
		$this->assertEquals(
			get_vendor_dir(),
			dirname( __DIR__ ) . '/vendor/'
		);
	}

	function test_composer_autoloader() {
		$this->assertEquals(
			composer_autoloader(),
			dirname( __DIR__ ) . '/vendor/autoload.php'
		);
	}

	function test_parsedown_enabled() {
		$this->assertTrue( parsedown_enabled() );
	}
}