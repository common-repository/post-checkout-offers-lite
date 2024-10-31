<?php

/**
* Fired during plugin activation
*
* @link       https://makewebbetter.com
* @since      1.0.0
*
* @package    Post_Checkout_Offers_Lite
* @subpackage Post_Checkout_Offers_Lite/includes
*/

/**
* Fired during plugin activation.
*
* This class defines all code necessary to run during the plugin's activation.
*
* @since      1.0.0
* @package    Post_Checkout_Offers_Lite
* @subpackage Post_Checkout_Offers_Lite/includes
* @author     MakeWebBetter <webmaster@makewebbetter.com>
*/
class Post_Checkout_Offers_Lite_Activator {
	
	/**
	*
	* @since    1.0.0
	*/
	public static function activate() { 
		
		if(empty( get_option( "mwb_wcpcolite_funnel_default_offer_page", "" ) )) {
			
			$mwb_wcpcolite_funnel_page = array(
				'comment_status' 		=> 'closed',
				'ping_status' 			=> 'closed',
				'post_content' 			=> '[mwb_wcpcolite_funnel_default_offer_page]',
				'post_name' 			=> 'special-discount-offer',
				'post_status' 			=> 'publish',
				'post_title' 			=> 'Special Discount Offer',
				'post_type' 			=> 'page',
			);
			
			$mwb_wcpcolite_post = wp_insert_post( $mwb_wcpcolite_funnel_page );
			$mwb_wcpcolite_default_offer_page = $mwb_wcpcolite_post;
			update_option( "mwb_wcpcolite_funnel_default_offer_page", $mwb_wcpcolite_default_offer_page ); 
		}
	}
	
}
