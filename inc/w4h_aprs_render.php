<?php
defined( 'ABSPATH' ) or die( 'Go Away!' );

class w4h_aprs_render {

	private $_data = array();

	private $_fields = array(
		'width', 'height', 'zoom', 'maptype', 'track',
		'show_others', 'lat', 'lng', 'hide_tcp', 'show_aprs',
		'show_aprs_w', 'show_aprs_i', 'show_ais',
	);

	public function __set($k, $v) {
		switch($k) {
		// Numeric fields:
		case 'width':
		case 'height':
			if( !is_numeric( $v ) || $v < 1 ) return;
			break;

		case 'zoom':
			if( !iS_numeric( $v ) || $v < 0 || $v > 17 ) return;
			break;

		case 'lat':
		case 'lng':
			if( !is_float( $v ) ) return;
			break;

		// Boolean fields:
		case 'show_others':
		case 'hide_tcp':
			if( $v === true ) $v = 1;
			if( $v === false ) $v = 0;
			if( $v != 0 && $v != 1 ) return;
			break;

		// "Show" fields:
		case 'show_aprs':
		case 'show_aprs_w':
		case 'show_aprs_i':
		case 'show_ais':
			if( !in_array( $v, array( '', 'p', 't', 'w' ) ) ) return;
			break;

		// "Map Type":
		case 'maptype':
			if( !in_array( $v, array( 'm', 'k', 'h', 'p' ) ) ) return;
			break;
		}

		$_data[$k] = $v;
	}

	public function render() {
		$ret = <<<PREAMBLE
		<p>
		<script type="text/javascript">
PREAMBLE;

		foreach( $this->_fields as $field ) {
			if( isset( $_data[$field] ) ) {
				$ret .= "\t\the_{$field} = " .
					is_numeric( $this->_data[$field] ) ?
						"{$this->_data[$field]};\n" :
						"'{$this->_data[$field]}';\n";
			}
		}

		$protocol = is_ssl() ? 'https' : 'http';

		$ret .= <<<EPILOGUE
		</script>
		<script type="text/javascript" src="{$protocol}://aprs.fi/js/embed.js">
		</script>
		</p>
EPILOGUE;
		return $ret;
	}
}
?>
