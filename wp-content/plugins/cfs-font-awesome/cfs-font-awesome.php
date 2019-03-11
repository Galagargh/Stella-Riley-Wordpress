<?php
/*
Plugin Name: CFS Font Awesome
Plugin URI: https://wordpress.org/plugins/cfs-font-awesome/
Description: Adds a Font Awesome field type to Custom Field Suite.
Version: 1.0.0
Author: Chetan Prajapati
Author URI: http://www.chetanprajapati.com
Text Domain: cfsfa
License: GPL2

Copyright 2015 Chetan Prajapati

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, see <http://www.gnu.org/licenses/>.
*/

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cfs_font_awesome = new cfs_font_awesome();

class cfs_font_awesome {
	function __construct() {
		add_filter( 'cfs_field_types', array($this, 'cfs_font_awesome_field_type') );
	}

	function cfs_font_awesome_field_type( $field_types ) {
		$field_types['font_awesome_field'] = dirname( __FILE__ ) . '/cfs-font-awesome-field.php';
		return $field_types;
	}
}