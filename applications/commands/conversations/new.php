<?php

  /*
   * Conversations module for TangoBB
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }

  $page_title = $LANG['bb']['conversations']['page_new'];
  $content    = '';
  $notice     = '';

  if( isset($_POST['create']) ) {
  	try {

  		foreach( $_POST as $parent => $child ) {
  			$_POST[$parent] = clean($child);
  		}

  		$user  = $_POST['receiver'];
  		$cont  = emoji_to_text($_POST['content']);
  		$title = $_POST['title'];
  		$time  = time();
      $uid   = explode(',', $user);

  		if( !$user or !$cont or !$title ) {
  			throw new Exception ($LANG['global_form_process']['all_fields_required']);
  		} else {

        foreach( $uid as $u ) {

          if( !usernameExists($u) ) {
            throw new Exception (
              str_replace(
                '%username%',
                $u,
                $LANG['bb']['conversations']['user_not_exist']
              )
            );
          }

          /*$MYSQL->where('username', $u);
          $query = $MYSQL->get('{prefix}users');
          $data = array(
            'message_title' => $title,
            'message_content' => $cont,
            'message_time' => $time,
            'message_sender' => $TANGO->sess->data['id'],
            'message_receiver' => $query['0']['id'],
            'message_type' => 1
          );

          try {
              $MYSQL->insert('{prefix}messages', $data);
            $notice .= $TANGO->tpl->entity(
              'success_notice',
              'content',
              str_replace(
                '%username%',
                $query['0']['username'],
                $LANG['bb']['conversations']['message_sent']
              )
            );
          } catch (mysqli_sql_exception $e) {
            throw new Exception (
              str_replace(
                '%username%',
                $query['0']['username'],
                $LANG['bb']['conversations']['error_sending']
              )
            );
          }*/
          $us = $TANGO->user($u);
          $MYSQL->bindMore(
            array(
              'message_title' => $title,
              'message_content' => $cont,
              'message_time' => $time,
              'message_sender' => $TANGO->sess->data['id'],
              'message_receiver' => $us['username']
            )
          );

          if( $MYSQL->query("INSERT INTO {prefix}messages (message_title, message_content, message_time, message_sender, message_receiver, message_type) VALUES (:message_title, :message_content, :message_time, :message_Sender, :message_receiver, 1)") > 0 ) {
            $notice .= $TANGO->tpl->entity(
              'success_notice',
              'content',
              str_replace(
                '%username%',
                $query['0']['username'],
                $LANG['bb']['conversations']['message_sent']
              )
            );
          } else {
            throw new Exception (
              str_replace(
                '%username%',
                $query['0']['username'],
                $LANG['bb']['conversations']['error_sending']
              )
            );
          }

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

  //Breadcrumbs
  $TANGO->tpl->addBreadcrumb(
    $LANG['bb']['forum'],
    SITE_URL . '/forum.php'
  );
  $TANGO->tpl->addBreadcrumb(
    $LANG['bb']['conversations']['page_conversations'],
    SITE_URL . '/conversations.php'
    );
  $TANGO->tpl->addBreadcrumb(
    $LANG['bb']['conversations']['page_new'],
    '#',
    true
  );
  $content .= $TANGO->tpl->breadcrumbs();

  $content .= $notice . '<form action="" method="POST">
                 ' . $FORM->build('hidden', '', 'csrf_token', array('value' => CSRF_TOKEN)) . '
                 ' . $FORM->build('text', $LANG['bb']['conversations']['form_to'], 'receiver', array('value' => $pm_user, 'style' => 'width:100%')) . '
                 ' . $FORM->build('text', $LANG['bb']['conversations']['form_title'], 'title', array('value' => $pm_title)) . '
                 ' . $FORM->build('textarea', '', 'content', array('id' => 'editor', 'style' => 'width:100%;height:300px;max-width:100%;min-width:100%;', 'value' => $pm_cont)) . '
                 <br />
                 ' . $FORM->build('submit', '', 'create', array('value' => $LANG['bb']['conversations']['form_send'])) . '
               </form>';

?>