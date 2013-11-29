<?php

  define('BASEPATH', 'Forum');
  require_once('applications/wrapper.php');

  $TANGO->tpl->getTpl('page');

  $TANGO->tpl->addParam('page_title', '404');
  $TANGO->tpl->addParam('content', 'Sorry, the resource you are looking for could not be found.');

  echo $TANGO->tpl->output();

?>