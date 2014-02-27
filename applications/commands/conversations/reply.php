<?php

   /*
   * Conversations module for TangoBB
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }

  $page_title = '';
  $content    = '';
  $notice     = '';

  if( $PGET->g('id') ) {

    $MYSQL->where('id', $PGET->g('id'));
    $MYSQL->where('message_type', 1);
    $query = $MYSQL->get('{prefix}messages');

    if( !empty($query) ) {

      if( isset($_POST['reply']) ) {
      	try {

      		foreach( $_POST as $parent => $child ) {
      			$_POST[$parent] = clean($child);
      		}

      		NoCSRF::check( 'csrf_token', $_POST );
      		$cont = clean($_POST['content']);
      		$time = time();

      		if( !$cont ) {
      			throw new Exception ($LANG['global_form_process']['all_fields_required']);
      		} else {

      			$data = array(
      				'message_title' => 'RE: ' . $query['0']['message_title'],
      				'message_content' => $cont,
      				'message_time' => $time,
      				'origin_message' => $query['0']['id'],
      				'message_sender' => $TANGO->sess->data['id'],
      				'message_receiver' => $query['0']['message_sender'],
      				'message_type' => 2
      			);

      			if( $MYSQL->insert('{prefix}messages', $data) ) {
      				redirect(SITE_URL . '/conversations.php/cmd/view/v/' . $query['0']['id']);
      			} else {
      				throw new Exception ($LANG['bb']['conversations']['error_sending_alt']);
      			}

      		}

      	} catch (Exception $e) {
      		$notice .= $TANGO->tpl->entity(
      			'danger_notice',
      			'content',
      			$e->getMessage()
      		);
      	}
      }

      define('CSRF_TOKEN', NoCSRF::generate( 'csrf_token' ));

      $page_title = 'Reply: ' . $query['0']['message_title'];
      $content    = $notice . '
                    <form action="" method="POST">
                      ' . $FORM->build('hidden', '', 'csrf_token', array('value' => CSRF_TOKEN)) . '
                      ' . $FORM->build('textarea', '', 'content', array('id' => 'editor', 'style' => 'width:100%;height:300px;max-width:100%;min-width:100%;')) . '
                      <br />
                      ' . $FORM->build('submit', '', 'reply', array('value' => $LANG['bb']['conversations']['form_reply'])) . '
                    </form>';

    } else {
    	redirect(SITE_URL . '/404.php');
    }

  } else {
  	redirect(SITE_URL . '/404.php');
  }

?>