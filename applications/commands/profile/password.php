<?php

  /*
   * Profile edit module for TangoBB.
   */
  if( !defined('BASEPATH') ){ die(); }
  if( !$TANGO->sess->isLogged ) { redirect(SITE_URL . '/404.php'); }//Check if user is logged in.

  $page_title = $LANG['bb']['profile']['password'];
  $content    = '';
  $notice     = '';

  if( isset($_POST['edit']) ) {

      try {

          foreach( $_POST as $parent => $child ) {
              $_POST[$parent] = clean($child);
          }

          NoCSRF::check( 'csrf_token', $_POST );
          $new_password = $_POST['new_password'];
          $con_password = $_POST['current_password'];

          if( !$new_password or !$con_password ) {
              throw new Exception ($LANG['global_form_process']['all_fields_required']);
          }elseif( !userExists($TANGO->sess->data['user_email'], $con_password, false) ) {
              throw new Exception ($LANG['global_form_process']['invalid_password']);
          } else {

              $data = array(
                  'user_password' => encrypt($new_password)
              );
              $MYSQL->where('id', $TANGO->sess->data['id']);

              try {
                  $MYSQL->update('{prefix}users', $data);
                  $notice .= $TANGO->tpl->entity(
                      'success_notice',
                      'content',
                      $LANG['global_form_process']['save_success']
                  );
              } catch (mysqli_sql_exception $e) {
                  throw new Exception ($LANG['bb']['profile']['error_updating_password']);
              }

          }

      } catch( Exception $e ) {
          $notice .= $TANGO->tpl->entity(
              'danger_notice',
              'content',
              $e->getMessage()
          );
      }

  }

  define('CSRF_TOKEN', NoCSRF::generate( 'csrf_token' ));
  //define('CSRF_INPUT', '<input type="hidden" name="csrf_token" value="' . CSRF_TOKEN . '">');

  /*$content .= '<form id="tango_form" action="" method="POST">
                 ' . CSRF_INPUT . '
                 <label for="current_password">Current Password</label>
                 <input type="password" name="current_password" id="current_password" />
                 <label for="new_password">New Password</label>
                 <input type="password" name="new_password" id="new_password" />
                 <br /><br />
                 <input type="submit" name="edit" value="Save Changes" />
               // </form>';*/
  $content .= '<form id="tango_form" action="" method="POST">
                 ' . $FORM->build('hidden', '', 'csrf_token', array('value' => CSRF_TOKEN)) . '
                 ' . $FORM->build('password', $LANG['bb']['profile']['current_password'], 'current_password') . '
                 ' . $FORM->build('password', $LANG['bb']['profile']['new_password'], 'new_password') . '
                 <br /><br />
                 ' . $FORM->build('submit', '', 'edit', array('value' => $LANG['bb']['profile']['form_save'])) . '
               </form>';

  $content  = $notice . $content;

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
    $LANG['bb']['profile']['password'],
    '#',
    true
  );
  $bc      = $TANGO->tpl->breadcrumbs();

  $content = $bc . $content;

?>