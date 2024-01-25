<?php
global $wpdb;
$table_name = $wpdb->prefix . 'custom_gmaps';

if ($_POST) {
	$map_name = $_POST["map_name"];
	$map_description = !empty($_POST["map_description"]) ? $_POST["map_description"] : "";
	$map_lat = $_POST["map_lat"];
	$map_lon = $_POST["map_lon"];
	$map_zoom = !empty($_POST["map_zoom"]) ? $_POST["map_zoom"] : 12;
	$map_dark_mode = !empty($_POST["map_dark_mode"]) ? $_POST["map_dark_mode"] : "standard";


	$error = null;
	if (empty($map_name) || empty($map_lat) || empty($map_lon)) {
		$error = 'All fields should be filled.';
	}

	if (empty($error)) {
		$wpdb->insert(
			$table_name,
			array(
				'map_name' => $map_name,
				'map_description' => $map_description,
				'map_lat' => $map_lat,
				'map_lon' => $map_lon,
				'map_zoom' => $map_zoom,
				'map_dark_mode' => $map_dark_mode,
			)
		);
		echo '<script>window.location = "admin.php?page=custom-gmaps-plugin";</script>';
	}
}
$images_path = plugin_dir_url(__DIR__) . 'assets/images/';
?>

<div class="wrap">
	<h1>Custom G-Maps</h1>
	<small>Made by Enzo Manone</small>

	<form method="post" class="cgmaps-form">
		<label for="map_name">Map Name:</label>
		<input type="text" id="map_name" name="map_name" placeholder="MapPuppa" required />

		<label for="map_description">Map Description:</label>
		<input type="text" id="map_description" name="map_description" placeholder="Descuppa" />

		<label for="map_lat">Map Latitude:</label>
		<input type="number" step=any id="map_lat" name="map_lat" placeholder="45.44204484161089" required />

		<label for="map_lon">Map Longitude:</label>
		<input type="number" step=any id="map_lon" name="map_lon" placeholder="12.337863041586978" required />

		<label for="map_zoom">Map Zoom:</label>
		<input type="number" id="map_zoom" name="map_zoom" placeholder="12" />

		<label for="map_dark_mode">Map Dark Mode:</label>
		<select name="map_dark_mode" id="map_dark_mode" onchange="theme_changed(this.value, '<?php echo $images_path ?>')">
			<option value="standard">Standard</option>
			<option value="silver">Silver</option>
			<option value="retro">Retro</option>
			<option value="night">Night</option>
			<option value="dark">Dark</option>
			<option value="aubergine">Aubergine</option>
		</select>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Map">
		</p>
	</form>

	<h2>Preview</h2>
	<img id="cgmaps-preview" style="width: 50%;" src="<?php echo plugin_dir_url(__DIR__) . 'assets/images/standard.png' ?>" alt="Preview Image">
</div>