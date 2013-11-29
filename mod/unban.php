<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_moderation') ) { header('Location: ' . SITE_URL); }//Checks if user has permission to create a thread.
  $TANGO->tpl->getTpl('page');

  $content    = '';

  if( $PGET->g('id') ) {
      
      $MYSQL->where('id', $PGET->g('id'));
      $query = $MYSQL->get('{prefix}users');
      
      if( !empty($query) ) {
          
          if( $query['0']['is_banned'] == "1" ) {
              
              $data = array(
                  'is_banned' => '0',
                  'user_group' => '1'
              );
              $MYSQL->where('id', $PGET->g('id'));
              if( $MYSQL->update('{prefix}users', $data) ) {
                  $content .= $TANGO->tpl->entity(
                      'success_notice',
                      'content',
                      'User has been unbanned. <a href="' . SITE_URL . '/members.php/cmd/user/id/' . $query['0']['id'] . '">Back to user profile</a>.'
                  );
              } else {
                  $content .= $TANGO->tpl->entity(
                      'danger_notice',
                      'content',
                      'Error unbanning user.'
                  );
              }
              
          } else {
              $content .= $TANGO->tpl->entity(
                  'danger_notice',
                  'content',
                  'User is already unbanned!'
              );
          }
          
      } else {
          header('Location: ' .  SITE_URL);
      }
      
  } else {
      header('Location: ' . SITE_URL);
  }

  $TANGO->tpl->addParam(
      array(
          'page_title',
          'content'
      ),
      array(
          'Unban User',
          $content
      )
  );

  echo $TANGO->tpl->output();

?>