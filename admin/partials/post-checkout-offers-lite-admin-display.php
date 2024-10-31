<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Post_Checkout_Offers
 * @subpackage Post_Checkout_Offers/admin/partials
 */
if ( ! defined( 'ABSPATH' ) ) 
{
	exit;
}

$active_tab = isset( $_GET[ 'tab' ] ) ? sanitize_text_field( $_GET[ 'tab' ] ) :'settings';

do_action('mwb_wcpcolite_setting_tab_active');

?>
<div class="wrap woocommerce clearfix" id="mwb_wcpcolite_setting_wrapper">

	<div class="hide"  id="mwb_wcpcolite_loader">	
		<img id="mwb-wcpco-loading-image" src="<?php echo plugin_dir_url( __FILE__ ) . 'templates/images/ajax-loader.gif'?>" >
	</div>

	<h1 class="mwb_wcpcolite_setting_title"><?php _e('Post Checkout Offers Lite', 'post_checkout_offers_lite')?>   
    </h1> 
    <div class="mwb_wcpcolite_left_content">
        <div class="mwb_wcpcolite_inner">
                <nav class="nav-tab-wrapper nav-tab-wrapper">   

            <a class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>" href="?page=mwb-wcpcolite-settings&tab=settings"><?php _e('General Settings', 'post_checkout_offers_lite');?></a>

            <a class="nav-tab <?php echo $active_tab == 'creation-setting' ? 'nav-tab-active' : ''; ?>" href="?page=mwb-wcpcolite-settings&tab=creation-setting"><?php _e('Edit/Create Funnel', 'post_checkout_offers_lite');?></a> 

            <a class="nav-tab <?php echo $active_tab == 'funnels-list' ? 'nav-tab-active' : ''; ?>" href="?page=mwb-wcpcolite-settings&tab=funnels-list"><?php _e('Funnels List', 'post_checkout_offers_lite');?></a> 

        </nav>
    <?php 

            if( $active_tab == 'creation-setting' ) 
            {
                include_once 'templates/mwb_wcpcolite_creation.php';
            } 
            elseif($active_tab == 'funnels-list')
            {
                include_once 'templates/mwb_wcpcolite_funnels_list.php';
            }
            elseif($active_tab == 'settings')
            {
                include_once 'templates/mwb_wcpcolite_settings.php';
            }
            do_action('mwb_wcpcolite_setting_tab_html');
    ?>
        </div>
    </div>
    <div class="mwb_wcpcolite_right_content">
        <div class="mwb_wcpcolite_inner mwb_wcpcolite_inner_feature">
            <h1 style="background-color: #155FA7;padding: 12px;"><?php _e('Try Our Pro Vesrion' , 'post_checkout_offers_lite') ;?></h1>
            <a href="https://goo.gl/K4Y1Wn" target="_blank">
                <img class="mwb_wcpcolite_logo"  src="<?php echo plugin_dir_url( __FILE__ ) . 'templates/images/logo.jpg'?>">  
            </a>
            <h1 style="margin: 20px 0px; border-radius:0;"><?php echo 'POST CHECKOUT OFFERS';?></h1>
            <h3><?php _e('Main Features' , 'post_checkout_offers_lite') ;?></h3>
            <ul class="mwb_wcpcolite_pro_feature">
                <li>
                    <?php _e('Supports all product types' , 'post_checkout_offers_lite') ;?>
                </li> 
                <li>
                    <?php _e('Supports all core payment methods' , 'post_checkout_offers_lite') ;?>
                </li>
                <li>
                    <?php _e('Shortcode support to design custom offer page' , 'post_checkout_offers_lite') ;?>
                </li>
                <li>
                    <?php _e('Add timer to your offers' , 'post_checkout_offers_lite') ;?>
                </li>
                <li>
                    <?php _e('Schedule your funnels' , 'post_checkout_offers_lite') ;?>
                </li> 
                 <li>
                    <?php _e('Track report of our offers sale' , 'post_checkout_offers_lite') ;?>
                </li>
                 <li>
                    <?php _e('WPML Compatible' , 'post_checkout_offers_lite') ;?>
                </li>
            </ul>
            <a target="_blank" href="https://goo.gl/K4Y1Wn" class="mwb_wcpcolite_pro_buy_now"><?php _e('GET IT NOW' , 'post_checkout_offers_lite') ;?></a>
        </div> 
    </div>
</div>


