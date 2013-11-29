<?PHP

$logo_type = "text";    # Set to 'text' or 'image'
	
$logo_text = "%site_name%";		# If logo type is set to 'text', this will be your logo text.
		
		
# To use an image, you must have an image called 'logo.png' in the 'img' directory of the Seda theme #



# MORE SETTINGS TO COME IN FUTURE UPDATES












# THEME SETTING SCRIPTS BELOW - DO NOT EDIT #









































































$logo = '';
if(!isset($logo_type)) {
	$logo .= $logo_text;
}
else if($logo_type === "text"){
		$logo .= $logo_text;
}

else if($logo_type ==="image") {
	if(file_exists("/public/themes/seda/assets/img/logo.png")) {
		$logo .= "<img src='/public/themes/seda/assets/img/logo.png' />";	
	}
	else {
		$logo .= $logo_text;
	}
}
		
?>