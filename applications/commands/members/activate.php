<?php

  /*
   * Account Activation Module for TangoBB.
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }

  if( $TANGO->sess->isLogged ){ redirect(SITE_URL); } //If user is logged in.

  $page_title = $LANG['bb']['members']['activate_account'];

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
                      $LANG['bb']['members']['account_activated']
                  );
              } else {
                  $content = $TANGO->tpl->entity(
                      'danger_notice',
                      'content',
                      $LANG['bb']['members']['error_activating']
                  );
              }
              
          } else {
              redirect(SITE_URL);
          }
          
      } else {
          redirect(SITE_URL);
      }
      
  } else {
      redirect(SITE_URL);
  }

?>