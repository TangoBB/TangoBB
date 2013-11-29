<?php

  /*
   * Account Activation Module for TangoBB.
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }

  if( $TANGO->sess->isLogged ){ header('Location: ' . SITE_URL); } //If user is logged in.

  $page_title = 'Activate Account';

  if( $PGET->g('code') ) {
      
      $code = clean($PGET->g('code'));
      $MYSQL->where('date_joined', $code);
      $query = $MYSQL->get('{prefix}users');
      
      if( !empty($query) ) {
          
          if( $query['0']['user_disabled'] == 1 ) {
              
              $data = array(
                  'user_disabled' => '0'
              );
              $MYSQL->where('id', $query['0']['id']);
              
              if( $MYSQL->update('{prefix}users', $data) ) {
                  $content = $TANGO->tpl->entity(
                      'success_notice',
                      'content',
                      'Your account has been activated! <a href="' . SITE_URL . '/members.php/cmd/signin">Sign in</a> now.'
                  );
              } else {
                  $content = $TANGO->tpl->entity(
                      'danger_notice',
                      'content',
                      'Error activating account. Try again later.'
                  );
              }
              
          } else {
              header("Location: " . SITE_URL);
          }
          
      } else {
          header('Location: ' . SITE_URL);
      }
      
  } else {
      header('Location: ' . SITE_URL);
  }

?>