function cgmaps_copy_map_shortcode(mapid) {
	var textarea = document.createElement('textarea');
	textarea.value = '[cgmaps_shortcode mapid="' + mapid + '"]';
	document.body.appendChild(textarea);
	textarea.select();
	document.execCommand('copy');
	alert("Shortcode copied: " + textarea.value);
	document.body.removeChild(textarea);
}

function theme_changed(theme, images_path) {
	img = document.getElementById('cgmaps-preview').src = images_path + theme + '.png';
}