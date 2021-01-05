<?php
/**
 * Plugin Name: Dashboard Changelog
 * Plugin URI: https://jazzsequence.com
 * Description: Adds a GitHub release widget to your WordPress dashboard for a public GitHub repository.
 * Version: 1.0
 * Author: Chris Reynolds
 * Author URI: https://github.com/jazzsequence
 * License: GPLv3
 *
 * @package Dashboard-Changelog
 */

/**
 * Copyright (c) 2021 Chris Reynolds (email : me@chrisreynolds.io)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace jazzsequence\DashboardChangelog;

require_once __DIR__ . '/inc/api.php';
require_once __DIR__ . '/inc/namespace.php';

add_action( 'plugins_loaded', __NAMESPACE__ . '\\bootstrap' );
