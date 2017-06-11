<?php
defined( 'ABSPATH' ) or die( 'Go Away!' );

require_once( plugin_dir_path( __FILE__ ) . 'w4h_aprs_render.php' );

class w4h_aprs_widget extends WP_Widget {

	private $_fields = array(
		'width', 'height', 'zoom', 'maptype', 'track',
		'show_others', 'lat', 'lng', 'hide_tcp', 'show_aprs',
		'show_aprs_w', 'show_aprs_i', 'show_ais',
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

	private $_checkboxFields = array( 'show_others', 'hide_tcp' );

	private $render;

	public static function register() {
		return register_widget( 'w4h_aprs_widget' );
	}

	public function __construct() {
		parent::__construct(
			'w4h_aprs_widget',
			'W4H APRS Widget',
			array(
				'description' => 'Displays an APRS tracker widget from APRS.FI.'
			)
		);
		$this->render = new w4h_aprs_render();
	}

	public function form( $instance ) {

		foreach( $this->_fields as $field) {
			$$field = ( isset( $instance[$field] ) ? $instance[$field] : '' );
		}

		$this->_adminTextBox( 'width', 'Map Width', $width, '550' );
		$this->_adminTextBox( 'height', 'Map Height', $height, '350' );
		$this->_adminSelect( 'zoom', 'Map Zoom Level', $zoom, $this->_zoomOptions );
		$this->_adminSelect( 'maptype', 'Map Type', $maptype, $this->_mapOptions );
		$this->_adminTextBox( 'track', 'Callsign/Item(s) to Track', $track, '' );
		$this->_adminCheckBox( 'show_others', 'Show Other Stations', $show_others );
		$this->_adminTextBox( 'lat', 'Latitude to Center the Map On', $lat, '' );
		$this->_adminTextBox( 'lng', 'Longitude to Center the Map On', $lng, '' );
		$this->_adminCheckBox( 'hide_tcp', 'Hide Non-RF Stations', $hide_tcp );
		$this->_adminSelect( 'show_aprs', 'Show APRS Stations', $show_aprs, $this->_showOptions );
		$this->_adminSelect( 'show_aprs_w', 'Show Weather Stations', $show_aprs_w, $this->_showOptions );
		$this->_adminSelect( 'show_aprs_i', 'Show Items and Objects', $show_aprs_i, $this->_showOptions );
		$this->_adminSelect( 'show_ais', 'Show Vessel AIS Data', $show_ais, $this->_showOptions );
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

		foreach( $this->_fields as $field ) {
			if( !isset( $instance[$field] ) || $instance[$field] === '' ) continue;
			$this->render->$field = $instance[$field];
		}
		echo $this->render->render();
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

add_action( 'widgets_init', array( 'w4h_aprs_widget', 'register' ) );
?>
