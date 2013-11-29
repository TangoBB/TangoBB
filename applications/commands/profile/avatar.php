<?php

  /*
   * Profile edit module for TangoBB.
   */
  if( !defined('BASEPATH') ){ die(); }
  if( !$TANGO->sess->isLogged ) { header('Location: ' . SITE_URL . '/404.php'); }//Check if user is logged in.

  $page_title = 'Avatar';
  $content    = '';
  $notice     = '';

  if( isset($_POST['edit']) ) {
      
      try {
          
          $mime  = array(
              'image/png',
              'image/jpeg',
              'image/jpg',
              'image/gif'
          );
          
         if( !$_FILES['avatar'] ) {
             throw new Exception ('All fields are required!');
         } elseif( !in_array($_FILES['avatar']['type'], $mime) ) {
             throw new Exception ('Unidentified file format.');
         } else {
             
             $image   = $_FILES['avatar'];
             $bin_dir = 'public/img/bin/' . $TANGO->sess->data['id'] . '.png';
             //touch($bin_dir);
             copy($image['tmp_name'], $bin_dir);
             list($width, $height, $type, $attr) = getimagesize($bin_dir);
             
             if( $width > 200 && $height > 200 ) {
                 throw new Exception ('Image dimension too big!');
             } else {
                 
                 unlink($bin_dir);
                 $avatar_dir = 'public/img/avatars/' . $TANGO->sess->data['id'] . '.png';
                 if( copy($image['tmp_name'], $avatar_dir) ) {
                     
                     $data = array(
                         'user_avatar' => $TANGO->sess->data['id'] . '.png'
                     );
                     $MYSQL->where('id', $TANGO->sess->data['id']);
                     
                     if( $MYSQL->update('{prefix}users', $data) ) {
                         $notice .= $TANGO->tpl->entity(
                             'success_notice',
                             'content',
                             'Avatar suceessfully saved!'
                         );
                     } else {
                         $notice .= $TANGO->tpl->entity(
                             'success_notice',
                             'content',
                             'Avatar suceessfully saved!'
                         );
                     }
                 } else {
                     throw new Exception ('Error uploading avatar. Try again later. (1)');
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

  $content .= '<form id="tango_form" action="" method="POST" enctype="multipart/form-data">
                 <label for="avatar">Change Avatar <small>A maximum of 200x200 Pixels</small></label>
                 <input type="file" name="avatar" id="avatar" />
                 <br /><br />
                 <input type="submit" name="edit" value="Save Changes" />
               </form>';

  $content  = $notice . $content;

?>