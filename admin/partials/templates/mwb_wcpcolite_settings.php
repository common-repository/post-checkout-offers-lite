<?php
/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( isset( $_GET["mwb_wcpcolite_new_offer_page"] ) )
{
	$new_page = sanitize_text_field( $_GET["mwb_wcpcolite_new_offer_page"] );

	unset( $_GET["mwb_wcpcolite_new_offer_page"] );

	if( $new_page == "yes" )
	{
       	$mwb_wcpcolite_funnel_page = array(
			'comment_status' 	=> 'closed',
			'ping_status' 		=> 'closed',
			'post_content' 		=> '[mwb_wcpcolite_funnel_default_offer_page]',
			'post_name' 		=> 'special-discount-offer',
			'post_status' 		=> 'publish',
			'post_title' 		=> 'Special Discount Offer',
			'post_type' 		=> 'page',
		);

        $mwb_wcpcolite_post = wp_insert_post($mwb_wcpcolite_funnel_page);
       	update_option("mwb_wcpcolite_funnel_default_offer_page",$mwb_wcpcolite_post);
    }
    
	wp_redirect(admin_url('admin.php').'?page=mwb-wcpcolite-settings&tab=settings');
}
?>
<?php
if( isset( $_POST["mwb_wcpcolite_common_settings_save"] ) )
{   

	$nonce = isset($_POST['mwb_wcpcolite_common_settings_nonce']) ? sanitize_text_field( $_POST['mwb_wcpcolite_common_settings_nonce'] ) : "" ;

	if( ! wp_verify_nonce( $nonce, 'mwb_wcpcolite_common_settings_nonce' ) ){

		 return;
	}
	if( ! current_user_can( 'manage_woocommerce' ) ){
		
		 return;
	}
	
	unset($_POST["mwb_wcpcolite_common_settings_save"]);

	if( !empty( $_POST["mwb_wcpcolite_enable_plugin"] ) )
	{
		$_POST["mwb_wcpcolite_enable_plugin"] = 'on';
	}
	else
	{
		$_POST["mwb_wcpcolite_enable_plugin"] = 'off';
	}

	if( !empty( $_POST["mwb_wcpcolite_buy_text"] ) )
	{
		$_POST["mwb_wcpcolite_buy_text"] = stripslashes($_POST["mwb_wcpcolite_buy_text"]);
	}
	
	if( !empty( $_POST["mwb_wcpcolite_no_text"] ) )
	{
		$_POST["mwb_wcpcolite_no_text"] = stripslashes($_POST["mwb_wcpcolite_no_text"]);
	}
	
	if( !empty( $_POST["mwb_wcpcolite_no_offer_text"] ) )
	{
		$_POST["mwb_wcpcolite_no_offer_text"] = stripslashes($_POST["mwb_wcpcolite_no_offer_text"]);
	}

	if( !empty( $_POST["mwb_wcpcolite_offer_banner_text"] ) )
	{
		$_POST["mwb_wcpcolite_offer_banner_text"] = stripslashes($_POST["mwb_wcpcolite_offer_banner_text"]);
	}

	if( !empty( $_POST["mwb_wcpcolite_before_offer_price_text"] ) )
	{
		$_POST["mwb_wcpcolite_before_offer_price_text"] = stripslashes($_POST["mwb_wcpcolite_before_offer_price_text"]);
	} 

	if( !empty( $_POST["mwb_wcpcolite_offer_expiry_text"] ) )
	{
		$_POST["mwb_wcpcolite_offer_expiry_text"] = stripslashes($_POST["mwb_wcpcolite_offer_expiry_text"]);
	}   

	if( !empty( $_POST["mwb_wcpcolite_offer_expiry_button_text"] ) )
	{
		$_POST["mwb_wcpcolite_offer_expiry_button_text"] = stripslashes($_POST["mwb_wcpcolite_offer_expiry_button_text"]);
	}
	

	foreach( $_POST as $key => $data )
	{
		update_option($key,$data);
	}
	?>
	<div class="notice notice-success is-dismissible"> 
		<p><strong><?php _e('Settings saved','post_checkout_offers_lite'); ?></strong></p>
	</div>
	<?php
}  

$mwb_wcpcolite_enable_plugin = get_option( "mwb_wcpcolite_enable_plugin", "off" );

$mwb_wcpcolite_buy_text = !empty(get_option("mwb_wcpcolite_buy_text"))?get_option("mwb_wcpcolite_buy_text"):"";

$mwb_wcpcolite_no_text = !empty(get_option("mwb_wcpcolite_no_text"))?get_option("mwb_wcpcolite_no_text"):"";

