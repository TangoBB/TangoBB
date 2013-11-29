<?php

  /*
   * TangoBB Permissions Library
   */
  if( !defined('BASEPATH') ){ die(); }

  class Library_Permissions {
      
      public function check($permission) {
          global $TANGO;
          if( in_array($permission, $TANGO->sess->data['permissions']) ) {
              return true;
          } else {
              return false;
          }
      }
      
  }

?>