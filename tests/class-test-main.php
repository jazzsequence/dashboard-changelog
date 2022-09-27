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
	public function setUp() : void {
		parent::setUp();

		$this->define_constants();
	}

	private function define_constants() {
		$options = $this->get_options();

		defined( 'JSDC_REPOSITORY' ) or define( 'JSDC_REPOSITORY', $options['repo'] );
		defined( 'JSDC_PAT' ) or define( 'JSDC_PAT', $options['pat'] );
	}

	private function get_options() : array {
		return [
			'repo' => 'jazzsequence/dashboard-changelog',
			'pat' => '1234567890',
		];
	}

	public function test_parsedown_enabled() {
		$this->assertTrue( parsedown_enabled() );
	}

	public function test_get_cache_expiration() {
		$this->assertEquals( DAY_IN_SECONDS, get_cache_expiration() );
	}

	public function test_get_repository() {
		$repo = $this->get_options()['repo'];
		$this->assertEquals( $repo, get_repository() );
	}

	public function test_get_pat() {
		$pat = $this->get_options()['pat'];
		$this->assertEquals( $pat, get_pat() );
	}
}
