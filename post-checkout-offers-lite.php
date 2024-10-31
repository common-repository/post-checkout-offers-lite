<?php
/**
*
* @link              https://makewebbetter.com
* @since             1.0.0
* @package           Post_Checkout_Offers_Lite
*
* @wordpress-plugin
* Plugin Name:       Post Checkout Offers Lite
* Plugin URI:        https://makewebbetter.com/woocommerce-post-checkout-offers/
* Description:       Increases your woocommerce store sales instantly by showing upsell offers on purchased products after checkout. Users can add these upsell products in to cart and pay later.                   
* Version:           1.0.1
* Author:            MakeWebBetter
* Author URI:        https://makewebbetter.com
* License:           GPL-3.0+
* License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
* Text Domain:       post_checkout_offers_lite
* Domain Path:       /languages
* Requires at least: 4.4
* Tested up to:      5.4
* WC requires at least: 3+
* WC tested up to:   4.0.1
*/



// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$activated = true;

if (function_exists('is_multisite') && is_multisite()){
	
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
	if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) )
	{
		$activated = false;
	}
}
else{
	
	if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
	{
		$activated = false;
	}
}

if($activated){  
	
	if(!defined( 'MWB_WCPCOLITE_URL' )){
		define('MWB_WCPCOLITE_URL', plugin_dir_url( __FILE__ ));
	} 
	
	if(!defined( 'MWB_WCPCOLITE_DIRPATH' )){
		define('MWB_WCPCOLITE_DIRPATH', plugin_dir_path( __FILE__ ));
	} 
	
	if(!defined( 'MWB_WCPCOLITE_VERSION' )){
		define('MWB_WCPCOLITE_VERSION', '1.0.1');
	} 
	
	add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'mwb_wcpcolite_plugin_settings_link'); 
	
	function mwb_wcpcolite_plugin_settings_link( $links ) {
		
		$plugin_links= array('<a href="' .
		admin_url( 'admin.php?page=mwb-wcpcolite-settings' ) .
		'">' . __("Settings","post_checkout_offers_lite") .'</a>');
		return array_merge($plugin_links,$links);
	} 
	
	
	/**
	* The code that runs during plugin activation.
	* This action is documented in includes/class-post-checkout-offers-lite-activator.php
	*/
	function activate_post_checkout_offers_lite() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-post-checkout-offers-lite-activator.php';
		Post_Checkout_Offers_Lite_Activator::activate();
	} 

	register_activation_hook( __FILE__, 'activate_post_checkout_offers_lite' );

	/**
	* The core plugin class that is used to define internationalization,
	* admin-specific hooks, and public-facing site hooks.
	*/
	require plugin_dir_path( __FILE__ ) . 'includes/class-post-checkout-offers-lite.php'; 

	/**
	* Begins execution of the plugin.
	*
	* Since everything within the plugin is registered via hooks,
	* then kicking off the plugin from this point in the file does
	* not affect the page life cycle.
	*
	* @since    1.0.0
	*/
	function run_post_checkout_offers_lite() {
		
		$plugin = new Post_Checkout_Offers_Lite();
		$plugin->run();
		
	}
	run_post_checkout_offers_lite();
}
else{

	/**
	 * Show warning message if woocommerce is not install
	 * @since 1.0.0
	 * @name mwb_wcpcolite_plugin_error_notice()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */

	function mwb_wcpcolite_plugin_error_notice()
 	{ ?>
 		<div class="error notice is-dismissible">
 			<p><?php _e('WooCommerce is not activated, Please activate WooCommerce first to install Post Checkout Offers Lite','post_checkout_offers_lite'); ?></p>
   		</div>
   		<style>
   		#message{display:none;}
   		</style>
   	<?php 
 	} 

 	add_action('admin_init','mwb_wcpcolite_plugin_deactivate');  

 	/**
 	 * Call Admin notices
 	 * 
 	 * @name mwb_wcpcolite_plugin_deactivate()
 	 * @author makewebbetter<webmaster@makewebbetter.com>
 	 * @link https://www.makewebbetter.com/
 	 */ 	
  	function mwb_wcpcolite_plugin_deactivate(){
	
	   deactivate_plugins( plugin_basename( __FILE__ ) );
	   add_action('admin_notices','mwb_wcpcolite_plugin_error_notice');
	}
}
	
	
	

	

	

	

	