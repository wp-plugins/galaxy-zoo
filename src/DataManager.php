<?php

require_once( 'GalaxyZooConstants.php' );


/**
 * @author Tomas Vorobjov
 * @version 1.0
 * @date Oct 05 2009
 * 
 * @file DataManager.php
 * 
 * This class serves as the data manager class for the Galaxy Zoo 
 * wordpress plugin
 */
class DataManager {
	
	/**
	 * Retrieves Galaxy Zoo stats for the user specified in the
	 * settings (via WP Dash board)
	 * 
	 * @since	0.9
	 * 
	 * @return	An object of class <code>SimpleXMLElement</code> with 
	 * 			Galaxy Zoo stats data or <code>FALSE</code> on errors.
	 */
	public static function getStatsData(){

		$searches = array( '[user_id]', '[api_key]' );
		$replaces = array( 
			get_option( OPTIONS_USER_ID ), 
			get_option( OPTIONS_API_KEY ) 
		);
		
		$url = str_replace( $searches, $replaces, GALAXY_ZOO_STATS_URL );
		$xmlData = DataManager::requestData( $url );
		
		$data = FALSE;
		try {
			$data = @simplexml_load_string( $xmlData );
		}
		catch( Exception $e ){};
		
		return $data;
		
	}
	
	/**
	 * Retrieves data of the galaxy specified by the value of <i>id</i> 
	 * 
	 * @param 	string $id The galaxy id
	 * 
	 * @since	0.9
	 * 
	 * @return	An object of class <code>SimpleXMLElement</code> with 
	 * 			Galaxy Zoo galaxy data or <code>FALSE</code> on errors.
	 */
	public static function getGalaxyData( $id ){
		
		$searches = array( '[galaxy_id]', '[api_key]' );
		$replaces = array( $id, get_option( OPTIONS_API_KEY ) );
		
		$url = str_replace( $searches, $replaces, GALAXY_ZOO_GALAXY_URL );
		$xmlData = DataManager::requestData( $url );
		
		$data = FALSE;
		try {
			$data = @simplexml_load_string( $xmlData );
		}
		catch( Exception $e ){};
		
		return $data;		
		
	}
	
	/**
	 * Retrieves galaxy zoo favorites data
	 *
	 * @since	0.9
	 * 
	 * @return	An object of class <code>SimpleXMLElement</code> with 
	 * 			Galaxy Zoo favorites galaxy data or <code>FALSE</code> 
	 * 			on errors.
	 */
	public static function getFavoritesData(){
		
		$searches = array( '[user_id]', '[api_key]' );
		$replaces = array( 
			get_option( OPTIONS_USER_ID ), 
			get_option( OPTIONS_API_KEY ) 
		);
		
		$url = str_replace( $searches, $replaces, GALAXY_ZOO_FAVORITE_URL );
		$xmlData = DataManager::requestData( $url );
		
		$data = FALSE;
		try {
			$data = @simplexml_load_string( $xmlData );
		}
		catch( Exception $e ){};
		
		return $data;		
	}
	
	/**
	 * Deletes all cache files (by setting the content to zero length)
	 * 
	 * @since	1.0
	 */
	public static function deleteCache(){
		
		if ( @$handle = opendir( GALAXY_ZOO_CACHE_FOLDER ) ) {
			while ( FALSE !== ( $file = readdir( $handle ) ) ) {
				if( $file != "." && $file != "..") {
					$file = GALAXY_ZOO_CACHE_FOLDER . '/' . $file;
					$fp = fopen( $file, 'w+' );
					fwrite( $fp, '' );
					fclose( $fp );
				};
			};
			closedir( $handle );
		}		
		
	}
	
	/**
	 * Returns the number of active cache files (i.e. non expired)
	 *   
	 * @return 	The number of active cache files (i.e. non expired)
	 */	
	public static function getNumberOfCacheFiles(){
		
		$cache_expire_time = get_option( OPTIONS_CACHE_EXPIRE_TIME );
		if ( empty( $cache_expire_time ) ){ $cache_expire_time = CACHE_EXPIRE_TIME_DEFAULT; }
		
		$numberOfCacheFiles = 0;
		
		if ( @$handle = opendir( GALAXY_ZOO_CACHE_FOLDER ) ) {
			while ( FALSE !== ( $file = readdir( $handle ) ) ) {
				if( $file != "." && $file != ".." && $file != '.htaccess' ) {
					
					$filepath = GALAXY_ZOO_CACHE_FOLDER . '/' . $file;
					$expire_time = filemtime( $filepath ) + $cache_expire_time * 60;
						
					if ( time() < $expire_time ){
						$result = @file_get_contents( $filepath );
						if ( !empty( $result ) ){ $numberOfCacheFiles++; }
					}
				};
			};
			closedir( $handle );
		}		
		
		
		return $numberOfCacheFiles;
	}
	
	/**
	 * Requests data from the specified url
	 * 
	 * @param string $url
	 * 
	 * @since	0.9
	 * 
	 * @return 
	 */
	private static function requestData( $url ) {
		
		$result = '';
		
		$filename = GALAXY_ZOO_CACHE_FOLDER . '/' . md5( $url );
		
		if ( file_exists( $filename ) ){
			
			$cache_expire_time = get_option( OPTIONS_CACHE_EXPIRE_TIME );
			if ( empty( $cache_expire_time ) ){ $cache_expire_time = CACHE_EXPIRE_TIME_DEFAULT; }
			$expire_time = filemtime( $filename ) + $cache_expire_time * 60;			
			
			if ( time() < $expire_time ){
				$result = @file_get_contents( $filename );
				if ( !empty( $result ) ){ return $result; }
			}
		}
		
		if ( function_exists('curl_init') ) {
			
			$useragent = 'Galaxy Zoo v' . GALAXY_ZOO_VERSION;
			$ch = curl_init( $url );
			
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_USERAGENT, $useragent );
			curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
			curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
			$result = curl_exec( $ch );
			curl_close( $ch );			
			
		} else {

			$content_type = 'application/x-www-form-urlencoded';
			$content = $post_string;
			$result = DataManager::run_http_post_transaction(
				$content_type,
				$content,
				$url_with_get
			);
		}
		
		// store the result
		$fp = fopen( $filename, 'w+' );
		fwrite( $fp, $result );
		fclose( $fp );
		
		return $result;
	}	
	
	/**
	* Execute a curl transaction 
	*
	* @param resource $ch a curl handle
	* 
	* @since	0.9
	*/
	private static function curl_exec( $ch ) {
		
		
		return $result;
		
	}	
	
	/**
	 * Creates
	 * @param object $content_type
	 * @param object $content
	 * @param object $server_addr
	 * @return 
	 * 
	 * @since	0.9
	 */
	private static function run_http_post_transaction( $content_type, $content, $server_addr ) {
	
		$user_agent = 'Galaxy Zoo (non-curl) v' . GALAXY_ZOO_VERSION;
		$content_length = strlen( $content );
		$context =
		array( 'http' =>
			array(
				'method' => 'POST',
				'user_agent' => $user_agent,
				'header' => 'Content-Type: ' . $content_type . "\r\n" .
				            'Content-Length: ' . $content_length,
				'content' => $content
			)
		);
		$context_id = stream_context_create( $context );
		$sock = fopen( $server_addr, 'r', false, $context_id );
		
		$result = '';
		if ( $sock ) {
			while ( !feof( $sock ) ) {
				$result .= fgets( $sock, 4096 );
			}
			fclose( $sock );
		}
		return $result;
	}	
	
}

?>