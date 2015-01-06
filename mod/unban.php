<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_moderation') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.
  $TANGO->tpl->getTpl('page');

  $content    = '';

  if( $PGET->g('id') ) {

      //$MYSQL->where('id', $PGET->g('id'));
      //$query = $MYSQL->get('{prefix}users');
      $MYSQL->bind('id', $PGET->g('id'));
      $query = $MYSQL->query("SELECT * FROM {prefix}users WHERE id = :id");

      if( !empty($query) ) {

          if( $query['0']['is_banned'] == "1" ) {

              /*$data = array(
                  'is_banned' => '0',
                  'user_group' => '1'
              );
              $MYSQL->where('id', $PGET->g('id'));
              try {
                  $MYSQL->update('{prefix}users', $data);
                  $content .= $TANGO->tpl->entity(
                      'success_notice',
                      'content',
                      str_replace(
                        '%url%',
                        SITE_URL . '/members.php/cmd/user/id/' . $query['0']['id'],
                        $LANG['mod']['ban']['unban_success']
                      )
                  );
              } catch (mysqli_sql_exception $e) {
                  $content .= $TANGO->tpl->entity(
                      'danger_notice',
                      'content',
                      $LANG['mod']['ban']['unban_error']
                  );
              }*/

              $MYSQL->bind('id', $PGET->g('id'));

              if( $MYSQL->query("UPDATE {prefix}users SET is_banned = 0, user_group = DEFAULT WHERE id = :id") > 0 ) {
                $content .= $TANGO->tpl->entity(
                      'success_notice',
                      'content',
                      str_replace(
                        '%url%',
                        SITE_URL . '/members.php/cmd/user/id/' . $query['0']['id'],
                        $LANG['mod']['ban']['unban_success']
                      )
                  );
              } else {
                $content .= $TANGO->tpl->entity(
                      'danger_notice',
                      'content',
                      $LANG['mod']['ban']['unban_error']
                  );
              }

          } else {
              $content .= $TANGO->tpl->entity(
                  'danger_notice',
                  'content',
                  $LANG['mod']['ban']['already_unbanned']
              );
          }

      } else {
          redirect(SITE_URL);
      }

  } else {
      redirect(SITE_URL);
  }

  $TANGO->tpl->addParam(
      array(
          'page_title',
          'content'
      ),
      array(
          $LANG['mod']['ban']['unban'],
          $content
      )
  );

  echo $TANGO->tpl->output();

?>