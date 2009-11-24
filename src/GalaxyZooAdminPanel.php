<?php

require_once( 'GalaxyZooConstants.php' );

/**
 * @author Tomas Vorobjov
 * @version 1.0
 * @date 05 Oct 2009
 * 
 * @file GalaxyZooAdminPanel.php
 * 
 * This class provides functionality for the wordpress dashboard admin
 * panel for the Galaxy Zoo wordpress plugin
 */
class GalaxyZooAdminPanel {


	/**
	 * Creates a new instance of GalaxyZooAdminPanel
	 * 
	 * @since	0.9
	 * 
	 */
	function GalaxyZooAdminPanel(){
		
		if ( get_option( OPTIONS_DATE_FORMAT ) == '' ){
			update_option( OPTIONS_DATE_FORMAT, 'm/d/Y' );	
		}
				
		add_menu_page(
			"GalaxyZoo", 
			"GalaxyZoo", 
			8, 
			"galaxyzoo/manage.php", 
			array( &$this, 'menuPageHandler' ) 
		);
		
		if ( isset( $_POST['Submit'] ) ){
			$this->updateOptions();
		}
		
	}

	/**
	 * This function is executed when the user clicks on the Galaxy Zoo
	 * link in the Wordpress Dashboard
	 */
	function menuPageHandler(){
		
?>		
	
	<div class="wrap">
		
		<div id="icon-options-general" class="icon32"><br/></div>		
		
		<h2>Galaxy Zoo Settings</h2>

<?php if ($custom_msg) { ?>
		<div class="updated fade below-h2" id="message" style="background-color: rgb(255, 251, 204);"><p><?=$custom_msg;?></p></div>
<?php } ?>

		<form method="post" class="" action="?page=galaxyzoo/manage.php"; ?>
		<?php wp_nonce_field(); ?>
			
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="api_key">Galaxy Zoo API key</label>
						</th>
						<td>
							<input id="api_key" class="regular-text code" type="text" value="<?php echo get_option( OPTIONS_API_KEY ); ?>" name="api_key" />
							<span class="description">
								You can create an API key on your Galaxy Zoo account page
							</span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="user_id">Galaxy Zoo User ID</label>
						</th>
						<td>
							<input id="user_id" class="regular-text code" type="text" value="<?php echo get_option( OPTIONS_USER_ID ); ?>" name="user_id" />
							<span class="description">
								You Galaxy Zoo user ID
							</span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="date_format">Galaxy Zoo Date Format</label>
						</th>
						<td>
							<input id="date_format" class="small-text" type="text" value="<?php echo get_option( OPTIONS_DATE_FORMAT ); ?>" name="date_format" />
							<?php echo date( get_option( OPTIONS_DATE_FORMAT ) ); ?>
							<p><a href="http://uk3.php.net/manual/en/function.date.php" target="_blank">Documentation on date formatting</a>. Click "Save Changes" to update sample output.</p>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input class="button-primary" type="submit" value="Save Changes" name="Submit" id="Submit" />
			</p>
		</form>		
		
	</div>
	
<?php
	}
	
	/**
	 * Updates plugin options after user submits the settings page form
	 * @return 
	 */
	function updateOptions(){
		
		if ( isset( $_POST['api_key'] ) ){
			update_option( OPTIONS_API_KEY, trim( $_POST['api_key'] ) );
		}
		if ( isset( $_POST['user_id'] ) ){
			update_option( OPTIONS_USER_ID, trim( $_POST['user_id'] ) );
		}

		if ( isset( $_POST['date_format'] ) ){
			update_option( OPTIONS_DATE_FORMAT, trim( $_POST['date_format'] ) );
		}		
	}
}

?>