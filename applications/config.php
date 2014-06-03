<?php

  if( !defined('BASEPATH') ){ die(); }

  /*
   * Iko Configuration File.
   * Iko (http://iko.im) was previously TangoBB (http://tangobb.net)
   */
  define('MYSQL_HOST', 'localhost');
  define('MYSQL_USERNAME', 'root');
  define('MYSQL_PASSWORD', '');
  define('MYSQL_DATABASE', 'TangoBB');
  define('MYSQL_PREFIX', 'tan_');

  /*
   * TangoBB Local Details
   */
  define('SITE_URL', 'http://127.0.0.1/tangobb');//Without the ending "/"
  define('TANGOBB_VERSION', '1.3.4');
  define('TANGO_SESSION_TIMEOUT', 31536000);//In seconds.
  define('USER_PASSWORD_HASH_COST', 10);

  /*
   * Usergroup Details.
   * DO NOT CHANGE IF YOU DON'T KNOW WHAT THIS WILL DO.
   */
  define('ADMIN_ID', '4');
  define('MOD_ID', '3');
  define('BAN_ID', '2');

  /*
   * Forum Configuration.
   */
  define('THREAD_RESULTS_PER_PAGE', 12);
  define('POST_RESULTS_PER_PAGE', 9);

?>