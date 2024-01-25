<?php
if ($_POST) {
	if (!empty($_POST["api_key"])) {
		update_option('cgmaps_api_key', $_POST["api_key"]);
	}
}
?>

<div class="wrap">
	<div class="cgmaps-main-header">
		<div>
			<h1>Custom G-Maps</h1>
			<small>Made by Enzo Manone</small>
		</div>
		<p class="submit">
			<a href="admin.php?page=add-map"><button type="submit" class="button button-primary">Add Map</button></a>
		</p>
	</div>
	<p><strong>You can add a map to your website by copying the shortcode and pasting it wherever you want manually OR by using Elementor directly (search for "Custom GMaps").</strong></p>

	<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th>ID</th>
				<th>Map Name</th>
				<th>Map Description</th>
				<th>Map Latitude</th>
				<th>Map Longitude</th>
				<th>Map Zoom</th>
				<th>Map Dark Mode</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			global $wpdb;
			$table_name = $wpdb->prefix . 'custom_gmaps';

			$maps = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

			foreach ($maps as $map) {
				echo '<tr>';
				echo '<td>' . esc_html($map['id']) . '</td>';
				echo '<td>' . esc_html($map['map_name']) . '</td>';
				echo '<td>' . esc_html($map['map_description']) . '</td>';
				echo '<td>' . esc_html($map['map_lat']) . '</td>';
				echo '<td>' . esc_html($map['map_lon']) . '</td>';
				echo '<td>' . esc_html($map['map_zoom']) . '</td>';
				echo '<td>' . ucfirst(esc_html($map['map_dark_mode'])) . '</td>';
				echo '<td>
					<a href=# onclick=cgmaps_copy_map_shortcode(' . $map['id'] . ')>
						<span class="material-symbols-outlined">content_copy</span>
					</a>
					<a href=admin.php?page=edit-map&mapid=' . $map['id'] . '>
						<span class="material-symbols-outlined">edit</span>
					</a>
				</td>';
				echo '</tr>';
			}
			?>
		</tbody>
	</table>

	<div class="api-setting">
		<form method="post" class="cgmaps-form">
			<label for="api_key">API Key:</label>
			<input type="text" id="api_key" name="api_key" placeholder="MapPuppa" value="<?php echo get_option('cgmaps_api_key', ''); ?>" required />

			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="Save API Key">
			</p>
		</form>
	</div>
</div>