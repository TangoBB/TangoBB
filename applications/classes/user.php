<?php

  /*
   * User class of TangoBB
   */
  if( !defined('BASEPATH') ){ die(); }

  class Tango_User {
      
      private $user_links = array();
      
      public function __construct() {
      }

      /*
       * Change user's usergroup.
       */
      public function changeUserGroup($user, $group) {
        global $MYSQL;
        $MYSQL->where('id', $user);
        $user  = $MYSQL->get('{prefix}users');
        $MYSQL->where('id', $group);
        $group = $MYSQL->get('{prefix}usergroups');

        if( !empty($user) && !empty($group) ) {

          $data = array(
            'user_group' => $group
          );
          $MYSQL->where('id', $user);

          if( $MYSQL->update('{prefix}users', $data) ) {
            return true;
          } else {
            return false;
          }

        } else {
          return false;
        }
      }

      /*
       * Change Username
       */
      public function changeUsername($user, $username) {
        global $MYSQL;
        $MYSQL->where('id', $user);
        $query = $MYSQL->get('{prefix}users');
        if( !empty($query) ) {

          $data = array(
            'username' => $username
          );
          $MYSQL->where('id', $user);
          if( $MYSQL->update('{prefix}users', $data) ) {
            return true;
          } else {
            return false;
          }

        } else {
          return false;
        }
      }
      
      /*
       * Return user links as an array.
       * For template use.
       */
      function userLinks() {
          return $this->user_links;
      }
      
      /*
       * Add link to the user links.
       */
      public function addUserLink($link = array()) {
          foreach( $link as $name => $href ) {
              $this->user_links[$name] = $href;
          }
      }
      
      /*
       * User messages.
       */
      public function userMessages() {
          global $MYSQL, $TANGO;
          $return = array();
          $MYSQL->where('message_receiver', $TANGO->sess->data['id']);
          $MYSQL->where('receiver_viewed', 0);
          $query = $MYSQL->get('{prefix}messages');
          foreach( $query as $msg ) {
              if( $msg['message_type'] == 1 ) {
                $receiver = $TANGO->user($msg['message_receiver']);
                $sender   = $TANGO->user($msg['message_sender']);
                $msg['message_receiver'] = $receiver['username'];
                $msg['message_sender']   = $sender['username'];
                $msg['view_url']         = SITE_URL . '/conversations.php/cmd/view/v/' . $msg['id'];
                $return[] = $msg;
              } else {
                $MYSQL->where('id', $msg['origin_message']);
                $origin = $MYSQL->get('{prefix}messages');
                $receiver = $TANGO->user($msg['message_receiver']);
                $sender   = $TANGO->user($msg['message_sender']);
                $msg['message_receiver'] = $receiver['username'];
                $msg['message_sender']   = $sender['username'];
                $msg['view_url']         = SITE_URL . '/conversations.php/cmd/view/v/' . $origin['0']['id'];
                $return[] = $msg;
              }
          }
          return $return;
      }
      
      
  }

?>