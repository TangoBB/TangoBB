<?php

  /*
   * Standard Functions of TangoBB.
   */
  if( !defined('BASEPATH') ){ die(); }

  /*
   * Conversion
   */
  function bytesToSize($bytes, $precision = 2) {
    $kilobyte = 1024;
    $megabyte = $kilobyte * 1024;
    $gigabyte = $megabyte * 1024;
    $terabyte = $gigabyte * 1024;

    if (($bytes >= 0) && ($bytes < $kilobyte)) {
      return $bytes . ' B';
    } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
      return round($bytes / $kilobyte, $precision) . ' KB';
    } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
        return round($bytes / $megabyte, $precision) . ' MB';
    } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
      return round($bytes / $gigabyte, $precision) . ' GB';
    } elseif ($bytes >= $terabyte) {
      return round($bytes / $terabyte, $precision) . ' TB';
    } else {
      return $bytes . ' B';
    }
}

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
   * Users that are online over the past 24 hours.
   * time >= session_time
   */
  /*function users_online() {
    global $MYSQL, $TANGO;
    $time  = strtotime("-1 day");
    $time  = time();
    $query = $MYSQL->query("SELECT * FROM {prefix}sessions ORDER BY session_time DESC");
    $users = array();
    foreach( $query as $u ) {
      if( $u['session_time'] < $time ) {
        if( !in_array($u['logged_user'], $users) ) {
          $users[] = $u['logged_user'];
        }
      }
    }
    //die(var_dump($users));

    $total = array();
    foreach( $users as $u ) {
      $us = $TANGO->user($u);
      $total[] = '<a href="' . SITE_URL . '/members.php/cmd/user/id/' . $us['id'] . '">' . $us['username_style'] . '</a>';
    }
    //die(var_dump($total));
    if( !empty($total) ) {
      return implode(', ', $total);
    } else {
      return 'None';
    }
  }*/
  function users_online() {
    global $MYSQL, $TANGO;
    $time  = time();
    $query = $MYSQL->query("SELECT * FROM {prefix}sessions ORDER BY session_time DESC");
    $users = array();
    foreach( $query as $u ) {
      $session_time  = strtotime("+1 day", $u['session_time']);
      if( $time >= $session_time ) {
      } else {
        if( !in_array($u['logged_user'], $users) ) {
          $users[] = $u['logged_user'];
        }
      }
    }
    //die(var_dump($users));

    $total = array();
    foreach( $users as $u ) {
      $us = $TANGO->user($u);
      $total[] = '<a href="' . SITE_URL . '/members.php/cmd/user/id/' . $us['id'] . '">' . $us['username_style'] . '</a>';
    }
    //die(var_dump($total));
    if( !empty($total) ) {
      return implode(', ', $total);
    } else {
      return 'None';
    }
  }

  /*
   * List themes for theme changer.
   */
  function listThemes() {
    if( BASEPATH == "Staff" ) {
      $directory = scandir('../public/themes');
    } else {
      $directory = scandir('public/themes');
    }
    unset($directory['0']); unset($directory['1']); //unset($directory['2']);//Remove ".", ".." and "index.html"
    $return = array();
    foreach( $directory as $t ) {
      if( is_dir('public/themes/' . $t) ) {

        $return[] = array(
          'change_link' => SITE_URL . '/profile.php/cmd/theme/set/' . $t,
          'theme_name' => $t
        );

      }
    }
    $return[] = array(
      'change_link' => SITE_URL . '/profile.php/cmd/theme/set/default',
      'theme_name' => 'Default'
    );
    return $return;
  }
  /*
   * Cleans string.
   * Does not escape with MySQL because the MySQL Library already does that.
   */
  function clean($string) {
      //die($string);
      $string = htmlentities($string);
      //die($string);
      $string = str_replace(
        array(
          '&amp;#65279;',
          '`'
        ),
        array(
          '',
          '&#96;'
        ),
        $string
      );
      $string = str_replace('`', '\`', $string);
      //$string = $MYSQL->escape($string);
      return $string;
  }

  function redirect($url) {
      header('Location: ' . $url);
      exit;
  }

  /*
   * Generate hex-encoded pseudo-random bytes.
   *
   * The function first tries to read from a secure randomness source. If neither the
   * OpenSSL extension nor the Mcrypt extension nor direct access to /dev/urandom is
   * available, it falls back to mt_rand().
   */
  function randomHexBytes($length) {
      $raw_bytes = '';

      if (function_exists('openssl_random_pseudo_bytes')) {
          $raw_bytes = openssl_random_pseudo_bytes($length);
      } elseif (function_exists('mcrypt_create_iv')) {
          $raw_bytes = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
      } else {
          $urandom = @fopen('/dev/urandom', 'rb');

          if (is_resource($urandom)) {
              $raw_bytes = fread($urandom, $length);
              fclose($urandom);
          }
      }

      if (!is_string($raw_bytes) || strlen($raw_bytes) < $length) {
          for ($byte_index = 0; $byte_index < $length; $byte_index++) {
              $raw_bytes .= chr(mt_rand(0, 255));
          }
      }

      return bin2hex($raw_bytes);
  }

  function randomString($length = 16) {
      trigger_error('The function randomString() is deprecated. Use randomHexBytes() instead.', E_USER_WARNING);

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
      return password_hash($password, PASSWORD_BCRYPT, array('cost' => USER_PASSWORD_HASH_COST));
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
  function validEmail($email) {
    if( preg_match('/^[a-z0-9_\.-]+@([a-z0-9]+([\-]+[a-z0-9]+)*\.)+[a-z]{2,7}$/i', $email) ) {
      return true;
    } else {
      return false;
    }
  }
  function userBanned($email) {
      global $MYSQL;
      $MYSQL->where('user_email', $email);
      $a                    = $MYSQL->get('{prefix}users');
      $MYSQL->where('username', $email);
      $b                    = $MYSQL->get('{prefix}users');

      $a                    = ( $a )? $a : $b;

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

      $MYSQL->where('username', $email);
      $b = $MYSQL->get('{prefix}users');

      $a = ( $a )? $a : $b;

      if( $a['0']['user_disabled'] == "0" ) {
          return true;
      } else {
          return false;
      }
  }
  function userExists($email, $password, $rehash_if_necessary = true) {
      global $MYSQL;

      $login_successful = false;

      $MYSQL->where('user_email', $email);
      $a = $MYSQL->get('{prefix}users');

      $MYSQL->where('username', $email);
      $b = $MYSQL->get('{prefix}users');
      if( $a or $b ) {
          $user_data = ( $a )? $a[0] : $b[0];
          $hash = $user_data['user_password'];

          /*
           * There are two types of hashes: insecure legacy hashes based on SHA-256
           * and the new bcrypt hashes.
           *
           * The legacy hashes need to be replaced with bcrypt hashes after the
           * password has been verified. The bcrypt hashes need to be refreshed
           * in case the cost factor has changed.
           */
          $obsolete_hash = false;

          if ( substr($hash, 0, 4) === '$SHA' ) {
              list(, , $salt) = explode('$', $hash);

              $hash_to_test =
                  '$SHA$' . $salt . '$' . hash('sha256', hash('sha256', $password) . hash('sha256', $salt));

              $login_successful = strcasecmp($hash_to_test, $hash) === 0;

              $obsolete_hash = $rehash_if_necessary;
          } else {
              $login_successful = password_verify($password, $hash);

              $obsolete_hash = $rehash_if_necessary
                  && password_needs_rehash($hash, PASSWORD_BCRYPT, array('cost' => USER_PASSWORD_HASH_COST))
              ;
          }

          if ( $login_successful && $obsolete_hash ) {
              $MYSQL
                  ->where('id', $user_data['id'])
                  ->update('{prefix}users', array('user_password' => encrypt($password)))
              ;
          }
      }

      return $login_successful;
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
  function thread($id, $callback = null) {
      global $MYSQL;
      $MYSQL->where('id', $id);
      $query = $MYSQL->get('{prefix}forum_posts');

      if( is_callable($callback) ) {
          call_user_func($callback, $query['0']);
      } else {
          return $query['0'];
      }
  }
  function node($id, $callback = null) {
      global $MYSQL;
      $MYSQL->where('id', $id);
      $query = $MYSQL->get('{prefix}forum_node');

      if( is_callable($callback) ) {
          call_user_func($callback, $query['0']);
      } else {
          return $query['0'];
      }
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

  /*
   * Include all installed extensions.
   */
  function include_extensions() {
    global $MYSQL;
    $query = $MYSQL->get('{prefix}extensions');
    foreach( $query as $extension ) {
      require_once('extensions/' . $extension['extension_folder'] . '/manifest.php');
    }
  }

  /*
   * Forum Listings
   */
  function list_forums() {
      global $MYSQL;
      $return = array();
      $query  = $MYSQL->get('{prefix}forum_node');
      foreach( $query as $node ) {
        $return[] = array(
          'id' => $node['id'],
          'name' => $node['node_name']
        );
      }
      return $return;
  }

  /*
   * Features to tell time.
   * @return array;
   * @array
   *  - tooltip
   *  - time
   */
  function simplify_time($timestamp) {
    global $LANG;
    if( (time()-$timestamp) > 86400 && (time()-$timestamp) < 604800 ) {
      $post_time         = date('l h:i A', $timestamp);
      $post_time_tooltip = date('F jS, Y', $timestamp);
    } elseif( (time()-$timestamp) > 3600 && (time()-$timestamp) < 86400 ) {
      //$post_time         = round((time()-$timestamp)/3600).' hours ago.';
      $post_time         = str_replace(
        '%time%',
        round((time()-$timestamp)/3600),
        $LANG['time']['hours_ago']
      );
      $post_time_tooltip = date('F jS, Y', $timestamp);
    } elseif( (time()-$timestamp) > 60 && (time()-$timestamp) < 3600 ) {
      //$post_time         = round((time()-$timestamp)/60).' minutes ago.';
      $time              = round((time()-$timestamp)/60);
      $post_time         = str_replace(
        '%time%',
        $time,
        $LANG['time']['minutes_ago']
      );
      $post_time_tooltip = date('F jS, Y', $timestamp);
    } elseif( (time()-$timestamp) < 60 ) {
      //$post_time         = 'Just now.';
      $post_time         = $LANG['time']['just_now'];
      $post_time_tooltip = date('F jS, Y', $timestamp);
    } else {
      $post_time         = date('F jS, Y', $timestamp);
      $post_time_tooltip = date('l h:i A', $timestamp);
    }

    $return = array(
      'tooltip' => $post_time_tooltip,
      'time' => $post_time
    );
    return $return;
  }
  
  /* Function which counts the replies of a conversation
   * by N8boy
   */
  function amount_replies($origin_massage_id){
    global $MYSQL;
    if(is_numeric($origin_massage_id)){
      $query = $MYSQL->query("SELECT * FROM
                              {prefix}messages
                              WHERE
                              origin_message = " . $origin_massage_id);
      return number_format(count($query));
      }
  }
  function gender($in) {
    if($in==1)
    {
        $out = '&#9792;';
    }
    elseif($in==2)
    {
        $out = '&#9794;';
    }
    else
    {
        $out = '-';
    }
    return $out;
  }

?>