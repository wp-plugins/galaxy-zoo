<?php

require_once( 'DataManager.php' );
require_once( 'GalaxyZooConstants.php' );

/**
 * @author Tomas Vorobjov
 * @version 1.0
 * @date 05 Oct 2009
 * 
 * @file GalaxZooWidget.php
 * 
 * This class provides methods for the Galaxy Zoo widget
 */
class GalaxyZooWidget extends WP_Widget {
	
	/**
	 * Creates new instance of the GalaxZooWidget
	 * 
	 * @since 0.9 
	 */
	function GalaxyZooWidget() {
		
		$widget_ops = array(
			'classname' => 'widget-galaxy-zoo', 
			'description' => 'Galaxy Zoo Widget' 
		);
		
		$this->WP_Widget( 'galaxy-zoo', 'Galaxy Zoo', $widget_ops );		
		
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
		wp_enqueue_style('galaxy-zoo-widget', $path . 'css/galaxy-zoo-widget.css', 'global', GALAXY_ZOO_VERSION, 'screen' );

	}	
	
// ----------------------------- WP Hooks - END -------------------------- //

	/**
	 * Prints the content of the widget. This function is mainly here for the
	 * use by AJAX calls
	 * 
	 * @param boolean $show_stats
	 * @param boolean $show_username
	 * @param boolean $show_joined
	 * @param boolean $show_latest_galaxy
	 * 
	 * @since	0.9
	 */
	public static function printWidget( $show_stats, $show_username, $show_joined, $show_latest_galaxy ){
		
		/* Show stats. */
		if ( $show_stats ){
			
			$data = DataManager::getStatsData();
			
			if ( $data !== FALSE ){
				
				if ( $show_username ){
					echo '<p class="galaxy-zoo-widget-username"><span class="widget-galaxy-zoo-orange">Name:</span> ', $data->name ,'</p>', "\n";
				}
				
				if ( $show_joined ){
					$time = strtotime( $data->joined );
					echo '<p class="galaxy-zoo-widget-joined"><span class="widget-galaxy-zoo-orange">Joined:</span> ', date( get_option( OPTIONS_DATE_FORMAT ), $time ) ,'</p>', "\n";
				}
				
				echo '<p class="galaxy-zoo-widget-count"><span class="widget-galaxy-zoo-orange">Classification count:</span> ', $data->classifications ,'</p>', "\n";
				
				/* Show Latest Galaxy. */
				if ( $show_latest_galaxy ){
					
					$time = strtotime( $data->last_active );
					echo '<p class="galaxy-zoo-widget-last-text"><span class="widget-galaxy-zoo-orange">Last classified:</span> ', date( get_option( OPTIONS_DATE_FORMAT ), $time ) ,'</p>', "\n";
									
					$last_galaxy = DataManager::getGalaxyData( $data->last_classified );
					
					if ( $last_galaxy !== FALSE ){					
						echo '<a href="', $last_galaxy->external_ref ,'" target="_blank" rel="nofollow"><img class="galaxy-zoo-widget-last-image" src="', $last_galaxy->location ,'" /></a>', "\n";
						$parts = split( 'id=', $last_galaxy->external_ref );
						$sdss_id = $parts[1];
						echo '<p align="center" class="galaxy-zoo-widget-last-info"><a href="', $last_galaxy->external_ref ,'" target="_blank" rel="nofollow">SDSS ', $sdss_id ,'</a></p>';
					}
					
				}				
			}
			else {
				echo '<p class="error">Couldn\'t retrieve data from Galaxy Zoo.</p>', "\n";
			}
			
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
		$show_username = $instance['show_username'];
		$show_joined = $instance['show_joined'];
		$show_stats = $instance['show_stats'];
		$show_latest_galaxy = $instance['show_latest_galaxy'];
		$use_ajax = $instance['use_ajax'];
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
		if ( $title ){
			echo $before_title . $title . $after_title;
		}

		echo '<div id="', $this->id ,'-container" class="widget-galaxy-zoo-content">', "\n";

		if ( $use_ajax ){
			echo '<div class="widget-galaxy-zoo-loader"></div>', "\n";
			echo '<script type="text/javascript" language="Javascript">', "\n";
?>

( function( $ ) {

	var id = '<?php echo $this->id; ?>-container';
	var url = '<?php echo get_option( 'siteurl' ), '/wp-content/plugins/galaxy-zoo/galaxy-zoo-proxy.php'; ?>';
	var query_vars = {};
	query_vars['show_username'] = '<?php echo ( $show_username ) ? 'true' : 'false' ; ?>';
	query_vars['show_joined'] = '<?php echo ( $show_joined ) ? 'true' : 'false' ; ?>';
	query_vars['show_stats'] = '<?php echo ( $show_stats ) ? 'true' : 'false' ; ?>';
	query_vars['show_latest_galaxy'] = '<?php echo ( $show_latest_galaxy ) ? 'true' : 'false' ; ?>';
	query_vars['type'] = '<?php echo WIDGET_TYPE_GALAXY_ZOO; ?>';
	
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
			GalaxyZooWidget::printWidget(
				$show_stats, 
				$show_username, 
				$show_joined, 
				$show_latest_galaxy
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
		$instance['show_username'] = isset( $new_instance['show_username'] );
		$instance['show_joined'] = isset( $new_instance['show_joined'] );
		$instance['show_stats'] = isset( $new_instance['show_stats'] );
		$instance['show_latest_galaxy'] = isset( $new_instance['show_latest_galaxy'] );
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
			'title' => GALAXY_ZOO_WIDGET_NAME,
			'show_username' => TRUE,
			'show_joined' => TRUE,
			'show_stats' => TRUE, 
			'show_latest_galaxy' => TRUE, 
			'use_ajax' => TRUE 
		);
		$instance = wp_parse_args( (array) $instance, $defaults );		
		
?>		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show_username' ); ?>">Show Username:</label>
			<input type="checkbox" <?php echo ( $instance['show_username'] === TRUE ) ? 'checked="checked"' : ''; ?> id="<?php echo $this->get_field_id( 'show_username' ); ?>" name="<?php echo $this->get_field_name( 'show_username' ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'show_joined' ); ?>">Show Joined Time:</label>
			<input type="checkbox" <?php echo ( $instance['show_joined'] === TRUE ) ? 'checked="checked"' : ''; ?> id="<?php echo $this->get_field_id( 'show_joined' ); ?>" name="<?php echo $this->get_field_name( 'show_joined' ); ?>" />
		</p>
				
		<p>
			<label for="<?php echo $this->get_field_id( 'show_stats' ); ?>">Show Stats:</label>
			<input type="checkbox" <?php echo ( $instance['show_stats'] === TRUE ) ? 'checked="checked"' : ''; ?> id="<?php echo $this->get_field_id( 'show_stats' ); ?>" name="<?php echo $this->get_field_name( 'show_stats' ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'show_latest_galaxy' ); ?>">Show Latest Galaxy:</label>
			<input type="checkbox" <?php echo ( $instance['show_latest_galaxy'] === TRUE ) ? 'checked="checked"' : ''; ?> id="<?php echo $this->get_field_id( 'show_latest_galaxy' ); ?>" name="<?php echo $this->get_field_name( 'show_latest_galaxy' ); ?>" />
		</p>
		
		<br />
		
		<p>
			<label for="<?php echo $this->get_field_id( 'use_ajax' ); ?>">Use AJAX (recommended):</label>
			<input type="checkbox" <?php echo ( $instance['use_ajax'] === TRUE ) ? 'checked="checked"' : ''; ?> id="<?php echo $this->get_field_id( 'use_ajax' ); ?>" name="<?php echo $this->get_field_name( 'use_ajax' ); ?>" />
		</p>		
		
<?php
	}
}


?>