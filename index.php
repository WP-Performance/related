<?php

namespace WPPerformanceRelated;

use WPPerformanceRelated\Inc\QueryLoopRelated;

require_once(plugin_dir_path(__FILE__) . 'inc/QueryLoopRelated.php');



/**
 * Plugin Name:       Related Loop Block
 * Description:       Add variation to loop block to display related posts
 * Update URI:        wp-performance-related
 * Requires at least: 6.1
 * Requires PHP:      7.4
 * Version:           0.0.1
 * Author:            Faramaz Patrick <infos@goodmotion.fr>
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-performance-related
 *
 * @package           wp-performance
 */


// init
new QueryLoopRelated();
