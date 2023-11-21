<?php
/**
 * Main
 *
 * @category Wodrpress-Plugins
 * @package  WP-FoodTec
 * @author   FoodTec Solutions <info@foodtecsolutions.com>
 * @license  GPLv2 or later
 * @link     https://gitlab.foodtecsolutions.com/fts/wordpress/plugins/wp-foodtec
 * @since    1.0.0
 */

namespace WP_FoodTec_Core\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class
 */
class WP_FoodTec_Core {
	/**
	 * The zoho object
	 *
	 * @var    Libraries\Zoho
	 * @access public
	 * @since  1.0.0
	 */
	public $zoho;
	/**
	 * The single instance of WP_FoodTec_Core.
	 *
	 * @var    WP_FoodTec_Core
	 * @access private
	 * @since  1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 *
	 * @var    object
	 * @access public
	 * @since  1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $script_suffix;

	/**
	 * The google recaptcha object.
	 *
	 * @var Libraries\Google_Recaptcha
	 */
	public $recaptcha;

	/**
	 * The admnin API.
	 *
	 * @var Libraries\Admin_API
	 */
	public $admin;

	/**
	 * Constructor function.
	 *
	 * @param string $file    The file.
	 * @param string $version The plugin version.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token   = 'fts';

		// Load plugin environment variables.
		$this->file       = $file;
		$this->dir        = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'dist';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/dist/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Load frontend JS & CSS.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );

		// Load API for generic admin functions.
		if ( is_admin() ) {
			$this->admin = new Libraries\Admin_API();
		}

		$update              = new Update( $this );
		$brand = new Libraries\Requests\Marketing\Brand();
		$this->recaptcha     = new Libraries\Google_Recaptcha();
		$enqueue_options     = new Libraries\Enqueue_Options();
		$allowed_hosts       = new Libraries\Allowed_Hosts();
		$redirection         = new Libraries\Redirection();
		$geocoder            = new Libraries\Geocoding\Geocoder();
		$forgot_password     = new Libraries\Forgot_Password();
		$preorder            = new Libraries\Preorder();
		$auth_service        = new Libraries\Auth_Service();
		$addresses           = new Libraries\Addresses();
		$css_classes         = new Libraries\Css_Classes();

		$this->register_shortcodes();
		$this->register_widgets();

		// Handle localisation.
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );
	}

	

	/**
	 * Registers all shortcodes.
	 *
	 * @return void
	 */
	private function register_shortcodes() {
		$shortcodes = array(
			'Register_Form',
			'Signup_Form',
			'Subscribe_Form',
			'Gift_Card_Balance_Form',
			'Order_Tracker',
			'Button',
			'Order_Button',
			'Login_Button',
			'Login_Form',
			'Reset_Password_Form',
			'Subscription_Matrix',
			'Account',
			'Rewards',
			'Rewards_Block',
			'Order_Details',
			'Order_History',
			'Favorite_Order',
			'Tremendous',
			'Map',
			'Store_List',
			'Carousel'
		);

		$registered_shortcodes = array();

		foreach ( $shortcodes as $shortcode ) {
			$shortcode_class                     = '\WP_FoodTec_Core\Includes\Shortcodes\\' . $shortcode;
			$registered_shortcodes[ $shortcode ] = new $shortcode_class();
		}
	}

	/**
	 * Registers all widgets.
	 *
	 * @return void
	 */
	private function register_widgets() {
		$widgets = array(
			'Subscribe_Form',
			'SMS_Form',
			'Order_Tracker',
			'Register_Form',
			'Gift_Card_Balance_Form',
			'Login_Form',
			'Powered_By',
			'Page'
		);

		foreach ( $widgets as $class_name ) {
			add_action(
				'widgets_init',
				function () use ( $class_name ) {
					return register_widget( '\WP_FoodTec_Core\Includes\Widgets\\' . $class_name );
				}
			);
		}
	}

	/**
	 * Load frontend Javascript.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_register_script( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'scripts/frontend' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version, true );
		wp_enqueue_script( $this->_token . '-frontend' );

		if ( get_option( 'foodtec_bootstrap_scripts', true ) ) {
			wp_enqueue_script( $this->_token . '-boostrap3-script','https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery' ) );
		}

		if ( get_option( 'foodtec_maskedinput', true ) ) {
			wp_enqueue_script( $this->_token . '-maskedinput','https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js', array( 'jquery' ) );
		}

	}

	/**
	 * Load frontend CSS.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function enqueue_styles() {
		wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'styles/styles' . $this->script_suffix . '.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-frontend' );

		if ( get_option( 'foodtec_bootstrap_styles', true ) ) {
			wp_enqueue_style( $this->_token . '-boostrap3-styles','https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' );
		}

		if ( get_option( 'foodtec_fontawesome', true ) ) {
			wp_enqueue_style( $this->_token . '-fontawesome','https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css' );
		}

		if ( get_option( 'foodtec_fontawesome_shims', true ) ) {
			wp_enqueue_style( $this->_token . '-fontawesome-v4-shims','https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/v4-shims.min.css' );
		}
	}

	/**
	 * Load plugin localisation
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function load_localisation() {
		load_plugin_textdomain( 'wp-foodtec-core', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}

	/**
	 * Load plugin textdomain
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$domain = 'wp-foodtec-core';
		// phpcs:disable
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		// phpcs:enable

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}

	/**
	 * Main WP_FoodTec_Core Instance
	 *
	 * @static
	 * @see wp_foodTec_core()
	 *
	 * @param string $file    The file.
	 * @param string $version The plugin version.
	 *
	 * @return WP_FoodTec_Core     The WP_FoodTec_Core instance.
	 */
	public static function instance( $file = '', $version = '1.0.0' ): self {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-foodtec-core' ), $this->_version );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-foodtec-core' ), $this->_version );
	}

	/**
	 * Installation. Runs on activation.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function install() {
		$this->_log_version_number();
	}

	/**
	 * Log the plugin version number.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	private function _log_version_number() {
		update_option( $this->_token . '_version', $this->_version );
	}
}
