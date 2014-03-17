<?php

  session_start();

  ob_start();

  header('Content-Type: text/html;charset=utf8');

  if( !defined('BASEPATH') ){ die(); }

  if( BASEPATH == "Staff" ) {
      define('PATH', '../');
      define('PATH_A', '../applications/');
      define('TEMPLATE', '../');
      define('APPLICATION', '');
  }elseif( BASEPATH == "Extension" ) {
      define('PATH', '../../../');
      define('PATH_A', '../../../applications/');
      define('TEMPLATE', '../../../');
      define('APPLICATION', '../../../applications/');
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

  // password_compat library for legacy PHP versions before PHP 5.5
  require_once(PATH_A . LIB . 'password.php');

  require_once(PATH_A . 'functions.php');
  require_once(PATH_A . 'pagination.php');

  //Using the language package.
  if( !defined('Install') ) {
    $MYSQL->where('id', 1);
    $query   = $MYSQL->get('{prefix}generic');
    switch( BASEPATH ) {
      case "Staff":
        $package = '../applications/languages/' . $query['0']['site_language'] . '.php';
        $default = '../applications/languages/english.php';
      break;
      case "Extension";
        $package = '../../../applications/languages/' . $query['0']['site_language'] . '.php';
        $default = '../../../applications/languages/english.php';
      break;
      default:
        $package = 'applications/languages/' . $query['0']['site_language'] . '.php';
        $default = 'applications/languages/english.php';
      break;
    }
    //die(file_get_contents($package));
    if( file_exists($package) ) {
      require_once($package);
    } else {
      require_once($default);
    }
    //die(var_dump($LANG));
  }

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

  require_once(PATH_A . LIB . 'parse.php');
  $TANGO->lib_parse  = new Library_Parse();

  //Mail Library
  require_once(PATH_A . CLA . 'mail.php');
  $MAIL = new SimpleMail();

  require_once(PATH_A . LIB . 'nocsrf.php');

  //Terminal Library
  require_once(PATH_A . LIB . 'terminal.php');
  $TERMINAL = new Library_Terminal();

  //Form Builder Library
  require_once(PATH_A . LIB . 'form.php');
  $FORM = new Library_FormBuilder();

  //Timezone
  require_once(PATH_A . 'timezone.php');

  //Admin Class
  if( $TANGO->perm->check('access_administration') ) {
      require_once(PATH_A . CLA . 'admin.php');
      $ADMIN = new Tango_Admin();
  }

  $FB_USER = false;
  if( $TANGO->data['facebook_authenticate'] == "1" ) {
      require_once('facebook.php');
  } else {
      if( $TANGO->sess->isLogged ) {
          $TANGO->user->addUserLink(array(
              'Log Out' => SITE_URL . '/members.php/cmd/logout'
          ));
      }
  }

  }

  if( !defined('Install') ) {
    //Including installed extensions.
    include_extensions();
  }

?>