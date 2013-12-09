<?php

  /*
   * Conversations module for TangoBB
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }

  $page_title = 'New Message';
  $content    = '';
  $notice     = '';

  if( isset($_POST['create']) ) {
  	try {

  		foreach( $_POST as $parent => $child ) {
  			$_POST[$parent] = clean($child);
  		}

  		$user  = $_POST['receiver'];
  		$cont  = $_POST['content'];
  		$title = $_POST['title'];
  		$time  = time();

  		if( !$user or !$cont or !$title ) {
  			throw new Exception ('All fields are required!');
  		} elseif( !usernameExists($user) ) {
  			throw new Exception ('User does not exist!');
  		} else {

  			$MYSQL->where('username', $user);
  			$query = $MYSQL->get('{prefix}users');

  			$data = array(
  				'message_title' => $title,
  				'message_content' => $cont,
  				'message_time' => $time,
  				'message_sender' => $TANGO->sess->data['id'],
  				'message_receiver' => $query['0']['id'],
  				'message_type' => 1
  			);

  			if( $MYSQL->insert('{prefix}messages', $data) ) {
  				$notice .= $TANGO->tpl->entity(
  					'success_notice',
  					'content',
  					'Your message has been sent!'
  				);
  			} else {
  				throw new Exception ('Error sending message.');
  			}

  		}

  	} catch ( Exception $e ) {
  		$notice .= $TANGO->tpl->entity(
  			'danger_notice',
  			'content',
  			$e->getMessage()
  		);
  	}
  }

  define('CSRF_TOKEN', NoCSRF::generate( 'csrf_token' ));

  $pm_cont   = (isset($_POST['content']))? $_POST['content'] : '';
  $pm_user   = (isset($_POST['receiver']))? $_POST['receiver'] : '';
  $pm_title  = (isset($_POST['title']))? $_POST['title'] : '';

  $content .= $notice . '<script type="text/javascript" src="' . SITE_URL . '/accounts_js.php"></script>
               <form action="" method="POST">
                 ' . $FORM->build('hidden', '', 'csrf_token', array('value' => CSRF_TOKEN)) . '
                 ' . $FORM->build('text', 'To', 'receiver', array('class' => 'typeahead-users', 'value' => $pm_user)) . '
                 ' . $FORM->build('text', 'Title', 'title', array('value' => $pm_title)) . '
                 ' . $FORM->build('textarea', '', 'content', array('id' => 'editor', 'style' => 'width:100%;height:300px;max-width:100%;min-width:100%;', 'value' => $pm_cont)) . '
                 <br />
                 ' . $FORM->build('submit', '', 'create', array('value' => 'Send')) . '
               </form>';

?>