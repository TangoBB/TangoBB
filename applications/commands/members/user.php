<?php

  /*
   * User Profile module for TangoBB
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }
  $content    = '';
  $page_title = '';

  if( $PGET->g('id') ) {
      
      $id    = clean($PGET->g('id'));
      $MYSQL->where('id', $id);
      $query = $MYSQL->get('{prefix}users');
      
      if( !empty($query) ) {
          
          $page_title     .= $LANG['bb']['members']['profile_of'] . ' ' . $query['0']['username'];
          $userg           = $TANGO->usergroup($query['0']['user_group']);
          $user            = $TANGO->user($id);
          
          $recent_activity = '';
          $query           = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE post_user = {$query['0']['id']} ORDER BY post_time DESC LIMIT 15");
          foreach( $query as $ac ) {
              if( $ac['post_type'] == "1" ) {
                  //$recent_activity .= 'Posted a new thread <a href="' . SITE_URL . '/thread.php/v/' . $ac['title_friendly'] . '.' . $ac['id'] . '">' . $ac['post_title'] . '</a> <small>(' . date('F j, Y', $ac['post_time']) . ')</small><hr size="1" />';
                  $recent_activity .= str_replace(
                    array(
                      '%url%',
                      '%title%',
                      '%date%'
                    ),
                    array(
                      SITE_URL . '/thread.php/v/' . $ac['title_friendly'] . '.' . $ac['id'],
                      $ac['post_title'],
                      date('F j, Y', $ac['post_time'])
                    ),
                    $LANG['bb']['members']['posted_thread']
                  );
              } else {
                  $thread           = thread($ac['origin_thread']);
                  //$recent_activity .= 'Replied to the thread <a href="' . SITE_URL . '/thread.php/v/' . $thread['title_friendly'] . '.' . $thread['id'] . '#post-' . $thread['id'] . '">' . $thread['post_title'] . '</a> <small>(' . date('F j, Y', $ac['post_time']) . ')</small><hr size="1" />';
                  $recent_activity .= str_replace(
                    array(
                      '%url%',
                      '%title%',
                      '%date%'
                    ),
                    array(
                      SITE_URL . '/thread.php/v/' . $thread['title_friendly'] . '.' . $thread['id'] . '#post-' . $thread['id'],
                      $thread['post_title'],
                      date('F j, Y', $ac['post_time'])
                    ),
                    $LANG['bb']['members']['replied_to']
                  );
              }
          }
          
          $mod_tools       = '';
          
          if( $TANGO->perm->check('access_moderation') ) {
             if( $user['is_banned'] == "1" ) {
                  $mod_tools .= $TANGO->tpl->entity(
                      'mod_tools_profile',
                      array(
                          'ban_user',
                          'ban_user_url'
                      ),
                      array(
                          'Unban User',
                          SITE_URL . '/mod/unban.php/id/' . $id
                      ),
                      'buttons'
                  );
             } else {
                 $mod_tools .= $TANGO->tpl->entity(
                      'mod_tools_profile',
                      array(
                          'ban_user',
                          'ban_user_url'
                      ),
                      array(
                          'Ban User',
                          SITE_URL . '/mod/ban.php/id/' . $id
                      ),
                      'buttons'
                  );
             }
          }
          
          $content        .= $TANGO->tpl->entity(
              'user_profile_page',
              array(
                  'username',
                  'user_avatar',
                  'usergroup',
                  'registered_date',
                  'user_signature',
                  'recent_activity',
                  'mod_tools'
              ),
              array(
                  $user['username_style'],
                  $user['user_avatar'],
                  $userg['group_name'],
                  date('F j, Y', $user['date_joined']),
                  $TANGO->lib_parse->parse($user['user_signature']),
                  $recent_activity,
                  $mod_tools
              )
          );
          
      } else {
          redirect(SITE_URL . '/404.php');
      }
      
  } else {
      
      if( $TANGO->sess->isLogged ) {
          
          $page_title     .= $LANG['bb']['members']['profile_of'] . ' ' . $TANGO->sess->data['username'];
          $user            = $TANGO->usergroup($TANGO->sess->data['user_group']);
          
          $recent_activity = '';
          $query           = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE post_user = {$TANGO->sess->data['id']} ORDER BY post_time DESC LIMIT 15");
          foreach( $query as $ac ) {
              if( $ac['post_type'] == "1" ) {
                  //$recent_activity .= 'Posted a new thread <a href="' . SITE_URL . '/thread.php/v/' . $ac['title_friendly'] . '.' . $ac['id'] . '">' . $ac['post_title'] . '</a> <small>(' . date('F j, Y', $ac['post_time']) . ')</small><hr size="1" />';
                  $recent_activity .= str_replace(
                    array(
                      '%url%',
                      '%title%',
                      '%date%'
                    ),
                    array(
                      SITE_URL . '/thread.php/v/' . $ac['title_friendly'] . '.' . $ac['id'],
                      $ac['post_title'],
                      date('F j, Y', $ac['post_time'])
                    ),
                    $LANG['bb']['members']['posted_thread']
                  );      
              } else {
                  $thread           = thread($ac['origin_thread']);
                  //$recent_activity .= 'Replied to the thread <a href="' . SITE_URL . '/thread.php/v/' . $thread['title_friendly'] . '.' . $thread['id'] . '#post-' . $thread['id'] . '">' . $thread['post_title'] . '</a> <small>(' . date('F j, Y', $ac['post_time']) . ')</small><hr size="1" />';
                  $recent_activity .= str_replace(
                    array(
                      '%url%',
                      '%title%',
                      '%date%'
                    ),
                    array(
                      SITE_URL . '/thread.php/v/' . $thread['title_friendly'] . '.' . $thread['id'] . '#post-' . $thread['id'],
                      $thread['post_title'],
                      date('F j, Y', $ac['post_time'])
                    ),
                    $LANG['bb']['members']['replied_to']
                  );
              }
          }
          
          $content        .= $TANGO->tpl->entity(
              'user_profile_page',
              array(
                  'username',
                  'user_avatar',
                  'usergroup',
                  'registered_date',
                  'user_signature',
                  'recent_activity',
                  'mod_tools'
              ),
              array(
                  $TANGO->sess->data['username_style'],
                  $TANGO->sess->data['user_avatar'],
                  $user['group_name'],
                  date('F j, Y', $TANGO->sess->data['date_joined']),
                  $TANGO->lib_parse->parse($TANGO->sess->data['user_signature']),
                  $recent_activity,
                  ''
              )
          );
          
      } else {
          redirect(SITE_URL . '/404.php');
      }
      
  }

?>