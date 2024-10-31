<?php

/**
* The admin-specific functionality of the plugin.
*
* @link       https://makewebbetter.com
* @since      1.0.0
*
* @package    Post_Checkout_Offers_Lite
* @subpackage Post_Checkout_Offers_Lite/admin
*/

/**
* The admin-specific functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the admin-specific stylesheet and JavaScript.
*
* @package    Post_Checkout_Offers_Lite
* @subpackage Post_Checkout_Offers_Lite/admin
* @author     MakeWebBetter <webmaster@makewebbetter.com>
*/
class Post_Checkout_Offers_Lite_Admin {
	
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
	* @param      string    $plugin_name       The name of this plugin.
	* @param      string    $version    The version of this plugin.
	*/
	public function __construct( $plugin_name, $version ) {
		
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
	}
	
	/**
	* Register the stylesheets for the admin area.
	*
	* @since    1.0.0
	*/
	public function enqueue_styles() {
		
		
		$screen = get_current_screen();   

		global $wp_styles ; 
		
		if( isset( $screen->id ) )
		{
			$pagescreen = $screen->id;
			
			if( $pagescreen == 'woocommerce_page_mwb-wcpcolite-settings' )
			{ 

				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/post-checkout-offers-lite-admin.css', array(), $this->version, 'all' );
				
				wp_enqueue_style( $this->plugin_name );
				
				wp_enqueue_style( 'mwb_wcpcolite_select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
				
				wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
				
				wp_enqueue_style( 'woocommerce_admin_menu_styles' );
				
				wp_enqueue_style( 'woocommerce_admin_styles' );
			}
		}
	}
	
	/**
	* Register the JavaScript for the admin area.
	*
	* @since    1.0.0
	*/
	public function enqueue_scripts() {
		
		
		$screen = get_current_screen(); 
		
		if( isset( $screen->id ) )
		{
			$pagescreen = $screen->id;
			
			if( $pagescreen == 'woocommerce_page_mwb-wcpcolite-settings' )
			{
				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, false );
				
				wp_register_script('mwb_wcpcolite_admin_script', plugin_dir_url( __FILE__ ) . 'js/post-checkout-offers-lite-admin.js', array( 'jquery' ), $this->version, false );
				
				wp_register_script( 'woocommerce_admin', WC()->plugin_url() . '/assets/js/admin/woocommerce_admin.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip', 'wc-enhanced-select' ), WC_VERSION );
				
				wp_register_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip.js', array( 'jquery' ), WC_VERSION, true );	
				$locale  = localeconv();
				$decimal = isset( $locale['decimal_point'] ) ? $locale['decimal_point'] : '.';
				$params = array(
					/* translators: %s: decimal */
					'i18n_decimal_error'                => sprintf( __( 'Please enter in decimal (%s) format without thousand separators.', "post_checkout_offers_lite" ), $decimal ),
					/* translators: %s: price decimal separator */
					'i18n_mon_decimal_error'            => sprintf( __( 'Please enter in monetary decimal (%s) format without thousand separators and currency symbols.', 'post_checkout_offers_lite' ), wc_get_price_decimal_separator() ),
					'i18n_country_iso_error'            => __( 'Please enter in country code with two capital letters.', 'post_checkout_offers_lite' ),
					'i18_sale_less_than_regular_error'  => __( 'Please enter in a value less than the regular price.', 'post_checkout_offers_lite' ),
					'decimal_point'                     => $decimal,
					'mon_decimal_point'                 => wc_get_price_decimal_separator(),
					'strings' => array(
						'import_products' => __( 'Import', 'post_checkout_offers_lite' ),
						'export_products' => __( 'Export', 'post_checkout_offers_lite' ),
					),
					'urls' => array(
						'import_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_importer' ) ),
						'export_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_exporter' ) ),
					),
				);	

				$localized_data = array(); 
				$localized_data['ajax_url'] = admin_url('admin-ajax.php') ;

				$localized_data['mwb_wcpcolite_location'] = admin_url('admin.php').'?page=mwb-wcpcolite-settings&tab=settings' ;

				$localized_data['mwb_wcpcolite_offer_deletion'] =  __( "Are you sure to delete this offer","post_checkout_offers_lite" ) ;

				$localized_data['mwb_wcpcolite_target_notice'] =  __( "Please select a target product.","post_checkout_offers_lite" ) ; 

				$localized_data['mwb_wcpcolite_offer_notice'] =  __( "Please select a offer product.","post_checkout_offers_lite" ) ; 

				$localized_data['mwb_wcpcolite_discount_notice'] =  __( "Please enter a valid discount price.","post_checkout_offers_lite" ) ;  

				$localized_data['mwb_wcpcolite_search_products_nonce']     = wp_create_nonce( 'mwb_wcpcolite_search_products_nonce' ) ; 
				
				
				wp_localize_script( 'mwb_wcpcolite_admin_script','localized_data', $localized_data ) ;
				
				wp_enqueue_script('mwb_wcpcolite_admin_script');
				
				wp_localize_script( 'woocommerce_admin', 'woocommerce_admin', $params );
				
				wp_enqueue_script( 'woocommerce_admin' );
				
				wp_enqueue_style( 'wp-color-picker' ); 		       
				
				wp_enqueue_script('mwb_post_checkout_offers_lite_color_picker', plugin_dir_url( __FILE__ ) . 'js/post-checkout-offers-lite-color-picker.js', array('jquery','wp-color-picker' ), $this->version, true);
			}
			
		}
		
	} 
	
	/**
	* adding post checkout offers lite menu page
	*
	* @since    1.0.0
	*/
	public function mwb_wcpcolite_admin_menu(){
	
		add_submenu_page( "woocommerce", __("Post Checkout Offers Lite","post_checkout_offers_lite" ), __("Post Checkout Offers Lite","post_checkout_offers_lite" ), "manage_woocommerce", "mwb-wcpcolite-settings", array($this, "mwb_wcpcolite_admin_setting") );
	}  

	/**
	 * callable function for post checkout offers lite menu page
	 *
	 * @since    1.0.0
	 */

	public function mwb_wcpcolite_admin_setting(){
 	
		require_once plugin_dir_path( __FILE__ ).'/partials/post-checkout-offers-lite-admin-display.php';
		
	} 

		/**
	 * creating html template for new offer block
	 *
	 * @since    1.0.0
	 */

	public function mwb_wcpcolite_return_offer_content()
	{
		if( isset( $_POST["mwb_wcpcolite_flag"] ) && isset( $_POST["mwb_wcpcolite_funnel"] ) && isset($_POST["create_new_offer_nonce"]))
		{
			$index 	= sanitize_text_field( $_POST["mwb_wcpcolite_flag"] );

			$funnel = sanitize_text_field( $_POST["mwb_wcpcolite_funnel"] ); 

			$nonce =  sanitize_text_field( $_POST["create_new_offer_nonce"] ) ;

			if( ! wp_verify_nonce( $nonce, 'create_new_offer_nonce' ) || ! current_user_can( 'manage_woocommerce' ) ){
				$data = "";
				echo $data ; 
				wp_die();
			}

			unset( $_POST["mwb_wcpcolite_flag"] );

			unset( $_POST["mwb_wcpcolite_funnel"] );

			$mwb_wcpcolite_funnel = get_option( "mwb_wcpcolite_funnels_list" );

			$mwb_wcpcolite_offers_to_add = isset( $mwb_wcpcolite_funnel[$funnel]["mwb_wcpcolite_applied_offer_number"] )?$mwb_wcpcolite_funnel[$funnel]["mwb_wcpcolite_applied_offer_number"]:array();

			$mwb_wcpcolite_buy_offers = '<select name="mwb_wcpcolite_attached_offers_on_buy['.$index.']"><option value="thanks">'.__('ThankYou Page','post_checkout_offers_lite').'</option>';

			$mwb_wcpcolite_no_offers = '<select name="mwb_wcpcolite_attached_offers_on_no['.$index.']"><option value="thanks">'.__('ThankYou Page','post_checkout_offers_lite').'</option>';

			if( !empty( $mwb_wcpcolite_offers_to_add ) )
			{
				foreach( $mwb_wcpcolite_offers_to_add as $mwb_single_offer_to_add ):
					$mwb_wcpcolite_buy_offers .= '<option value='.$mwb_single_offer_to_add.'>'.__('Offer','post_checkout_offers_lite').$mwb_single_offer_to_add.'</option>';
					$mwb_wcpcolite_no_offers .= '<option value='.$mwb_single_offer_to_add.'>'.__('Offer','post_checkout_offers_lite').$mwb_single_offer_to_add.'</option>';
				endforeach;
			}

			$mwb_wcpcolite_buy_offers .= '</select>';

			$mwb_wcpcolite_no_offers .= '</select>';

			$data = '<div style="display:none;" data-id="'.$index.'" class="new_created_offers"><h2>'.__('Offer #','post_checkout_offers_lite').$index.'</h2>
			<table>
			<tr><th><label><h4>'.__('Product search : ','post_checkout_offers_lite').'</h4></label></th><td><select class="wc-offer-product-search" multiple="multiple" style="" name="mwb_wcpcolite_products_in_offer['.$index.'][]" data-placeholder="'.__( 'Search for a product&hellip;', 'post_checkout_offers_lite').'"></select>
				</td></tr>
			<tr><th><label><h4>'.__('Offer price : ','post_checkout_offers_lite').'</h4></label></th><td><input type="text" placeholder="'.__('enter in percentage','post_checkout_offers_lite').'" name="mwb_wcpcolite_offer_discount_price['.$index.']" style="width:50%;height:40px;" value="50%">
			<span style="color:green">'.__(" Note: Enter in % or a new offer price","post_checkout_offers_lite").'</span></td></tr>
		    <tr><th><label><h4>'.__('After "Buy Now" go to:','post_checkout_offers_lite').'</h4></label></th>
			    <td>'.$mwb_wcpcolite_buy_offers.'</td></tr>
		    <tr><th><label><h4>'.__('After "No thanks" go to: ','post_checkout_offers_lite').'</h4></label></th><td>'.$mwb_wcpcolite_no_offers.'</td></tr>
		    </table>
		    <input type="hidden" name="mwb_wcpcolite_applied_offer_number['.$index.']" value="'.$index.'">
		    </div>';

		    $new_data = apply_filters("mwb_wcpcolite_add_more_to_offers",$data);

			echo $new_data;
		}

		wp_die();
	}  

	/**
	 * select2 search for adding funnel target products
	 *
	 * @since    1.0.0
	 */

	public function mwb_wcpcolite_search_products_for_funnel()
	{
		$return = array();
 		
 		$nonce = isset( $_GET['nonce'] ) ? $_GET['nonce'] : "" ;

 		$search_string = isset( $_GET['q'] ) ? sanitize_text_field( $_GET['q'] ) : "" ;

 		if(!wp_verify_nonce( $nonce, 'mwb_wcpcolite_search_products_nonce' )){

 			echo json_encode( $return );
 			wp_die();

 		}

		$search_results = new WP_Query( array( 
			's'						=> $search_string,
			'post_type' 			=> array( 'product' ),
			'ignore_sticky_posts' 	=> 1,
			'posts_per_page' 		=> 50 
		) );

		if( $search_results->have_posts() ) :

			while( $search_results->have_posts()): 

				$search_results->the_post();
					
				$title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;

				$product = wc_get_product( $search_results->post->ID );
				$downloadable = $product->is_downloadable();
				$stock = $product->get_stock_status();

				if( $product->is_type('variable') || $product->is_type('subscription') || $downloadable || $product->is_type('grouped') ||  $product->is_type('external') || $stock === "outofstock" )
				{
					continue;
				}

				$return[] = array( $search_results->post->ID, $title ); 

			endwhile;

		endif;

		echo json_encode( $return );

		wp_die();
	} 

	/**
	 * select2 search for adding offer products
	 *
	 * @since    1.0.0
	 */

	public function mwb_wcpcolite_search_products_for_offers()
	{
		$return = array();

		$search_string = isset( $_GET['q'] ) ? sanitize_text_field( $_GET['q'] ) : "" ;

 		$nonce = isset( $_GET['nonce'] ) ? $_GET['nonce'] : "" ;

 		if(!wp_verify_nonce( $nonce, 'mwb_wcpcolite_search_products_nonce' )){

 			echo json_encode( $return );
 			wp_die();

 		}

		$search_results = new WP_Query( array( 
			's'						=> $search_string,
			'post_type' 			=> array('product'),
			'ignore_sticky_posts' 	=> 1,
			'posts_per_page' 		=> 50 
		) );

		if( $search_results->have_posts() ) :

			while( $search_results->have_posts() ): 

				$search_results->the_post();	

				$title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;

				$product = wc_get_product( $search_results->post->ID );
				$downloadable = $product->is_downloadable();
				$stock = $product->get_stock_status();

				if( $product->is_type('variable') || $product->is_type('subscription') || $downloadable || $product->is_type('grouped') ||  $product->is_type('external') || $stock === "outofstock" )
				{
					continue;
				}

				$return[] = array( $search_results->post->ID, $title ); 

			endwhile;

		endif;
		
		echo json_encode( $return );

		wp_die();
	}  

	/**
	 * adding custom column in orders table at backend
	 *
	 * @since    1.0.0
	 * @param    array    $columns    array of columns on orders table 
	 * @return   array    $columns    array of columns on orders table alongwith upsell column
	 */
	public function mwb_wcpcolite_add_columns_to_admin_orders($columns){
	

	  	$columns['wcpcolite-orders'] = __('Post Checkout Lite Orders','post_checkout_offers_lite');

    	return $columns;
	}  


		/**
	 * displaying upsell purchases in parent order details in  backend
	 *
	 * @since    1.0.0
	 * @param    object   $order   object of parent order
	 */

	public function mwb_wcpcolite_change_admin_order_details( $order )
	{
		$output = ""; 

		$mwb_wcpcolite_order = get_posts(array(
			'posts_per_page' =>  -1,
			'post_type'      =>  'shop_order',
			'post_status'    =>  'any',
			'meta_key'       =>  'mwb_wcpcolite_upsell_parent_order',
			'meta_value'     =>   $order->get_id(),
			'orderby'        =>  'ID',
			'order'          =>  'ASC'
		)); 

		if( !empty( $mwb_wcpcolite_order ) )
		{
			$output .= '<br><div style="margin:top:20px;padding-top:20px;" class="mwb_wcpcolite_funnel_details"><p class="mwb_wcpcolite_funnel_head"><h3>'.__('Post Checkout Orders','post_checkout_offers_lite').'</h3></p>';

			foreach( $mwb_wcpcolite_order as $mwb_wcpcolite_single_order )
			{
				$mwb_wcpcolite_upsell_order = wc_get_order( $mwb_wcpcolite_single_order->ID );

				$output .= '<div style="" class="mwb_wcpcolite_funnel_orders"><a href="'.get_edit_post_link( $mwb_wcpcolite_upsell_order->get_id() ).'">'.__('Post Checkout order #','post_checkout_offers_lite').$mwb_wcpcolite_upsell_order->get_order_number().'</div>';
			}

			$output .= '</div>';
		}

		echo $output;
	} 


	public function mwb_wcpcolite_add_upsell_orders_to_parent($column, $post_id){
	
		$output = "";

		$mwb_wcpcolite_order = get_posts(array(
				'posts_per_page' =>  -1,
				'post_type'      =>  'shop_order',
				'post_status'    =>  'any',
				'meta_key'       =>  'mwb_wcpcolite_upsell_parent_order',
				'meta_value'     =>   $post_id,
				'orderby'        =>  'ID',
				'order'          =>  'ASC'
			)); 


		$mwb_wcpcolite_upsell_order = get_post_meta( $post_id, "mwb_wcpcolite_upsell_parent_order", true ); 
 		
 		$mwb_wcpcolite_upsell_offer_order = get_post_meta( $post_id, "mwb_wcpcolite_upsell_offer_order", true );

		switch( $column )
		{
			case 'wcpcolite-orders':

			$data = "";

			if( !empty( $mwb_wcpcolite_order ) )
			{   

				foreach( $mwb_wcpcolite_order as $mwb_wcpcolite_single_order )
				{
					$mwb_wcpcolite_upsell_order = wc_get_order( $mwb_wcpcolite_single_order->ID );

					$data .= '<p><a href="'.get_edit_post_link( $mwb_wcpcolite_upsell_order->get_id() ).'">'.__('Post Checkout order #','post_checkout_offers_lite').$mwb_wcpcolite_upsell_order->get_order_number().'</a></p>';
				}	
			}
			elseif( empty( $mwb_wcpcolite_upsell_order ) )
			{
				$data .= __("Single Order","post_checkout_offers_lite");
			} 
			elseif(!empty($mwb_wcpcolite_upsell_offer_order))
			{
				$data .= __("Post Checkout order","post_checkout_offers_lite");
			}
			else
			{
				$data = '<p style="">_</p>';
			}

			echo $data;
			
			break;	
		}	
	}  

	/**
	 * adding distraction free mode to the offers page.
	 *
	 * @since    	1.0.0
	 * @param  		$page_template 		default template for the page
	 */

	public function mwb_wcpcolite_page_template( $page_template ){
	
		$pages_available = get_posts(array(
			'posts_per_page' 		=> -1,
			'post_type' 			=> 'any',
			'post_status' 			=> 'publish',
			's' 					=> '[mwb_wcpcolite_funnel_default_offer_page]',
			'orderby' 				=> 'ID',
			'order' 				=> 'ASC',
		));

		foreach( $pages_available as $single_page )
		{
			if( is_page( $single_page->ID ) )
			{
				$page_template = dirname( __FILE__ ) .'/partials/templates/mwb-wcpcolite-template.php';
			}
		}

		return $page_template;
	}  


	/**
	 * adding select dropdown for filtering:- "upsells orders","single parent order" or "parent orders with linked upsell orders"
	 *
	 * @since    1.0.0
	 */

	public function mwb_wcpcolite_restrict_manage_posts(){

		if( isset( $_GET["post_type"] ) && $_GET["post_type"] == "shop_order" ){

			if( isset( $_GET["mwb_wcpcolite_filter"] ) ):?> 
				<?php $filter = sanitize_text_field( $_GET["mwb_wcpcolite_filter"] ) ;?>
				<select name="mwb_wcpcolite_filter">
					<option value="select" <?php echo $filter=="select"?"selected=selected":""?>><?php _e('All Orders','post_checkout_offers_lite')?></option>
					<option value="all_wcpcolite" <?php echo $filter=="all_wcpcolite"?"selected=selected":""?>><?php _e('Only Post Checkout Orders','post_checkout_offers_lite')?></option>
				</select>
			<?php endif;

			if( !isset( $_GET["mwb_wcpcolite_filter"] ) ):?>
				<select name="mwb_wcpcolite_filter">
					<option value="select"><?php _e('All Orders','post_checkout_offers_lite')?></option>
					<option value="all_wcpcolite"><?php _e('Only Post Checkout Orders','post_checkout_offers_lite')?></option>
				</select>
			<?php endif;
		}
	}  

	/**
	 * modifying query vars for filtering orders
	 *
	 * @since    1.0.0
	 * @param    array    $vars    array of queries
	 * @return   array    $vars    array of queries alongwith select dropdown query for post checkout offer orders
	 */

	public function mwb_wcpcolite_request_query( $vars ){
		
		if( isset( $_GET["mwb_wcpcolite_filter"] ) && $_GET["mwb_wcpcolite_filter"] == "all_wcpcolite" )
		{
			$vars = array_merge( $vars, array( 'meta_key' => 'mwb_wcpcolite_upsell_parent_order' ) );
		}
		elseif( isset( $_GET["mwb_wcpcolite_filter"] ) && $_GET["mwb_wcpcolite_filter"] == "all_single" )
		{
			$vars = array_merge( $vars, array( 'meta_key' => 'mwb_wcpcolite_upsell_parent_order' , 'meta_compare' => 'NOT EXISTS' ) );
		}

		return $vars;
	}
	
}
