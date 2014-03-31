<?php

	define('BASEPATH', 'Extension');

	include('../../wrapper.php');
	
	global $MYSQL;
	global $TANGO;
	
	if(isset($_POST['shout'])) {


		$timestamp = time();
		
		$post = clean($_POST['shout']);
		$post = trim($post);
		$message = $post;
		$user = $TANGO->sess->data['id'];
		$type = 0;
		$command = NULL;
		
		// CHECK IF CMD IS EMPTY
		
		if(substr($post, 0, 1) == '/' && substr($post, 1) == '') {
			return false;
		}	
		
		// BAN COMMAND
		
		
		if(substr($post, 0, 1) == '/' && substr($post, 1, 3) == 'ban') {
			$username = strtolower(trim(substr($post, 5)));
			$username = str_replace(' ', '', $username);
			
			$post_username = trim(substr($post, 5));
			$post_username =  str_replace(' ', '', $post_username);
			
			if($username == '') {
				return false;
			}
			
			if($TANGO->perm->check('access_moderation')) {
				if(usernameExists($username)) {
					if($username == strtolower($TANGO->sess->data['username'])) {
						return false;
					}
					
					$MYSQL->where('user', $username);
					$results = $MYSQL->get('{prefix}shoutbox_bans', 1);
					$banned = '';
					foreach($results as $bans) {
						$banned .= $bans['user'];
					}

					if($username !== $banned) {
						$message = $post_username . " has been banned from the shoutbox";
						$type = 1;
						$command = "/ban" . $post_username;
						$new_ban  = array('user' => $username); 
						$MYSQL->insert('{prefix}shoutbox_bans', $new_ban); 
					}
					else {
						$message = $username . " has already been banned ";
						$type = 3;
						$command = "/ban " . $post_username;
					}
				}
				else {
					$message = "The user '{$post_username}' could not be found ";
					$type = 3;
					$command = "/ban " . $post_username;	
				}
			}
			else {
				$message = 'You do not have permissions to use that command';
				$type = 3;
				$command = "/ban " . $post_username;
			}
		}
		
		// UNBAN COMMAND
		
		if(substr($post, 0, 1) == '/' && substr($post, 1, 5) == 'unban') {
			$username = strtolower(trim(substr($post, 7)));
			$username = str_replace(' ', '', $username);
			
			$post_username = trim(substr($post, 7));
			$post_username =  str_replace(' ', '', $post_username);
			
			
			if($username == '') {
				return false;
			}
			
			if($TANGO->perm->check('access_moderation')) {
				if(usernameExists($username)) {
					if($username == strtolower($TANGO->sess->data['username'])) {
						return false;
					}
					
					$MYSQL->where('user', $username);
					$results = $MYSQL->get('{prefix}shoutbox_bans', 1);
					$banned = '';
					foreach($results as $bans) {
						$banned .= $bans['user'];
					}

					if($username !== $banned) {
						$message = $post_username . " is not banned";
						$type = 3;
						$command = "/unban" . $post_username;
					}
					else {
						$message = $post_username . " has been unbanned";
						$type = 1;
						$command = "/unban " . $post_username;
						$MYSQL->where('user', $username);
						$MYSQL->delete('{prefix}shoutbox_bans');
					}
				}
				else {
					$message = "The user '{$post_username}' could not be found ";
					$type = 3;
					$command = "/unban " . $post_username;	
				}
			}
			else {
				$message = 'You do not have permissions to use that command';
				$type = 3;
				$command = "/unban " . $post_username;
			}
		}
		
		// CLEAR COMMAND
		
		if(substr($post, 0, 1) == '/' && substr($post, 1) == 'clear') {
			if($TANGO->perm->check('access_moderation')) {
				$message = 'SHOUTBOX CLEARED';
				$type = 1;
				$command = "/clear";
				$MYSQL->query("TRUNCATE TABLE {prefix}shoutbox_posts");
			}
			else {
				$message = 'You do not have permissions to use that command';
				$type = 3;
				$command = "/clear";
			}
		}
		
		// ME COMMAND
		
		if(substr($post, 0, 1) == '/' && substr($post, 1, 2) == 'me') {
			$text = trim(substr($post, 4));
			$message = $text;
			$type = 2;
			$command = '/me';
		}
		
		
		$data  = array(
						'timestamp' => $timestamp,
						'user' => $user,
						'message' => $message,
						'type' => $type,
						'cmd' => $command
					   ); 
		
		if($post !== '') {
			$MYSQL->insert('{prefix}shoutbox_posts', $data); 
		}
	}
	
	else {
		return false;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/* Sean Davies - http://seandavies.pw */
	
	
 ?>
