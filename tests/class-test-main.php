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
	function test_parsedown_enabled() {
		$this->assertTrue( parsedown_enabled() );
	}
}