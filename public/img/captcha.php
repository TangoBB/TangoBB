<?php
 
  /*
   * TangoBB Captcha
   * Source: NekoGD (https://github.com/jtpox/NekoGD)
   */
  session_start();

  require_once('../../applications/libraries/nekogd_library.php');
  $gd = new NekoGD();

  $characters = str_split('abcdefghijklmnopqrstuvwxyz'
                          .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                          .'0123456789!@#$%^&*()');
  shuffle($characters);

  $string = '';

  foreach( array_rand($characters, 5) as $k )  {
  	$string .= $characters[$k];
  }

  //Setting font color.
  $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
  $bgc   = array(
  	'#ffffff',
  	'#eeeeee',
  	'#cccccc'
  );
  /* Check if color conflicts with the background color. */
  if( !in_array($color, $bgc) ) {
  	$color = explode('#', $color);
  	$color = $color[1];
  } else {
  	$color = '000000';
  }

  try {
    $gd->config('png', 50, 100, $string);
    $gd->bgc('ffffff');

    $gd->text('../fonts/2.ttf', $color, 20);
    $gd->write($string, '15,30');

    echo $gd->output();
  } catch(Exception $e) {
    die($e->getMessage());
  }

  $_SESSION['TangoBB_Captcha'] = md5($string);
  //die($string . '<br />' . $_SESSION['TangoBB_Captcha']);

?>