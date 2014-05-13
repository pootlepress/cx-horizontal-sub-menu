<?php
/*
Plugin Name: Canvas Extension - Horizontal Sub-menu
Plugin URI: http://pootlepress.com/
Description: An extension for WooThemes Canvas that allow you to have horizontal sub-menu.
Version: 1.0.0
Author: PootlePress
Author URI: http://pootlepress.com/
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once( 'pootlepress-horizontal-submenu-functions.php' );
require_once( 'classes/class-pootlepress-horizontal-submenu.php' );
require_once( 'classes/class-horizontal-submenu-nav-walker.php' );

$GLOBALS['pootlepress_horizontal_submenu'] = new Pootlepress_Horizontal_Submenu( __FILE__ );
$GLOBALS['pootlepress_horizontal_submenu']->version = '1.0.1';

?>
