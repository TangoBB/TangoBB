<?php

  /*
   * Password Reset Module for TangoBB.
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }

  if( $TANGO->sess->isLogged ){ redirect(SITE_URL); } //If user is logged in.

  $page_title = $LANG['bb']['members']['forgot_password'];
  $content    = '';
  $notice     = '';

  if( isset($_POST['forget']) ) {
      try {

          foreach( $_POST as $parent => $child ) {
              $_POST[$parent] = clean($child);
          }

          $email = $_POST['email'];

          NoCSRF::check( 'csrf_token', $_POST );

          if( !$email ) {
              throw new Exception ($LANG['global_form_process']['all_fields_required']);
          } elseif( !emailTaken($email) ) {
              throw new Exception ($LANG['global_form_process']['email_not_exist']);
          } else {
              $MYSQL->where('user_email', $email);
              $query = $MYSQL->get('{prefix}users');

              // deactivate all previous reset requests
              $data         = array(
                  'user' => $query[0]['id'],
                  'active' => 0,
              );

              $MYSQL->where('user', $query[0]['id']);
              $MYSQL->update('{prefix}password_reset_requests', $data);

              $reset_token = randomHexBytes(16);
              $token_hash = hash('sha256', $reset_token);
              $data         = array(
                  'user' => $query[0]['id'],
                  'reset_token' => $token_hash,
                  'request_time' => time(),
              );

              $successful = false;
              try {
                  $MYSQL->insert('{prefix}password_reset_requests', $data);
                  $to      = $email;
                  $subject = 'Password Reset';
                  $message = 'You have recently requested a password reset on ' . $TANGO->data['site_name'] . '. To set a new password, please use the following URL: ' . SITE_URL . '/members.php/cmd/resetpassword/token/' . urlencode($reset_token);
                  $headers = 'From: ' . $TANGO->data['site_email'] . "\r\n" .
                             'Reply-To: ' . $TANGO->data['site_email'];

                  $successful = mail($to, $subject, $message, $headers);
              } catch (mysqli_sql_exception $e) {
                  $successful = false;
              }

              if ( $successful ) {
                  $notice .= $TANGO->tpl->entity(
                      'success_notice',
                      'content',
                      $LANG['bb']['members']['password_reset_link_sent']
                  );
              } else {
                  throw new Exception ($LANG['bb']['members']['error_request_password_reset']);
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

  $content .= $TANGO->tpl->entity(
    'forget_password_form',
    array(
      'csrf_field',
      'email_field_name',
      'submit_field_name'
    ),
    array(
      $FORM->build('hidden', '', 'csrf_token', array('value' => CSRF_TOKEN)),
      'email',
      'forget'
    )
  );

  $content  = $notice . $content;
