<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_moderation') ) { header('Location: ' . SITE_URL); }//Checks if user has permission to create a thread.
  $TANGO->tpl->getTpl('page');

  $content    = '';

  if( $PGET->g('thread') ) {
      
      $MYSQL->where('id', $PGET->g('thread'));
      $query = $MYSQL->get('{prefix}forum_posts');
      
      if( !empty($query) ) {
          
          if( $query['0']['post_locked'] == "1" ) {
              
              $data = array(
                  'post_locked' => '0'
              );
              $MYSQL->where('id', $PGET->g('thread'));
              if( $MYSQL->update('{prefix}forum_posts', $data) ) {
                  $content .= $TANGO->tpl->entity(
                      'success_notice',
                      'content',
                      'Thread has been opened. <a href="' . SITE_URL . '/thread.php/v/' . $query['0']['title_friendly'] . '.' . $query['0']['id'] . '">Back to thread</a>.'
                  );
              } else {
                  $content .= $TANGO->tpl->entity(
                      'danger_notice',
                      'content',
                      'Error opening thread.'
                  );
              }
              
          } else {
              $content .= $TANGO->tpl->entity(
                  'danger_notice',
                  'content',
                  'Thread is already opened.'
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
          'Open Thread',
          $content
      )
  );

  echo $TANGO->tpl->output();

?>