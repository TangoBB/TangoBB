<?php

  define('BASEPATH', 'Forum');
  require_once('applications/wrapper.php');
  $TANGO->tpl->getTpl('forum');
  $TANGO->tpl->addParam('forum_listings', $TANGO->bb->listings());

  echo $TANGO->tpl->output();

?>