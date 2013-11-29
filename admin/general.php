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
          
          if( !$site_name or !$board_email ) {
              throw new Exception ('All fields are required!');
          } else {
              
              $data = array(
                  'site_name' => $site_name,
                  'site_email' => $board_email
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
     
  echo $ADMIN->box(
      'General Settings',
      $notice .
      '<form action="" method="POST">
         <input type="hidden" name="csrf_token" value="' . $token . '">
         <label for="site_name">Board Name</label>
         <input type="text" class="form-control" name="site_name" id="site_name" value="' . $TANGO->data['site_name'] . '" />
         <label for="board_email">Board Email</label>
         <input type="text" class="form-control" name="board_email" id="board_email" value="' . $TANGO->data['site_email'] . '" />
         <br />
         <input type="submit" name="update" class="btn btn-default" value="Save Settings" />
       </form>'
  );

  require_once('template/bot.php');

?>