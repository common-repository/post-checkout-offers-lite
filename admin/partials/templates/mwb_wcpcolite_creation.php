<?php 
/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * General Settings Template
 */

function mwb_wcpcolite_array_push_assoc( $array, $key, $value )
{
	$array[$key] = $value;
	return $array;
}

$flag = 0; 
$mwb_wcpcolite_funnels = get_option( "mwb_wcpcolite_funnels_list" ); 


if( !isset( $_GET["funnel_id"] ) )
{
	$mwb_wcpcolite_funnels = get_option( "mwb_wcpcolite_funnels_list" );

	if( !empty( $mwb_wcpcolite_funnels ) )
	{
		$mwb_wcpcolite_funnel_duplicate = $mwb_wcpcolite_funnels;
		end( $mwb_wcpcolite_funnel_duplicate );
		$mwb_wcpcolite_funnel_number = key( $mwb_wcpcolite_funnel_duplicate );
		$mwb_wcpcolite_funnel_id = $mwb_wcpcolite_funnel_number+1;
	}
	else
	{
		$mwb_wcpcolite_funnel_id = 0;	
	}
}
else
{
	$mwb_wcpcolite_funnel_id = sanitize_text_field( $_GET["funnel_id"] );
}

if( isset( $_POST['mwb_wcpcolite_creation_setting_save'] ) )
{   

	$nonce = isset($_POST['mwb_wcpcolite_creation_setting_nonce']) ? sanitize_text_field( $_POST['mwb_wcpcolite_creation_setting_nonce'] ) : "" ;

	if( ! wp_verify_nonce( $nonce, 'mwb_wcpcolite_creation_setting_nonce' ) ){

		 return;
	}
	if( ! current_user_can( 'manage_woocommerce' ) ){

		 return;
	}

	unset( $_POST['mwb_wcpcolite_creation_setting_save'] );

	$mwb_wcpcolite_funnel_id = sanitize_text_field( $_POST["mwb_wcpcolite_funnel_id"] );

	if( empty( $_POST["mwb_wcpcolite_target_pro_ids"] ) )
	{   
		$_POST["mwb_wcpcolite_target_pro_ids"] = array();
	}

	$mwb_wcpcolite_funnel = array();

	foreach( $_POST as $key => $data )
	{   
		$mwb_wcpcolite_funnel = mwb_wcpcolite_array_push_assoc( $mwb_wcpcolite_funnel, $key, $data );
	}

	$mwb_wcpcolite_funnel_series = array();
	
	$mwb_wcpcolite_funnel_series[$mwb_wcpcolite_funnel_id] = $mwb_wcpcolite_funnel;

	if( !empty( get_option( "mwb_wcpcolite_funnels_list" ) ) )
	{
		$mwb_wcpcolite_created_funnels = get_option( "mwb_wcpcolite_funnels_list" );

		foreach( $mwb_wcpcolite_created_funnels as $key => $data )
		{
			if( $key == $mwb_wcpcolite_funnel_id )
			{
				$mwb_wcpcolite_created_funnels[$key] = $mwb_wcpcolite_funnel_series[$mwb_wcpcolite_funnel_id];
				$flag = 1;
				break;
			}
		}
		if( $flag != 1 )
		{
			$mwb_wcpcolite_created_funnels = array_merge( $mwb_wcpcolite_created_funnels, $mwb_wcpcolite_funnel_series );
		}

		update_option( "mwb_wcpcolite_funnels_list", $mwb_wcpcolite_created_funnels );
	}
	else
	{
		update_option( "mwb_wcpcolite_funnels_list", $mwb_wcpcolite_funnel_series );
	}
	
	?>
	<div class="notice notice-success is-dismissible"> 
		<p><strong><?php _e('Settings saved','post_checkout_offers_lite'); ?></strong></p>
	</div>
	
	<?php
	
}	 
?>
<div class="notice notice-error is-dismissible wcpcolite_notice"> 
</div>
<?php

$mwb_wcpcolite_funnel_data = get_option( "mwb_wcpcolite_funnels_list" );

$mwb_wcpcolite_custom_th_page = !empty( $mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_custom_th_page"] )?$mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_custom_th_page"]:"off";

