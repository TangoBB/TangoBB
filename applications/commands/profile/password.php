<?php

  /*
   * Profile edit module for TangoBB.
   */
  if( !defined('BASEPATH') ){ die(); }
  if( !$TANGO->sess->isLogged ) { header('Location: ' . SITE_URL . '/404.php'); }//Check if user is logged in.

  $page_title = 'Password';
  $content    = '';
  $notice     = '';

  if( isset($_POST['edit']) ) {
      
      try {
          
          foreach( $_POST as $parent => $child ) {
              $_POST[$parent] = clean($child);
          }
          
          NoCSRF::check( 'csrf_token', $_POST, true, 60*10, true );
          $new_password = $_POST['new_password'];
          $con_password = $_POST['current_password'];
          
          if( !$new_password or !$con_password ) {
              throw new Exception ('All fields are required!');
          }elseif( !userExists($TANGO->sess->data['user_email'], $con_password) ) {
              throw new Exception ('Current password is invalid!');
          } else {
              
              $data = array(
                  'user_password' => encrypt($new_password)
              );
              $MYSQL->where('id', $TANGO->sess->data['id']);
              
              if( $MYSQL->update('{prefix}users', $data) ) {
                  $notice .= $TANGO->tpl->entity(
                      'success_notice',
                      'content',
                      'Saved!'
                  );
              } else {
                  throw new Exception ('Error updating password. Try again later.');
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
  define('CSRF_INPUT', '<input type="hidden" name="csrf_token" value="' . CSRF_TOKEN . '">');

  $content .= '<form id="tango_form" action="" method="POST">
                 ' . CSRF_INPUT . '
                 <label for="current_password">Current Password</label>
                 <input type="password" name="current_password" id="current_password" />
                 <label for="new_password">New Password</label>
                 <input type="password" name="new_password" id="new_password" />
                 <br /><br />
                 <input type="submit" name="edit" value="Save Changes" />
               </form>';

  $content  = $notice . $content;

?>