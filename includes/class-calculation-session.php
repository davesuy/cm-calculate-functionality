<?php

class CM_Calculation_Session {

	public $cart_session;
	public $cart_add_session;
	
	public function __construct() {

		$this->store_wc();

	}

	public static function init() {

		$instance = false;

		if(!$instance) {
			$instance = new CM_Calculation_Session;
		}

		return $instance;

	}


	public function store_wc() {

		$cart_session_var = WC()->session;

		if(!empty($cart_session_var)) {

			$cart_session = WC()->session->get( 'cm_calculate_session' );
			$cart_add_session = WC()->session->get( 'cm_add_cost_session' );

		}

		$this->cart_session = $cart_session;
		$this->cart_add_session = $cart_add_session;
	}

}