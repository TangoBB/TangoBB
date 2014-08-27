<?php

  /*
   * Terminal Library
   */

  if( !defined('BASEPATH') ){ die(); }

  class Library_Terminal {

      /*
       * Check is command exists.
       */
      public function commandExists($name) {
        global $MYSQL;
        $MYSQL->where('command_name', $name);
        $query = $MYSQL->get('{prefix}terminal');
        if( $query ) {
          return true;
        } else {
          return false;
        }
      }

      /*
       * Create new command.
       * $name - Must be lowercase.
       * $syntax - %s for arguements, example "cugroup %s %s". cugroup must be the same as in $name.
       * $function - Function to be ran when the command is called out. Full function terminal_FUNCTION(). Only FUNCTION() is allowed
       */
      public function create($name, $syntax, $function) {
        if( $this->commandExists($name) ) {
          return false;
        } else {
          global $MYSQL;
          $data = array(
            'command_name' => $name,
            'command_syntax' => $syntax,
            'run_function' => $function
          );
          if( $MYSQL->insert('{prefix}terminal', $data) ) {
            return true;
          } else {
            return false;
          }
        }
      }

      public function delete($name) {
        global $MYSQL;
        if( $this->commandExists($name) ) {
          $MYSQL->where('command_name');
          if( $MYSQL->delete('{prefix}terminal') ) {
            return true;
          } else {
            return false;
          }
        } else {
          return true;
        }
      }

  }

?>