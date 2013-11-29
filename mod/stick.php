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
          
          if( $query['0']['post_sticky'] == "0" ) {
              
              $data = array(
                  'post_sticky' => '1'
              );
              $MYSQL->where('id', $PGET->g('thread'));
              if( $MYSQL->update('{prefix}forum_posts', $data) ) {
                  $content .= $TANGO->tpl->entity(
                      'success_notice',
                      'content',
                      'Thread has been stuck. <a href="' . SITE_URL . '/thread.php/v/' . $query['0']['title_friendly'] . '.' . $query['0']['id'] . '">Back to thread</a>.'
                  );
              } else {
                  $content .= $TANGO->tpl->entity(
                      'danger_notice',
                      'content',
                      'Error sticking thread.'
                  );
              }
              
          } else {
              $content .= $TANGO->tpl->entity(
                  'danger_notice',
                  'content',
                  'Thread is already stuck.'
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
          'Stick Thread',
          $content
      )
  );

  echo $TANGO->tpl->output();

?>