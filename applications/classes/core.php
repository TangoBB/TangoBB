<?php

  /*
   * Core class of TangoBB
   */
  if( !defined('BASEPATH') ){ die(); }

  class Tango_Core {
      
      public $data;
      public $tpl, $sess, $user, $perm, $bb, $node, $lib_parse;
      
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
      public function user($id) {
          global $MYSQL;
          $MYSQL->where('id', $id);
          $query = $MYSQL->get('{prefix}users');
          
          $MYSQL->where('post_user', $id);
          $query['0']['post_count']     = count($MYSQL->get('{prefix}forum_posts'));
          $query['0']['username_style'] = $this->usergroup($query['0']['user_group'], 'username_style', $query['0']['username']);
                              
          return $query['0'];
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