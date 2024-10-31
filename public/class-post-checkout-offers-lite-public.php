<?php

/**
* The public-facing functionality of the plugin.
*
* @link       https://makewebbetter.com
* @since      1.0.0
*
* @package    Post_Checkout_Offers_Lite
* @subpackage Post_Checkout_Offers_Lite/public
*/

/**
*
* @package    Post_Checkout_Offers_Lite
* @subpackage Post_Checkout_Offers_Lite/public
* @author     MakeWebBetter <webmaster@makewebbetter.com>
*/
class Post_Checkout_Offers_Lite_Public {
	
	/**
	* The ID of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      string    $plugin_name    The ID of this plugin.
	*/
	private $plugin_name;
	
	/**
	* The version of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      string    $version    The current version of this plugin.
	*/
	private $version;
	
	/**
	* Initialize the class and set its properties.
	*
	* @since    1.0.0
	* @param      string    $plugin_name       The name of the plugin.
	* @param      string    $version    The version of this plugin.
	*/
	public function __construct( $plugin_name, $version ) {
		
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
	}
	
	/**
	* Register the stylesheets for the public-facing side of the site.
	*
	* @since    1.0.0
	*/
	public function enqueue_styles() {
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/post-checkout-offers-lite-public.css', array(), $this->version, 'all' );
		
	}
	
	/**
	* Register the JavaScript for the public-facing side of the site.
	*
	* @since    1.0.0
	*/
	public function enqueue_scripts() {
		
		wp_register_script('mwb_wcpcolite_public_script', plugin_dir_url( __FILE__ ) . 'js/post-checkout-offers-lite-public.js', array( 'jquery' ), $this->version, false);
		wp_localize_script( 'mwb_wcpcolite_public_script', 'ajax_url', admin_url('admin-ajax.php') );
		wp_enqueue_script('mwb_wcpcolite_public_script');
		
	} 
	
	/**
	* changing the checkout url if a funnel exists for purchased products
	*
	* @since   1.0.0
	* @param 	string  $result  the url to change
	* @param   object  $order   current order of store
	* @param   string  $result  url of offers page 
	*/
	
