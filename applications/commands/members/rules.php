<?php

  /*
   * Account Activation Module for TangoBB.
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }

  $page_title = $LANG['bb']['members']['rules'];
  $content    = '';

  //Breadcrumb
  $TANGO->tpl->addBreadcrumb(
    $LANG['bb']['forum'],
    SITE_URL . '/forum.php'
  );
  $TANGO->tpl->addBreadcrumb(
    $LANG['bb']['members']['home'],
    SITE_URL . '/members.php'
  );
  $TANGO->tpl->addBreadcrumb(
  	$LANG['bb']['members']['rules'],
  	'#',
  	true
  );
  $content .= $TANGO->tpl->breadcrumbs();

  $content   .= str_replace('%rules%', $TANGO->data['site_rules'], nl2br($LANG['bb']['members']['rules_message']));

?>