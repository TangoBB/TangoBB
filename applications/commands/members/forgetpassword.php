<?php

  /*
   * Account Activation Module for TangoBB.
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }

  if( $TANGO->sess->isLogged ){ header('Location: ' . SITE_URL); } //If user is logged in.

  $page_title = 'Forget Password';
  $content    = '';
  $notice     = '';

  if( isset($_POST['forget']) ) {
      try {
          
          foreach( $_POST as $parent => $child ) {
              $_POST[$parent] = clean($child);
          }
          
          $email = $_POST['email'];
          
          NoCSRF::check( 'csrf_token', $_POST, true, 60*10, true );

          if( !$email ) {
              throw new Exception ('All fields are required!');
          } elseif( !emailTaken($email) ) {
              throw new Exception ('Email does not exist in our records!');
          } else {
              
              $new_password = randomString(9);
              $enc_password = encrypt($new_password);
              $data         = array(
                  'user_password' => $enc_password
              );
              $MYSQL->where('user_email', $email);
              
              $to      = $email;
              $subject = 'Password Reset';
              $message = 'You have recently reset your password on ' . $TANGO->data['site_name'] . '. Your new password is: ' . $new_password;
              $headers = 'From: ' . $TANGO->data['site_email'] . "\r\n" .
                         'Reply-To: ' . $TANGO->data['site_email'] . "\r\n" .
                         'X-Mailer: PHP/' . phpversion();
              
              if( $MYSQL->update('{prefix}users', $data) ) {
                  mail($to, $subject, $message, $headers);
                  $notice .= $TANGO->tpl->entity(
                      'success_notice',
                      'content',
                      'Your new password has been sent to your email!'
                  );
              } else {
                  throw new Exception ('Error resetting password.');
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

  /*$content .= '<form action="" id="tango_form" method="POST">
                 <label for="email">Email</label>
                 <input type="text" name="email" />
                 <br />
                 <input type="submit" name="forget" value="Reset Password" />
               </form>';*/
  $content .= '<form action="" method="POST" id="tango_form">
                 ' . $FORM->build('hidden', '', 'csrf_token', array('value' => CSRF_TOKEN)) . '
                 ' . $FORM->build('text', 'Email', 'email') . '
                 <br /><br />
                 ' . $FORM->build('submit', '', 'forget', array('value' => 'Reset Password')) . '
               </form>';

  $content = $notice . $content;

?>