<?php

  /*
   * Profile edit module for TangoBB.
   */
  if( !defined('BASEPATH') ){ die(); }
  if( !$TANGO->sess->isLogged ) { redirect(SITE_URL . '/404.php'); }//Check if user is logged in.

  $page_title = $LANG['bb']['profile']['avatar'];
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

          $gravatar = ( isset($_POST['gravatar']) )? '1' : '0';

         if( $gravatar == "1" ) {

             $data = array(
              'avatar_type' => '1'
             );
             $MYSQL->where('id', $TANGO->sess->data['id']);

             try {
                 $MYSQL->update('{prefix}users', $data);
                 $notice .= $TANGO->tpl->entity(
                    'success_notice',
                    'content',
                    $LANG['bb']['profile']['successful_adding_gravatar']
                 );
             } catch (mysqli_sql_exception $e) {
                 throw new Exception ($LANG['bb']['profile']['error_adding_gravatar']);
             }

         } elseif( !$_FILES['avatar'] ) {
             throw new Exception ($LANG['global_form_process']['all_fields_required']);
         } elseif( !in_array($_FILES['avatar']['type'], $mime) ) {
             throw new Exception ($LANG['global_form_process']['invalid_file_format']);
         } else {

             $image   = $_FILES['avatar'];
             $bin_dir = 'public/img/bin/' . $TANGO->sess->data['id'] . '.png';
             //touch($bin_dir);
             copy($image['tmp_name'], $bin_dir);
             list($width, $height, $type, $attr) = getimagesize($bin_dir);

             if( $width > 500 && $height > 500 ) {
                 throw new Exception ($LANG['global_form_process']['img_dimension_limit']);
             } else {

                 unlink($bin_dir);
                 $avatar_dir = 'public/img/avatars/' . $TANGO->sess->data['id'] . '.png';
                 if( copy($image['tmp_name'], $avatar_dir) ) {

                     $data = array(
                         'user_avatar' => $TANGO->sess->data['id'] . '.png',
                         'avatar_type' => '0'
                     );
                     $MYSQL->where('id', $TANGO->sess->data['id']);

                     try {
                         $MYSQL->update('{prefix}users', $data);
                         $notice .= $TANGO->tpl->entity(
                             'success_notice',
                             'content',
                             $LANG['bb']['profile']['successful_upload_avatar']
                         );
                     } catch (mysqli_sql_exception $e) {
                         $notice .= $TANGO->tpl->entity(
                             'success_notice',
                             'content',
                             $LANG['bb']['profile']['successful_upload_avatar']
                         );
                     }
                 } else {
                     throw new Exception ($LANG['bb']['profile']['error_upload_avatar']);
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

  $gravatar_checked = ( $TANGO->sess->data['avatar_type'] == "1" )? ' checked' : '';
  $content .= '<form id="tango_form" action="" method="POST" enctype="multipart/form-data">
                 <label for="avatar">' . $LANG['bb']['profile']['change_avatar'] . '</label>
                 <input type="file" name="avatar" id="avatar" />
                 <br />
                 <input type="checkbox" id="gravatar" name="gravatar" value="1"' . $gravatar_checked . ' /> <label for="gravatar">' . $LANG['bb']['profile']['use_gravatar'] . '</label>
                 <br /><br />
                 <input type="submit" name="edit" value="' . $LANG['bb']['profile']['form_save'] . '" />
               </form>';

  $content  = $notice . $content;

?>