<?php

  /*
   * Register Module for TangoBB.
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }

  if( $TANGO->sess->isLogged ){ header('Location: ' . SITE_URL); } //If user is logged in.

  $notice = '';
      
        if( isset($_POST['register']) ) {
            try {
                
                foreach( $_POST as $parent => $child ) {
                    $_POST[$parent] = clean($child);
                }
                
                NoCSRF::check('csrf_token', $_POST, true, 60*10, true);//CSRF Checking.
                $username   = $_POST['username'];
                $password   = $_POST['password'];
                $a_password = $_POST['a_password'];
                $email      = $_POST['email'];
                
                $time       = time();
                
                if( !$username or !$password or !$a_password or !$email ) {
                    throw new Exception ($LANG['global_form_process']['all_fields_required']);
                } elseif( $password !== $a_password ) {
                    throw new Exception ($LANG['bb']['members']['password_different']);
                } elseif( usernameExists($username) ) {
                    throw new Exception ($LANG['bb']['members']['username_taken']);
                } elseif( !preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email) ) {
                    throw new Exception ($LANG['global_form_process']['invalid_email']);
                } elseif( emailTaken($email) ) {
                    throw new Exception ($LANG['global_form_process']['email_used']);
                } else {
                    
                    if( $TANGO->data['register_email_activate'] == "1" ) {
                        $data = array(
                            'username' => $username,
                            'user_password' => encrypt($password),
                            'user_email' => $email,
                            'date_joined' => $time,
                            'user_disabled' => 1
                        );
                    } else {
                        $data = array(
                            'username' => $username,
                            'user_password' => encrypt($password),
                            'user_email' => $email,
                            'date_joined' => $time,
                            'user_disabled' => 0
                        );
                    }
                    
                    if( $MYSQL->insert('{prefix}users', $data) ) {
                        
                        $email = 'You have registered on ' . $TANGO->data['site_name'] . '<br />
                                  Click <a href="' . SITE_URL . '/members.php/activate/code/' . $time . '">here</a> to activate your account.';
                        
                        if( $TANGO->data['register_email_activate'] == "1" ) {
                            $MAIL->send('Account Activation', $email, 'Account Activation', $email);
                            $notice .= $TANGO->tpl->entity(
                                'success_notice',
                                'content',
                                $LANG['bb']['members']['register_successful_email']
                            );
                        } else {
                            $MYSQL->where('username', $username);
                            $l_q     = $MYSQL->get('{prefix}users');
                            $TANGO->sess->assign($l_q['0']['user_email'], true);
                            header('refresh:5;url=' . SITE_URL);
                            $notice .= $TANGO->tpl->entity(
                                'success_notice',
                                'content',
                                $LANG['bb']['members']['register_successful']
                            );
                        }
                        
                    } else {
                        throw new Exception ($LANG['bb']['members']['error_register']);
                    }
                    
                }
                
            } catch (Exception $e) {
                $notice .= $TANGO->tpl->entity(
                    'danger_notice',
                    'content',
                    $e->getMessage()
                );
            }
        }
        
        define('CSRF_TOKEN', NoCSRF::generate( 'csrf_token' ));
        //define('CSRF_INPUT', '<input type="hidden" name="csrf_token" value="' . CSRF_TOKEN . '">');
        /*$content = '<form action="" method="POST">
                      ' . $notice . '
                      ' . CSRF_INPUT . '
                      <label for="username">Username</label>
                      <input type="text" name="username" id="username" />
                      <label for="password">Password</label>
                      <input type="password" name="password" id="password" />
                      <label for="a_password">Confirm Password</label>
                      <input type="password" name="a_password" id="a_password" />
                      <label for="email">Email</label>
                      <input type="text" name="email" id="email" /><br /><br />
                      <input type="submit" name="register" value="Register" />
                    </form>';*/
        $content = '<form action="" method="POST">
                      ' . $notice . '
                      ' . $FORM->build('hidden', '', 'csrf_token', array('value' => CSRF_TOKEN)) . '
                      ' . $FORM->build('text', $LANG['bb']['members']['form_username'], 'username') . '
                      ' . $FORM->build('password', $LANG['bb']['members']['form_password'], 'password') . '
                      ' . $FORM->build('password', $LANG['bb']['members']['form_confirm_password'], 'a_password') . '
                      ' . $FORM->build('text', $LANG['bb']['members']['form_email'], 'email') . '
                      <br /><br />
                      ' . $FORM->build('submit', $LANG['bb']['members']['form_register'], 'register', array('value' => $LANG['bb']['members']['form_register'])) . '<br />
                      ' . $LANG['bb']['members']['register_message'] . '
                    </form>';

?>