<?php

  /*
   * User class of TangoBB
   */
  if( !defined('BASEPATH') ){ die(); }

  class Tango_User {
      
      private $user_links;
      
      public function __construct() {
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
      
      
  }

?>