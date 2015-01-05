<?php

  /*
   * Profile edit module for TangoBB.
   */
  if( !defined('BASEPATH') ){ die(); }
  if( !$TANGO->sess->isLogged ) { redirect(SITE_URL . '/404.php'); }//Check if user is logged in.

  $page_title = 'Change Theme';

  if( $PGET->g('set') ) {

    //Breadcrumbs
    $TANGO->tpl->addBreadcrumb(
      $LANG['bb']['forum'],
      SITE_URL . '/forum.php'
    );
    $TANGO->tpl->addBreadcrumb(
      $LANG['bb']['members']['home'],
      SITE_URL . '/conversations.php'
    );
    $TANGO->tpl->addBreadcrumb(
      $LANG['bb']['profile']['change_theme'],
      '#',
      true
    );
    $content = $TANGO->tpl->breadcrumbs();

    $themes  = listThemes();
    $t_names = array();

    foreach( $themes as $name ) {
      $t_names[] = $name['theme_name'];
    }
    $t_names[] = 'default';

    if( in_array($PGET->g('set'), $t_names) ) {

      $theme = ($PGET->g('set') == "default")? '0' : clean($PGET->g('set'));
      /*$data  = array(
        'chosen_theme' => $theme
      );
      $MYSQL->where('id', $TANGO->sess->data['id']);

      try {
        $MYSQL->update('{prefix}users', $data);
        $content .= $TANGO->tpl->entity(
          'success_notice',
          'content',
          $LANG['bb']['profile']['theme_set']
          );
        header('refresh:3;url=' . SITE_URL . '/forum.php');
      } catch (mysqli_sql_exception $e) {
        $content .= $TANGO->tpl->entity(
          'danger_notice',
          'content',
          $LANG['bb']['profile']['theme_error']
          );
      }*/
      $MYSQL->bindMore(
        array(
          'chosen_theme' => $theme,
          'id' => $TANGO->sess->data['id']
        )
      );

      if( $MYSQL->query("UPDATE {prefix}users SET chosen_theme = :chosen_theme WHERE id = :id") > 0 ) {
        $content .= $TANGO->tpl->entity(
          'success_notice',
          'content',
          $LANG['bb']['profile']['theme_set']
        );
        header('refresh:3;url=' . SITE_URL . '/forum.php');
      } else {
        $content .= $TANGO->tpl->entity(
          'danger_notice',
          'content',
          $LANG['bb']['profile']['theme_error']
        );
      }

    } else {
      $content .= $TANGO->tpl->entity(
        'danger_notice',
        'content',
        $LANG['bb']['profile']['theme_not_exist']
      );
    }

  } else {
    redirect(SITE_URL . '/404.php');
  }

?>