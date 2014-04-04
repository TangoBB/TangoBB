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
          return $return;
      }

      /*
       * Forums
       */
      private function forums($category) {
          global $MYSQL, $TANGO;

          $return = '';
      $data = array($category);
          $query  = $MYSQL->rawQuery("SELECT * FROM
                                   {prefix}forum_node
                                   WHERE
                                   in_category = ?
                                   AND
                                   node_type = 1
                                   ORDER BY
                                   node_place
                                   ASC", $data);
          foreach( $query as $node ) {
              $MYSQL->where('node_type', 2);
              $MYSQL->where('parent_node', $node['id']);
              $sub     = $MYSQL->get('{prefix}forum_node');
              $subs    = array();
              foreach( $sub as $suf ) {
                $subs[] = '<a href="' . SITE_URL . '/node.php/' . $suf['name_friendly'] . '.' . $suf['id'] . '">' . $suf['node_name'] . '</a>';
              }

              $subs = (!empty($subs))? implode(', ', array_slice($subs, 0, 3)) : 'None';

              $return .= $TANGO->tpl->entity(
                  'forum_listings_node',
                  array(
                      'node_name',
                      'node_desc',
                      'latest_post',
                      'sub_forums'
                  ),
                  array(
                      '<a href="' . SITE_URL . '/node.php/' . $node['name_friendly'] . '.' . $node['id'] . '">' . $node['node_name'] . '</a>',
                      $node['node_desc'],
                      $this->latestPost($node['id']),
                      $subs
                  )
              );
          }
          return $return;
      }

      /*
       * Sub-Forums
       */
      public function subForums($parent_forum) {
        global $MYSQL, $TANGO;

          $return = '';
      $data = array($parent_forum);
          $query  = $MYSQL->rawQuery("SELECT * FROM
                                   {prefix}forum_node
                                   WHERE
                                   parent_node = ?
                                   AND
                                   node_type = 2
                                   ORDER BY
                                   node_place
                                   ASC", $data);
          foreach( $query as $node ) {
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

          $MYSQL->where('node_type', 2);
          $MYSQL->where('parent_node', $forum);
          $query = $MYSQL->get('{prefix}forum_node');
          $where = 'NULL';
          $data = array();
          foreach( $query as $wh ) {
            $where .= ',?';
            $data[] = $wh['id'];
          }

          $return = '';
          if( !empty($data) ) {
            if( count($data) > 1 ) {
              $query  = $MYSQL->rawQuery("SELECT * FROM
                                   {prefix}forum_posts
                                   WHERE
                                   origin_node
                                   IN
                                   (" . $where . ")
                                   ORDER BY
                                   post_time
                                   DESC
                                   LIMIT 1", $data);
            } else {
        $data = array($forum, $data[0]);
              $query = $MYSQL->rawQuery("SELECT * FROM
                                  {prefix}forum_posts
                                  WHERE
                                  origin_node = ?
                                  OR
                                  origin_node = ?
                                  ORDER BY
                                  post_time
                                  DESC
                                  LIMIT 1", $data);
            }
          } else {
        $data = array($forum);
            $query = $MYSQL->rawQuery("SELECT * FROM
                                  {prefix}forum_posts
                                  WHERE
                                  origin_node = ?
                                  ORDER BY
                                  post_time
                                  DESC
                                  LIMIT 1", $data);
          }

          if( !empty($query) ) {

              foreach( $query as $post ) {
                  $user    = $TANGO->user($post['post_user']);

                  if( $post['post_type'] == "1" ) {
                      $latest = (strlen($post['post_title']) > 24)? '<a href="' . SITE_URL . '/thread.php/' . $post['title_friendly'] . '.' . $post['id'] . '" title="' . $post['post_title'] . '">' . substr($post['post_title'], 0, 24) . '...' . '</a>' : '<a href="' . SITE_URL . '/thread.php/' . $post['title_friendly'] . '.' . $post['id'] . '">' . $post['post_title'] . '</a>';
                  } elseif( $post['post_type'] == "2" ) {
                      $MYSQL->where('origin_thread', $post['origin_thread']);
                      $q      = $MYSQL->get('{prefix}forum_posts');

                      $q      = (count($q) / POST_RESULTS_PER_PAGE);
                      $page   = ( $q > 1 )? '/page/' . ceil($q) . '/' : '';

                      $p      = thread($post['origin_thread']);
                      $latest = (strlen($p['post_title']) > 24)? '<a href="' . SITE_URL . '/thread.php/' . $p['title_friendly'] . '.' . $p['id'] . $page . '#post-' . $post['id'] . '" title="' . $p['post_title'] . '">' . substr($p['post_title'], 0, 24) . '...' . '</a>' : '<a href="' . SITE_URL . '/thread.php/' . $p['title_friendly'] . '.' . $p['id'] . $page . '#post-' . $post['id'] . '">' . $p['post_title'] . '</a>';
                  }

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
                          '<span title="' . date('F j, Y', $post['post_time']) . '">' . date('l h:i A', $post['post_time']) . '</span>'
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
          $query = $MYSQL->rawQuery("SELECT * FROM
                                  {prefix}forum_posts
                                  WHERE
                                  origin_node = ?
                                  ORDER BY
                                  post_time
                                  DESC
                                  LIMIT 1", $data);
          if( !empty($query) ) {

              foreach( $query as $post ) {
                  $user    = $TANGO->user($post['post_user']);

                  if( $post['post_type'] == "1" ) {
                      $latest = (strlen($post['post_title']) > 24)? '<a href="' . SITE_URL . '/thread.php/' . $post['title_friendly'] . '.' . $post['id'] . '" title="' . $post['post_title'] . '">' . substr($post['post_title'], 0, 24) . '...' . '</a>' : '<a href="' . SITE_URL . '/thread.php/' . $post['title_friendly'] . '.' . $post['id'] . '">' . $post['post_title'] . '</a>';
                  } elseif( $post['post_type'] == "2" ) {
                      $MYSQL->where('origin_thread', $post['origin_thread']);
                      $q      = $MYSQL->get('{prefix}forum_posts');

                      $q      = (count($q) / POST_RESULTS_PER_PAGE);
                      $page   = ( $q > 1 )? '/page/' . ceil($q) . '/' : '';

                      $p      = thread($post['origin_thread']);
                      $latest = (strlen($p['post_title']) > 24)? '<a href="' . SITE_URL . '/thread.php/' . $p['title_friendly'] . '.' . $p['id'] . $page . '#post-' . $post['id'] . '" title="' . $p['post_title'] . '">' . substr($p['post_title'], 0, 24) . '...' . '</a>' : '<a href="' . SITE_URL . '/thread.php/' . $p['title_friendly'] . '.' . $p['id'] . $page . '#post-' . $post['id'] . '">' . $p['post_title'] . '</a>';
                  }

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
                          '<span title="' . date('F j, Y', $post['post_time']) . '">' . date('l h:i A', $post['post_time']) . '</span>'
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