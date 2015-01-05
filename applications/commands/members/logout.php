<?php

  /*
   * Sign Out module for TangoBB
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }

  if( !$TANGO->sess->isLogged ){ redirect(SITE_URL); } //If user is not logged in.

  if( $FB_USER ) {
      $FACEBOOK->destroySession();
      $TANGO->sess->remove();
  } else {
      $TANGO->sess->remove();
  }
  redirect(SITE_URL);

?>