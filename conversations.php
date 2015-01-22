<?php

define('BASEPATH', 'Forum');
require_once('applications/wrapper.php');

if (!$TANGO->sess->isLogged) {
    redirect(SITE_URL);
}

$TANGO->tpl->getTpl('page');

switch ($PGET->g('cmd')) {

    case "view":
        require_once('applications/commands/conversations/view.php');
        $TANGO->tpl->addParam(
            array(
                'page_title',
                'content'
            ),
            array(
                $page_title,
                $content
            )
        );
        break;

    case "new":
        require_once('applications/commands/conversations/new.php');
        $TANGO->tpl->addParam(
            array(
                'page_title',
                'content'
            ),
            array(
                $page_title,
                $content
            )
        );
        break;

    case "reply":
        require_once('applications/commands/conversations/reply.php');
        $TANGO->tpl->addParam(
            array(
                'page_title',
                'content'
            ),
            array(
                $page_title,
                $content
            )
        );
        break;

    case "delete":
        require_once('applications/commands/conversations/delete.php');
        $TANGO->tpl->addParam(
            array(
                'page_title',
                'content'
            ),
            array(
                $page_title,
                $content
            )
        );
        break;

    default:
        require_once('applications/commands/conversations/home.php');
        $TANGO->tpl->addParam(
            array(
                'page_title',
                'content'
            ),
            array(
                $page_title,
                $content
            )
        );
        break;

}

echo $TANGO->tpl->output();

?>