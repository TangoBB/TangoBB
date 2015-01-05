<?php

  /*
   * Admin Terminal Commands.
   */
  if( !defined('BASEPATH') ){ die(); }

  function terminal_cugroup($username, $usergroup) {
  	global $MYSQL, $ADMIN;
  	if( !$g = usergroupExists($usergroup) ) {
  		throw new Exception ('Usergroup does not exist.');
  	} else {
  		$data = array(
  			'user_group' => $g['id']
  		);
  		$MYSQL->where('username', $username);
  		try {
            $MYSQL->update('{prefix}users', $data);
  			return $ADMIN->alert(
  				'User\'s usergroup has been changed!',
  				'success'
  			);
  		} catch (mysqli_sql_exception $e) {
  			throw new Exception ('Error changing user\'s usergroup.');
  		}
  	}
  }

  function terminal_ban($username) {
  	global $MYSQL, $ADMIN;
  	$data = array(
  		'is_banned' => 1,
  		'user_group' => BAN_ID
  	);
  	$MYSQL->where('username', $username);
  	try {
        $MYSQL->update('{prefix}users', $data);
  		return $ADMIN->alert(
  			'User has been banned!',
  			'success'
  		);
  	} catch (mysqli_sql_exception $e) {
  		throw new Exception ('Error banning user.');
  	}
  }

  function terminal_unban($username) {
  	global $MYSQL, $ADMIN;
  	$data = array(
  		'is_banned' => 0,
  		'user_group' => 1
  	);
  	$MYSQL->where('username', $username);
  	try {
        $MYSQL->update('{prefix}users', $data);
  		return $ADMIN->alert(
  			'User has been unbanned!',
  			'success'
  		);
  	} catch (mysqli_sql_exception $e) {
  		throw new Exception ('Error unbanning user.');
  	}
  }

?>