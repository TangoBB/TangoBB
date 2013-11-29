<?php

  /*
   * Profile edit module for TangoBB.
   */
  if( !defined('BASEPATH') ){ die(); }
  if( !$TANGO->sess->isLogged ) { header('Location: ' . SITE_URL . '/404.php'); }//Check if user is logged in.

  $page_title = 'Personal Details';
  $content    = '';
  $notice     = '';

  if( isset($_POST['edit']) ) {
      
      try {
          
          foreach( $_POST as $parent => $child ) {
              $_POST[$parent] = clean($child);
          }
          
          NoCSRF::check( 'csrf_token', $_POST, true, 60*10, true );
          $email = $_POST['email'];
          
          if( !$email ) {
              throw new Exception ('All fields are required!');
          } else {
              if( $email !== $TANGO->sess->data['user_email'] ) {
                  
                  if( !emailTaken($email) ) {
                      
                      $data = array(
                          'user_email' => $email
                      );
                      $MYSQL->where('id', $TANGO->sess->data['id']);
                      
                      if( $MYSQL->update('{prefix}users', $data) ) {
                          $notice .= $TANGO->tpl->entity(
                              'success_notice',
                              'content',
                              'Saved!'
                          );
                      } else {
                          throw new Exception ('Error saving. Try again later.');
                      }
                      
                  } else {
                      throw new Exception ('Email is used!');
                  }
                  
              } else {
                  $notice .= $TANGO->tpl->entity(
                      'success_notice',
                      'content',
                      'Saved!'
                  );
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
                 <label for="email">Email</label>
                 <input type="text" name="email" id="email" value="' . $TANGO->sess->data['user_email'] . '" />
                 <br /><br />
                 <input type="submit" name="edit" value="Save Changes" />
               </form>';

  $content  = $notice . $content;

?>