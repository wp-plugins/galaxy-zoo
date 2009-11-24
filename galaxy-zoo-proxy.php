<?php

/** Sets up the WordPress Environment. */
require( './../../../wp-load.php' );

require_once( 'src/GalaxyZooConstants.php' );
require_once( 'src/GalaxyZooWidget.php' );
require_once( 'src/GalaxyZooFavoritesWidget.php' );
//require_once( 'src/GalaxyZooConstants.php' );

switch ( $_POST['type'] ){
	
	case WIDGET_TYPE_GALAXY_ZOO : {
		
		print_galaxy_zoo_widget();
		
		break;
	}
	
	case WIDGET_TYPE_GALAXY_ZOO_FAVORITES : {
		
		print_galaxy_zoo_favorites_widget();
		
		break;
	}		
	
}

function print_galaxy_zoo_widget(){
	
	$show_username = ( $_POST['show_username'] == 'true' );
	$show_joined = ( $_POST['show_joined'] == 'true' );
	$show_stats = ( $_POST['show_stats'] == 'true' );
	$show_latest_galaxy = ( $_POST['show_latest_galaxy'] == 'true' );
	
	GalaxyZooWidget::printWidget(
		$show_stats, 
		$show_username, 
		$show_joined, 
		$show_latest_galaxy
	);
}

function print_galaxy_zoo_favorites_widget(){
	
	$number_of_favorites = 2;
	$image_size = 80;
		
	if ( is_numeric( $_POST['number_of_favorites'] ) ){
		$number_of_favorites = intval( $_POST['number_of_favorites'] );
	}
	
	if ( is_numeric( $_POST['image_size'] ) ){
		$image_size = intval( $_POST['image_size'] );
	}
	
	GalaxyZooFavoritesWidget::printWidget(
		$number_of_favorites,
		$image_size
	);
}

?>