$mwb_wcpcolite_no_offer_text = !empty(get_option("mwb_wcpcolite_no_offer_text"))?get_option("mwb_wcpcolite_no_offer_text"):"" ; 

$mwb_wcpcolite_offer_banner_text = !empty(get_option("mwb_wcpcolite_offer_banner_text"))?get_option("mwb_wcpcolite_offer_banner_text"):"" ;

$mwb_wcpcolite_offer_default_page_id = !empty(get_option("mwb_wcpcolite_funnel_default_offer_page"))?get_option("mwb_wcpcolite_funnel_default_offer_page"):"";

$mwb_wcpcolite_buy_button_color = !empty(get_option("mwb_wcpcolite_buy_button_color"))?get_option("mwb_wcpcolite_buy_button_color"):"#ff0000";

$mwb_wcpcolite_thanks_button_color = !empty(get_option("mwb_wcpcolite_thanks_button_color"))?get_option("mwb_wcpcolite_thanks_button_color"):"#000000";

$mwb_wcpcolite_before_offer_price_text = !empty(get_option("mwb_wcpcolite_before_offer_price_text"))?get_option("mwb_wcpcolite_before_offer_price_text"):"";

$mwb_wcpcolite_offer_expiry_text = !empty(get_option("mwb_wcpcolite_offer_expiry_text"))?get_option("mwb_wcpcolite_offer_expiry_text"):"";

