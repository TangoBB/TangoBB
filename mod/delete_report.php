<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_moderation') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.
  $TANGO->tpl->getTpl('page');

  $content    = '';

  if( $PGET->g('id') ) {
      $MYSQL->bind('id', $PET->g('id'));
      $query = $MYSQL->query("SELECT * FROM {prefix}reports WHERE id = :id");

      if( !empty($query) ) {
        $MYSQL->bind('id', $query['0']['id']);
        $MYSQL->query("DELETE FROM {prefix}reports WHERE id = :id");
        $content .= $TANGO->tpl->entity(
          'success_notice',
          'content',
          $notice
        );

      } else {
          redirect(SITE_URL);
      }

  } else {
      redirect(SITE_URL);
  }

  $TANGO->tpl->addParam(
      array(
          'page_title',
          'content'
      ),
      array(
          $LANG['mod']['delete']['delete'],
          $content
      )
  );

  echo $TANGO->tpl->output();

?>