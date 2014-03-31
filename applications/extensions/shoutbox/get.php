	
	<?php
	
	if(!defined('access')) { die(); }
	
	define('BASEPATH', 'Extension');
	
	include('../../wrapper.php');
	
	global $TANGO;
	global $MYSQL;
	
	$results = $MYSQL->query("SELECT * FROM {prefix}shoutbox_posts ORDER BY id DESC LIMIT 20");
	
	$shouts = '';
	
	foreach ($results as $shout) {
		$id = $shout['id'];
		$timestamp = $shout['timestamp'];
		$time = date('H:i', $timestamp);
		$user = $TANGO->user($shout['user']);
		$post = $shout['message'];
		
		$delete = '';
		if($TANGO->perm->check("access_moderation")) {
			$delete = "<button class='delete_button' onClick='delete_shout(".$id.");'>&times;</button>";
		}
		else {
			$delete = null;
		}
		
		if($shout['deleted'] == 0 && $shout['type'] == "0") {
			$shouts .= '<div id="' . $id . '"> '.$delete.' <span class="label label-info">' . $time . '</span> <a href="' . SITE_URL . '/members.php/cmd/user/' . $user['id'] . '">' . $user['username_style'] . '</a>: ' . $post . ' </div>';
		}
		if($shout['deleted'] == 0 && $shout['type'] == "1") {
			$shouts .= '<div id="' . $id . '"> '.$delete.'  <span class="label label-default">' . $time . '</span> ' . $post . ' </div>';
		}
		if($shout['deleted'] == 0 && $shout['type'] == "2") {
			$shouts .= '<div id="' . $id . '"> '.$delete.'  <span class="label label-info">' . $time . '</span> * <a href="' . SITE_URL . '/members.php/cmd/user/' . $user['id'] . '">' . $user['username_style'] . '</a> ' . $post . ' *</div>';
		}
		if($shout['deleted'] == 0 && $shout['type'] == "3" && $TANGO->sess->data['id'] == $shout['user']) {
			$shouts .= '<div id="' . $id . '"> '.$delete.'  <span class="label label-default">' . $time . '</span> ' . $post . ' </div>';
		}
	}
	
	
	$banned_username = $TANGO->sess->data['username'];
	$banned_username = strtolower($banned_username);
	
	$MYSQL->where('user', $banned_username);
	$ban_query = $MYSQL->get('{prefix}shoutbox_bans', 1);
	$banned = '';
	foreach($ban_query as $bans) {
		$banned .= $bans['user'];
	}
	
	if(strtolower($TANGO->sess->data['username']) == $banned) {
		echo "<script type='text/javascript'>window.location = window.location.pathname;</script>";
	}
	else {
		echo $shouts;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		
	
	
	
	
	
	
	
	
	/* Sean Davies - http://seandavies.pw */
	
	
	?>