	public function mwb_wcpcolite_process_funnel_offers( $result, $order ) { 
		
		
		global $woocommerce; 
		
		$post_checkout_data = WC()->session->get('mwb_wcpcolite_post_checkout_data' ,array() );  
		
		$gateways = $woocommerce->payment_gateways->get_available_payment_gateways(); 
		
		$allowed_gateways = array('bacs' , 'cheque' , 'cod') ; 
		
		$mwb_wcpcolite_all_funnels = get_option( "mwb_wcpcolite_funnels_list" );
		
		$mwb_wcpcolite_flag = 0;
		
		$mwb_wcpcolite_proceed = false;
		
		$order_id = $order->get_id(); 
		
		$mwb_wcpcolite_offer_order = (get_post_meta( $order_id, 'mwb_wcpcolite_upsell_offer_order', true )) ? get_post_meta( $order_id, 'mwb_wcpcolite_upsell_offer_order', true ) : false ;
		
		if($mwb_wcpcolite_offer_order) {
			
			return $result;
		} 
		
		$payment_method = $order->get_payment_method();
		
		if(!in_array($payment_method , $allowed_gateways))
		{
			
			return $result;
		}
		
		if( empty( $mwb_wcpcolite_all_funnels ) )
		{
			return $result;
		} 
		
		elseif( empty( $order ) )
		{
			return $result;
		}  
		
		elseif( !empty( $_REQUEST["mwb_wcpcolite_buy"] ) )
		{
			return $result;
		} 
		
		elseif( !empty( $_REQUEST["wcpcol_ns"] ) )
		{
			return $result;
		}  
		
		if( !empty( $order ) )
		{
			
			$payment_method = $order->get_payment_method();
			
			$mwb_wcpcolite_placed_order_items = $order->get_items();
			
			$wcpcol_ok = $order->get_order_key();
			
			$wcpcol_ofd = 0;
			
			if( is_array( $mwb_wcpcolite_all_funnels ) )
			{
				foreach( $mwb_wcpcolite_all_funnels as $mwb_wcpcolite_single_funnel => $mwb_wcpcolite_funnel_data )
				{
					
					$mwb_wcpcolite_funnel_target_products = $mwb_wcpcolite_all_funnels[$mwb_wcpcolite_single_funnel]["mwb_wcpcolite_target_pro_ids"];
					
					$mwb_wcpcolite_existing_offers = !empty( $mwb_wcpcolite_funnel_data["mwb_wcpcolite_applied_offer_number"] )?$mwb_wcpcolite_funnel_data["mwb_wcpcolite_applied_offer_number"]:array();
					
					if( count( $mwb_wcpcolite_existing_offers ) ){
						foreach( $mwb_wcpcolite_existing_offers as $key=>$value ){
							$wcpcol_ofd = $key;
							break;
						}
					}
					
					if( is_array( $mwb_wcpcolite_placed_order_items ) )
					{
						foreach( $mwb_wcpcolite_placed_order_items as $item_key => $mwb_wcpcolite_single_item )
						{
							$mwb_wcpcolite_product_id = $mwb_wcpcolite_single_item->get_product_id();
							
							if( in_array( $mwb_wcpcolite_product_id, $mwb_wcpcolite_funnel_target_products ) )
							{
								
								if( !empty( $mwb_wcpcolite_all_funnels[$mwb_wcpcolite_single_funnel]["mwb_wcpcolite_products_in_offer"][$wcpcol_ofd] ) )
								{
									$mwb_wcpcolite_offer_page_id = get_option( "mwb_wcpcolite_funnel_default_offer_page" );
									
									if( get_post_status( $mwb_wcpcolite_offer_page_id ) !== "trash" && get_post_status( $mwb_wcpcolite_offer_page_id ) !== false )
									{
										if( !empty( $mwb_wcpcolite_all_funnels[$mwb_wcpcolite_single_funnel]["mwb_wcpcolite_offer_custom_page_url"][$wcpcol_ofd] ) )
										{
											$result = $mwb_wcpcolite_all_funnels[$mwb_wcpcolite_single_funnel]["mwb_wcpcolite_offer_custom_page_url"][$wcpcol_ofd];
										}
										else
										{
											$mwb_wcpcolite_offer_page_url = get_page_link($mwb_wcpcolite_offer_page_id);
											
											$result = $mwb_wcpcolite_offer_page_url;
										}
									}
									elseif( !empty( $mwb_wcpcolite_all_funnels[$mwb_wcpcolite_single_funnel]["mwb_wcpcolite_offer_custom_page_url"][$wcpcol_ofd] ) )
									{
										$result = $mwb_wcpcolite_all_funnels[$mwb_wcpcolite_single_funnel]["mwb_wcpcolite_offer_custom_page_url"][$wcpcol_ofd];
									}
									else
									{
										return $result;
									}
									
									$mwb_wcpcolite_nonce = wp_create_nonce("funnel_offers");
									
									$result.='?wcpcol_ns='.$mwb_wcpcolite_nonce.'&wcpcol_fid='.$mwb_wcpcolite_single_funnel.'&wcpcol_ok='.$wcpcol_ok.'&wcpcol_ofd='.$wcpcol_ofd;
									
									$mwb_wcpcolite_flag = 1;
									
									break;
								}
							}
						}
					}
					
					if($mwb_wcpcolite_flag == 1)
					{
						break;
					}
				}
			}
			
			return $result;
			
		}
		
		return $result;
	} 
	
	/**
	* if customer rejects to buy upsell products then redirect to thankyou page
	*
	* @since    1.0.0
	*/
	
