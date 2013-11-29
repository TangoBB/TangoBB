<?php

  define('BASEPATH', 'Forum');
  require_once('applications/wrapper.php');
  if( !$TANGO->sess->isLogged ) { header('Location: ' . SITE_URL . '/404.php'); }//Check if user is logged in.

  $TANGO->tpl->getTpl('members');

  switch( $PGET->g('cmd') ) {
      
      case "edit":
        require_once('applications/commands/profile/edit.php');
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
      
      case "avatar":
        require_once('applications/commands/profile/avatar.php');
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
      
      case "signature":
        require_once('applications/commands/profile/signature.php');
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
      
      case "password":
        require_once('applications/commands/profile/password.php');
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
        header('Location: ' . SITE_URL . '/404.php');
      break;
      
  }

  echo $TANGO->tpl->output();

?>