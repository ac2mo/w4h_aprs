<?php
defined( 'ABSPATH' ) or die( 'Go Away!' );

class w4h_aprs_widget extends WP_Widget {

	private $_fields = array(
		'he_width', 'he_height', 'he_zoom', 'he_maptype', 'he_track',
		'he_show_others', 'he_lat', 'he_lng', 'he_hide_tcp', 'he_show_aprs',
		'he_show_aprs_w', 'he_show_aprs_i', 'he_show_ais',
	);

	private $_zoomOptions = array(
		'17' => '17 (Maximum Zoom)',
		'16' => '16',
		'15' => '15',
		'14' => '14',
		'13' => '13',
		'12' => '12',
		'11' => '11 (Default)',
		'10' => '10',
		'9' => '9',
		'8' => '8',
		'7' => '7',
		'6' => '6',
		'5' => '5',
		'4' => '4',
		'3' => '3',
		'2' => '2',
		'1' => '1',
		'0' => '0 (Entire Earth)',
	);

	private $_mapOptions = array(
		'm' => 'Normal Map',
		'k' => 'Satellite View',
		'h' => 'Hybrid Satellite/Map',
		'p' => 'Physical Map',
	);

	private $_showOptions = array(
		'' => 'Do Not Show',
		'p' => 'Show Current Position',
		't' => 'Show Track Line',
		'w' => 'Show Track Line and Waypoints',
	);

	private $_checkboxFields = array( 'he_show_others', 'he_hide_tcp' );

	public function __construct() {
		parent::__construct(
			'w4h_aprs_widget',
			'W4H APRS Widget',
			array(
				'description' => 'Displays an APRS tracker widget from APRS.FI.'
			)
		);
	}

	public function form( $instance ) {

		foreach( $this->_fields as $field) {
			$$field = ( isset( $instance[$field] ) ? $instance[$field] : '' );
		}

		$this->_adminTextBox( 'he_width', 'Map Width', $he_width, '550' );
		$this->_adminTextBox( 'he_height', 'Map Height', $he_height, '350' );
		$this->_adminSelect( 'he_zoom', 'Map Zoom Level', $he_zoom, $this->_zoomOptions );
		$this->_adminSelect( 'he_maptype', 'Map Type', $he_maptype, $this->_mapOptions );
		$this->_adminTextBox( 'he_track', 'Callsign/Item(s) to Track', $he_track, '' );
		$this->_adminCheckBox( 'he_show_others', 'Show Other Stations', $he_show_others );
		$this->_adminTextBox( 'he_lat', 'Latitude to Center the Map On', $he_lat, '' );
		$this->_adminTextBox( 'he_lng', 'Longitude to Center the Map On', $he_lng, '' );
		$this->_adminCheckBox( 'he_hide_tcp', 'Hide Non-RF Stations', $he_hide_tcp );
		$this->_adminSelect( 'he_show_aprs', 'Show APRS Stations', $he_show_aprs, $this->_showOptions );
		$this->_adminSelect( 'he_show_aprs_w', 'Show Weather Stations', $he_show_aprs_w, $this->_showOptions );
		$this->_adminSelect( 'he_show_aprs_i', 'Show Items and Objects', $he_show_aprs_i, $this->_showOptions );
		$this->_adminSelect( 'he_show_ais', 'Show Vessel AIS Data', $he_show_ais, $this->_showOptions );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();

		foreach( $this->_fields as $field ) {
			if( in_array( $field, $this->_checkboxFields ) && $new_instance[$field] != '1' ) {
				$instance[$field] = 0;
				continue;
			}
			$instance[$field] = $new_instance[$field];
		}

		return $instance;
	}

	public function widget( $args, $instance ) {

		?><p>
		<script type="text/javascript">
		<?php foreach( $this->_fields as $field ) {
			if( !isset( $instance[$field] ) || $instance[$field] === '' ) continue;
			echo $field; ?> = <?php echo 
				( !is_numeric( $instance[$field] ) ? "'" : "" ) .
				$instance[$field] .
				( !is_numeric( $instance[$field] ) ? "'" : "" ) . ";\n";
		} ?>
		</script>
		<script type="text/javascript" src="http://aprs.fi/js/embed.js">
		</script>
		</p>
		<?php
	}

	private function _adminTextBox( $name, $label, $value, $placeholder ) {
		?>
		<p>
			<label for="<?php echo $this->get_field_name( $name ); ?>"><?php echo $label; ?></label>
			<input name="<?php echo $this->get_field_name( $name ); ?>"
				id="<?php echo $this->get_field_id( $name ); ?>"
				value="<?php echo $value; ?>" placeholder="<?php echo $placeholder; ?>" 
				class="widefat" type="text" />
		</p>
		<?php
	}

	private function _adminCheckBox( $name, $label, $value ) {
		?>
		<p>
			<input name="<?php echo $this->get_field_name( $name ); ?>"
				id="<?php echo $this->get_field_id( $name ); ?>"
				type="checkbox" value="1" <?php echo $value == 1 ? 'checked="checked" ' : ''; ?>/>
			<label for="<?php echo $this->get_field_name( $name ); ?>"><?php echo $label; ?></label>
		</p>
		<?php
	}

	private function _adminSelect( $name, $label, $value, $options ) {
		?>
		<p>
			<label for="<?php echo $this->get_field_name( $name ); ?>"><?php echo $label; ?></label>
			<select name="<?php echo $this->get_field_name( $name ); ?>"
				id="<?php echo $this->get_field_id( $name ); ?>"
				class="widefat">
				<?php foreach( $options as $k => $v ) { ?>
				<option value="<?php echo $k; ?>"<?php echo ( $k == $value ? ' selected="selected"' : '' ); ?>><?php echo $v; ?></option>
				<?php } ?>
			</select>
		</p>
		<?php
	}
}

add_action( 'widgets_init', create_function( '', 'return register_widget( "w4h_aprs_widget" ); ' ) );
?>
