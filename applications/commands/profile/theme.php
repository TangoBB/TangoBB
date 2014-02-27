<?php

  /*
   * Profile edit module for TangoBB.
   */
  if( !defined('BASEPATH') ){ die(); }
  if( !$TANGO->sess->isLogged ) { redirect(SITE_URL . '/404.php'); }//Check if user is logged in.

  $page_title = 'Change Theme';

  if( $PGET->g('set') ) {

    $themes  = listThemes();
    $t_names = array();

    foreach( $themes as $name ) {
      $t_names[] = $name['theme_name'];
    }
    $t_names[] = 'default';

    if( in_array($PGET->g('set'), $t_names) ) {

      $theme = ($PGET->g('set') == "default")? '0' : clean($PGET->g('set'));
      $data  = array(
        'chosen_theme' => $theme
      );
      $MYSQL->where('id', $TANGO->sess->data['id']);

      try {
        $MYSQL->update('{prefix}users', $data);
        $content = $TANGO->tpl->entity(
          'success_notice',
          'content',
          'Theme has been set!'
          );
      } catch (mysqli_sql_exception $e) {
        $content = $TANGO->tpl->entity(
          'danger_notice',
          'content',
          'Error setting theme.'
          );
      }

    } else {
      $content = $TANGO->tpl->entity(
        'danger_notice',
        'content',
        'Theme does not exist!'
      );
    }

  } else {
    redirect(SITE_URL . '/404.php');
  }

?>