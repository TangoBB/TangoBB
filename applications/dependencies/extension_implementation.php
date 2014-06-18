<?php

  /* 
   * Iko Extension Setup template as a dependency.
   */
  if( !defined('BASEPATH') ){ die(); }

  abstract class Iko_Extensions_Setup {
  	public $extension_name = 'Iko Extension';
  	/* Required methods in order to get extension installed. */
  	abstract public function __construct();
  	abstract public function install();
  	abstract public function uninstall();
  }

?>