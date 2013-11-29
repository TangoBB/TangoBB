<?php

  /*
   * Session class of TangoBB
   */
  if( !defined('BASEPATH') ){ die(); }

  class Tango_Session {
      
      public $isLogged = false;
      public $data;
      
      public function __construct() {
          global $TANGO, $MYSQL;
          if( $this->check() ) {
              $this->isLogged               = true;
              $user_id                      = ( isset($_SESSION['tangobb_sess']) )? $_SESSION['tangobb_sess'] : $_COOKIE['tangobb_sess'];
                                               
              $this->data                   = $TANGO->user($user_id);
              $this->data['username_style'] = $TANGO->usergroup($this->data['user_group'], 'username_style', $this->data['username']);
              $this->data['permissions']    = $TANGO->usergroup($this->data['user_group'], 'permissions');
              
              //Adding links for users who are logged in and everything else in the template.
              $TANGO->user->addUserLink(array(
                  'Profile' => SITE_URL . '/members.php/cmd/user/',
                  'Personal Details' => SITE_URL . '/profile.php/cmd/edit',
                  'Avatar' => SITE_URL . '/profile.php/cmd/avatar',
                  'Signature' => SITE_URL . '/profile.php/cmd/signature',
                  'Password' => SITE_URL . '/profile.php/cmd/password',
                  'Log Out' => SITE_URL . '/members.php/cmd/logout'
              ));
              
              //Getting user's total post/messages.
              $MYSQL->where('post_user', $this->data['id']);
              $user_post_count = $MYSQL->get('{prefix}forum_posts');
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
                      SITE_URL . '/public/img/avatars/' . $this->data['user_avatar'],
                      $user_post_count,
                      $mod_report_integer
                  )
              );
          } else {
              $this->data['permissions'] = array();
          }
      }
      
      /*
       * Check if session or cookie exists.
       */
      public function check() {
          global $MYSQL;
          
          if( isset($_SESSION['tangobb_sess']) or isset($_COOKIE['tangobb_sess']) ) {
              $id = (isset($_SESSION['tangobb_sess']))? $_SESSION['tangobb_sess'] : $_COOKIE['tangobb_sess'];
              $MYSQL->where('id', $id);
              $query = $MYSQL->get('{prefix}users');
              if( !empty($query) ) {
                  return true;
              } else {
                  return false;
              }
          } else {
              return false;
          }
      }
      
      /*
       * Assign session to user.
       */
      public function assign($email, $remember) {
          global $MYSQL;
          
          $MYSQL->where('user_email', $email);
          $query = $MYSQL->get('{prefix}users');
          
          if( $remember ) {
              return setcookie('tangobb_sess', $query['0']['id'], time()+31536000, '/', NULL, isset($_SERVER['HTTPS']), true);
          } else {
              $_SESSION['tangobb_sess'] = $query['0']['id'];
              return true;
          }
      }
      
      /*
       * Remove session to user.
       */
      public function remove() {
          if( isset($_SESSION['tangobb_sess']) ) {
              session_destroy();
          } else {
              return setcookie('tangobb_sess', '', time()-3600, '/', NULL, isset($_SERVER['HTTPS']), true);
          }
      }
      
  }

?>