<?php

  /*
   * Session class of TangoBB
   */
  if( !defined('BASEPATH') ){ die(); }

  class Tango_Session {

      public $isLogged = false;
      public $data, $date;
      private $session;

      public function __construct() {
          global $TANGO, $MYSQL, $LANG;
          $this->clear();
          if( $this->check() ) {
              //die(var_dump($session_id));
              $this->isLogged               = true;
              //$user_id                      = ( isset($_SESSION['tangobb_sess']) )? $_SESSION['tangobb_sess'] : $_COOKIE['tangobb_sess'];
              //$this->data                   = $TANGO->user($user_id);
              $this->data                   = $TANGO->user($this->session['logged_user']);
              $this->data['username_style'] = $TANGO->usergroup($this->data['user_group'], 'username_style', $this->data['username']);
              //$this->data['permissions']    = $TANGO->usergroup($this->data['user_group'], 'permissions');
              if( $this->data['additional_permissions'] !== "0" ) {
                $current_perms             = $TANGO->usergroup($this->data['user_group'], 'permissions');
                foreach( $this->data['additional_permissions'] as $ap ) {
                  $current_perms[] = $ap;
                }
                $this->data['permissions'] = $current_perms;
              } else {
                $this->data['permissions']    = $TANGO->usergroup($this->data['user_group'], 'permissions');
              }

              if( $this->session['session_time'] >= strtotime('24 hours ago') ) {
                $time = time();
                //$MYSQL->where('session_id', $this->session['session_id']);
                /*$MYSQL->update(
                  '{prefix}sessions',
                  array(
                    'session_time' => $time
                    )
                );*/
                $MYSQL->bind('session_id', $this->session['session_id']);
                $MYSQL->bind('session_time', $time);
                $MYSQL->query("UPDATE {prefix}sessions SET session_time = :session_time WHERE session_id = :session_id");
              }

              //Adding links for users who are logged in and everything else in the template.
              $TANGO->user->addUserLink(array(
                  $LANG['bb']['profile']['profile'] => SITE_URL . '/members.php/cmd/user/',
                  //$LANG['bb']['conversations']['page_conversations'] => SITE_URL . '/conversations.php', // has been moved in the Conversation category
                  $LANG['bb']['profile']['personal_details'] => SITE_URL . '/profile.php/cmd/edit',
                  $LANG['bb']['profile']['avatar'] => SITE_URL . '/profile.php/cmd/avatar',
                  $LANG['bb']['profile']['signature'] => SITE_URL . '/profile.php/cmd/signature',
                  $LANG['bb']['profile']['password'] => SITE_URL . '/profile.php/cmd/password'
              ));

              //Getting user's total post/messages.
              //$MYSQL->where('post_user', $this->data['id']);
              //$user_post_count = $MYSQL->get('{prefix}forum_posts');
              $MYSQL->bind('post_user', $this->data['id']);
              $user_post_count = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE post_user = :post_user");
              $user_post_count = number_format(count($user_post_count));

              $mod_report_integer = modReportInteger();

              $TANGO->tpl->addParam(
                  array(
                      'username',
                      'username_style',
                      'user_avatar',
                      'user_post_count',
                      'mod_report_integer'
                  ),
                  array(
                      $this->data['username'],
                      $this->data['username_style'],
                      $this->data['user_avatar'],
                      $user_post_count,
                      $mod_report_integer
                  )
              );

              date_default_timezone_set($this->data['set_timezone']);
              if( $this->data['chosen_theme'] == "0" ) {
                $TANGO->tpl->setTheme($TANGO->data['site_theme']);
              } else {
                $TANGO->tpl->setTheme($this->data['chosen_theme']);
              }
          } else {
              $this->data['permissions'] = array();
              $this->data['user_group']  = '0';
              date_default_timezone_set('US/Central');
              $TANGO->tpl->setTheme($TANGO->data['site_theme']);
          }
      }

      /*
       * Check if session or cookie exists.
       */
      public function check() {
          global $MYSQL;

          if( isset($_SESSION['tangobb_sess']) or isset($_COOKIE['tangobb_sess']) ) {
              $id = (isset($_SESSION['tangobb_sess']))? $_SESSION['tangobb_sess'] : $_COOKIE['tangobb_sess'];
              //$MYSQL->where('session_id', $id);
              //$query = $MYSQL->get('{prefix}sessions');
              $MYSQL->bind('session_id', $id);
              $query = $MYSQL->query("SELECT * FROM {prefix}sessions WHERE session_id = :session_id");
              if( !empty($query) ) {
                  $this->session = $query['0'];
                  return true;
              } else {
                  return false;
              }
          } else {
              return false;
          }
      }

      /*
       * Clear expired sessions.
       */
      public function clear() {
          global $MYSQL;
          $time = strtotime(TANGO_SESSION_TIMEOUT . ' seconds ago');
          $query = $MYSQL->query("SELECT * FROM {prefix}sessions");
          foreach( $query as $s ) {
              if( $s['session_time'] < $time ) {
			          //$data = array($s['id']);
                //$MYSQL->rawQuery("DELETE FROM {prefix}sessions WHERE id = ?", $data);
                $MYSQL->bind('id', $s['id']);
                $MYSQL->query("DELETE FROM {prefix}sessions WHERE id = :id");
              }
          }
      }

      /*
       * Assign session to user.
       * Session Type
       *  - 1 ($_SESSION)
       *  - 2 ($_COOKIE)
       */
      public function assign($email, $remember = false, $facebook = false) {
          global $MYSQL;

          $MYSQL->where('user_email', $email);
          $a         = $MYSQL->get('{prefix}users');
          $MYSQL->where('username', $email);
          $b         = $MYSQL->get('{prefix}users');

          $query     = ( $a )? $a : $b;

          $session_id = randomHexBytes(16);
          $time       = time();

          if( $facebook ) {
              setcookie('tangobb_facebook', true, time()+TANGO_SESSION_TIMEOUT, '/', NULL, isset($_SERVER['HTTPS']), true);
          }

          if( $remember ) {

              $data = array(
                  'session_id' => $session_id,
                  'logged_user' => $query['0']['id'],
                  'session_type' => '2',
                  'session_time' => $time
              );
              try {
                  $MYSQL->insert('{prefix}sessions', $data);
                  return setcookie('tangobb_sess', $session_id, time()+TANGO_SESSION_TIMEOUT, '/', NULL, isset($_SERVER['HTTPS']), true);
              } catch (mysqli_sql_exception $e) {
                  return false;
              }

          } else {

              $data = array(
                  'session_id' => $session_id,
                  'logged_user' => $query['0']['id'],
                  'session_type' => '1',
                  'session_time' => $time
              );
              try {
                  $MYSQL->insert('{prefix}sessions', $data);
                  $_SESSION['tangobb_sess'] = $session_id;
                  return true;
              } catch (mysqli_sql_exception $e) {
                  return false;
              }

          }
      }

      /*
       * Remove session to user.
       */
      public function remove() {
          global $MYSQL;

          if( isset($_SESSION['tangobb_sess']) ) {
              $MYSQL->where('session_id', $_SESSION['tangobb_sess']);
              $MYSQL->delete('{prefix}sessions');
              session_destroy();
          } else {
              $MYSQL->where('session_id', $_COOKIE['tangobb_sess']);
              $MYSQL->delete('{prefix}sessions');
              return setcookie('tangobb_sess', '', time()-3600, '/', NULL, isset($_SERVER['HTTPS']), true);
          }
      }

  }

?>