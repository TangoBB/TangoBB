<?php

  /*
   * User class of TangoBB
   */
  if( !defined('BASEPATH') ){ die(); }

  class Tango_User {

      private $user_links = array();
      private $notice_type;

      public function __construct() {
        global $LANG;
        $this->notice_type = array(
          'mention',
          'reply',
          'quote'
        );
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

          try {
            $MYSQL->update('{prefix}users', $data);
            return true;
          } catch (mysqli_sql_exception $e) {
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
          try {
            $MYSQL->update('{prefix}users', $data);
            return true;
          } catch (mysqli_sql_exception $e) {
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

      /*
       * Notification 
       */
      public function notifications() {
        global $MYSQL, $TANGO;
        $return = array();
        if( $TANGO->sess->isLogged ) {
          $query = $MYSQL->query("SELECT * FROM {prefix}notifications WHERE user = {$TANGO->sess->data['id']} AND viewed = 0 ORDER BY time_received ASC");
          foreach( $query as $note ) {
              $note['notice_link'] = ($query['0']['notice_link'] == "0")? '#' : $query['0']['notice_link'];
              $return[] = $note;
            }
        } else {
          unset($return);
        }
        return $return;
      }

      public function clearNotification() {
        global $MYSQL, $TANGO;
        $update = array(
          'viewed' => '1'
        );
        $MYSQL->where('user', $TANGO->sess->data['id']);
        $MYSQL->update('{prefix}notifications', $update);
      }

      public function notifyUser($type, $user, $email = false, $extra = array()) {
        global $MYSQL, $TANGO, $LANG, $MAIL;
        if( in_array($type, $this->notice_type) ) {
          switch($type) {
            //Mention notification.
            case "mention":
            $notice = str_replace(
              '%username%',
              $extra['username'],
              $LANG['notification']['mention']
            );
            $insert  = array(
              'notice_content' => $notice,
              'notice_link' => $extra['link'],
              'user' => $user
            );
            break;

            //Reply notification
            case "reply":
            $notice = str_replace(
              array(
                '%username%',
                '%thread_title%'
              ),
              array(
                $extra['username'],
                $extra['thread_title']
              ),
              $LANG['notification']['reply']
            );
            $insert  = array(
              'notice_content' => $notice,
              'notice_link' => $extra['link'],
              'user' => $user
            );
            break;

            //Quote notification.
            case "quote":
            $notice = str_replace(
              array(
                '%username%',
                '%thread_title%'
              ),
              array(
                $extra['username'],
                $extra['thread_title']
              ),
              $LANG['notification']['quoted']
            );
            $insert  = array(
              'notice_content' => $notice,
              'notice_link' => $extra['link'],
              'user' => $user
            );
            break;
          }
        } else {
          //Uncategorized notification.
          $link          = (isset($extra['link']))? $extra['link'] : '';
          $extra['link'] = $link;
          $notice       .= $type;
          $insert        = array(
            'notice_content' => $notice,
            'notice_link' => $link,
            'user' => $user
          );
        }
        if( $MYSQL->insert('{prefix}notifications', $insert) ) {
          $info = str_replace(
            '%url%',
            $extra['link'],
            $LANG['email']['notify']['more_info']
          );
          if( $email ) {
            $user = $TANGO->user($user);
            //Setting up email
            /*$send = $MAIL->setTo($user['user_email'], $user['username'])
                         ->setFrom($TANGO->data['site_email'], $TANGO->data['site_name'])
                         ->setSubject($notice)
                         ->addGenericHeader('X-Mailer', 'PHP/' . phpversion())
                         ->addGenericHeader('Content-Type', 'text/html; charset="utf-8"')
                         ->setMessage($notice . $info)
                         ->send();*/
            $MAIL->to($user['user_email']);
            $MAIL->from($TANGO->data['site_email']);
            $MAIL->subject($notice);
            $MAIL->body($notice . $info);
            if( $MAIL->send() ) {
              return true;
            } else {
              return false;
            }
          } else {
            return true;
          }
        }
      }


  }

?>