$mwb_wcpcolite_cron_options = array(
		'0'		=> __( 'on every Sunday', 'post_checkout_offers_lite' ),
		'1'		=> __( 'on every Monday', 'post_checkout_offers_lite'),
		'2'		=> __( 'on every Tuesday', 'post_checkout_offers_lite' ),
		'3'		=> __( 'on every Wednesday', 'post_checkout_offers_lite' ),
		'4'		=> __( 'on every Thursday', 'post_checkout_offers_lite' ),
		'5'		=> __( 'on every Friday', 'post_checkout_offers_lite' ),
		'6'		=> __( 'on every Saturday', 'post_checkout_offers_lite' ),
		'7'  	=> __( '--select schedule--', 'post_checkout_offers_lite' ),
		);

?>
<form action="" method="POST">
	<div class="mwb_table">
		<table class="form-table mwb_wcpcolite_creation_setting">
			<tbody>
				<input type="hidden" name="mwb_wcpcolite_funnel_id" value="<?php echo $mwb_wcpcolite_funnel_id?>">
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="mwb_wcpcolite_funnel_name"><?php _e('Name of the funnel','post_checkout_offers_lite');?></label>
					</th>
					<td class="forminp forminp-text">
						<?php 
						$attribut_description = __('Provide the name of your funnel','post_checkout_offers_lite');
						echo wc_help_tip( $attribut_description );
						?>
						<input type="text" name="mwb_wcpcolite_funnel_name" <?php if(!empty($mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_funnel_name"])){?> value="<?php echo $mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_funnel_name"]?>" <?php }else{?> value="<?php _e("Untitled Funnel ","post_checkout_offers_lite"); echo $mwb_wcpcolite_funnel_id+1?>" <?php } ?> id="mwb_wcpcolite_funnel_name" class="input-text mwb_wcpcolite_commone_class" required="">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="mwb_wcpcolite_target_pro_ids"><?php _e('Select target product','post_checkout_offers_lite');?></label>
					</th>
					<td class="forminp forminp-text">
						<?php 
						$attribut_description = __('Select the products which will be the funnel target products at Checkout Page.','post_checkout_offers_lite');
						echo wc_help_tip( $attribut_description );
						?>

						<select class="wc-funnel-product-search" multiple="multiple" style="" name="mwb_wcpcolite_target_pro_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'post_checkout_offers_lite' ); ?>">
						<?php

						if(!empty($mwb_wcpcolite_funnel_data))
						{
						$mwb_wcpcolite_target_products = $mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_target_pro_ids"];

							$mwb_wcpcolite_target_product_ids = ! empty( $mwb_wcpcolite_target_products ) ? array_map( 'absint',  $mwb_wcpcolite_target_products ) : null;
							
							if ( $mwb_wcpcolite_target_product_ids ) 
							{
								foreach ( $mwb_wcpcolite_target_product_ids as $mwb_wcpcolite_single_target_product_id ) 
								{
									$product_name =  get_the_title($mwb_wcpcolite_single_target_product_id);
									echo '<option value="'.$mwb_wcpcolite_single_target_product_id. '" selected="selected">'.$product_name.'(#'.$mwb_wcpcolite_single_target_product_id.')'.'</option>';
								}
							}
						}
						?>
						</select>
					</td>	
				</tr>

			</tbody>
		</table>
		
		<div class="mwb_wcpcolite_offers"><h1><?php _e('Offers In Funnel','post_checkout_offers_lite');?></h1></div><br>
		<?php 

		$mwb_wcpcolite_existing_offers = !empty( $mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_applied_offer_number"] )?$mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_applied_offer_number"]:"";

		$mwb_wcpcolite_products_offer = !empty( $mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_products_in_offer"] )?$mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_products_in_offer"]:"";

		$mwb_wcpcolite_products_discount = !empty( $mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_offer_discount_price"] )?$mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_offer_discount_price"]:"";

		$mwb_wcpcolite_custom_details = !empty( $mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_custom_details"] )?$mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_custom_details"]:""; 

		$mwb_wcpcolite_offer_expiration = !empty( $mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_offer_expiration"] )?$mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_offer_expiration"]:"";

		$mwb_wcpcolite_offers_buy_on_offers = !empty( $mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_attached_offers_on_buy"] )?$mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_attached_offers_on_buy"]:"";

		$mwb_wcpcolite_offers_no_thanks_offers = !empty( $mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_attached_offers_on_no"] )?$mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_attached_offers_on_no"]:"";

		$mwb_wcpcolite_offer_custom_page_url = !empty( $mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_offer_custom_page_url"] )?$mwb_wcpcolite_funnel_data[$mwb_wcpcolite_funnel_id]["mwb_wcpcolite_offer_custom_page_url"]:"";

		$mwb_wcpcolite_offers_to_add = $mwb_wcpcolite_existing_offers; 

		?>
		<div class="new_offers">
			<div class="new_created_offers" data-id="0">
			</div>
			<?php if( !empty( $mwb_wcpcolite_existing_offers ) )
			{   

				foreach( $mwb_wcpcolite_existing_offers as $offers => $mwb_wcpcolite_single_offer ) 
				{
					$mwb_wcpcolite_buy_attached_offers = "";

					$mwb_wcpcolite_no_attached_offers = "";

					if( !empty( $mwb_wcpcolite_offers_to_add ) )
					{
						foreach( $mwb_wcpcolite_offers_to_add as $mwb_single_offer_to_add ): 

 
							if( $mwb_single_offer_to_add != $mwb_wcpcolite_single_offer )
							{

								$mwb_wcpcolite_buy_attached_offers .= '<option value='.$mwb_single_offer_to_add.'>'.__('Offer','post_checkout_offers_lite').$mwb_single_offer_to_add.'</option>';
							
								$mwb_wcpcolite_no_attached_offers .= '<option value='.$mwb_single_offer_to_add.'>'.__('Offer','post_checkout_offers_lite').$mwb_single_offer_to_add.'</option>';
							}

						endforeach;
					}

					$mwb_wcpcolite_buy_offers = "";

					if( !empty( $mwb_wcpcolite_offers_buy_on_offers ) )
					{ 

					
						if( $mwb_wcpcolite_offers_buy_on_offers[$offers] == 'thanks' )
						{
							$mwb_wcpcolite_buy_offers = '<select style="" name="mwb_wcpcolite_attached_offers_on_buy['.$mwb_wcpcolite_single_offer.']"><option value="thanks" selected="">'.__('ThankYou Page','post_checkout_offers_lite').'</option>'.$mwb_wcpcolite_buy_attached_offers;
						}
						elseif( $mwb_wcpcolite_offers_buy_on_offers[$offers]>0 )
						{
							$mwb_wcpcolite_buy_offers = '<select style="" name="mwb_wcpcolite_attached_offers_on_buy['.$mwb_wcpcolite_single_offer.']"><option value="thanks">'.__('ThankYou Page','post_checkout_offers_lite').'</option>';

							if( !empty( $mwb_wcpcolite_offers_to_add ) )
							{
								foreach( $mwb_wcpcolite_offers_to_add as $mwb_single_offer_to_add )
								{
									if( $mwb_single_offer_to_add != $mwb_wcpcolite_single_offer )
									{
										if( $mwb_wcpcolite_offers_buy_on_offers[$offers] == $mwb_single_offer_to_add )
										{
											$mwb_wcpcolite_buy_offers .= '<option value='.$mwb_single_offer_to_add.' selected="">'.__('Offer','post_checkout_offers_lite').$mwb_single_offer_to_add.'</option>';
										}
										else
										{
											$mwb_wcpcolite_buy_offers .= '<option value='.$mwb_single_offer_to_add.'>'.__('Offer','post_checkout_offers_lite').$mwb_single_offer_to_add.'</option>';
										}
									}
								}
							}
						}
					}

					$mwb_wcpcolite_no_offers="";

					if( !empty( $mwb_wcpcolite_offers_no_thanks_offers ) )
					{
						if( $mwb_wcpcolite_offers_no_thanks_offers[$offers] == 'thanks' )
						{
							$mwb_wcpcolite_no_offers = '<select style="" name="mwb_wcpcolite_attached_offers_on_no['.$mwb_wcpcolite_single_offer.']"><option value="thanks">'.__('ThankYou Page','post_checkout_offers_lite').'</option>'.$mwb_wcpcolite_no_attached_offers;
						}
						elseif( $mwb_wcpcolite_offers_no_thanks_offers[$offers] > 0 )
						{
							$mwb_wcpcolite_no_offers = '<select style="" name="mwb_wcpcolite_attached_offers_on_no['.$mwb_wcpcolite_single_offer.']"><option value="thanks">'.__('ThankYou Page','post_checkout_offers_lite').'</option>';

							if( !empty( $mwb_wcpcolite_offers_to_add ) )
							{
								foreach( $mwb_wcpcolite_offers_to_add as $mwb_single_offer_to_add )
								{
								
									if( $mwb_wcpcolite_single_offer != $mwb_single_offer_to_add )
									{
										if( $mwb_wcpcolite_offers_no_thanks_offers[$offers]==$mwb_single_offer_to_add )
										{
											$mwb_wcpcolite_no_offers .= '<option value='.$mwb_single_offer_to_add.' selected="">'.__('Offer','post_checkout_offers_lite').$mwb_single_offer_to_add.'</option>';
										}
										else
										{
											$mwb_wcpcolite_no_offers .= '<option value='.$mwb_single_offer_to_add.'>'.__('Offer','post_checkout_offers_lite').$mwb_single_offer_to_add.'</option>';
										}
									}
								}
							}
						}
					}
					
					$mwb_wcpcolite_buy_offers .= '</select>';

					$mwb_wcpcolite_no_offers .= '</select>';

					?>
					<div class="new_created_offers" data-id="<?php echo $mwb_wcpcolite_single_offer ?>">

						<h2>
							<?php _e('Offer#','post_checkout_offers_lite')?>
							<?php echo $mwb_wcpcolite_single_offer?>
						</h2>

						<table>

							<tr>
								<th><label><h4><?php _e('Product search : ','post_checkout_offers_lite')?></h4></label>
								</th>
								<td>
								<select class="wc-offer-product-search" multiple="multiple" name="mwb_wcpcolite_products_in_offer[<?php echo $offers ?>][]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'post_checkout_offers_lite' ); ?>">
								<?php
								
									$mwb_wcpcolite_offers_products = $mwb_wcpcolite_products_offer[$offers];

									$mwb_wcpcolite_target_offer_ids = ! empty( $mwb_wcpcolite_offers_products ) ? array_map( 'absint',  $mwb_wcpcolite_offers_products ) : null;
									
									if ( $mwb_wcpcolite_target_offer_ids ) 
									{
										foreach ( $mwb_wcpcolite_target_offer_ids as $mwb_wcpcolite_single_target_offer_id ) 
										{
											
											$product_name = get_the_title($mwb_wcpcolite_single_target_offer_id);
											?>
											<option value="<?php echo $mwb_wcpcolite_single_target_offer_id ?>" selected="selected"><?php echo $product_name."(# $mwb_wcpcolite_single_target_offer_id)" ?>
											</option>
											<?php
										}
									}
								?>
								</select> 
								
								</td>
							</tr>
							
						    <tr>
							    <th><label><h4><?php _e('Offer price: ','post_checkout_offers_lite')?></h4></label></th>
							    <td>
							    <input type="text" style="width:50%;height:40px;" placeholder="<?php _e('enter in percentage','post_checkout_offers_lite')?>" name="mwb_wcpcolite_offer_discount_price[<?php echo $offers?>]" value="<?php echo $mwb_wcpcolite_products_discount[$offers]?>" id = "mwb_wcpcolite_offer_discount">
							    
							    <span style="color:green"><?php _e(" Note: Enter in % or a new offer price","post_checkout_offers_lite")?></span> 
							    </td>
						    </tr>

						    <tr>
							    <th><label><h4><?php _e('After "Buy Now" go to: ','post_checkout_offers_lite')?></h4></label></th>
							    <td><?php echo $mwb_wcpcolite_buy_offers;?></td>
							</tr>

							<tr>
								<th><label><h4><?php _e('After "No thanks" go to: ','post_checkout_offers_lite')?></h4></label></th>
							    <td><?php echo $mwb_wcpcolite_no_offers;?></td>
						    </tr>
					    </table>
					    <input type="hidden" name="mwb_wcpcolite_applied_offer_number[<?php echo $offers?>]" value="<?php echo $mwb_wcpcolite_single_offer?>">
				    </div>
			    <?php
				}
			}
			?>
		</div>

		<?php if(!isset($mwb_wcpcolite_single_offer) || ! ($mwb_wcpcolite_single_offer >= 1 )) : ?> 

			<div class="mwb_wcpcolite_new_offer">
				<button id="create_new_offer" class="mwb_wcpcolite_create_new_offer" data-id="<?php echo $mwb_wcpcolite_funnel_id?>" data-create_new_offer_nonce = "<?php echo wp_create_nonce( 'create_new_offer_nonce' );?>">
				<?php _e('Add New Offer','post_checkout_offers_lite');?>
				</button>
			</div>
		<?php endif ; 

		do_action('mwb_wcpcolite_general_setting');

		?>
		<input type="hidden" name="mwb_wcpcolite_creation_setting_nonce" value="<?php echo wp_create_nonce( 'mwb_wcpcolite_creation_setting_nonce' ) ;?>">		
		<p class="submit">
			<input type="submit" value="<?php _e('Save Changes', 'post_checkout_offers_lite'); ?>" class="button-primary woocommerce-save-button button-hide" name="mwb_wcpcolite_creation_setting_save" id="mwb_wcpcolite_creation_setting_save">
		</p>
	</div>
</form>