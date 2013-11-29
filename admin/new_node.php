<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_administration') ) { header('Location: ' . SITE_URL); }//Checks if user has permission to create a thread.
  require_once('template/top.php');
  $notice = '';

  function list_category() {
      global $MYSQL;
      $query  = $MYSQL->get('{prefix}forum_category');
      $return = '';
      foreach( $query as $s ) {
          $return .= '<option value="' . $s['id'] . '">' . $s['category_title'] . '</option>';
      }
      return $return;
  }

  if( isset($_POST['create']) ) {
      try {
          
          foreach( $_POST as $parent => $child ) {
              $_POST[$parent] = clean($child);
          }
          
          NoCSRF::check( 'csrf_token', $_POST, true, 60*10, true );
          
          $title  = $_POST['node_title'];
          $desc   = $_POST['node_desc'];
          $locked = (isset($_POST['lock_node']))? '1' : '0';
          
          if( !$title or !$desc ) {
              throw new Exception ('All fields are required!');
          } else {
              
              $data = array(
                  'node_name' => $title,
                  'node_desc' => $desc,
                  'name_friendly' => title_friendly($title),
                  'in_category' => $_POST['node_parent']
              );
              
              if( $MYSQL->insert('{prefix}forum_node', $data) ) {
                  header('Location: ' . SITE_URL . '/admin/manage_node.php/notice/create_success');
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
      'Create Node <p class="pull-right"><a href="' . SITE_URL . '/admin/manage_node.php" class="btn btn-default btn-xs">Back</a></p>',
      $notice .
      '<form action="" method="POST">
         <input type="hidden" name="csrf_token" value="' . $token . '">
         <label for="node_title">Title</label>
         <input type="text" name="node_title" id="node_title" class="form-control" />
         <label for="node_desc">Description</label>
         <textarea name="node_desc" id="node_desc" class="form-control"></textarea>
         <label for="parent">Parent</label>
         <select name="node_parent" id="parent" class="form-control">
           ' . list_category() . '
         </select>
         <label for="additional_option">Additional Options</label><br />
         <input type="checkbox" name="lock_node" value="1" /> Lock Node
         <br />
         <input type="submit" name="create" value="Create Node" class="btn btn-default" />
       </form>',
              '',
              '12'
          );  

  require_once('template/bot.php');
?>