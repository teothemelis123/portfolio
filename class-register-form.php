<?php
/**
 * Register shortcode
 *
 * @category Wodrpress-Plugins
 * @package  WP-FoodTec-Core
 * @author   FoodTec Solutions <wordpress_support@foodtecsolutions.com>
 * @license  GPLv2 or later
 * @since    1.0.0
 */

namespace WP_FoodTec_Core\Includes\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registration shortcode class
 */
class Register_Form {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_shortcode( 'foodtec_register', array( $this, 'register_callback' ) );
		add_action( 'wp_ajax_register', array( $this, 'register_request' ) );
		add_action( 'wp_ajax_nopriv_register', array( $this, 'register_request' ) );
	}

	/**
	 * The foodtec_register shortcode callback function.
	 *
	 * @param array $atts The shortcode attributes.
	 *
	 * @return string
	 */
	public function register_callback( $atts ): string {
		if ( ! is_ssl() && ! WP_DEBUG ) {
			return '';
		}

		$atts = shortcode_atts( array( 'theme' => 'light' ), $atts );

		$brand = ( new \WP_FoodTec_Core\Includes\Libraries\Requests\Marketing\Brand )->request();

		if ( empty( $brand ) ) {
			return '';
		}

		if ( is_string( $brand ) ) {
			return WP_DEBUG ? $brand : '';
		}

		$states = ( new \WP_FoodTec_Core\Includes\Libraries\Store_Helpers )->get_states( $brand->stores );

		return ( new \WP_FoodTec_Core\Includes\Libraries\Template )->load(
			'register.php',
			array(
				'has_sms' => $brand->hasSMSCampaigns,
				'loyalty_plans' => isset( $brand->plans ) ? $brand->plans : array(),
				'birthday'      => $this->has_birthday( $brand->customerTagTypes ),
				'store_select'  => ( new \WP_FoodTec_Core\Includes\Libraries\Html_Helpers )->store_selection_widget( $states, $brand->stores ),
				'recaptcha'     => ( new \WP_FoodTec_Core\Includes\Libraries\Google_Recaptcha )->html( $atts['theme'] ),
				'nonce'         => wp_create_nonce( 'register_nonce' ),
			)
		);
	}

	/**
	 * Makes the registration request
	 *
	 * @see https://docs.foodtecsolutions.com/pdirect/9.5/ads/doc/util/docspublic/MarketingPublicAPI.html#registration
	 *
	 * @return void
	 */
	public function register_request() {
		$ajax_options = array(
			'nonce'           => 'register_nonce',
			'recaptcha'       => true,
			'ssl'             => true,
			'query_arguments' => array(
				'email'            => array(
					'sanitize_function' => 'sanitize_email',
					'required'          => true,
				),
				'firstName'         => array(
					'sanitize_function' => 'sanitize_text_field',
					'required'          => true,
				),
				'lastName'         => array(
					'sanitize_function' => 'sanitize_text_field',
					'required'          => true,
				),
				'favoriteStore'    => array(
					'sanitize_function' => 'sanitize_text_field',
					'encoding_function' => 'base64_decode',
					'required'          => true,
				),
				'password'         => array(
					'sanitize_function' => 'sanitize_text_field',
					'required'          => true,
				),
				'phone'            => array(
					'sanitize_function' => 'sanitize_text_field',
					'required'          => true,
				),
				'signupForLoyalty' => array(
					'sanitize_function' => 'sanitize_text_field',
					'required'          => false,
				),
				'smsOffers'        => array(
					'sanitize_function' => 'sanitize_text_field',
					'required'          => false, // Update this as needed
				),
				'emailOffers'        => array(
					'sanitize_function' => 'sanitize_text_field',
					'required'          => false, // Update this as needed
				),
				'smsDisclaimer'        => array(
					'sanitize_function' => 'sanitize_text_field',
					'required'          => false, // Update this as needed
				),
				'acceptPolicy'     => array(
					'sanitize_function' => 'sanitize_text_field',
					'required'          => true,
				),
				'giftCard'         => array(
					'sanitize_function' => 'sanitize_text_field',
					'required'          => false,
				),
				'loyaltyCard'      => array(
					'sanitize_function' => 'sanitize_text_field',
					'required'          => false,
				),
				'birthMonth'       => array(
					'sanitize_function' => 'sanitize_text_field',
					'required'          => false,
				),
				'birthDay'         => array(
					'sanitize_function' => 'sanitize_text_field',
					'required'          => false,
				),
			),
		);

		$params = ( new \WP_FoodTec_Core\Includes\Libraries\Ajax_Validator )->validate( $ajax_options );

		$ip = $_SERVER['REMOTE_ADDR'];
		// Create the 'channels' array based on what checkboxes have been checked
		$name = $params['firstName'] . ' ' . $params['lastName'];
		// Rest of your code remains unchanged...

		// Add the 'channels' array to your data
		$data = array(
			'email' => $params['email'],
			'name' => $name,
			'store' => $params['favoriteStore'],
			'password' => $params['password'],
			'phone' => $params['phone'],
			'signupForLoyalty' => $params['signupForLoyalty'],
			'giftCard' => $params['giftCard'],
			'smsDisclaimer' => $params['smsDisclaimer'],
			'userIpAddress' => $ip,
			'channels' => [
					[
						"channelName" => "EMAIL",
						"services" => [
							[
								"serviceName" => "OFFERS",
								"subscribed" => isset($params['emailOffers']) && $params['emailOffers'] === 'true' ? true : false,
								"optedOut" => false
							]
						]
					],
					[
						"channelName" => "SMS",
						"services" => [
							[
								"serviceName" => "OFFERS",
								"subscribed" => isset($params['smsOffers']) && $params['smsOffers'] === 'true' ? true : false,
								"optedOut" => false,
								"dateSubscribed" => isset($params['smsOffers']) && $params['smsOffers'] === 'true' ? 1670424234000 : null
							]
						]
					]
				]
			);

		if (!empty($params['birthMonth']) && !empty($params['birthDay'])) {
			$data['attributes'] = array(
				'Birthday' => $params['birthMonth'] . '/' . $params['birthDay'],
			);
		}
// Log the JSON string to the browser's console

		exit((new \WP_FoodTec_Core\Includes\Libraries\Requests\Marketing\Register)->request($data));
	}


	/**
	 * Checks if the merchant has Birthday attribute set.
	 *
	 * @see https://docs.foodtecsolutions.com/marketing/2.1/MarketingPublicAPI.html#attributes
	 *
	 * @param array $attributes The attributes array.
	 *
	 * @return boolean
	 */
	private function has_birthday( $attributes ) {
		foreach ( $attributes as $attribute ) {
			if ( 'Birthday' === $attribute->name ) {
				return true;
			}
		}

		return false;
	}
}