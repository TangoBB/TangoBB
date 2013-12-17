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
          
          if( $query['0']['is_banned'] == "0" ) {
              
              $data = array(
                  'is_banned' => '1',
                  'user_group' => BAN_ID
              );
              $MYSQL->where('id', $PGET->g('id'));
              if( $MYSQL->update('{prefix}users', $data) ) {
                  $content .= $TANGO->tpl->entity(
                      'success_notice',
                      'content',
                      str_replace(
                        '%url%',
                        SITE_URL . '/members.php/cmd/user/id/' . $query['0']['id'],
                        $LANG['mod']['ban']['ban_success']
                      )
                  );
              } else {
                  $content .= $TANGO->tpl->entity(
                      'danger_notice',
                      'content',
                      $LANG['mod']['ban']['ban_error']
                  );
              }
              
          } else {
              $content .= $TANGO->tpl->entity(
                  'danger_notice',
                  'content',
                  $LANG['mod']['ban']['already_banned']
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
          $LANG['mod']['ban']['ban'],
          $content
      )
  );

  echo $TANGO->tpl->output();

?>