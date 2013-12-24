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
          $tz    = $_POST['timezone'];
          
          if( !$email or !$tz ) {
              throw new Exception ($LANG['global_form_process']['all_fields_required']);
          } elseif( !validEmail($email) ) {
              throw new Exception ($LANG['global_form_process']['invalid_email']);
          } else {
              if( $email !== $TANGO->sess->data['user_email'] ) {
                  
                  if( !emailTaken($email) ) {

                      $data  = array(
                          'user_email' => $email,
                          'set_timezone' => $tz
                      );
                      $MYSQL->where('id', $TANGO->sess->data['id']);
                      
                      if( $MYSQL->update('{prefix}users', $data) ) {
                          $notice .= $TANGO->tpl->entity(
                              'success_notice',
                              'content',
                              $LANG['global_form_process']['save_success']
                          );
                      } else {
                          throw new Exception ($LANG['global_form_process']['error_saving']);
                      }
                      
                  } else {
                      throw new Exception ($LANG['global_form_process']['email_used']);
                  }
                  
              } else {

                  $data  = array(
                    'set_timezone' => $tz
                  );
                  $MYSQL->where('id', $TANGO->sess->data['id']);

                  if( $MYSQL->update('{prefix}users', $data) ) {
                    $notice .= $TANGO->tpl->entity(
                      'success_notice',
                      'content',
                      $LANG['global_form_process']['save_success']
                      );
                  } else {
                    throw new Exception ($LANG['global_form_process']['error_saving']);
                  }
                  
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
                 <label for="email">Email</label>
                 <input type="text" name="email" id="email" value="' . $TANGO->sess->data['user_email'] . '" />
                 <br /><br />
                 <input type="submit" name="edit" value="Save Changes" />
               </form>';*/

  $timezones = '<select id="timezone" name="timezone">';
  foreach( timezones() as $timezone => $code ) {
    if( $TANGO->sess->data['set_timezone'] == $code ) {
      $timezones .= '<option value="' . $code . '" selected="selected">' . $timezone . '</option>';
    } else {
      $timezones .= '<option value="' . $code . '">' . $timezone . '</option>';
    }
  }
  $timezones .= '</select>';

  $content .= '<form id="tango_form" action="" method="POST">
                 ' . $FORM->build('hidden', '', 'csrf_token', array('value' => CSRF_TOKEN)) . '
                 ' . $FORM->build('text', 'Email', 'email', array('value' => $TANGO->sess->data['user_email'])) . '
                 <label for="timezone">Timezone</label>
                 ' . $timezones . '
                 <br /><br />
                 ' . $FORM->build('submit', '', 'edit', array('value' => 'Save Changes')) . '
               </form>';

  $content  = $notice . $content;

?>