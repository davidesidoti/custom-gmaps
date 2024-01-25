<?php
/*
Plugin Name: Custom G-Maps
Description: Fully customizable Google Maps you can add to your website.
Version: 1.0
Author: Enzo Manone
*/

/**
 * The function creates a custom table in the WordPress database for storing Google Maps data.
 */
function create_custom_gmaps_table()
{
	global $wpdb;

	$table_name = $wpdb->prefix . 'custom_gmaps';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        map_name varchar(255) NOT NULL,
        map_description varchar(255) NOT NULL,
        map_lat varchar(255) NOT NULL,
        map_lon varchar(255) NOT NULL,
        map_zoom varchar(255) NOT NULL,
        map_dark_mode varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}
register_activation_hook(__FILE__, 'create_custom_gmaps_table');

/**
 * The below code creates a custom menu page in the WordPress admin dashboard for a plugin called
 * "Custom G-Maps" and loads a template file when the menu page is accessed.
 */
function custom_gmaps_menu()
{
	add_menu_page(
		'Custom G-Maps',
		'Custom G-Maps',
		'manage_options',
		'custom-gmaps-plugin',
		'custom_gmaps_page',
		'dashicons-smiley',
		30
	);
	add_submenu_page(
		'super-fake-menu-gmaps',            // Parent menu slug
		'Edit Map',                    // Page title
		'Edit Map',                    // Menu title
		'manage_options',             // Capability required to access
		'edit-map',    // Sub-menu slug
		'edit_map'     // Callback function to display the sub-page
	);
	add_submenu_page(
		'super-fake-menu-gmaps',            // Parent menu slug
		'Add Map',                    // Page title
		'Add Map',                    // Menu title
		'manage_options',             // Capability required to access
		'add-map',    // Sub-menu slug
		'add_map'     // Callback function to display the sub-page
	);
}
function custom_gmaps_page()
{
	// Load the template file
	include(plugin_dir_path(__FILE__) . 'templates/dashboard-template.php');
}
function edit_map()
{
	// Check if the GET parameter 'mapid' is set
	if (isset($_GET['mapid'])) {
		// Load the template file
		include(plugin_dir_path(__FILE__) . 'templates/edit-map-template.php');
	} else {
		echo '<div class="wrap"><p>Please, select a map to edit from the dashboard.</p></div>';
	}
}
function add_map()
{
	// Load the template file
	include(plugin_dir_path(__FILE__) . 'templates/add-map-template.php');
}
add_action('admin_menu', 'custom_gmaps_menu');

/**
 * The function enqueue_custom_styles is used to enqueue a custom stylesheet in the admin area of a
 * WordPress website.
 */
function enqueue_custom_styles()
{
	// Enqueue your custom stylesheet
	wp_enqueue_style('main-cgmaps-style', plugin_dir_url(__FILE__) . 'assets/css/main_style.css');
	wp_enqueue_script('main-cgmaps-script', plugin_dir_url(__FILE__) . 'assets/js/main_script.js');
}
add_action('admin_enqueue_scripts', 'enqueue_custom_styles');

/**
 * The function adds a link to a Google Fonts stylesheet in the head section of the admin area in
 * WordPress.
 */
function add_links_to_head()
{
	echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />';
}
add_action('admin_head', 'add_links_to_head');

function custom_gmaps_shortcode($atts)
{
	global $wpdb;
	$atts = shortcode_atts(
		array(
			'mapid' => 0,
		),
		$atts,
		'dark_map_shortcode'
	);

	$mapid = $atts['mapid'];
	$table_name = $wpdb->prefix . 'custom_gmaps';
	$map_settings = $wpdb->get_results("SELECT * FROM $table_name WHERE id='" . $mapid . "'", ARRAY_A);

	$style = file_get_contents(plugin_dir_url(__FILE__) . 'themes/' . $map_settings[0]["map_dark_mode"] . '.json');

	$html = '
	<div id="googleMap" style="height: 400px; width: 100%"></div>
	<script>
		function myMap() {
			var myCenter = new google.maps.LatLng(' . floatval($map_settings[0]["map_lat"]) . ', ' . floatval($map_settings[0]["map_lon"]) . ');
			var mapProp = {
				center: myCenter,
				zoom: ' . $map_settings[0]["map_zoom"] . ',
				streetViewControl: false,
				scrollwheel: true,
				draggable: true,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				styles: ' . $style . ',
			};
			var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
			var marker = new google.maps.Marker({ position: myCenter });
			marker.setMap(map);
			}
	</script>';
	return $html;
}
add_shortcode('cgmaps_shortcode', 'custom_gmaps_shortcode');

function register_hello_world_widget($widgets_manager)
{
	require_once(__DIR__ . '/widgets/custom-gmaps-widget.php');

	$widgets_manager->register(new \Custom_GMaps_Widget());
}
add_action('elementor/widgets/register', 'register_hello_world_widget');
