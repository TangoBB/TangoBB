<?php

  if( !defined('BASEPATH') ){ die(); }

  /*
   * Iko Configuration File.
   * Iko (http://iko.im) was previously TangoBB (http://tangobb.net)
   */
  define('MYSQL_HOST', '%mysql_host%');
  define('MYSQL_USERNAME', '%mysql_username%');
  define('MYSQL_PASSWORD', '%mysql_password%');
  define('MYSQL_DATABASE', '%mysql_database%');
  define('MYSQL_PREFIX', '%mysql_prefix%');
  define('MYSQL_PORT', 3306);

  /*
   * Iko Local Details
   */
  define('SITE_URL', '%site_url%');//Without the ending "/"
  define('TANGOBB_VERSION', '1.3.5-A2');
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

  /*
   * Additional Databases.
   * These will be used in order of the first in line to the last.
   * Backup databases for when the main one (MYSQL_HOST) fails.
   * ADDED - 1.3.5-A2
   */
  $ADDITIONAL_DATABASES = array(
    '0' => array(
      'mysql_host' => 'localhost',
      'mysql_username' => 'root',
      'mysql_password' => 'root',
      'mysql_database' => 'iko_2',
      'mysql_prefix' => 'iko_',
      'mysql_port' => 3306
    )
  );
  define('ADDITIONAL_MYSQL_DATABASE', json_encode($ADDITIONAL_DATABASES));

?>