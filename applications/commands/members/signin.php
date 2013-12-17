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
              throw new Exception ($LANG['global_form_process']['all_fields_required']);
          } elseif( !userExists($email, $password) ) {
              throw new Exception ($LANG['bb']['members']['invalid_login']);
          }elseif( ($ban = userBanned($email)) !== false ) {
              throw new Exception (
                str_replace(
                  array(
                    '%unban_date%',
                    '%ban_reason%'
                  ),
                  array(
                    date('F j, Y', $ban['unban_time']),
                    $ban['ban_reason']
                  ),
                  $LANG['bb']['members']['banned']
                )
              );
          } elseif( !userActivated($email) ) {
              throw new Exception ($LANG['bb']['members']['email_not_activated']);
          } else {
              
              $remember = (isset($_POST['remember']))? true : false;
              $TANGO->sess->assign($email, $remember);
              header('refresh:5;url=' . SITE_URL);
              
              $content .= $TANGO->tpl->entity(
                  'success_notice',
                  'content',
                  $LANG['bb']['members']['login_success']
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

  /*$form = '<form action="" method="POST">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" />
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" />
                    <br /><br />
                    <input type="submit" name="signin" value="Sign in" />
                    <label>
                      <input type="checkbox" name="remember"> Remember me
                    </label>
                  </form>';*/
  $form    = '<form action="" method="POST">
                ' . $FORM->build('text', 'Email', 'email') . '
                ' . $FORM->build('password', 'Password', 'password') . '
                <br /><br />
                ' . $FORM->build('submit', '', 'signin', array('value' => 'Sign In')) . '
                ' . $FORM->build('checkbox', 'Remember me', 'remember') . '
                <a href="' . SITE_URL . '/members.php/cmd/forgetpassword">Forgot Password</a>
              </form>';
  $content = $content . $form;

?>