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

      $MYSQL->where('username', $id);
      $u_query = $MYSQL->get('{prefix}users');

      $query = (empty($query))? $u_query : $query;
      
      if( !empty($query) ) {
          
          $page_title     .= $LANG['bb']['members']['profile_of'] . ' ' . $query['0']['username'];
          $userg           = $TANGO->usergroup($query['0']['user_group']);
          $user            = $TANGO->user($id);
          
          $recent_activity = '';
      $data = array($query['0']['id']);
          $query           = $MYSQL->rawQuery("SELECT * FROM {prefix}forum_posts WHERE post_user = ? ORDER BY post_time DESC LIMIT 15", $data);
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
                      SITE_URL . '/thread.php/' . $ac['title_friendly'] . '.' . $ac['id'],
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
                      SITE_URL . '/thread.php/' . $thread['title_friendly'] . '.' . $thread['id'] . '#post-' . $thread['id'],
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

          //Breadcrumbs
          $TANGO->tpl->addBreadcrumb(
            $LANG['bb']['forum'],
            SITE_URL . '/forum.php'
          );
          $TANGO->tpl->addBreadcrumb(
            $LANG['bb']['members']['home'],
            SITE_URL . '/members.php'
          );
          $TANGO->tpl->addBreadcrumb(
            $LANG['bb']['members']['profile_of'] . ' ' . $user['username'],
            '#',
            true
          );
          $content .= $TANGO->tpl->breadcrumbs();
          
          $content        .= $TANGO->tpl->entity(
              'user_profile_page',
              array(
                  'username',
                  'user_avatar',
                  'usergroup',
                  'registered_date',
                  'user_signature',
                  'about_user',
                  'location',
                  'flag',
                  'gender',
                  'age',
                  'recent_activity',
                  'mod_tools'
              ),
              array(
                  $user['username_style'],
                  $user['user_avatar'],
                  $userg['group_name'],
                  localized_date($user['date_joined'],@$TANGO->sess->data['location']),
                  $TANGO->lib_parse->parse($user['user_signature']),
                  $TANGO->lib_parse->parse($user['about_user']),
                  $LANG['location'][$user['location']],
                  '<span class="flag-icon flag-icon-'.strtolower($user['location']).'"></span>',
                  gender($user['gender']),
                  birthday_to_age($user['user_birthday']),
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
      $data = array($TANGO->sess->data['id']);
          $query           = $MYSQL->rawQuery("SELECT * FROM {prefix}forum_posts WHERE post_user = ? ORDER BY post_time DESC LIMIT 15", $data);
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
                      SITE_URL . '/thread.php/' . $ac['title_friendly'] . '.' . $ac['id'],
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
                      SITE_URL . '/thread.php/' . $thread['title_friendly'] . '.' . $thread['id'] . '#post-' . $thread['id'],
                      $thread['post_title'],
                      date('F j, Y', $ac['post_time'])
                    ),
                    $LANG['bb']['members']['replied_to']
                  );
              }
          }

          //Breadcrumbs
          $TANGO->tpl->addBreadcrumb(
            $LANG['bb']['forum'],
            SITE_URL . '/forum.php'
          );
          $TANGO->tpl->addBreadcrumb(
            $LANG['bb']['members']['home'],
            SITE_URL . '/members.php'
          );
          $TANGO->tpl->addBreadcrumb(
            $LANG['bb']['members']['profile_of'] . ' ' . $TANGO->sess->data['username'],
            '#',
            true
          );
          $content .= $TANGO->tpl->breadcrumbs();
          
          $content        .= $TANGO->tpl->entity(
              'user_profile_page',
              array(
                  'username',
                  'user_avatar',
                  'usergroup',
                  'registered_date',
                  'user_signature',
                  'about_user',
                  'location',
                  'flag',
                  'gender',
                  'age',
                  'recent_activity',
                  'mod_tools'
              ),
              array(
                  $TANGO->sess->data['username_style'],
                  $TANGO->sess->data['user_avatar'],
                  $user['group_name'],
                  localized_date($TANGO->sess->data['date_joined'],$TANGO->sess->data['location']),
                  $TANGO->lib_parse->parse($TANGO->sess->data['user_signature']),
                  $TANGO->lib_parse->parse($TANGO->sess->data['about_user']),
                  $LANG['location'][$TANGO->sess->data['location']],
                  '<span class="flag-icon flag-icon-'.strtolower($TANGO->sess->data['location']).'"></span>',
                  gender($TANGO->sess->data['gender']),
                  birthday_to_age($TANGO->sess->data['user_birthday']),
                  $recent_activity,
                  ''
              )
          );
          
      } else {
          redirect(SITE_URL . '/404.php');
      }
      
  }

?>