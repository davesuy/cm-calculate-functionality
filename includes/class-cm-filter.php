<?php

class CM_Cm_Filter {

	public $cart_packaging_cost_session;
	public $cart_freight_cost_session;

	public function __construct() {


		add_action('init', array($this, 'cm_calculate_shipping_init'), 20);

	}

	public static function init() {

		$instance = false;

		if(!$instance) {
			$instance = new CM_Cm_Filter;
		}

		return $instance;

	}


	public function cm_calculate_shipping_init() {

	   	//add_filter( 'woocommerce_package_rates', array($this, 'adjust_shipping_rate'), 20, 1 );
		add_action( 'woocommerce_thankyou', array($this, 'cm_woocommerce_thankyou'), 10, 1 ); 
		add_action( 'woocommerce_admin_order_data_after_shipping_address', array($this,'cm_shipping_display_admin_order_meta'), 10, 1 );
	   	add_action( 'woocommerce_checkout_update_order_meta', array($this, 'action_woocommerce_shipping_order_meta'), 10, 2 ); 
	   	add_filter( 'woocommerce_cart_shipping_method_full_label', array($this, 'cm_remove_shipping_label'), 10, 2 );
	   	add_action( 'woocommerce_cart_calculate_fees', array($this, 'cm_add_cart_fee' ), 20);
	   	add_action( 'woocommerce_check_cart_items', array($this, 'cm_empty_cart'), 20 );

	}

	public function cm_empty_cart() {

		$cart_session = CM_Calculation_Session::init();

		$items = WC()->cart->get_cart();
	    
		if ( WC()->cart->get_cart_contents_count() == 0 ) {

	     	$cm_session_unset = WC()->session->__unset( 'cm_calculate_session' );
	     	$cm_session_cost_unset_ = WC()->session->__unset( 'cm_add_cost_session' );
	     	$cm_session_cost_unset_ = WC()->session->__unset( 'cm_order_item_meta' );
	         	
		}

	}

		// define the woocommerce_thankyou callback 
	public function cm_woocommerce_thankyou( $order_id ) { 

	    $cart_session = CM_Calculation_Session::init();


     	$cm_session_unset = WC()->session->__unset( 'cm_calculate_session' );
     	$cm_session_cost_unset_ = WC()->session->__unset( 'cm_add_cost_session' );
     	$cm_session_cost_unset_ = WC()->session->__unset( 'cm_order_item_meta' );

	}
	         

	public function adjust_shipping_rate( $rates ) {

		//echo '<pre>'.print_r( $rates, true).'</pre>';

		$this->session_var();

		if(!empty($this->cart_freight_cost_session)) {

	
			$rates['flat_rate:2']->cost = $this->cart_freight_cost_session;

		} 

		//$rates['flat_rate:2']->cost = $this->cart_freight_cost_session ;

		return $rates;

	}

	public function cm_remove_shipping_label($label, $method) {

		$new_label = preg_replace( '/^.+:/', '', $label );

		return $new_label;

	}

	public function cm_add_cart_fee() {

		$this->session_var();

		if(!empty($this->cart_packaging_cost_session )) {
			

	  		WC()->cart->add_fee( __('Packaging', 'woocommerce'), $this->cart_packaging_cost_session );

	  	}
	
	}

	public function action_woocommerce_shipping_order_meta( $order_id, $posted ) { 

		$session_var =  'cm_order_item_meta';
		$session_data = WC()->session->get( $session_var );


		if(!empty($session_data )) {

			 update_post_meta( $order_id, 'Total Number of Boxes', $session_data['total_number_boxes']);
			 update_post_meta( $order_id, 'Shipping Location', $session_data['shipping_location']);
			 update_post_meta( $order_id, 'Shipping Carrier', $session_data['shipping_carrier']);
			 update_post_meta( $order_id, 'Shipping Time', $session_data['shipping_time']);

		} else {

			  error_log("no session data", 0);

		}

   		
	}

	public function cm_shipping_display_admin_order_meta($order) {

		$cm_total =  get_post_meta( $order->id, 'Total Number of Boxes', true );
		$cm_location =  get_post_meta( $order->id, 'Shipping Location', true );
		$cm_carrier =  get_post_meta( $order->id, 'Shipping Carrier', true );
		$cm_time =  get_post_meta( $order->id, 'Shipping Time', true );

		echo '<p><strong>'.__('Total Number of Boxes').':</strong> <br/>'.$cm_total.'</p>';
		echo '<p><strong>'.__('Destination').':</strong> <br/>'.$cm_location.'</p>';
		echo '<p><strong>'.__('Carrier').':</strong> <br/>'.$cm_carrier.'</p>';
		echo '<p><strong>'.__('Pickup time').':</strong> <br/>'.$cm_time.'</p>';

	}
      

	public function session_var() {

			$cart_session = CM_Calculation_Session::init();

			$this->cart_packaging_cost_session = $cart_session->cart_add_session['cost_packaging'];
			$this->cart_freight_cost_session = $cart_session->cart_add_session['cost_freight'];
	}
	

}