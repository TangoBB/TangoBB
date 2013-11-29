<?php

  /*
   * Terminal Library
   */
 
  if( !defined('BASEPATH') ){ die(); }

  class Library_Terminal {
      
      protected $commands = array();
      
      
      /*
       * Construct.
       * Adding default commands for TangoBB.
       */
      public function __construct() {
      }
      
      /*
       * Adding Commands.
       * Format - http://www.php.net/manual/en/function.sprintf.php
       */
      public function setParent($parent) {
          $this->commands[$cmd] = $result;
      }
      
  }

?>