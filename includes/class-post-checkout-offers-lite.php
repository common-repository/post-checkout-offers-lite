<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Post_Checkout_Offers_Lite
 * @subpackage Post_Checkout_Offers_Lite/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Post_Checkout_Offers_Lite
 * @subpackage Post_Checkout_Offers_Lite/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Post_Checkout_Offers_Lite {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Post_Checkout_Offers_Lite_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MWB_WCPCOLITE_VERSION' ) ) {
			$this->version = MWB_WCPCOLITE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'post-checkout-offers-lite';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Post_Checkout_Offers_Lite_Loader. Orchestrates the hooks of the plugin.
	 * - Post_Checkout_Offers_Lite_i18n. Defines internationalization functionality.
	 * - Post_Checkout_Offers_Lite_Admin. Defines all hooks for the admin area.
	 * - Post_Checkout_Offers_Lite_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-post-checkout-offers-lite-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-post-checkout-offers-lite-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-post-checkout-offers-lite-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-post-checkout-offers-lite-public.php';

		$this->loader = new Post_Checkout_Offers_Lite_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Post_Checkout_Offers_Lite_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Post_Checkout_Offers_Lite_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Post_Checkout_Offers_Lite_Admin( $this->get_plugin_name(), $this->get_version() );

		$mwb_wcpcolite_enable_plugin = get_option( "mwb_wcpcolite_enable_plugin", "off" );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' , 9999 );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' ); 

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'mwb_wcpcolite_admin_menu' ); 

		$this->loader->add_action( 'wp_ajax_mwb_wcpcolite_return_offer_content', $plugin_admin, 'mwb_wcpcolite_return_offer_content' );

		$this->loader->add_action( 'wp_ajax_search_products_for_offers_lite', $plugin_admin, 'mwb_wcpcolite_search_products_for_offers' );

		$this->loader->add_action( 'wp_ajax_search_products_for_funnel_lite', $plugin_admin, 'mwb_wcpcolite_search_products_for_funnel' ); 

		$this->loader->add_filter( 'page_template', $plugin_admin, 'mwb_wcpcolite_page_template' );
		
		if($mwb_wcpcolite_enable_plugin == "on"){

			$this->loader->add_action( 'manage_shop_order_posts_custom_column', $plugin_admin, 'mwb_wcpcolite_add_upsell_orders_to_parent', 10, 2 );

			$this->loader->add_filter( 'manage_edit-shop_order_columns', $plugin_admin, 'mwb_wcpcolite_add_columns_to_admin_orders', 11 );

			$this->loader->add_action( 'woocommerce_admin_order_data_after_order_details' , $plugin_admin, 'mwb_wcpcolite_change_admin_order_details' ); 

			$this->loader->add_filter( 'restrict_manage_posts', $plugin_admin, 'mwb_wcpcolite_restrict_manage_posts' );

			$this->loader->add_filter( 'request', $plugin_admin, 'mwb_wcpcolite_request_query' );
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Post_Checkout_Offers_Lite_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' ); 

		$mwb_wcpcolite_enable_plugin = get_option( "mwb_wcpcolite_enable_plugin", "off" );

		if($mwb_wcpcolite_enable_plugin === "on"){

			$this->loader->add_action( 'init', $plugin_public, 'mwb_wcpcolite_create_funnel_offer_shortcode' );

			$this->loader->add_filter( 'woocommerce_get_checkout_order_received_url', $plugin_public, 'mwb_wcpcolite_process_funnel_offers', 20, 2 );

			$this->loader->add_action( 'wp_loaded', $plugin_public, 'mwb_wcpcolite_process_the_funnel' );

			$this->loader->add_action( 'wp_loaded', $plugin_public, 'mwb_wcpcolite_charge_the_offer' );
 
			$this->loader->add_action( 'template_redirect', $plugin_public, 'mwb_wcpcolite_add_to_cart_funnel_products' );
						
			$this->loader->add_action('woocommerce_before_calculate_totals' ,$plugin_public ,'mwb_wcpcolite_filter_upsell_product_price',10,1); 

			$this->loader->add_action('woocommerce_checkout_order_processed', $plugin_public, 'mwb_wcpcolite_update_meta_for_upsell_orders');   
			
			$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_public, 'mwb_wcpcolite_update_offer_order_meta' ,  10 ,2 ); 

			$this->loader->add_action( 'wp_footer', $plugin_public, 'mwb_wcpcolite_show_offer_noitce' ); 

			$this->loader->add_action( 'wp_ajax_mwb_wcpcolite_go_to_parent', $plugin_public, 'mwb_wcpcolite_go_to_parent' ); 

			$this->loader->add_action( 'wp_ajax_nopriv_mwb_wcpcolite_go_to_parent', $plugin_public, 'mwb_wcpcolite_go_to_parent' ); 

			$this->loader->add_filter( 'woocommerce_locate_template',$plugin_public,'mwb_wcpcolite_woocommerce_locate_template' , 100, 3 );

		}



	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Post_Checkout_Offers_Lite_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
