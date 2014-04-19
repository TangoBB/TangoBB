<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_moderation') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.
  $TANGO->tpl->getTpl('page');

  $content    = '';

  if( $PGET->g('id') ) {

      $MYSQL->where('id', $PGET->g('id'));
      $query = $MYSQL->get('{prefix}reports');

      if( !empty($query) ) {

        $MYSQL->where('id', $query['0']['id']);

        if( $MYSQL->delete('{prefix}reports') ) {
          $notice   = str_replace(
            '%url%',
            SITE_URL . '/mod',
            $LANG['mod']['del_report']['report_deleted']
          );
          $content .= $TANGO->tpl->entity(
            'success_notice',
            'content',
            $notice
          );
        } else {
          $notice   = str_replace(
            '%url%',
            SITE_URL . '/mod',
            $LANG['mod']['del_report']['error_deleting']
          );
          $content .= $TANGO->tpl->entity(
            'danger_notice',
            'content',
            $notice
          );
        }

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