<?php

  /*
   * Profile edit module for TangoBB.
   */
  if( !defined('BASEPATH') ){ die(); }
  if( !$TANGO->sess->isLogged ) { header('Location: ' . SITE_URL . '/404.php'); }//Check if user is logged in.

  $page_title = 'Signature';
  $content    = '';
  $notice     = '';

  if( isset($_POST['edit']) ) {
      
      try {
          
          foreach( $_POST as $parent => $child ) {
              $_POST[$parent] = clean($child);
          }
          
          NoCSRF::check( 'csrf_token', $_POST, true, 60*10, true );
          $sig = $_POST['sig'];
          
          if( !$sig ) {
              throw new Exception ('All fields are required!');
          } else {
              
              $data = array(
                  'user_signature' => $sig
              );
              $MYSQL->where('id', $TANGO->sess->data['id']);
              
              if( $MYSQL->update('{prefix}users', $data) ) {
                  $notice .= $TANGO->tpl->entity(
                      'success_notice',
                      'content',
                      'Saved!'
                  );
              } else {
                  throw new Exception ('Error updating signature. Try again later.');
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
                 ' . $FORM->build('submit', '', 'edit', array('value' => 'Save Changes')) . '
               </form>';

  $content  = $notice . $content;

?>