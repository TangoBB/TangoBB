<?php

  /*
   * Profile edit module for TangoBB.
   */
  if( !defined('BASEPATH') ){ die(); }
  if( !$TANGO->sess->isLogged ) { redirect(SITE_URL . '/404.php'); }//Check if user is logged in.

  $page_title = $LANG['bb']['profile']['signature'];
  $content    = '';
  $notice     = '';

  if( isset($_POST['edit']) ) {

      try {

          foreach( $_POST as $parent => $child ) {
              $_POST[$parent] = clean($child);
          }

          NoCSRF::check( 'csrf_token', $_POST );
          $sig = $_POST['sig'];

          if( !$sig ) {
              throw new Exception ($LANG['global_form_process']['all_fields_required']);
          } else {

              $data = array(
                  'user_signature' => $sig
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
                  throw new Exception ($LANG['bb']['profile']['error_updating_signature']);
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
                 ' . $FORM->build('hidden', '', 'csrf_token', array('value' => CSRF_TOKEN)) . '
                 <textarea id="editor" name="sig" style="width:100%;height:300px;max-width:100%;min-width:100%;">' . $TANGO->sess->data['user_signature'] . '</textarea>
                 <br /><br />
                 <input type="submit" name="edit" value="Save Changes" />
               </form>';*/
  $content .= '<form id="tango_form" action="" method="POST">
                 ' . $FORM->build('hidden', '', 'csrf_token', array('value' => CSRF_TOKEN)) . '
                 ' . $FORM->build('textarea', '', 'sig', array('value' => $TANGO->sess->data['user_signature'], 'id' => 'editor', 'style' => 'width:100%;height:300px;max-width:100%;min-width:100%;')) . '
                 <br /><br />
                 ' . $FORM->build('submit', '', 'edit', array('value' => $LANG['bb']['profile']['form_save'])) . '
               </form>';

  $content  = $notice . $content;

?>