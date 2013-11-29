<?php

  /*
   * Sign In module for TangoBB
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }

  if( $TANGO->sess->isLogged ){ header('Location: ' . SITE_URL); } //If user is logged in.
  $content = '';

  if( isset($_POST['signin']) ) {
      
      $notice  = '';
      
      try {
          
          foreach( $_POST as $parent => $child ) {
              $_POST[$parent] = clean($child);
          }
          
          $email    = $_POST['email'];
          $password = $_POST['password'];
          
          if( !$email or !$password ) {
              throw new Exception ('All fields are required!');
          } elseif( !userExists($email, $password) ) {
              throw new Exception ('Invalid details!');
          }elseif( ($ban = userBanned($email)) !== false ) {
              throw new Exception ('You are currently banned. Contact staff for details.<br />Unban Date: <b>' . date('F j, Y', $ban['unban_time']) . '</b><br />Ban Reason: <b>' . $ban['ban_reason'] . '</b>');
          } elseif( !userActivated($email) ) {
              throw new Exception ('Your email has not been activated yet.');
          } else {
              
              $remember = (isset($_POST['remember']))? true : false;
              $TANGO->sess->assign($email, $remember);
              header('refresh:5;url=' . SITE_URL);
              
              $content .= $TANGO->tpl->entity(
                  'success_notice',
                  'content',
                  'Successfully logged in! Click <a href="' . SITE_URL . '">here</a> if the page does not redirect you.'
              );
              
          }
          
      } catch( Exception $e ) {
          $content .= $TANGO->tpl->entity(
              'danger_notice',
              'content',
              $e->getMessage()
          );
      }
      
  }

  $form = '<form action="" method="POST">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" />
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" />
                    <br /><br />
                    <input type="submit" name="signin" value="Sign in" />
                    <label>
                      <input type="checkbox" name="remember"> Remember me
                    </label>
                  </form>';
  $content = $content . $form;

?>