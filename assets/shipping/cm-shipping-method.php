<?php

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	
	function cm_shipping_method_init() {

		if ( ! class_exists( 'WC_Cm_Shipping_Method' ) ) {

			class WC_Cm_Shipping_Method extends WC_Shipping_Method {

				/**
				 * Constructor for your shipping class
				 *
				 * @access public
				 * @return void
				 */
				public function __construct() {
					$this->id                 = 'cm_shipping_method'; // Id for your shipping method. Should be uunique.
					$this->method_title       = __( 'CM Freight' );  // Title shown in admin
					$this->method_description = __( 'CM Freight' ); // Description shown in admin

					$this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled
					$this->title              = "CM Freight"; // This can be added as an setting but for this example its forced.

					$this->init();
				}

				/**
				 * Init your settings
				 *
				 * @access public
				 * @return void
				 */
				function init() {
					// Load the settings API
					$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
					$this->init_settings(); // This is part of the settings API. Loads settings you previously init.

					// Save settings in admin if you have any defined
					add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
				}

				/**
				 * calculate_shipping function.
				 *
				 * @access public
				 * @param mixed $package
				 * @return void
				 */
				public function calculate_shipping( $package = array() ) {

					$freigh_session = new CM_Cm_Filter;
					$freigh_session->session_var();

					if(!empty($freigh_session->cart_freight_cost_session)) {

						$rate = array(
							'id' => $this->id,
							'label' => $this->title,
							'cost' => $freigh_session->cart_freight_cost_session
							//'calc_tax' => 'per_item'
						);

						// Register the rate
						$this->add_rate( $rate );

					}
				}

			}

		}

	}

}

add_action( 'woocommerce_shipping_init', 'cm_shipping_method_init' );

function add_cm_shipping_method( $methods ) {

	$methods['cm_shipping_method'] = 'WC_Cm_Shipping_Method';
	return $methods;

}

add_filter( 'woocommerce_shipping_methods', 'add_cm_shipping_method' );