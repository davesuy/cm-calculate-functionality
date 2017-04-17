<?php

/*
Plugin Name: Cairns Marine Calculate Functionality Plugin
*/


// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Backwards compatibility for older than PHP 5.3.0
if ( !defined( '__DIR__' ) ) {
    define( '__DIR__', dirname( __FILE__ ) );
}

define( 'CM_INCLUDES', __DIR__.'/includes' );
define( 'CM_ASSETS', plugin_dir_url( __DIR__.'/cm-calculate-functionality/' ).'assets');

include(__DIR__.'/assets/shipping/cm-shipping-method.php');

function cm_autoload( $class ) {

	if ( stripos( $class, 'CM_' ) !== false ) {

	    $class_name = 	str_replace( array( 'CM_', '_' ), array( '', '-' ), $class );
	    $file_path = 	strtolower( $class_name ) . '.php';
	    $file_path_final = CM_INCLUDES.'/class-'.$file_path;

	    if ( file_exists( $file_path_final ) ) {
	        require_once $file_path_final;
	    }

	}
   
}

spl_autoload_register( 'cm_autoload' );

class CairnsMarine {


	public function __construct() {

		$this->init_classes();

	}

	public static function init() {

        static $instance = false;

        if ( ! $instance ) {
            $instance = new CairnsMarine;
        }

        return $instance;
    }



	public function init_classes() {

		CM_Enqueue_Scripts::init();
		CM_After_Cart::init();
		CM_Ajax_Calculation::init();
		CM_Cm_Filter::init();
		CM_Boxes_After_Cart::init();
	}

}

CairnsMarine::init();