$mwb_wcpcolite_offer_expiry_button_text = !empty(get_option("mwb_wcpcolite_offer_expiry_button_text"))?get_option("mwb_wcpcolite_offer_expiry_button_text"):"";
?>
<form action="" method="POST">
	<div class="mwb_table">
		<table class="form-table mwb_wcpcolite_creation_setting">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="mwb_wcpcolite_enable_plugin"><?php _e('Enable Post Checkout Offers Lite','post_checkout_offers_lite');?></label>
					</th>
					<td class="forminp forminp-text">
						<?php 
						$attribut_description = __('Enable the checkbox if you want this extension to work properly.','post_checkout_offers_lite');
						echo wc_help_tip( $attribut_description );
						?>
						<input type="checkbox" <?php echo ($mwb_wcpcolite_enable_plugin == 'on')?"checked='checked'":""?> name="mwb_wcpcolite_enable_plugin" id="mwb_wcpcolite_funnel_enable" class="mwb_wcpcolite_common_class">				
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="mwb_wcpcolite_buy_text"><?php _e('Text for "Buy Now" action','post_checkout_offers_lite');?></label>
					</th>
					<td class="forminp forminp-text">
						<?php 
						$attribut_description = __('Set text to be visible on "Buy Now" button','post_checkout_offers_lite');
						echo wc_help_tip( $attribut_description );
						?>
					<input style="" type="text" name="mwb_wcpcolite_buy_text" id="mwb_wcpcolite_buy_text" class="mwb_wcpcolite_common_class" value="<?php echo trim($mwb_wcpcolite_buy_text,'"')?>" placeholder = "<?php _e('Buy Now' , 'post_checkout_offers_lite') ;?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="mwb_wcpcolite_no_text"><?php _e('Text for "No thanks" action','post_checkout_offers_lite');?></label>
					</th>
					<td class="forminp forminp-text">
						<?php 
						$attribut_description = __('Set text to be visible on "No,thanks" link ','post_checkout_offers_lite');
						echo wc_help_tip( $attribut_description );
						?>
					<input style="" type="text" name="mwb_wcpcolite_no_text" id="mwb_wcpcolite_no_text" class="mwb_wcpcolite_common_class" value="<?php echo trim($mwb_wcpcolite_no_text,'"')?>" placeholder = "<?php _e('Skip' , 'post_checkout_offers_lite') ;?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="mwb_wcpcolite_buy_button_color"><?php _e('Background color for "Buy Now" button','post_checkout_offers_lite');?></label>
					</th>
					<td class="forminp forminp-text">
						<?php 
						$attribut_description = __('Set your custom background color for "Buy Now" button','post_checkout_offers_lite');
						echo wc_help_tip( $attribut_description );
						?>
					<input type="text" style="max-width: 100px;" name="mwb_wcpcolite_buy_button_color" id="mwb_wcpcolite_buy_button_color" class="mwb_wcpcolite_colorpicker" value="<?php echo trim($mwb_wcpcolite_buy_button_color,'"')?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="mwb_wcpcolite_thanks_button_color"><?php _e('Text color for "No thanks" link','post_checkout_offers_lite');?></label>
					</th>
					<td class="forminp forminp-text">
						<?php 
						$attribut_description = __('Set your custom text color for "No thanks" link','post_checkout_offers_lite');
						echo wc_help_tip( $attribut_description );
						?>
					<input style="max-width:100px" type="text" name="mwb_wcpcolite_thanks_button_color" id="mwb_wcpcolite_thanks_button_color" class="mwb_wcpcolite_colorpicker" value="<?php echo trim($mwb_wcpcolite_thanks_button_color,'"')?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="mwb_wcpcolite_no_offer_text"><?php _e('Text to show when a customer has no offer','post_checkout_offers_lite');?></label>
					</th>
					<td class="forminp forminp-text">
						<?php 
						$attribut_description = __('Set text to be visible on offers page when a customer has no offer.','post_checkout_offers_lite');
						echo wc_help_tip( $attribut_description );
						?>
					<input style="" type="text" name="mwb_wcpcolite_no_offer_text" id="mwb_wcpcolite_no_offer_text" class="mwb_wcpcolite_common_class" value="<?php echo trim($mwb_wcpcolite_no_offer_text,'"')?>" placeholder = "<?php _e("Sorry, you have no offers","post_checkout_offers_lite") ?>";
					>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="mwb_wcpcolite_offer_banner_text"><?php _e('Header text for special offer page','post_checkout_offers_lite');?></label>
					</th>
					<td class="forminp forminp-text">
						<?php 
						$attribut_description = __('Set the banner text for special offer page visible only when an user has offers.','post_checkout_offers_lite');
						echo wc_help_tip( $attribut_description );
						?>
					<input style="" type="text" name="mwb_wcpcolite_offer_banner_text" id="mwb_wcpcolite_offer_banner_text" class="mwb_wcpcolite_common_class" value="<?php echo trim($mwb_wcpcolite_offer_banner_text,'"')?>" placeholder = "<?php _e('Special Offer For You Only','post_checkout_offers_lite')  ;?>"> 
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="mwb_wcpcolite_before_offer_price_text"><?php _e('Text to display before new offer price','post_checkout_offers_lite');?></label>
					</th>
					<td class="forminp forminp-text">
						<?php 
						$attribut_description = __('Set the custom text which you want to show just before the new offer price.','post_checkout_offers_lite');
						echo wc_help_tip( $attribut_description );
						?>
					<input style="" type="text" name="mwb_wcpcolite_before_offer_price_text" id="mwb_wcpcolite_before_offer_price_text" class="mwb_wcpcolite_common_class" value="<?php echo trim($mwb_wcpcolite_before_offer_price_text,'"')?>" placeholder = "<?php _e('Special Offer Price' , 'post_checkout_offers_lite') ;?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="mwb_wcpcolite_offer_default_page_url"><?php _e('Default page url for special offers','post_checkout_offers_lite');?></label>
					</th>
					<td class="forminp forminp-text">
						<?php 
						$attribut_description = __('URL for default offer page where funnel offers will be displayed. Please do not delete.','post_checkout_offers_lite');
						echo wc_help_tip( $attribut_description );
						?>
					<?php 
					
					if(get_post_status($mwb_wcpcolite_offer_default_page_id)!==false && get_post_status($mwb_wcpcolite_offer_default_page_id)!=="trash")
					{
						$mwb_wcpcolite_offer_default_page_url = get_page_link($mwb_wcpcolite_offer_default_page_id);

						echo trim($mwb_wcpcolite_offer_default_page_url,'"');
					}
					else
					{
						?>
						<span style="color:red;display:inline;"><?php _e("Default offer page not found or deleted.","post_checkout_offers_lite")?></span>
						<a href="?page=mwb-wcpco-settings&tab=settings&mwb_wcpcolite_new_offer_page=yes"><?php _e("Add new page","post_checkout_offers_lite")?></a>
						<?php 
					}
					?>
					</td>
				</tr> 
				
				<?php do_action("mwb_wcpcolite_create_more_settings");?>
			</tbody>
		</table>
	</div>
	<input type="hidden" name="mwb_wcpcolite_common_settings_nonce" value="<?php echo wp_create_nonce( 'mwb_wcpcolite_common_settings_nonce' ) ;?>">
	<p class="submit">
	<input type="submit" value="<?php _e('Save Changes', 'post_checkout_offers_lite'); ?>" class="button-primary woocommerce-save-button" name="mwb_wcpcolite_common_settings_save" id="mwb_wcpcolite_common_settings_save" >
	</p>
</form>