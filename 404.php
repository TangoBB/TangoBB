<?php

define('BASEPATH', 'Forum');
require_once('applications/wrapper.php');

$TANGO->tpl->getTpl('page');

$TANGO->tpl->addParam('page_title', $LANG['error_pages']['404']['header']);
$TANGO->tpl->addParam('content', $LANG['error_pages']['404']['message']);

echo $TANGO->tpl->output();

?>