<?php
/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * Funnels Listing Template
 */

if( isset( $_GET["del_funnel_id"] ) )
{
	$funnel_id = sanitize_text_field( $_GET["del_funnel_id"] );

	$mwb_wcpcolite_funnels = get_option( "mwb_wcpcolite_funnels_list" );

	foreach( $mwb_wcpcolite_funnels as $single_funnel => $data )
	{
		if( $funnel_id == $single_funnel )
		{
			unset( $mwb_wcpcolite_funnels[$single_funnel] );
			break;
		}
	}

	$mwb_wcpcolite_funnels = array_values($mwb_wcpcolite_funnels);

	update_option( "mwb_wcpcolite_funnels_list", $mwb_wcpcolite_funnels );

	wp_redirect( admin_url('admin.php').'?page=mwb-wcpcolite-settings&tab=funnels-list' );

	exit();
}

$mwb_wcpcolite_funnels_list = get_option( "mwb_wcpcolite_funnels_list" );

if( !empty( $mwb_wcpcolite_funnels_list ) )
{
	$mwb_wcpcolite_funnel_duplicate = $mwb_wcpcolite_funnels_list;
	end( $mwb_wcpcolite_funnel_duplicate );
	$mwb_wcpcolite_funnel_number = key( $mwb_wcpcolite_funnel_duplicate );
}
else
{
	$mwb_wcpcolite_funnel_number = -1;
}
?>
<div class="mwb_wcpcolite_funnels_list">
	<h1><?php _e('Your Funnels','post_checkout_offers_lite');?></h1>
	<?php if( empty( $mwb_wcpcolite_funnels_list ) ):?>
		<p class="mwb_wcpcolite_no_funnel"><?php _e('No funnels added','post_checkout_offers_lite');?></p>
	<?php endif; ?>
	<?php if( !empty( $mwb_wcpcolite_funnels_list ) ):?>
		<table>
			<tr>
				<th><?php _e('Funnel Name','post_checkout_offers_lite');?></th>
				<th><?php _e('Funnel Targets','post_checkout_offers_lite');?></th>
				<th><?php _e('Offers Count','post_checkout_offers_lite');?></th>
				<th><?php _e('Action','post_checkout_offers_lite');?></th>
				<?php do_action("mwb_wcpcolite_funnel_add_more_col_head");?>
			</tr>
			<?php foreach ( $mwb_wcpcolite_funnels_list as $key => $value ):
				$offers_count = !empty( $value["mwb_wcpcolite_applied_offer_number"] )?$value["mwb_wcpcolite_applied_offer_number"]:array();?>
				<tr>
					<td><a href="?page=mwb-wcpcolite-settings&tab=creation-setting&funnel_id=<?php echo $key?>"><?php echo $value["mwb_wcpcolite_funnel_name"] ?></a></td>
					<td>
					<?php if( !empty( $value["mwb_wcpcolite_target_pro_ids"] ) ){?>
						<?php foreach( $value["mwb_wcpcolite_target_pro_ids"] as $single_target_product ):
							$product = wc_get_product( $single_target_product );
							$product_id = $product->get_id();
							$post = get_post($product_id);
						?>
							<p><?php echo $post->post_title.'(#'.$product_id.')';?></p>
						<?php endforeach;?>
					<?php } else {?>
						<p><?php _e("No products added","post_checkout_offers_lite");?></p>
					<?php }?>
					</td>
					<td><p><?php echo count( $offers_count ) ?></p></td> 
					<td style="display: inline-flex;">
					<p><a class="mwb_wcpcolite_funnel_links" href="?page=mwb-wcpcolite-settings&tab=creation-setting&funnel_id=<?php echo $key?>"><?php _e('View','post_checkout_offers_lite');?></a></p>
					<p><a class="mwb_wcpcolite_funnel_links" href="?page=mwb-wcpcolite-settings&tab=funnels-list&del_funnel_id=<?php echo $key?>"><?php _e('Delete','post_checkout_offers_lite');?></a><p></td>
					<?php do_action("mwb_wcpcolite_funnel_add_more_col_data");?>
				</tr>
			<?php endforeach;?>
		</table>
	<?php endif;?>
</div>
<br>
<div class="mwb_wcpcolite_create_new_funnel">
<a href="?page=mwb-wcpcolite-settings&tab=creation-setting&funnel_id=<?php echo $mwb_wcpcolite_funnel_number+1 ?>"><?php _e('+Create New Funnel','post_checkout_offers_lite')?></a>
</div>
<?php
do_action("mwb_wcpcolite_extend_funnels_listing");
?>