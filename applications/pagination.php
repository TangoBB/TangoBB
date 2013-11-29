<?php

  /*
   * Standard Pagination Functions of TangoBB.
   */
  if( !defined('BASEPATH') ){ die(); }

  /* Pagination for threads */
  function getThreads($id, $page, $per_page = THREAD_RESULTS_PER_PAGE) {
      global $MYSQL;
      
      $start    = (int)($page - 1) * $per_page;
      $per_page = (int)$per_page;
      
      $query = $MYSQL->query("SELECT * FROM
                             {prefix}forum_posts
                             WHERE
                             post_type = 1
                             AND
                             origin_node = $id
                             ORDER BY
                             post_sticky
                             DESC,
                             last_updated
                             DESC,
                             post_time
                             DESC LIMIT
                             {$start}, {$per_page}");
      return $query;
  }
  function fetchTotalThread($id) {
      global $MYSQL;
      
      $MYSQL->where('post_type', 1);
      $MYSQL->where('origin_node', $id);
      $query = $MYSQL->query("SELECT * FROM
                             {prefix}forum_posts");
      return count($query);   
  }

  /* Pagination for posts */
  function getPosts($id, $page, $per_page = POST_RESULTS_PER_PAGE) {
      global $MYSQL;
      
      $start    = (int)($page - 1) * $per_page;
      $per_page = (int)$per_page;
      
      $query = $MYSQL->query("SELECT * FROM
                             {prefix}forum_posts
                             WHERE
                             post_type = 2
                             AND
                             origin_thread = $id
                             ORDER BY
                             post_time
                             ASC LIMIT
                             {$start}, {$per_page}");
      return $query;
  }
  function fetchTotalPost($id) {
      global $MYSQL;
      
      $MYSQL->where('post_type', 2);
      $MYSQL->where('origin_thread', $id);
      $query = $MYSQL->query("SELECT * FROM
                              {prefix}forum_posts");
      return count($query);   
  }

  /* Pagination for members. */
  function getMembers($page, $per_page = "20") {
      global $MYSQL;
      
      $start    = (int)($page - 1) * $per_page;
      $per_page = (int)$per_page;
      
      $query = $MYSQL->query("SELECT * FROM
                              {prefix}users
                              ORDER BY
                              username
                              ASC LIMIT
                              {$start}, {$per_page}");
      return $query;
  }
  function fetchTotalMembers() {
      global $MYSQL;
      
      $query = $MYSQL->query("SELECT * FROM
                              {prefix}users");
      return count($query);   
  }

?>