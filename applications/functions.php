<?php

  /*
   * Standard Functions of TangoBB.
   */
  if( !defined('BASEPATH') ){ die(); }
  
  /*
   * Forum statistics.
   */
  function stat_threads() {
      global $MYSQL;
      $query = $MYSQL->query("SELECT * FROM
                              {prefix}forum_posts
                              WHERE
                              post_type = 1");
      return number_format(count($query));
  }
  function stat_posts() {
      global $MYSQL;
      $query = $MYSQL->query("SELECT * FROM
                              {prefix}forum_posts
                              WHERE
                              post_type = 2");
      return number_format(count($query));
  }
  function stat_users() {
      global $MYSQL;
      $query = $MYSQL->query("SELECT * FROM
                              {prefix}users");
      return number_format(count($query));
  }

  /*
   * Cleans string.
   * Does not escape with MySQL because the MySQL Library already does that.
   */
  function clean($string) {
      $string = htmlentities($string);
      return $string;
  }

  function randomString($length) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, strlen($characters) - 1)];
      }
      return $randomString;
  }

  function title_friendly($string) {
      return strtolower(preg_replace("![^a-z0-9]+!i", "_", $string));
  }

  /*
   * Password Encryption
   */
  function encrypt($password) {
      $salty    = randomString(16);
      $salt     = hash('sha256', $salty);
      $password = hash('sha256', $password);
      $password = hash('sha256', $password . $salt);
      return '$SHA$' . $salty . '$' . $password;
      //return $password;
  }

  /*
   * Moderator Functions.
   */
  function modReportInteger() {
      global $MYSQL;
      $query = $MYSQL->get('{prefix}reports');
      
      return count($query);
  }

  /*
   * Check if username and email exists.
   */
  function usernameExists($username) {
      global $MYSQL;
      $MYSQL->where('username', $username);
      $query = $MYSQL->get('{prefix}users');
      
      if( !empty($query) ) {
          return true;
      } else {
          return false;
      }
  }
  function emailTaken($email) {
      global $MYSQL;
      $MYSQL->where('user_email', $email);
      $query = $MYSQL->get('{prefix}users');
      
      if( !empty($query) ) {
          return true;
      } else {
          return false;
      }
  }
  function userBanned($email) {
      global $MYSQL;
      $MYSQL->where('user_email', $email);
      $a                    = $MYSQL->get('{prefix}users');
      $return['unban_time'] = $a['0']['unban_time'];
      $return['ban_reason'] = $a['0']['ban_reason'];
      
      if( $a['0']['is_banned'] == "1" ) {
          return $return;
      } else {
          return false;
      }
  }
  function userActivated($email) {
      global $MYSQL;
      $MYSQL->where('user_email', $email);
      $a = $MYSQL->get('{prefix}users');
      
      if( $a['0']['user_disabled'] == "0" ) {
          return true;
      } else {
          return false;
      }
  }
  function userExists($email, $password) {
      global $MYSQL;
      
      $MYSQL->where('user_email', $email);
      $a = $MYSQL->get('{prefix}users');
      if( $a ) {
          $sha_info = explode('$', $a[0]['user_password']);
      } else {
          return false;
      }
      if( $sha_info[1] === "SHA"  ) {
          $password = hash('sha256', $password);
          $salty    = hash('sha256', $sha_info[2]);
          $password = hash('sha256', $password . $salty);
          $password = '$SHA$' . $sha_info['2'] . '$' . $password;
          if( strcasecmp(trim($password), $a['0']['user_password']) == 0  ) {
              return true;
          } else {
              return false;
          }
      } else {
          return false;
      }
  }
  function usergroupExists($name) {
      global $MYSQL;
      $MYSQL->where('group_name', $name);
      $query = $MYSQL->get('{prefix}usergroups');
      if( !empty($query) ) {
          return $query['0'];
      } else {
          return false;
      }
  }

  /*
   * Get details for a thread.
   */
  function thread($id) {
      global $MYSQL;
      $MYSQL->where('id', $id);
      $query = $MYSQL->get('{prefix}forum_posts');
      return $query['0'];
  }
  function node($id) {
      global $MYSQL;
      $MYSQL->where('id', $id);
      $query = $MYSQL->get('{prefix}forum_node');
      return $query['0'];
  }

  /*
   * Delete a folder with contents in it.
   */
  function rrmdir($dir) { 
      foreach(glob($dir . '/*') as $file) { 
          if(is_dir($file)) rrmdir($file); else unlink($file); 
      }
      if( rmdir($dir) ) {
          return true;
      } else {
          return false;
      }
  }

?>