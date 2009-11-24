<?php

require_once( 'DataManager.php' );
require_once( 'GalaxyZooConstants.php' );

/**
 * @author Tomas Vorobjov
 * @version 1.0
 * @date 06 Oct 2009
 * 
 * @file GalaxyZooFavoritesWidget.php
 * 
* This class provides methods for the Galaxy Zoo Favorites widget
 */
class GalaxyZooFavoritesWidget extends WP_Widget {
	
	/**
	 * Creates new instance of the GalaxZooWidgetFavorites
	 * 
	 * @since 0.9 
	 */
	function GalaxyZooFavoritesWidget() {
		
		$widget_ops = array(
			'classname' => 'widget-galaxy-zoo-favorites', 
			'description' => 'Galaxy Zoo Favorites Widget' 
		);
		
		$this->WP_Widget( 'galaxy-zoo-favorites', 'Galaxy Zoo Favorites', $widget_ops );		
		
		$this->add_init_hook();
		
	}

// ---------------------------- WP Hooks - START ------------------------- //
	
	/**
	 * Adds init wordpress hook. 
	 * 
	 * @since	0.9
	 */
	function add_init_hook(){
		//add_action( 'wp_head', array( &$this, 'wp_head' ) );
		$this->wp_head();
	}
	
	/**
	 * Adds content to html head for the plugin
	 *
	 * @since	0.9
	 */
	function wp_head(){
		
		$path = get_option( 'siteurl' ) . '/wp-content/plugins/galaxy-zoo/';
	
		wp_enqueue_script('jquery');
		wp_enqueue_script('galaxy-zoo-js', $path . 'js/galaxy.zoo.js', array('jquery'), GALAXY_ZOO_VERSION );	
		
		wp_enqueue_style('galaxy-zoo', $path . 'css/galaxy-zoo.css', 'global', GALAXY_ZOO_VERSION, 'screen' );
		wp_enqueue_style('galaxy-zoo-favorites-widget', $path . 'css/galaxy-zoo-favorites-widget.css', 'global', GALAXY_ZOO_VERSION, 'screen' );
		
	}	
	
// ----------------------------- WP Hooks - END -------------------------- //
	
	/**
	 * Prints the content of the widget. This function is mainly here for the
	 * use by AJAX calls
	 * 
	 * @param int $number_of_favorites
	 * @param int $image_size
	 * 
	 * @since	0.9
	 */
	public static function printWidget( $number_of_favorites, $image_size ){
		
		$data = DataManager::getFavoritesData();
		
		if ( $data !== FALSE ){
			$count = 1;
			if ( count( $data ) > 0 ){
				echo '<div class="galaxy-zoo-favorite-widget-container">';
				foreach( $data->favourite as $favorite ){
					echo '<div class="widget-galaxy-zoo-favorites-image-container">';
					echo '<a href="', $favorite->external_ref ,'" target="_blank" rel="nofollow">';
					echo '<img src="', $favorite->asset_location ,'" width="', $image_size ,'" height="', $image_size ,'"/>';
					echo '</a></div>';
					$count++;
					if ( $count > $number_of_favorites ){ break; }
				}
				echo '</div>';
			}
		}
		else {
			echo '<p class="error">Couldn\'t retrieve data from Galaxy Zoo.</p>';
		}		
		
	}
	
	/**
	 * Prints the widget
	 * 
	 * @since 0.9 
	 */ 
	function widget( $args, $instance ) {
		
		extract( $args );

		/* User-selected settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$image_size = $instance['image_size'];
		$number_of_favorites = intval( $instance['number_of_favorites'] );
		$use_ajax = $instance['use_ajax'];
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
		if ( $title ){
			echo $before_title . $title . $after_title;
		}

		echo '<div id="', $this->id ,'-container" class="widget-galaxy-zoo-favorites-content">', "\n";

		if ( $use_ajax ){
			echo '<div class="widget-galaxy-zoo-favorites-loader"></div>', "\n";
			echo '<script type="text/javascript" language="Javascript">', "\n";
?>

( function( $ ) {

	var id = '<?php echo $this->id; ?>-container';
	var url = '<?php echo get_option( 'siteurl' ), '/wp-content/plugins/galaxy-zoo/galaxy-zoo-proxy.php'; ?>';
	var query_vars = {};
	query_vars['image_size'] = <?php echo $image_size; ?>;
	query_vars['number_of_favorites'] = '<?php echo $number_of_favorites ; ?>';
	query_vars['type'] = '<?php echo WIDGET_TYPE_GALAXY_ZOO_FAVORITES; ?>';
	
	var widget = new GALAXY_ZOO.Widget();
	
	if ( $( '#<?php echo $this->id; ?>-container' ) ){
		widget.load( url, id, query_vars );
	}
	else { 
		$(document).ready(function(){
			widget.load( url, id, query_vars );
		});
	}

})( jQuery );

<?php
			echo '</script>', "\n";
		}
		else {
			GalaxyZooFavoritesWidget::printWidget(
				$number_of_favorites,
				$image_size
			);
		}
		
		echo '</div>', "\n";
		
		
		/* After widget (defined by themes). */
		echo $after_widget;
	}
 
 	/**
	 * Saves the widget
	 * 
	 * @since 0.9 
	 */
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		if ( is_numeric( $new_instance['image_size'] ) ){
			$instance['image_size'] = intval( $new_instance['image_size'] );
		}
		
		$instance['number_of_favorites'] = intval( $new_instance['number_of_favorites'] );
		if ( $instance['number_of_favorites'] > 20 ){ $instance['number_of_favorites'] = 20; }
		if ( $instance['number_of_favorites'] < 1 ){ $instance['number_of_favorites'] = 1; }

		$instance['use_ajax'] = isset( $new_instance['use_ajax'] );
		
		return $instance;
		
	}
 
 	/**
	 * Widget form in back-end
	 * 
	 * @since 0.9 
	 */
	function form( $instance ) {
		
		/* Set up some default widget settings. */
		$defaults = array( 
			'title' => GALAXY_ZOO_FAVORITES_WIDGET_NAME,
			'image_size' => 80, 
			'number_of_favorites' => 2,
			'use_ajax' => TRUE
		);
		$instance = wp_parse_args( (array) $instance, $defaults );		
		
?>		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'image_size' ); ?>">Image Size (in pixels):</label>
			<input id="<?php echo $this->get_field_id( 'image_size' ); ?>" name="<?php echo $this->get_field_name( 'image_size' ); ?>" value="<?php echo $instance['image_size']; ?>" style="width:100%;" />
		</p>		
		
		<p>
			<label for="<?php echo $this->get_field_id( 'number_of_favorites' ); ?>">Number of favorites (max. 20):</label>
			<input id="<?php echo $this->get_field_id( 'number_of_favorites' ); ?>" name="<?php echo $this->get_field_name( 'number_of_favorites' ); ?>" value="<?php echo $instance['number_of_favorites']; ?>" style="width:100%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'use_ajax' ); ?>">Use AJAX (recommended):</label>
			<input type="checkbox" <?php echo ( $instance['use_ajax'] === TRUE ) ? 'checked="checked"' : ''; ?> id="<?php echo $this->get_field_id( 'use_ajax' ); ?>" name="<?php echo $this->get_field_name( 'use_ajax' ); ?>" />
		</p>
				
<?php
	}
}


?>