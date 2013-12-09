<?php

  define('BASEPATH', 'Forum');
  require_once('applications/wrapper.php');

  $TANGO->tpl->getTpl('members');

  switch( $PGET->g('cmd') ) {
      
      case "register":
        require_once('applications/commands/members/register.php');
        $TANGO->tpl->addParam(
            array(
                'page_title',
                'content'
            ),
            array(
                'Register',
                $content
            )
        );
      break;
      
      case "signin":
        require_once('applications/commands/members/signin.php');
        $TANGO->tpl->addParam(
            array(
                'page_title',
                'content'
            ),
            array(
                'Sign In',
                $content
            )
        );
      break;
      
      case "logout":
        require_once('applications/commands/members/logout.php');
      break;
      
      case "user":
        require_once('applications/commands/members/user.php');
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
      
      case "activate":
        require_once('applications/commands/members/activate.php');
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
      
      case "forgetpassword":
        require_once('applications/commands/members/forgetpassword.php');
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

      case "rules":
        require_once('applications/commands/members/rules.php');
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
        require_once('applications/commands/members/home.php');
        $TANGO->tpl->addParam(
            array(
                'page_title',
                'content'
            ),
            array(
                'Members',
                $content
            )
        );
      break;
      
  }

  echo $TANGO->tpl->output();

?>