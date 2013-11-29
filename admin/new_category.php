<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_administration') ) { header('Location: ' . SITE_URL); }//Checks if user has permission to create a thread.
  require_once('template/top.php');
  $notice = '';

  if( isset($_POST['create']) ) {
      try {
          
          foreach( $_POST as $parent => $child ) {
              $_POST[$parent] = clean($child);
          }
          
          NoCSRF::check( 'csrf_token', $_POST, true, 60*10, true );
          
          $title = $_POST['cat_title'];
          $desc  = $_POST['cat_desc'];
          
          if( !$title or !$desc ) {
              throw new Exception ('All fields are required!');
          } else {
              
              $data = array(
                  'category_title' => $title,
                  'category_desc' => $desc
              );
              
              if( $MYSQL->insert('{prefix}forum_category', $data) ) {
                  header('Location: ' . SITE_URL . '/admin/manage_category.php/notice/create_success');
              } else {
                  throw new Exception ('Error creating forum category.');
              }
              
          }
          
      } catch ( Exception $e ) {
          $notice .= $ADMIN->alert(
              $e->getMessage(),
              'danger'
          );
      }
  }

  $token = NoCSRF::generate('csrf_token');

  echo $ADMIN->box(
      'Create Category <p class="pull-right"><a href="' . SITE_URL . '/admin/manage_category.php" class="btn btn-default btn-xs">Back</a></p>',
      $notice .
      '<form action="" method="POST">
         <input type="hidden" name="csrf_token" value="' . $token . '">
         <label for="cat_title">Title</label>
         <input type="text" name="cat_title" id="cat_title" class="form-control" />
         <label for="cat_desc">Description</label>
         <textarea name="cat_desc" id="cat_desc" class="form-control"></textarea>
         <br />
         <input type="submit" name="create" value="Create Category" class="btn btn-default" />
       </form>',
              '',
              '12'
          );  

  require_once('template/bot.php');
?>