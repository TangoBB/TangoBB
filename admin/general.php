<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_administration') ) { header('Location: ' . SITE_URL); }//Checks if user has permission to create a thread.
  require_once('template/top.php');
  $notice = '';

  if( isset($_POST['update']) ) {
      try {
          
          foreach( $_POST as $parent => $child ) {
              $_POST[$parent] = clean($child);
          }
          
          NoCSRF::check( 'csrf_token', $_POST, true, 60*10, true );
          
          $site_name   = $_POST['site_name'];
          $board_email = $_POST['board_email'];

          $fb_app_id   = $_POST['fb_app_id'];
          $fb_app_sec  = $_POST['fb_app_secret'];
          $enable_fb   = (isset($_POST['enable_facebook']))? '1' : '0';
          
          if( !$site_name or !$board_email ) {
              throw new Exception ('All fields are required!');
          } else {
              
              $data = array(
                  'site_name' => $site_name,
                  'site_email' => $board_email,
                  'facebook_app_id' => $fb_app_id,
                  'facebook_app_secret' => $fb_app_sec,
                  'facebook_authenticate' => $enable_fb
              );
              $MYSQL->where('id', 1);
              
              if( $MYSQL->update('{prefix}generic', $data) ) {
                  $notice .= $ADMIN->alert(
                      'Informations saved!',
                      'success'
                  );
              } else {
                  throw new Exception ('Error saving information. Try again later.');
              }
              
          }
          
      } catch (Exception $e) {
          $notice .= $ADMIN->alert(
              $e->getMessage(),
              'danger'
          );
      }
  }

  $token = NoCSRF::generate('csrf_token');
     
  echo '<form action="" method="POST">';

  echo $ADMIN->box(
      'General Settings',
      $notice .
      '<input type="hidden" name="csrf_token" value="' . $token . '">
       <label for="site_name">Board Name</label>
       <input type="text" class="form-control" name="site_name" id="site_name" value="' . $TANGO->data['site_name'] . '" />
       <label for="board_email">Board Email</label>
       <input type="text" class="form-control" name="board_email" id="board_email" value="' . $TANGO->data['site_email'] . '" />
       <br />
       <input type="submit" name="update" class="btn btn-default" value="Save Settings" />'
  );
  $fb_check = ($TANGO->data['facebook_authenticate'] == 1)? ' CHECKED' : '';
  echo $ADMIN->box(
      'Facebook Settings',
      'The Facebook application ID and secret are <strong>required</strong> for Facebook Authentication.<br />
       <label for="fb_app_id">Facebook App ID</label>
       <input type="text" name="fb_app_id" id="fb_app_id" class="form-control" value="' . $TANGO->data['facebook_app_id'] . '" />
       <label for="fb_app_secret">Facebook App Secret</label>
       <input type="text" name="fb_app_secret" id="fb_app_secret" class="form-control" value="' . $TANGO->data['facebook_app_secret'] . '" />
       <input type="checkbox" name="enable_facebook" value="1"' . $fb_check . ' /> Enable Facebook Authentication'
  );

  echo '</form>';

  require_once('template/bot.php');

?>