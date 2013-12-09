<?php

  /*
   * Account Activation Module for TangoBB.
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }

  if( $TANGO->sess->isLogged ){ header('Location: ' . SITE_URL); } //If user is logged in.

  $page_title = 'Forum Rules';

  $content = 'All users are to abide by the forum rules.<br />' . $TANGO->data['site_rules'] . 'Breaking the rules may result in your post being removed or edited, repeatedly breaking rules may result in a temporary or permanent ban.'

?>