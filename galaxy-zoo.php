<?php

/*
Plugin Name: Galaxy Zoo
Plugin URI: http://www.scibuff.com/2009/11/17/galaxy-zoo-wordpress-plugin/
Description: Connects your Galaxy Zoo account with your blog
Version: 1.0b
Author: Tomas Vorobjov aka SciBuff
Author URI: htpt://www.scibuff.com
*/

/*  Copyright 2009  SciBuff - Galaxy Zoo

    This file is part of Galaxy Zoo Wordpress Plugin.

    Alfisti Connect is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Alfisti Connect is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Galaxy Zoo Wordpress Plugin.  If not, see <http://www.gnu.org/licenses/>.

*/

require_once( 'src/GalaxyZoo.php' );

register_activation_hook( __FILE__, 'activate_galaxy_zoo' );
register_deactivation_hook( __FILE__, 'deactivate_galaxy_zoo' );

global $galaxy_zoo;

$galaxy_zoo = new GalaxyZoo();

if ( !function_exists('activate_comment_feeds') ){
	/**
	 * This function is executed when this plugin is activated. The
	 * function simply calls the <code>active</code> function of the
	 * <code>GalaxyZoo</code>'s object, which takes over the activation
	 * procedures.
	 * 
	 * @since	0.9
	 */
	function activate_galaxy_zoo(){
		global $galaxy_zoo;
		$galaxy_zoo->activate();
	}
}

if ( !function_exists('deactivate_galaxy_zoo') ){
	/**
	 * This function is executed when this plugin is activated. The
	 * function simply calls the <code>active</code> function of the
	 * <code>GalaxyZoo</code>'s object, which takes over the activation
	 * procedures.
	 * 
	 * @since	0.9
	 */
	function deactivate_galaxy_zoo(){
		global $galaxy_zoo;
		$galaxy_zoo->deactivate();
	}
}

?>