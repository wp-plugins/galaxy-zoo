<?php

require_once( 'GalaxyZooAdminPanel.php' );
require_once( 'GalaxyZooConstants.php' );
require_once( 'GalaxyZooWidget.php' );
require_once( 'GalaxyZooFavoritesWidget.php' );

/**
 * @author Tomas Vorobjov
 * @version 1.0
 * @date Oct 05 2009
 * 
 * @file GalaxyZoo.php
 * 
 * This class serves as the main class for the Galaxy Zoo 
 * wordpress plugin
 */
class GalaxyZoo {
	
	/**
	 * @var GalaxyZooAdminPanel
	 */
	var $adminPanel;	
	
	/**
	 * Creates a new GalaxyZoo object
	 * 
	 * @since	0.9
	 * 
	 */
	function GalaxyZoo(){
	
		$this->add_init_hook();
	}	
	
	/**
	 * This function is executed when the plugin is actived.
	 * 
	 * @since	0.9
	 */
	function activate(){
		
		//global $wpdb;
	}	
	
	/**
	 * This function is executed when the plugin is deactived. 
	 * 
	 * @since	0.9
	 */
	function deactivate(){
	}	
	
// ---------------------------- WP Hooks - START ------------------------- //
	
	/**
	 * Adds init wordpress hook. 
	 * 
	 * @since	0.9
	 */
	function add_init_hook(){
		add_action( 'admin_menu', array( &$this, 'add_admin_panel' ) );
		add_action( 'init', array( &$this, 'add_wp_hooks' ) );
		add_action( 'widgets_init', array( &$this, 'load_widget' ) );	
	}
	
	/**
	 * Adds wordpress hooks (and filters) necessary for this plugin
	 * 
	 * @private
	 * @since	0.9
	 */
	function add_wp_hooks(){
		
		// filters
		//add_filter( 'query_vars', array( &$this, 'handleFilterQueryVars') );
		//add_filter( 'post_rewrite_rules', array( &$this, 'handleFilterPostRewriteRules') );
		
		// feeds
		//add_feed( COMMENT_FEEDS_FEED_ID, array( &$this, 'createFeed' ), 999 );
		
		// comment form
		//add_action( 'comment_form', array( &$this, 'handleActionCommentForm' ) );
		//add_action( 'comment_post', array( &$this, 'handleActionCommentPost' ) );
		
		//global $wp_rewrite;
		//$wp_rewrite->flush_rules();		
	}

	/**
	 * Adds plugin's admin panel to the wp dashboard
	 * 
	 * @private 
	 * @since	0.9
	 */
	function add_admin_panel(){
		
		$this->adminPanel = new GalaxyZooAdminPanel();
		
	}

	/**
	 * Registers the Galaxy Zoo widget
	 * 
	 * @since	0.9
	 */
	function load_widget() {
		
		register_widget( 'GalaxyZooWidget' );
		register_widget( 'GalaxyZooFavoritesWidget' );
		
	}
	
// ----------------------------- WP Hooks - END -------------------------- //
	
}

?>