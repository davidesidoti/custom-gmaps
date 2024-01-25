<?php
global $wpdb;

$table_name = $wpdb->prefix . 'custom_gmaps';
$map = $wpdb->get_results("SELECT * FROM $table_name WHERE id='" . $_GET["mapid"] . "'", ARRAY_A);

if ($_POST) {
	$map_name = $_POST["map_name"];
	$map_description = !empty($_POST["map_description"]) ? $_POST["map_description"] : "";
	$map_lat = $_POST["map_lat"];
	$map_lon = $_POST["map_lon"];
	$map_zoom = !empty($_POST["map_zoom"]) ? $_POST["map_zoom"] : 12;
	$map_dark_mode = !empty($_POST["map_dark_mode"]) ? "on" : "off";

	$error = null;
	if (empty($map_name) || empty($map_lat) || empty($map_lon)) {
		$error = 'All fields should be filled.';
	}

	if (empty($error)) {
		$wpdb->update(
			$table_name,
			array(
				'map_name' => $map_name,
				'map_description' => $map_description,
				'map_lat' => $map_lat,
				'map_lon' => $map_lon,
				'map_zoom' => $map_zoom,
				'map_dark_mode' => $map_dark_mode,
			),
			array('id' => $_GET["mapid"])
		);
		echo '<script>window.location = "admin.php?page=custom-gmaps-plugin";</script>';
	}
}
$images_path = plugin_dir_url(__DIR__) . 'assets/images/';
?>

<script>
	window.onload = (event) => {
		theme_changed("<?php echo $map[0]['map_dark_mode'] ?>", "<?php echo $images_path ?>")
	};
</script>

<div class="wrap">
	<h1>Custom G-Maps</h1>
	<small>Made by Enzo Manone</small>

	<form method="post" class="cgmaps-form">
		<label for="map_name">Map Name:</label>
		<input type="text" id="map_name" name="map_name" placeholder="MapPuppa" value="<?php echo esc_html($map[0]['map_name']); ?>" required />

		<label for="map_description">Map Description:</label>
		<input type="text" id="map_description" name="map_description" placeholder="Descuppa" value="<?php echo esc_html($map[0]['map_description']); ?>" />

		<label for="map_lat">Map Latitude:</label>
		<input type="number" step=any id="map_lat" name="map_lat" placeholder="45.44204484161089" value="<?php echo esc_html($map[0]['map_lat']); ?>" required />

		<label for="map_lon">Map Longitude:</label>
		<input type="number" step=any id="map_lon" name="map_lon" placeholder="12.337863041586978" value="<?php echo esc_html($map[0]['map_lon']); ?>" required />

		<label for="map_zoom">Map Zoom:</label>
		<input type="number" id="map_zoom" name="map_zoom" placeholder="12" value="<?php echo esc_html($map[0]['map_zoom']); ?>" />

		<label for="map_dark_mode">Map Dark Mode:</label>
		<select name="map_dark_mode" id="map_dark_mode" onchange="theme_changed(this.value, '<?php echo $images_path ?>')">
			<option value="standard" <?php echo ($map[0]['map_dark_mode'] == "standard" ? "selected" : "") ?>>Standard</option>
			<option value="silver" <?php echo ($map[0]['map_dark_mode'] == "silver" ? "selected" : "") ?>>Silver</option>
			<option value="retro" <?php echo ($map[0]['map_dark_mode'] == "retro" ? "selected" : "") ?>>Retro</option>
			<option value="night" <?php echo ($map[0]['map_dark_mode'] == "night" ? "selected" : "") ?>>Night</option>
			<option value="dark" <?php echo ($map[0]['map_dark_mode'] == "dark" ? "selected" : "") ?>>Dark</option>
			<option value="aubergine" <?php echo ($map[0]['map_dark_mode'] == "aubergine" ? "selected" : "") ?>>Aubergine</option>
		</select>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Map">
		</p>
	</form>

	<h2>Preview</h2>
	<img id="cgmaps-preview" style="width: 50%;" src="<?php echo plugin_dir_url(__DIR__) . 'assets/images/standard.png' ?>" alt="Preview Image">
</div>