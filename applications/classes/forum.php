<?php

  /*
   * Forum class of TangoBB
   */
  if( !defined('BASEPATH') ){ die(); }

  class Tango_Forum {

      public $parser;

      public function listings() {
          global $MYSQL, $TANGO;

          $return = '';
          $query = $MYSQL->query("SELECT * FROM
                                  {prefix}forum_category
                                  ORDER BY
                                  category_place
                                  ASC");
          foreach( $query as $list ) {
              $allowed = explode(',', $list['allowed_usergroups']);
              if( in_array($TANGO->sess->data['user_group'], $allowed) ) {
                $return .= $TANGO->tpl->entity(
                  'forum_listings_category',
                  array(
                    'category_name',
                    'category_desc',
                    'category_forums'
                  ),
                  array(
                    $list['category_title'],
                    $list['category_desc'],
                    $this->forums($list['id'])
                  )
                );
              }
          }
          return $return;
      }

      /*
       * Forums
       */
      private function forums($category) {
          global $MYSQL, $TANGO;

          $return = '';
          //$data = array($category);
          /*$query  = $MYSQL->rawQuery("SELECT * FROM
                                   {prefix}forum_node
                                   WHERE
                                   in_category = ?
                                   AND
                                   node_type = 1
                                   ORDER BY
                                   node_place
                                   ASC", $data);*/
          $MYSQL->bind('in_category', $category);
          $query = $MYSQL->query("SELECT * FROM {prefix}forum_node WHERE in_category = :in_category AND node_type = 1 ORDER BY node_place ASC");
          foreach( $query as $node ) {
              $allowed = explode(',', $node['allowed_usergroups']);
              if( in_array($TANGO->sess->data['user_group'], $allowed) ) {
                //$MYSQL->where('node_type', 2);
                //$MYSQL->where('parent_node', $node['id']);
                //$sub     = $MYSQL->get('{prefix}forum_node');
                $MYSQL->bind('parent_node', $node['id']);
                $sub = $MYSQL->query("SELECT * FROM {prefix}forum_node WHERE node_type = 2 AND parent_node = :parent_node");
                $subs    = array();
                foreach( $sub as $suf ) {
                  $allowed = explode(',', $suf['allowed_usergroups']);
                  if( in_array($TANGO->sess->data['user_group'], $allowed) ) {
                    $subs[] = '<a href="' . SITE_URL . '/node.php/' . $suf['name_friendly'] . '.' . $suf['id'] . '">' . $suf['node_name'] . '</a>';
                  }
                }
                $subs = (!empty($subs))? implode(', ', array_slice($subs, 0, 3)) : 'None';
                
                // New posts in node?
                //$data = array($node['id']);                
                /*$posts = $MYSQL->rawQuery("SELECT id FROM
                                  {prefix}forum_posts
                                  WHERE
                                  origin_node = ?
                                  AND post_type = 1
                                  ORDER BY
                                  post_time
                                  DESC", $data);*/
                $MYSQL->bind('origin_node', $node['id']);
                $posts = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE origin_node = :origin_node AND post_type = 1 ORDER BY post_time DESC");
                $node_status = 'read';
                if(!empty($posts)) {
                    foreach($posts as $post) {
                        $status = $TANGO->node->thread_new_posts($post['id']);
                        if ($status == 'unread') {
                            $node_status = 'unread';
                            break;
                        }
                    }
                }
                
                
                $return .= $TANGO->tpl->entity(
                    'forum_listings_node',
                    array(
                      'node_name',
                      'node_desc',
                      'latest_post',
                      'sub_forums',
                      'status'
                    ),
                    array(
                      '<a href="' . SITE_URL . '/node.php/' . $node['name_friendly'] . '.' . $node['id'] . '">' . $node['node_name'] . '</a>',
                      $node['node_desc'],
                      $this->latestPost($node['id']),
                      $subs,
                      $node_status
                    )
                );
              }
          }
          return $return;
      }

      /*
       * Sub-Forums
       */
      public function subForums($parent_forum) {
        global $MYSQL, $TANGO;

        $return = '';
        //$data = array($parent_forum);
        /*$query  = $MYSQL->rawQuery("SELECT * FROM
                                   {prefix}forum_node
                                   WHERE
                                   parent_node = ?
                                   AND
                                   node_type = 2
                                   ORDER BY
                                   node_place
                                   ASC", $data);*/
        $MYSQL->bind('parent_node', $parent_forum);
        $query = $MYSQL->query("SELECT * FROM {prefix}forum_node WHERE parent_node = :parent_node AND node_type = 2 ORDER BY node_place ASC");
        foreach( $query as $node ) {
          $allowed = explode(',', $node['allowed_usergroups']);
          if( in_array($TANGO->sess->data['user_group'], $allowed) ) {
            $return .= $TANGO->tpl->entity(
              'forum_listings_node_sub_forums_posts',
                array(
                  'node_name',
                  'node_desc',
                  'latest_post'
                ),
                array(
                  '<a href="' . SITE_URL . '/node.php/' . $node['name_friendly'] . '.' . $node['id'] . '">' . $node['node_name'] . '</a>',
                  $node['node_desc'],
                  $this->latestSubForumPost($node['id'])
                )
            );
          }
        }

        $return = $TANGO->tpl->entity(
          'forum_listings_node_sub_forums',
          'nodes',
          $return
        );
        return $return;
      }

      /*
       * Latest Threads
       */
      private function latestPost($forum) {
          global $MYSQL, $TANGO;

          //$MYSQL->where('node_type', 2);
          //$MYSQL->where('parent_node', $forum);
          //$query = $MYSQL->get('{prefix}forum_node');
          $MYSQL->bind('parent_node', $forum);
          $query = $MYSQL->query("SELECT * FROM {prefix}forum_node WHERE node_type = 2 AND parent_node = :parent_node");
          $where = 'NULL';
          $data = array();
          foreach( $query as $wh ) {
            $where .= ',:' . $wh['id'];
            //$data[] = $wh['id'];
            $MYSQL->bind($wh['id'], $wh['id']);
          }
          $where .= ',:forum';
          //$data[] = $forum;
          $MYSQL->bind('forum', $forum);

          //die(var_dump($data));

          $return = '';

          /*$query = $MYSQL->rawQuery("SELECT * FROM
                                     {prefix}forum_posts
                                     WHERE
                                     origin_node
                                     IN (" . $where . ")
                                     ORDER BY
                                     post_time
                                     DESC LIMIT 1", $data);*/
          $query = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE origin_node IN (" . $where . ")");

          if( !empty($query) ) {

              foreach( $query as $post ) {
                  $user    = $TANGO->user($post['post_user']);

                  if( $post['post_type'] == "1" ) {
                      $latest = (strlen($post['post_title']) > 24)? '<a href="' . SITE_URL . '/thread.php/' . $post['title_friendly'] . '.' . $post['id'] . '" title="' . $post['post_title'] . '">' . substr($post['post_title'], 0, 24) . '...' . '</a>' : '<a href="' . SITE_URL . '/thread.php/' . $post['title_friendly'] . '.' . $post['id'] . '">' . $post['post_title'] . '</a>';
                  } elseif( $post['post_type'] == "2" ) {
                      //$MYSQL->where('origin_thread', $post['origin_thread']);
                      //$q      = $MYSQL->get('{prefix}forum_posts');
                      $MYSQL->bind('origin_thread', $post['origin_thread']);
                      $q = $MYSQL->query("SELECT * FROM {prefix}forum_post WHERE origin_thread = :origin_thread");

                      $q      = (count($q) / POST_RESULTS_PER_PAGE);
                      $page   = ( $q > 1 )? '/page/' . ceil($q) . '/' : '';

                      $p      = thread($post['origin_thread']);
                      $latest = (strlen($p['post_title']) > 24)? '<a href="' . SITE_URL . '/thread.php/' . $p['title_friendly'] . '.' . $p['id'] . $page . '#post-' . $post['id'] . '" title="' . $p['post_title'] . '">' . substr($p['post_title'], 0, 24) . '...' . '</a>' : '<a href="' . SITE_URL . '/thread.php/' . $p['title_friendly'] . '.' . $p['id'] . $page . '#post-' . $post['id'] . '">' . $p['post_title'] . '</a>';
                  }

                  /** Time Settings *
                  if((time()-$post['post_time']) > 86400 && (time()-$post['post_time']) < 604800) {
                      $post_time         = date('l h:i A', $post['post_time']);
                      $post_time_tooltip = date('F jS, Y', $post['post_time']);
                  }
                  elseif((time()-$post['post_time']) > 3600 && (time()-$post['post_time']) < 86400) {
                      $post_time         = round((time()-$post['post_time'])/3600).' hours ago';
                      $post_time_tooltip = date('F jS, Y', $post['post_time']);
                  }
                  elseif((time()-$post['post_time']) > 60 && (time()-$post['post_time']) < 3600) {
                      $post_time         = round((time()-$post['post_time'])/60).' minutes ago';
                      $post_time_tooltip = date('F jS, Y', $post['post_time']);
                  }
                  elseif((time()-$post['post_time']) < 60) {
                      $post_time         = 'Just now';
                      $post_time_tooltip = date('F jS, Y', $post['post_time']);
                  }
                  else {
                      $post_time         = date('F jS, Y', $post['post_time']);
                      $post_time_tooltip = date('l h:i A', $post['post_time']);
                  }*/
                  $post_time = simplify_time($post['post_time'],@$TANGO->sess->data['location']);
                  /** Output */
                  $return .= $TANGO->tpl->entity(
                      'forum_listings_node_latest',
                      array(
                          'user_avatar',
                          'latest_post',
                          'post_user',
                          'post_time'
                      ),
                      array(
                          $user['user_avatar'],
                          $latest,
                          '<a href="' . SITE_URL . '/members.php/cmd/user/id/' . $user['id'] . '">' . $user['username'] . '</a>',
                          '<span title="' . $post_time['tooltip'] . '">' . $post_time['time'] . '</span>'
                      )
                  );
              }

          } else {
              $return .= 'None';
          }
          return $return;
      }
      private function latestSubForumPost($forum) {
          global $MYSQL, $TANGO;

          $return = '';
      $data = array($forum);
          /*$query = $MYSQL->rawQuery("SELECT * FROM
                                  {prefix}forum_posts
                                  WHERE
                                  origin_node = ?
                                  ORDER BY
                                  post_time
                                  DESC
                                  LIMIT 1", $data);*/
          $MYSQL->bind('origin_node', $forum);
          $query = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE origin_node = :origin_node ORDER BY post_time DESC LIMIT 1");
          if( !empty($query) ) {

              foreach( $query as $post ) {
                  $user    = $TANGO->user($post['post_user']);

                  if( $post['post_type'] == "1" ) {
                      $latest = (strlen($post['post_title']) > 24)? '<a href="' . SITE_URL . '/thread.php/' . $post['title_friendly'] . '.' . $post['id'] . '" title="' . $post['post_title'] . '">' . substr($post['post_title'], 0, 24) . '...' . '</a>' : '<a href="' . SITE_URL . '/thread.php/' . $post['title_friendly'] . '.' . $post['id'] . '">' . $post['post_title'] . '</a>';
                  } elseif( $post['post_type'] == "2" ) {
                      //$MYSQL->where('origin_thread', $post['origin_thread']);
                      //$q      = $MYSQL->get('{prefix}forum_posts');
                      $MYSQL->bind('origin_thread', $post['origin_thread']);
                      $q      = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE origin_thread = :origin_thread");

                      $q      = (count($q) / POST_RESULTS_PER_PAGE);
                      $page   = ( $q > 1 )? '/page/' . ceil($q) . '/' : '';

                      $p      = thread($post['origin_thread']);
                      $latest = (strlen($p['post_title']) > 24)? '<a href="' . SITE_URL . '/thread.php/' . $p['title_friendly'] . '.' . $p['id'] . $page . '#post-' . $post['id'] . '" title="' . $p['post_title'] . '">' . substr($p['post_title'], 0, 24) . '...' . '</a>' : '<a href="' . SITE_URL . '/thread.php/' . $p['title_friendly'] . '.' . $p['id'] . $page . '#post-' . $post['id'] . '">' . $p['post_title'] . '</a>';
                  }

                  /** Time Settings *
                  if((time()-$post['post_time']) > 86400 && (time()-$post['post_time']) < 604800) {
                      $post_time         = date('l h:i A', $post['post_time']);
                      $post_time_tooltip = date('F jS, Y', $post['post_time']);
                  }
                  elseif((time()-$post['post_time']) > 3600 && (time()-$post['post_time']) < 86400) {
                      $post_time         = round((time()-$post['post_time'])/3600).' hours ago';
                      $post_time_tooltip = date('F jS, Y', $post['post_time']);
                  }
                  elseif((time()-$post['post_time']) > 60 && (time()-$post['post_time']) < 3600) {
                      $post_time         = round((time()-$post['post_time'])/60).' minutes ago';
                      $post_time_tooltip = date('F jS, Y', $post['post_time']);
                  }
                  elseif((time()-$post['post_time']) < 60) {
                      $post_time         = 'Just now';
                      $post_time_tooltip = date('F jS, Y', $post['post_time']);
                  }
                  else {
                      $post_time         = date('F jS, Y', $post['post_time']);
                      $post_time_tooltip = date('l h:i A', $post['post_time']);
                  }*/
                  $post_time = simplify_time($post['post_time'],@$TANGO->sess->data['location']);
                  /** Output */
                  $return .= $TANGO->tpl->entity(
                      'forum_listings_node_sub_forums_latest',
                      array(
                          'user_avatar',
                          'latest_post',
                          'post_user',
                          'post_time'
                      ),
                      array(
                          $user['user_avatar'],
                          $latest,
                          '<a href="' . SITE_URL . '/members.php/cmd/user/id/' . $user['id'] . '">' . $user['username'] . '</a>',
                          '<span title="' . $post_time['tooltip'] . '">' .  $post_time['time'] . '</span>'
                      )
                  );
              }

          } else {
              $return .= 'None';
          }
          return $return;
      }

  }

?>