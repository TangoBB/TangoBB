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

  function randomString($length = 16) {
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
      } elseif( !$a or $a['0']['facebook_id'] !== "0" ) {
          return false;
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

?>