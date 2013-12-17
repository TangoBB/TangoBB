<?php

  /*
   * Account Activation Module for TangoBB.
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }

  $page_title = $LANG['bb']['members']['rules'];

  $content = str_replace('%rules%', $TANGO->data['site_rules'], $LANG['bb']['members']['rules_message']);

?>