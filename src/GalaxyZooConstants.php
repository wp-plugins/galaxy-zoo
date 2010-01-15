<?php

/**
 * @author Tomas Vorobjov
 * @version 1.0
 * @date 05 Oct 2009
 * 
 * @file GalaxyZooConstants.php.php
 * 
 * Provides constant values for Galaxy Zoo plugin
 */

/** 
 * The version of this Galaxy Zoo file 
 */
define( 'GALAXY_ZOO_VERSION', '1.5' );

/* -------------------- OPTIONS START -------------------- */

define( 'GALAXY_ZOO_OPTIONS', 'GALAXY_ZOO_OPTIONS' );
define( 'OPTIONS_API_KEY', GALAXY_ZOO_OPTIONS . '_API_KEY' );
define( 'OPTIONS_USER_ID', GALAXY_ZOO_OPTIONS . '_USER_ID' );
define( 'OPTIONS_DATE_FORMAT', GALAXY_ZOO_OPTIONS . '_OPTIONS_DATE_FORMAT' );
define( 'OPTIONS_CACHE_EXPIRE_TIME', GALAXY_ZOO_OPTIONS . '_OPTIONS_CACHE_EXPIRE_TIME' );
define( 'CACHE_EXPIRE_TIME_MINIMUM', 30 );
define( 'CACHE_EXPIRE_TIME_DEFAULT', 720 );

/* --------------------- OPTIONS END --------------------- */

/* --------------------- WIDGET START -------------------- */

define( 'GALAXY_ZOO_WIDGET_NAME', 'Galaxy Zoo' );
define( 'GALAXY_ZOO_FAVORITES_WIDGET_NAME', 'Galaxy Zoo Favorites' );

/* --------------------- WIDGET END ---------------------- */

/* ------------------- DATA MANAGER START ---------------- */

define( 'GALAXY_ZOO_CACHE_FOLDER', ABSPATH . 'wp-content/plugins/galaxy-zoo/src/cache');
define( 'GALAXY_ZOO_STATS_URL', 'http://api.galaxyzoo.org/public/users/[user_id]?api_key=[api_key]' );
define( 'GALAXY_ZOO_GALAXY_URL', 'http://api.galaxyzoo.org/public/assets/[galaxy_id]?api_key=[api_key]' );
define( 'GALAXY_ZOO_FAVORITE_URL', 'http://api.galaxyzoo.org/public/users/[user_id]/favourites?api_key=[api_key]' );

/* ------------------- DATA MANAGER END ------------------ */

/* ----------------------- AJAX START -------------------- */

define( 'WIDGET_TYPE_GALAXY_ZOO', 'galaxy-zoo' );
define( 'WIDGET_TYPE_GALAXY_ZOO_FAVORITES', 'galaxy-zoo-favorites' );

/* ------------------------ AJAX END --------------------- */
?>