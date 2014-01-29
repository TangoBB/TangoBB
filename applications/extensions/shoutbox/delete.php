<?php
	define('BASEPATH', 'Extension');

	include('../../wrapper.php');
	
	global $MYSQL;
	global $TANGO;
	
	if(isset($_POST['id'])) {
	
		$id = $_POST['id'];
		
		if($TANGO->perm->check("access_moderation")) {
		
			$data = array(
				'deleted' => 1
			);
			
			$MYSQL->where('id', $id);
			$MYSQL->update('{prefix}shoutbox_posts', $data);
			
			}
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		/* Sean Davies - http://seandavies.pw */
		
		
		
?>