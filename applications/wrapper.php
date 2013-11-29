<?php
  
  if( !defined('Install') ) {
      session_start();
  }
  ob_start();
  if( !defined('BASEPATH') ){ die(); }

  if( BASEPATH == "Staff" ) {
      define('PATH', '../');
      define('PATH_A', '../applications/');
      define('TEMPLATE', '../');
      define('APPLICATION', '');
  } else {
      define('PATH', '');
      define('PATH_A', '');
      define('TEMPLATE', '');
      define('APPLICATION', 'applications/');
  }

  require_once(PATH . 'applications/config.php');

  //Directional paths.
  define('LIB', 'libraries/');
  define('CLA', 'classes/');

  //MySQLi Library
  require_once(PATH_A . LIB . 'mysqli.php');
  $MYSQL = new Mysqlidb(
      MYSQL_HOST,
      MYSQL_USERNAME,
      MYSQL_PASSWORD,
      MYSQL_DATABASE,
      MYSQL_PREFIX
  );
  $USERLINKS = array();

  if( !defined('Install') ) {

  //PermGET Library
  require_once(PATH_A . LIB . 'permget.php');
  $PGET = new Library_PermGET();

  require_once(PATH_A . 'functions.php');
  require_once(PATH_A . 'pagination.php');

  //Classes to run TangoBB
  require_once(PATH_A . CLA . 'core.php');
  $TANGO = new Tango_Core();

  require_once(PATH_A . CLA . 'template.php');
  $TANGO->tpl = new Tango_Template();

  require_once(PATH_A . CLA . 'user.php');
  $TANGO->user = new Tango_User();

  require_once(PATH_A . CLA . 'session.php');
  $TANGO->sess = new Tango_Session();

  require_once(PATH_A . CLA . 'forum.php');
  $TANGO->bb   = new Tango_Forum();

  require_once(PATH_A . CLA . 'node.php');
  $TANGO->node = new Tango_Node();

  //Permissions Library
  require_once(PATH_A . LIB . 'permissions.php');
  $TANGO->perm = new Library_Permissions();

  //BBCode Library
  require_once(PATH_A . APPLICATION . 'bbcode/BbCode.php');
  $TANGO->bb->parser = new BbCode();
  
  require_once(PATH_A . LIB . 'parse.php');
  $TANGO->lib_parse  = new Library_Parse();

  //Mail Library
  require_once(PATH_A . LIB . 'mail.php');
  $MAIL = new Library_Mail();

  require_once(PATH_A . LIB . 'nocsrf.php');

  //Terminal Library
  require_once(PATH_A . LIB . 'terminal.php');
  $TERMINAL = new Library_Terminal();

  //Admin Class
  if( $TANGO->perm->check('access_administration') ) {
      require_once(PATH_A . CLA . 'admin.php');
      $ADMIN = new Tango_Admin();
  }
      
  }

?>