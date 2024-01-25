<?php
class Custom_GMaps_Widget extends \Elementor\Widget_Base
{
	public function get_name()
	{
		return 'custom_gmaps';
	}

	public function get_title()
	{
		return esc_html__('Custom GMaps', 'cgmaps');
	}

	public function get_icon()
	{
		return 'eicon-code';
	}

	public function get_categories()
	{
		return ['basic'];
	}

	public function get_keywords()
	{
		return ['hello', 'world'];
	}

	protected function register_controls()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'custom_gmaps';
		$maps = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
		$options = array();
		foreach ($maps as $map) {
			$options[$map["id"]] = $map["id"] . " - " . $map["map_name"];
		}

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__('Settings', 'elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'map_select',
			[
				'label' => esc_html__('Select Map', 'elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => $maps[0]["id"],
				'options' => $options,
			]
		);

		$this->end_controls_section();

		// Content Tab End
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		global $wpdb;
		$table_name = $wpdb->prefix . 'custom_gmaps';
		$map = $wpdb->get_results("SELECT * FROM $table_name WHERE id='" . $settings['map_select'] . "'", ARRAY_A);

		$style = file_get_contents(plugin_dir_url(__DIR__) . 'themes/' . $map[0]["map_dark_mode"] . '.json');
?>

		<div id="googleMap" style="height: 400px; width: 100%"></div>
		<script>
			function myMap() {
				var myCenter = new google.maps.LatLng(<?php echo $map[0]["map_lat"] ?>, <?php echo $map[0]["map_lon"] ?>);
				var mapProp = {
					center: myCenter,
					zoom: <?php echo $map[0]["map_zoom"] ?>,
					streetViewControl: false,
					scrollwheel: true,
					draggable: true,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					styles: <?php echo $style ?>,
				};
				var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
				var marker = new google.maps.Marker({
					position: myCenter
				});
				marker.setMap(map);
			}
		</script>

<?php
	}
}
