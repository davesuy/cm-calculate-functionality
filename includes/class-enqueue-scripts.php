<?php

class CM_Enqueue_Scripts {

	public function __construct() {

		add_action( 'wp_enqueue_scripts', array($this, 'cm_enqueue_scripts'), 20);

	}

	public static function init() {
		$instance = false;

		if(!$instance) {
			$instance = new CM_Enqueue_Scripts;
		}

		return $instance;
	}

	function cm_enqueue_scripts() {

		// Custom Scripts & Ajax

		wp_enqueue_style( 'cm-style', CM_ASSETS.'/css/style.css');

		wp_enqueue_script( 'custom_script', CM_ASSETS . '/js/custom_script.js', array('jquery'), '1.0.0', true );

		$cm_params = array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' =>  wp_create_nonce( 'button_nonce' ) 
		);

		wp_localize_script( 'custom_script', 'cm_ajax', $cm_params); 


	}


}