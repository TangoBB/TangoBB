<?php
 
  define('BASEPATH', 'Forum');
  require_once('applications/wrapper.php');

  $TANGO->tpl->getTpl('page');

  $content    = '';
  $notice     = '';
  $page_title = $LANG['bb']['search'];

  if( isset($_POST['search_submit']) ) {
      try {
          
          foreach( $_POST as $parent => $child ) {
              $_POST[$parent] = clean($child);
          }
          
          $search_query = $_POST['search_query'];
          
          if( !$search_query ) {
              throw new Exception ($LANG['global_form_process']['all_fields_required']);
          } else {
              
              $searched_threads = '';
              $searched_users   = '';
              $key              = explode(' ', $search_query);
              
              //$MYSQL->where('post_type', '1');
              //$query   = $MYSQL->get('{prefix}forum_posts');
              $query   = $MYSQL->query("SELECT * FROM
                                        {prefix}forum_posts
                                        WHERE
                                        post_type = 1
                                        ORDER BY
                                        post_time
                                        DESC");
              $threads = array();
              
              foreach( $query as $re ) {
                  $tags = explode(',', $re['post_tags']);
                  $user = $TANGO->user($re['post_user']);
                  foreach( $tags as $tag ) {
                      if( in_array($tag, $key) ) {
                          $threads[] .= '<a href="' . SITE_URL . '/thread.php/v/' . $re['title_friendly'] . '.' . $re['id'] . '">' . $re['post_title'] . '</a> <small>By <a href="' . SITE_URL . '/members.php/cmd/user/id/' . $user['id'] . '">' . $user['username'] . '</a> (' . date('F j, Y', $re['post_time']) . ')</small><hr size="1" />';;
                      }
                  }
              }
              
              if( !empty($threads) ) {
                  foreach( $threads as $thread ) {
                      $searched_threads .= $thread;
                  }
              } else {
                  $searched_threads .= $LANG['global_form_process']['search_no_result'];
              }
              
              $query   = $MYSQL->query("SELECT * FROM
                                        {prefix}users
                                        ORDER BY
                                        date_joined
                                        DESC");
              $users = array();
              
              foreach( $query as $re ) {
                  if( in_array($re['username'], $key) ) {
                          $users[] .= '<a href="' . SITE_URL . '/members.php/cmd/user/id/' . $re['id'] . '">' . $re['username'] . '</a><hr size="1" />';;
                      }
              }
              
              if( !empty($users) ) {
                  foreach( $users as $u ) {
                      $searched_users .= $u;
                  }
              } else {
                  $searched_users .= $LANG['global_form_process']['search_no_result'];
              }
              
              $content .= $TANGO->tpl->entity(
                  'search_page',
                  array(
                      'searched_threads',
                      'searched_users'
                  ),
                  array(
                      $searched_threads,
                      $searched_users
                  )
              );
              
          }
          
      } catch( Exception $e ) {
          $notice .= $TANGO->tpl->entity(
              'danger_notice',
              'content',
              $e->getMessage()
          );
      }
  } else {
      $notice .= $TANGO->tpl->entity(
          'danger_notice',
          'content',
          $LANG['global_form_process']['enter_search_query']
      );
  }

  $TANGO->tpl->addParam(
      array(
          'page_title',
          'content'
      ),
      array(
          $page_title,
          $notice . $content
      )
  );

  echo $TANGO->tpl->output();

?>