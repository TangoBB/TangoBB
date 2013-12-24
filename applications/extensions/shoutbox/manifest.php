<?php

	global $TANGO;

	$banned_username = $TANGO->sess->data['username'];
	$banned_username = strtolower($banned_username);
	
	$MYSQL->where('user', $banned_username);
	$ban_query = $MYSQL->get('{prefix}shoutbox_bans', 1);
	$banned = '';
	foreach($ban_query as $bans) {
		$banned .= $bans['user'];
	}
	
	if($TANGO->perm->check('view_shoutbox')) {
		if(strtolower($TANGO->sess->data['username']) == $banned) {
			$return = '<div class="alert alert-danger">You have been banned from the shoutbox!</div>';
		}
		else {
		
		$return  = '<script src="' . SITE_URL . '/applications/extensions/shoutbox/shoutbox.js" type="text/javascript"></script>
						  <link href="' . SITE_URL . '/applications/extensions/shoutbox/shoutbox.css" rel="stylesheet" />';
						  
		$return .= $TANGO->tpl->entity(
			'content_box',
				array(
					'content_header',
					'content_body',
				),
				array(
				'Forum Shoutbox',
				'<tr>
					<td> <div id="shoutbox_posts" style="width: 100%; height: 150px; overflow-y: scroll; margin-bottom: 15px;"></div> </td>
				</tr>
				<tr>
					<td>
						<form id="shoutbox">
							<input class="form-control"style="width: 100%" type="text" name="shout" id="shout" placeholder="Type shout here..."">
						</form>
					</td>
				</tr>'
				)
			);
		}
	}
	else {
		$return = false;
	}
	
	$TANGO->tpl->addParam('shoutbox', $return);
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/* Sean Davies - http://seandavies.pw */
	
	
	
?>