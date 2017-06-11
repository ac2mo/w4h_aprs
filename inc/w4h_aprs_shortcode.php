<?php
defined( 'ABSPATH' ) or die( 'Go Away!' );

require_once( plugin_dir_path( __FILE__ ) . 'w4h_aprs_render.php' );

class w4h_aprs_shortcode {

	public static function shortcode( $atts, $content = null ) {
		$a = shortcode_atts( array(
			'width' => null,
			'height' => null,
			'zoom' => null,
			'maptype' => null,
			'track' => null,
			'show_others' => null,
			'lat' => null,
			'lng' => null,
			'hide_tcp' => null,
			'show_aprs' => null,
			'show_aprs_w' => null,
			'show_aprs_i' => null,
			'show_ais' => null
		), $atts );

		$render = new w4h_aprs_render();
		foreach( $a as $k => $v ) {
			if( !is_null( $v ) ) {
				$render->$k = $v;
			}
		}
		return $render->render();
	}
}
add_shortcode( 'aprs', array( 'w4h_aprs_shortcode', 'shortcode' ) );
?>