	public function mwb_wcpcolite_process_the_funnel() { 
		
		
		if( isset( $_GET["wcpcol_th"] ) && $_GET["wcpcol_th"] == 1 && isset( $_GET["wcpcol_ofd"] ) && isset( $_GET["wcpcol_fid"] ) && isset( $_GET["wcpcol_ok"] ) && isset( $_GET["wcpcol_ns"] ) )
		{
			$offer_id = sanitize_text_field( $_GET["wcpcol_ofd"] );
			
			$funnel_id = sanitize_text_field( $_GET["wcpcol_fid"] );
			
			$order_key = sanitize_text_field( $_GET["wcpcol_ok"] );
			
			$wp_nonce = sanitize_text_field( $_GET["wcpcol_ns"] );
			
			$mwb_wcpcolite_all_funnels = get_option( "mwb_wcpcolite_funnels_list" );
			
			$mwb_wcpcolite_action_on_no = isset( $mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_attached_offers_on_no"] )?$mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_attached_offers_on_no"]:array();
			
			$mwb_wcpcolite_check_action = isset( $mwb_wcpcolite_action_on_no[$offer_id] )?$mwb_wcpcolite_action_on_no[$offer_id]:"";  
			
			$post_checkout_data = WC()->session->get('mwb_wcpcolite_post_checkout_data' , array()); 
			
			if( $mwb_wcpcolite_check_action == "thanks" )
			{  
				
				
				if(empty($post_checkout_data['upsell_items'])) { 
					
					$order_id = wc_get_order_id_by_order_key( $order_key );
					
					$order = wc_get_order($order_id);
					
					$order_received_url = $order->get_checkout_order_received_url();
					
					wp_redirect($order_received_url);   
					
					exit();
				} 
				else { 
					
					$checkout_url = wc_get_checkout_url(); 
					
					$checkout_url = $checkout_url.'?wcpcol_ns='.$wp_nonce.'&mwb_wcpco=true' ;
					
					wp_redirect($checkout_url);   
					
					exit();
					
				}
				
				
			}
			elseif( $mwb_wcpcolite_check_action != "thanks" )
			{
				
				$offer_id = $mwb_wcpcolite_check_action;
				
				$mwb_wcpcolite_upcoming_offer = $mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_products_in_offer"][$offer_id];
				
				if( empty( $mwb_wcpcolite_upcoming_offer ) )
				{   
					if(empty($post_checkout_data['upsell_items'])) {  
						
						$order_id = wc_get_order_id_by_order_key( $order_key );
						
						$order = wc_get_order( $order_id );
						
						$order_received_url = $order->get_checkout_order_received_url();
						
						wp_redirect( $order_received_url );
						
						exit();
						
					} 
					else {
						
						$checkout_url = wc_get_checkout_url();  
						
						$checkout_url = $checkout_url.'?wcpcol_ns='.$wp_nonce.'&mwb_wcpco=true' ;
						
						wp_redirect($checkout_url);   
						
						exit();
					}
					
				}
				
				$mwb_wcpcolite_offer_page_id = get_option("mwb_wcpcolite_funnel_default_offer_page");
				
				if( get_post_status( $mwb_wcpcolite_offer_page_id ) !== false && get_post_status( $mwb_wcpcolite_offer_page_id ) !== "trash" )
				{	
					if( !empty( $mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_offer_custom_page_url"][$offer_id] ) )
					{
						$mwb_wcpcolite_next_offer_url = $mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_offer_custom_page_url"][$offer_id];
					}
					else
					{
						$mwb_wcpcolite_offer_page_url = get_page_link($mwb_wcpcolite_offer_page_id);
						
						$mwb_wcpcolite_next_offer_url = $mwb_wcpcolite_offer_page_url;
					}
				}
				elseif( !empty( $mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_offer_custom_page_url"][$offer_id] ) )
				{
					$mwb_wcpcolite_next_offer_url = $mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_offer_custom_page_url"][$offer_id];
				}
				else
				{
					if(empty($post_checkout_data['upsell_items'])) {  
						
						$order_id = wc_get_order_id_by_order_key( $order_key );
						
						$order = wc_get_order( $order_id );
						
						$order_received_url = $order->get_checkout_order_received_url();
						
						wp_redirect( $order_received_url );
						
						exit();
						
					} 
					else {
						
						$checkout_url = wc_get_checkout_url();  
						
						$checkout_url = $checkout_url.'?wcpcol_ns='.$wp_nonce.'&mwb_wcpco=true' ;
						
						wp_redirect($checkout_url);   
						
						exit();
					}
				}
				
				$mwb_wcpcolite_next_offer_url =  $mwb_wcpcolite_next_offer_url.'?wcpcol_ns='.$wp_nonce.'&wcpcol_ofd='.$offer_id.'&wcpcol_ok='.$order_key.'&wcpcol_fid='.$funnel_id;
				
				wp_redirect( $mwb_wcpcolite_next_offer_url );
				
				exit;
			}
		}
	} 
	
	/**
	* adding shortcode for funnel offer page
	*
	* @since    1.0.0
	*/
	
	public function mwb_wcpcolite_create_funnel_offer_shortcode() {
		
		add_shortcode( 'mwb_wcpcolite_funnel_default_offer_page', array( $this, 'mwb_wcpcolite_funnel_offers_shortcode' ) );
	} 
	
	public function mwb_wcpcolite_funnel_offers_shortcode() {
		
		$result = ''; 
		
		if( isset( $_GET["wcpcol_ok"] ) )
		{
			$order_key = sanitize_text_field( $_GET["wcpcol_ok"] );
			
			$order_id = wc_get_order_id_by_order_key( $order_key );
			
			if( isset( $_GET["wcpcol_ofd"] ) && isset( $_GET["wcpcol_fid"] ) )
			{
				$offer_id = sanitize_text_field( $_GET["wcpcol_ofd"] );
				
				$funnel_id = sanitize_text_field( $_GET["wcpcol_fid"] );
				
				if( isset( $_GET["wcpcol_ns"] ) && wp_verify_nonce( $_GET["wcpcol_ns"] , "funnel_offers" ) )
				{
					$wp_nonce = sanitize_text_field( $_GET["wcpcol_ns"] );
					
					$mwb_wcpcolite_all_funnels = get_option( "mwb_wcpcolite_funnels_list" );
					
					$mwb_wcpcolite_buy_text = !empty( get_option( "mwb_wcpcolite_buy_text" ) )?get_option( "mwb_wcpcolite_buy_text" ):__("Add to my order","post_checkout_offers_lite");
					
					$mwb_wcpcolite_no_text = !empty( get_option( "mwb_wcpcolite_no_text" ) )?get_option( "mwb_wcpcolite_no_text" ):__("No,thanks","post_checkout_offers_lite");
					
					$mwb_wcpcolite_before_offer_price_text = !empty( get_option( "mwb_wcpcolite_before_offer_price_text" ) )?get_option( "mwb_wcpcolite_before_offer_price_text" ):__("Special Offer Price","post_checkout_offers_lite");
					
					$mwb_wcpcolite_offered_products	=	isset( $mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_products_in_offer"] )?$mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_products_in_offer"]:array();
					
					$mwb_wcpcolite_offered_discount	=	isset( $mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_offer_discount_price"] )?$mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_offer_discount_price"]:array();  
					
					$mwb_wcpcolite_next_offer_url	=	isset( $mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_offer_custom_page_url"] )?$mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_offer_custom_page_url"]:array();  
					
					$mwb_wcpcolite_buy_button_color = !empty( get_option( "mwb_wcpcolite_buy_button_color") )?get_option( "mwb_wcpcolite_buy_button_color" ):"black";
					
					$wcpcol_th_button_color = !empty(get_option("mwb_wcpcolite_thanks_button_color"))?get_option("mwb_wcpcolite_thanks_button_color"):"black";
					
					$result .= '<div class="mwb_wcpcolite_offer_container"><div class="woocommerce"><div class="mwb_wcpcolite_special_offers_for_you">';
					
					$mwb_wcpcolite_offer_banner_text = !empty( get_option("mwb_wcpcolite_offer_banner_text") )?get_option("mwb_wcpcolite_offer_banner_text"):__("Special Offer For You Only","post_checkout_offers_lite");   
					
					$result .='<div class="mwb_wcpcolite_special_offer_banner">
					<h1>'.trim($mwb_wcpcolite_offer_banner_text,'"').'</h1></div>';  
					
					$next_offer_text = __('Check Next Offer' , 'post_checkout_offers_lite') ; 
					
					global $product;
					
					foreach( $mwb_wcpcolite_offered_products[$offer_id] as $key => $mwb_wcpcolite_single_offered_product ):
						
						$mwb_wcpcolite_saved_product = $product;
						
						$mwb_wcpcolite_original_offered_product = wc_get_product( $mwb_wcpcolite_single_offered_product );
						
						$original_price = $mwb_wcpcolite_original_offered_product->get_price_html();  
						
						if( $mwb_wcpcolite_original_offered_product->is_type('simple') )
						{
							$mwb_wcpcolite_offered_product = $this->mwb_wcpcolite_change_offered_product_price($mwb_wcpcolite_original_offered_product,$mwb_wcpcolite_offered_discount[$offer_id]);
							
							$product = $mwb_wcpcolite_offered_product;
							
							$result .= '<div class="mwb_wcpcolite_main_wrapper">';
							
							$image = wp_get_attachment_image_src(get_post_thumbnail_id($mwb_wcpcolite_single_offered_product),'single-post-thubnail');
							
							if(!isset($image[0]))
							{
								$image = wc_placeholder_img();
								$result .= '<div class="mwb_wcpcolite_product_image">'.$image.'</div>';
							}
							else
							{
								$result .= '<div class="mwb_wcpcolite_product_image"><img src="'.$image[0].'"></div>';
							}
							
							$result .='<div class="mwb_wcpcolite_offered_product"><div class="mwb_wcpcolite_product_title"><h2>'.$product->get_title().'</h2></div>'; 
							
							$result .= '<div class="mwb_wcpcolite_offered_product_description">'.$product->get_description().'</div>';

							if(isset($mwb_wcpcolite_custom_details)){ 

								$result .= '<div class="mwb_wcpcolite_offered_product_description">'.do_shortcode( stripslashes( wp_filter_post_kses( $mwb_wcpcolite_custom_details[$offer_id] ) ) ).'
								</div>';

							}
							
							$result .= '<div class="mwb_wcpcolite_product_price">
							<h4>'.$mwb_wcpcolite_before_offer_price_text.' : '.$product->get_price_html().'</h4></div></div></div>';
							
							$result .= '<div class="mwb_wcpcolite_offered_product_actions">
							<form id="mwb_wcpcolite_offer_form" method="post">
							<input type="hidden" name="wcpcol_ns" value="'.$wp_nonce.'">
							<input type="hidden" name="wcpcol_fid" value="'.$funnel_id.'">
							<input type="hidden" name="product_id" value="'.absint($product->get_id()).'">
							<input type="hidden" name="wcpcol_ofd" value="'.$offer_id.'">
							<input type="hidden" name="wcpcol_ok" value="'.$order_key.'">'; 
							
							$result .= '<button style="background-color:'.$mwb_wcpcolite_buy_button_color.'" class="mwb_wcpcolite_buy" type="submit" name="mwb_wcpcolite_buy">'.$mwb_wcpcolite_buy_text.'</button>
							<a style="color:'.$wcpcol_th_button_color.'" 
							class="mwb_wcpcolite_skip" href="?wcpcol_ns='.$wp_nonce.
							'&wcpcol_th=1&wcpcol_ok='. $order_key.'&wcpcol_ofd='.$offer_id.'
							&wcpcol_fid='.$funnel_id.'">'.$mwb_wcpcolite_no_text.'</a>
							</form></div>
							</div></div>' ;
							
							?>
							<script type="text/javascript">
							var mwb_wcpcolite_offer_bought = false;
							jQuery(document).ready(function(){
								jQuery('.mwb_wcpcolite_buy').on('click',function(e)
								{
									if( mwb_wcpcolite_offer_bought === false )
									{
										jQuery('#mwb_wcpcolite_offer_form').submit();
									}
									else
									{
										e.preventDefault();
										return;
									}
									mwb_wcpcolite_offer_bought = true;
								});
							});
							</script>
							<?php
						}
						
						$product = $mwb_wcpcolite_saved_product; 
						
						break;
						
					endforeach;
					
					$result .= '</div></div></div>';
				}
				else
				{
					$error_msg = __( 'You ran out of the special offers session.', 'post_checkout_offers_lite' );
					
					$link_text = __( 'Go to the "Order details" page.', 'post_checkout_offers_lite' );
					
					$error_msg = apply_filters( "mwb_wcpcolite_error_message", $error_msg );
					
					$link_text = apply_filters( "mwb_wcpcolite_order_details_link_text", $link_text );
					
					$order_received_url = wc_get_endpoint_url( 'order-received', $order_id, wc_get_page_permalink( 'checkout' ) );
					
					$order_received_url = add_query_arg( 'key', $order_key, $order_received_url );
					
					$result .= $error_msg.'<a href="'.$order_received_url.'" class="button">'.$link_text.'</a>';
				}
			}
			else
			{
				$error_msg = __( 'You ran out of the special offers session.', 'post_checkout_offers_lite' );
				
				$link_text = __( 'Go to the "Order details" page.', 'post_checkout_offers_lite' );
				
				$error_msg = apply_filters( "mwb_wcpcolite_error_message", $error_msg );
				
				$link_text = apply_filters( "mwb_wcpcolite_order_details_link_text", $link_text );
				
				$order_received_url = wc_get_endpoint_url('order-received',$order_id, wc_get_page_permalink( 'checkout' ) );
				
				$order_received_url = add_query_arg( 'key', $order_key, $order_received_url );
				
				$result .= $error_msg.'<a href="'.$order_received_url.'" class="button">'.$link_text.'</a>';
			}
		}
		
		if( !isset( $_GET["wcpcol_ok"] ) || !isset( $_GET["wcpcol_ofd"] ) || !isset( $_GET["wcpcol_fid"] ) )
		{
			$mwb_wcpcolite_no_offer_text = !empty( get_option( "mwb_wcpcolite_no_offer_text" ) )?get_option( "mwb_wcpcolite_no_offer_text" ):__("Sorry, you have no offers","post_checkout_offers_lite");
			
			$result .= "<h2>".trim( $mwb_wcpcolite_no_offer_text, '"' )."</h2>";
			
			$result .= '<a class="button wc-backward" href="'.esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ).'">'.__( 'Shop More', 'post_checkout_offers_lite' ).'</a>';
		}
		
		return $result;
	}  
	
	/**
	* applying offer on product price 
	*
	* @since    1.0.0
	* @param    object   $temp_product    object of product
	* @param    string   $price           offer price
	* @return   object   $temp_product    object of product with new offer price
	*/
	
	public function mwb_wcpcolite_change_offered_product_price( $temp_product, $price ) {
		
		
		if( empty( $price ) )
		{
			$price = '50%';
		}
		
		if( !empty( $temp_product ) )
		{
			$mwb_wcpcolite_product_price = $temp_product->get_price();
			
			if( strpos( $price, '%' ) !== FALSE )
			{
				$price = trim( $price, '%' );
				
				$price = floatval( $mwb_wcpcolite_product_price ) * ( floatval( $price ) / 100 );
				
				if( $mwb_wcpcolite_product_price > 0 )
				{
					$price = $mwb_wcpcolite_product_price - $price;
				}
				else
				{
					$price = $mwb_wcpcolite_product_price;
				}
				
				$temp_product->set_price( $price );
			}
			else
			{
				$price = floatval( $price );
				
				$temp_product->set_price( $price );
			}
		}
		
		return $temp_product;
	} 
	
	/**
	* processing the upsell offers payment on being purchased
	*
	* @since    1.0.0
	*/
	
	public function mwb_wcpcolite_charge_the_offer() {
		
		if( isset( $_POST["mwb_wcpcolite_buy"] ) )
		{   
			
			unset( $_POST["mwb_wcpcolite_buy"] );
			
			if( isset( $_POST["wcpcol_ns"] ) && isset( $_POST["wcpcol_ok"] ) && isset( $_POST["wcpcol_ofd"] ) && isset( $_POST["product_id"] ) && isset( $_POST["wcpcol_fid"] ) )
			{
				
				$order_key 	= sanitize_text_field( $_POST["wcpcol_ok"] );
				
				$wp_nonce 	= sanitize_text_field( $_POST["wcpcol_ns"] );
				
				$offer_id 	= sanitize_text_field( $_POST["wcpcol_ofd"] );
				
				$product_id = sanitize_text_field( $_POST["product_id"] );
				
				$funnel_id 	= sanitize_text_field( $_POST["wcpcol_fid"] );
				
				$order_id 	= wc_get_order_id_by_order_key( $order_key );
				
				$order_received_url = wc_get_endpoint_url( 'order-received', $order_id,  wc_get_page_permalink( 'checkout' ) );
				
				$order_received_url = add_query_arg( 'key', $order_key, $order_received_url );  
				
				
				if( !empty( $order_id ) )
				{
					$order = wc_get_order( $order_id );
				}
				
				if( !empty( $order ) )
				{
					$mwb_wcpcolite_purchased_product = wc_get_product( $product_id );
					
					if( !empty( $mwb_wcpcolite_purchased_product ) )
					{   
						//mwb 
						
						$result = $this->mwb_wcpcolite_add_to_cart_offer_product($product_id , $funnel_id ,$offer_id ,$order_id);  
						
					}
					if( $result )
					{   
						
						$mwb_wcpcolite_all_funnels = get_option( "mwb_wcpcolite_funnels_list" );
						
						$mwb_wcpcolite_buy_action	=	$mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_attached_offers_on_buy"];
						
						$url = "";
						
						if( $mwb_wcpcolite_buy_action[$offer_id] == "thanks" )
						{
							
							$checkout_url = wc_get_checkout_url();  
							$checkout_url = $checkout_url.'?wcpcol_ns='.$wp_nonce.'&mwb_wcpco=true' ;
							wp_redirect($checkout_url);
							
							exit;
						}
						elseif( $mwb_wcpcolite_buy_action[$offer_id] != "thanks" )
						{
							
							$offer_id = $mwb_wcpcolite_buy_action[$offer_id];
							
							$mwb_wcpcolite_upcoming_offer = $mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_products_in_offer"][$offer_id];
							
							if( empty( $mwb_wcpcolite_upcoming_offer ) )
							{	
								
								$url = $order->get_checkout_order_received_url();
							}
							else
							{   
								
								$mwb_wcpcolite_offer_page_id = get_option("mwb_wcpcolite_funnel_default_offer_page");
								
								if( get_post_status( $mwb_wcpcolite_offer_page_id ) !== false && get_post_status( $mwb_wcpcolite_offer_page_id ) !== "trash" )
								{
									if( !empty( $mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_offer_custom_page_url"][$offer_id] ) )
									{
										$mwb_wcpcolite_next_offer_url = $mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_offer_custom_page_url"][$offer_id]; 
										
									}
									else
									{
										$mwb_wcpcolite_offer_page_url = get_page_link( $mwb_wcpcolite_offer_page_id );
										
										$mwb_wcpcolite_next_offer_url = $mwb_wcpcolite_offer_page_url; 
										
									}
								}
								elseif( !empty( $mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_offer_custom_page_url"][$offer_id] ) )
								{
									$mwb_wcpcolite_next_offer_url = $mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_offer_custom_page_url"][$offer_id]; 
									
								}
								else
								{
									$url = $order->get_checkout_order_received_url(); 
									wp_redirect( $url );
									exit();
								}
								
								$mwb_wcpcolite_next_offer_url =  $mwb_wcpcolite_next_offer_url.'?wcpcol_ns='.$wp_nonce.'&wcpcol_ofd='.$offer_id.'&wcpcol_ok='.$order_key.'&wcpcol_fid='.$funnel_id;
								$url = $mwb_wcpcolite_next_offer_url;
							}
							wp_redirect( $url );
							exit;
						}
					}
					else
					{   
						wp_redirect( $order_received_url );   
						exit();
					}
				}
			}
		}
	}  

	/**
	* create session for if offer is accpeted
	*
	* @param $product_id product id of the product
	* @param $funnel_id  funnel id of the current funnel
	* @param $offer_id   offer id of the current funnel
	* @param $order_id   order id of the main order
	* @since    1.0.0
	*/
	
	public function mwb_wcpcolite_add_to_cart_offer_product($product_id , $funnel_id , $offer_id , $order_id ) {
		
		$upsell_data = WC()->session->get('mwb_wcpcolite_upsell_data' , array());
		
		if(!isset($upsell_data['upsell_parent_order_id'])) {
			$upsell_data['upsell_parent_order_id'] = $order_id ;
		}
		
		global $woocommerce;
		
		$result = FALSE; 
		
		global $product;
		
		$mwb_wcpcolite_saved_product = $product;
		
		$mwb_wcpcolite_purchased_product = wc_get_product( $product_id );
		
		$product = $mwb_wcpcolite_purchased_product;
		
		if( $product->is_purchasable() ) {   
			
			$upsell_prodcuts = array () ;
			$upsell_prodcuts['product_id'] = $product_id ;  
			$upsell_prodcuts['funnel_id'] = $funnel_id ;
			$upsell_prodcuts['offer_id'] = $offer_id ; 
			$upsell_data['upsell_items'][] = $upsell_prodcuts; 
			
			WC()->session->set('mwb_wcpcolite_upsell_data' , $upsell_data); 
			$result = TRUE; 
		}    
		return $result ;
	} 
	
	/**
	* @since    1.0.0
	* @param    $orders     query of orders on front end
	* @return   $orders     modified query of orders
	*/
	
	public function mwb_wcpcolite_my_account_my_orders_query($orders) {
		
		$orders['meta_key'] = "mwb_wcpcolite_upsell_parent_order";
		$orders['meta_compare'] = 'NOT EXISTS';
		return $orders;
	}  

	/**
	* add session products in to cart
	*
	* @since    1.0.0
	*/
	
	public function mwb_wcpcolite_add_to_cart_funnel_products () {  
		
		
		global $woocommerce;
		if(isset($_GET['mwb_wcpco']) &&  wp_verify_nonce( $_GET["wcpcol_ns"] , "funnel_offers" ) ) { 
			$upsell_data = WC()->session->get('mwb_wcpcolite_upsell_data' , array());  
			if(!empty($upsell_data)) {
				
				$funnel_products = array();
				$funnel_products = $upsell_data['upsell_items'] ;   
				$woocommerce->cart->empty_cart();
				$mwb_wcpcolite_all_funnels = get_option( "mwb_wcpcolite_funnels_list" );
				foreach ($funnel_products as $key => $value) { 
					
					$product_id = $value['product_id'] ;
					$offer_id = $value['offer_id'] ; 
					$funnel_id = $value['funnel_id'] ; 
					
					$mwb_wcpcolite_offered_discount	=	$mwb_wcpcolite_all_funnels[$funnel_id]["mwb_wcpcolite_offer_discount_price"][$offer_id];  
					$quantity = 1 ;
					$variation_id = 0 ; 
					$variation = array() ;  
					$price = $this->mwb_wcpcolite_get_cart_offer_price($mwb_wcpcolite_offered_discount , $product_id);
					$cart_item_data = array( 
						'upsell_price' => $price , 
					);   
					
					$woocommerce->cart->add_to_cart($product_id , $quantity, $variation_id , $variation, $cart_item_data);  
				}
			}
		}
	} 

	/**
	* change price of cart products
	*
	* @param $cart_object object of current cart
	* @since    1.0.0
	*/  
	
	public function mwb_wcpcolite_filter_upsell_product_price( $cart_object ) {  
		
		if(!WC()->session->__isset( "reload_checkout" )){
			foreach ( $cart_object->cart_contents as $key => $value ) {  
				if(isset($value["upsell_price"])) {
					$value['data']->set_price($value["upsell_price"]);
				}  
			}
		}  
	} 

	/**
	* get offer price of accepted offer product
	*
	* @param $discount discount ammount of offer
	* @param $product_id product id of the offer product
	* @since    1.0.0
	*/
	
	public function mwb_wcpcolite_get_cart_offer_price ( $discount , $product_id ) {	
		
		
		$current_price = wc_get_product( $product_id )->get_price();	
		$discount_price = 0 ;
		
		if( empty( $discount ) )
		{
			$discount = '50%';
		}  
		
		if( strpos( $discount, '%' ) !== FALSE )
		{
			$discount = trim( $discount, '%' ); 
			
			$discount = floatval( $current_price ) * ( floatval( $discount ) / 100 ); 
			
			if( $current_price > 0 )
			{
				$discount_price = $current_price - $discount;
			} 
			
			else
			{
				$discount_price = $current_price;
			} 
			
			
		}  
		else
		{
			$discount = floatval( $discount );
			
			$discount_price = $discount;
		} 
		return $discount_price ;
	} 
	
	/**
	* save meta for main order
	*
	* @param $order_id order id of parent order
	* @since    1.0.0
	*/
	public function mwb_wcpcolite_update_meta_for_upsell_orders ($order_id) {
		
		$upsell_data = WC()->session->get('mwb_wcpcolite_upsell_data' ,array() );
		if(!empty($upsell_data)){  
			if(isset($upsell_data['upsell_parent_order_id'])) {
				$mwb_wcpcolite_parent_order_id = $upsell_data['upsell_parent_order_id'] ;
				update_post_meta( $order_id, 'mwb_wcpcolite_upsell_parent_order', $mwb_wcpcolite_parent_order_id ); 
				WC()->session->__unset( 'mwb_wcpcolite_upsell_data' ); 
			}
		}  
		
		WC()->session->__unset( 'mwb_wcpcolite_upsell_data' ); 
		
	}

	/**
	* save meta for offer order
	*
	* @param $order_id  order id of offer order
	* @param $data      data of offer order
	* @since    1.0.0
	*/
	
	public function mwb_wcpcolite_update_offer_order_meta( $order_id ,  $data ){
		
		$upsell_data = WC()->session->get('mwb_wcpcolite_upsell_data' ,array() ); 
		
		if(!empty($upsell_data) && isset($upsell_data['upsell_parent_order_id'])){
			
			update_post_meta( $order_id, 'mwb_wcpcolite_upsell_offer_order' , true ) ;
		}
	} 

	/**
	* show offer notice at the bottom of the page
	*
	* @since    1.0.0
	*/
	
	public function mwb_wcpcolite_show_offer_noitce(){
		
		$upsell_data = WC()->session->get('mwb_wcpcolite_upsell_data' ,array() );  

		$line1 = __('You have offer products in your cart. Please complete your order' , 'post_checkout_offers_lite'); 
		$line2 = __('Don\'t want this offer' , 'post_checkout_offers_lite');
		$link_text = __('Take me to the main order' , 'post_checkout_offers_lite');
		$funnel_products = array();
		if(!empty($upsell_data)){
			$funnel_products = $upsell_data['upsell_items'] ;
		}
		if(!empty($funnel_products)){
			$html  = '<div id = "mwb_wcpcolite_footer">';
			$html .= '<p>'.$line1.'</p>' ;  
			$html .= '<p>'.$line2.'  <a id = "mwb_wcpcolite_go_to_parent">'.$link_text.'</a> </p>' ;
			$html .= '</div>' ;
			echo $html ;
		}  
	} 


	/**
	* leads to the main order
	*
	* @since    1.0.0
	*/
	
	public function mwb_wcpcolite_go_to_parent(){
		
		$upsell_data = WC()->session->get('mwb_wcpcolite_upsell_data' ,array() );  
		
		$data = array('success' => 'false');
		
		if(!empty($upsell_data) && isset($upsell_data['upsell_parent_order_id'])){ 
			
			WC()->session->__unset( 'mwb_wcpcolite_upsell_data' );  
			global $woocommerce; 
		    $woocommerce->cart->empty_cart();
			$order = wc_get_order($upsell_data['upsell_parent_order_id']); 
			$url =$this->get_default_checkout_order_received_url($order); 
			
			$data['success'] = 'true';  
			$data['url'] = $url; 
		}
		echo json_encode($data); 
		wp_die();
	} 

	/**
	* get default order received url
	*
	* @param $order  order object of current order
	* @since    1.0.0
	*/
	
	public function get_default_checkout_order_received_url($order){ 
		
		$order_received_url = wc_get_endpoint_url( 'order-received', $order->get_id(), wc_get_page_permalink( 'checkout' ) );
		if ( 'yes' === get_option( 'woocommerce_force_ssl_checkout' ) || is_ssl() ) {
			$order_received_url = str_replace( 'http:', 'https:', $order_received_url );
		}	
		$order_received_url = add_query_arg( 'key', $order->get_order_key(), $order_received_url ); 	
		return $order_received_url ; 
	}  


	/**
	* override woocommerce thankyou page 
	*
	* @param $template  woo template
	* @param $template_name  woo template name
	* @param $template_path  default path of that template
	* @since    1.0.0
	*/

	public function mwb_wcpcolite_woocommerce_locate_template( $template, $template_name, $template_path ) {
		
		global $woocommerce;
		
		$_template = $template;
		if ( ! $template_path ) $template_path = $woocommerce->template_url;
		$plugin_path  = MWB_WCPCOLITE_DIRPATH . '/woocommerce/';

		$template = locate_template(
			array(
				$template_path . $template_name,
				$template_name
			)
		);

		if ( file_exists( $plugin_path . $template_name ) )
			$template = $plugin_path . $template_name;
		
		if ( ! $template )
			$template = $_template;
		
		return $template; 
	} 
}
