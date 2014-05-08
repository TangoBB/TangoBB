<?php

  /*
   * Core class of TangoBB
   */
  if( !defined('BASEPATH') ){ die(); }

  class Tango_Core {
      
      public $data;
      public $tpl, $sess, $user, $perm, $bb, $node, $lib_parse, $captcha;
      
      public function __construct() {
          global $MYSQL;
          
          //Forum generic details.
          $MYSQL->where('id', 1);
          $query = $MYSQL->get('{prefix}generic');
          $this->data = $query['0'];
      }
      
      /*
       * Get every detail from a user using ID.
       */
      public function user($id, $callback = null) {
          global $MYSQL;

          $MYSQL->where('id', $id);
          $query   = $MYSQL->get('{prefix}users');

          $MYSQL->where('username', $id);
          $u_query = $MYSQL->get('{prefix}users');

          $query = (!$query)? $u_query : $query;

          $MYSQL->where('post_user', $query['0']['id']);
          $query['0']['post_count']     = count($MYSQL->get('{prefix}forum_posts'));
          $query['0']['username_style'] = $this->usergroup($query['0']['user_group'], 'username_style', $query['0']['username']);

          if( $query['0']['avatar_type'] == "0" ) {
            $query['0']['user_avatar'] = SITE_URL . '/public/img/avatars/' . $query['0']['user_avatar'];
          } else {
            $query['0']['user_avatar'] = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($query['0']['user_email']))) . "?d=" . urlencode('http://www.somewhere.com/homestar.jpg') . "&s=200";
          }

          if( $query['0']['additional_permissions'] !== "0" ) {
            $a_perm = explode(',', $query['0']['additional_permissions']);
            $n_perm = array();
            foreach( $a_perm as $cp ) {
              $MYSQL->where('id', $cp);
              $p_query = $MYSQL->get('{prefix}permissions');
              if( $p_query ) {
                $n_perm[] = $p_query['0']['permission_name'];
              }
            }
            $query['0']['additional_permissions'] = $n_perm;
          }

          if( is_callable($callback) ) {
            call_user_func($callback, $query['0']);
          } else {
            return $query['0'];
          }
          
      }
      
      /*
       * Checking for the usergroup.
       */
      public function usergroup($group, $result = NULL, $extra_data = NULL) {
          global $MYSQL;
          $MYSQL->where('id', $group);
          $query = $MYSQL->get('{prefix}usergroups');
          
          switch( $result ) {
              case "permissions":
                if( $query['0']['group_permissions'] == "*" ) {
                    $p_query = $MYSQL->get('{prefix}permissions');
                    foreach( $p_query as $p ) {
                        $perms[] = $p['permission_name'];
                    }
                } else {
                    $perm_id = explode(',', $query['0']['group_permissions']);
                    $perms   = array();
                    
                    foreach( $perm_id as $id ) {
                        $MYSQL->where('id', $id);
                        $p_query = $MYSQL->get('{prefix}permissions');
                        $perms[] = $p_query['0']['permission_name'];
                    }
                }
              return $perms;
              break;
              
              case "username_style":
                $username = str_replace('%username%', $extra_data, $query['0']['group_style']);
                return $username;
              break;
              
              default:
                return $query['0'];
              break;
          }
      }
      
      /*
      * Getting in between lines.
      */
      public function getBetween($content, $start, $end) {
          $r = explode($start, $content);
          if( isset($r[1]) ){
              $r = explode($end, $r[1]);
              return $r[0];
          }else{
              return '';
          }
      }
      
  }

?>