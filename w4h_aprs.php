<?php
/*
Plugin Name:	W4H-APRS
Plugin URI:	https://github.com/ac2mo/w4h_aprs
Description:	Places an APRS tracking widget on your site.
Version:	0.1
Author:		Gregory S. Hoernern (AC2MO)
License:	MIT
*/
defined( 'ABSPATH' ) or die( 'Go Away!' );

class w4h_aprs {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts_and_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_scripts_and_styles' ) );

		register_activation_hook( __FILE__, array( $this, 'plugin_activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'plugin_deactivate' ) );
	}

	public function enqueue_admin_scripts_and_styles() {
		wp_enqueue_style( 'w4h_aprs_admin', plugin_dir_url( __FILE__ ) . '/css/w4h_aprs_admin.css' );
	}

	public function enqueue_public_scripts_and_styles() {
		wp_enqueue_style( 'w4h_aprs_public', plugin_dir_url( __FILE__ ) . '/css/w4h_aprs_public.css' );
	}

	public function plugin_activate() {
	}

	public function plugin_deactivate() {
	}
}

include( plugin_dir_path( __FILE__ ) . 'inc/w4h_aprs_widget.php' );
?>
