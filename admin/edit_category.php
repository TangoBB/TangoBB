<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_administration') ) { header('Location: ' . SITE_URL); }//Checks if user has permission to create a thread.
  require_once('template/top.php');
  $notice = '';

  if( $PGET->g('id') ) {
      
      $id    = clean($PGET->g('id'));
      $MYSQL->where('id', $id);
      $query = $MYSQL->get('{prefix}forum_category');
      
      if( !empty($query) ) {
          
          if( isset($_POST['update']) ) {
              try {
                  
                  foreach( $_POST as $parent => $child ) {
                      $_POST[$parent] = clean($child);
                  }
                  
                  NoCSRF::check( 'csrf_token', $_POST, true, 60*10, true );
                  
                  $title = $_POST['cat_title'];
                  $desc  = (!$_POST['cat_desc'])? '' : $_POST['cat_desc'];
                  
                  if( !$title ) {
                      throw new Exception ('All fields are required!');
                  } else {
                      
                      $data = array(
                          'category_title' => $title,
                          'category_desc' => $desc
                      );
                      $MYSQL->where('id', $id);
                      
                      if( $MYSQL->update('{prefix}forum_category', $data) ) {
                          header('Location: ' . SITE_URL . '/admin/manage_category.php/notice/edit_success');
                      } else {
                          throw new Exception ('Error updating category.');
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
              'Edit Category (' . $query['0']['category_title'] . ') <p class="pull-right"><a href="' . SITE_URL . '/admin/manage_category.php" class="btn btn-default btn-xs">Back</a></p>',
              $notice .
              '<form action="" method="POST">
                 <input type="hidden" name="csrf_token" value="' . $token . '">
                 <label for="cat_title">Title</label>
                 <input type="text" name="cat_title" id="cat_title" value="' . $query['0']['category_title'] . '" class="form-control" />
                 <label for="cat_desc">Description</label>
                 <textarea name="cat_desc" id="cat_desc" class="form-control">' . $query['0']['category_desc'] . '</textarea>
                 <br />
                 <input type="submit" name="update" value="Save Changes" class="btn btn-default" />
               </form>',
              '',
              '12'
          );
          
      } else {
          header('Location: ' . SITE_URL . '/admin');
      }
      
  } else {
      header('Location: ' . SITE_URL . '/admin');
  }

  require_once('template/bot.php');
?>