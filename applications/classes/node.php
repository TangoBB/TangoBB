<?php

  /*
   * Node Display class of TangoBB
   */
  if( !defined('BASEPATH') ){ die(); }

  class Tango_Node {
      
      /*
       * Putting the forum node together.
       * Breadcrumbs feature is to be postponed.
       */
      public function threads($id) {
          global $MYSQL, $TANGO;
          
          $MYSQL->where('id', $id);
          $MYSQL->where('post_type', 1);
          $query = $MYSQL->get('{prefix}forum_posts');
          
          $return = '';
          foreach( $query as $post ) {
              $user     = $TANGO->user($post['post_user']);
              $closed   = ( $post['post_locked'] == "1" )? $TANGO->tpl->entity('thread_closed') : '';
              $stickied =  ( $post['post_sticky'] == "1" ) ? $TANGO->tpl->entity('thread_stickied') : '';
              
              $return .= $TANGO->tpl->entity(
                  'forum_listings_node_threads_posts',
                  array(
                      'thread_name',
                      'user',
                      'user_avatar',
                      'post_time',
                      'latest_post'
                  ),
                  array(
                      '<a href="' . SITE_URL . '/thread.php/v/' . $post['title_friendly'] . '.' . $post['id'] . '">' . $post['post_title'] . '</a>' . $closed . $stickied,
                      '<a href="' . SITE_URL . '/members.php/cmd/user/id/' . $user['id'] . '">' . $user['username'] . '</a>',
                       $user['user_avatar'],
                      '<span data-toggle="tooltip" data-placement="bottom" title="' . date('F j, Y', $post['post_time']) . '">' . date('l h:i A', $post['post_time']) . '</span>',
                      $this->latestReply($post['id'], SITE_URL . '/thread.php/v/' . $post['title_friendly'] . '.' . $post['id'])
                  )
              );
          }
          return $return;
      }
      
      /*
       * Adding latest reply to the thread.
       */
      public function latestReply($id, $url) {
          global $MYSQL, $TANGO;
          
          /*$MYSQL->where('origin_thread', $id);
          $MYSQL->where('post_type', '2');
          $query = $MYSQL->get('{prefix}forum_posts');*/
          $id    = (int) $id;
		  $data = array($id);
          $query = $MYSQL->rawQuery("SELECT * FROM
                                  {prefix}forum_posts
                                  WHERE
                                  origin_thread = ?
                                  AND
                                  post_type = 2
                                  ORDER BY
                                  post_time
                                  DESC", $data);
          if( !empty($query) ) {
              
              $user   = $TANGO->user($query['0']['post_user']);
              $return = $TANGO->tpl->entity(
                  'forum_listings_node_threads_latestreply',
                  array(
                      'user_avatar',
                      'post_user',
                      'post_time'
                  ),
                  array(
                      $user['user_avatar'],
                      '<a href="' . SITE_URL . '/members.php/cmd/user/id/' . $user['id'] . '">' . $user['username'] . '</a>',
                      '<small><a href="' . $url . '#post-' . $query['0']['id'] . '"><span data-toggle="tooltip" data-placement="bottom" title="' . date('F j, Y', $query['0']['post_time']) . '">' . date('l h:i A', $query['0']['post_time']) . '</span></a></small>',
                  )
              );
              
              return $return;
              
          } else {
              return 'None';
          }
      }
      
  }

?>