<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_administration') ) { header('Location: ' . SITE_URL); }//Checks if user has permission to create a thread.
  require_once('template/top.php');
  $notice = '';

  function list_category($check) {
      global $MYSQL;
      $query  = $MYSQL->get('{prefix}forum_category');
      $return = '';
      foreach( $query as $s ) {
          $check   = ($s['id'] == $check)? ' checked' : '';
          $return .= '<option value="' . $s['id'] . '"' . $check . '>' . $s['category_title'] . '</option>';
      }
      return $return;
  }

  if( $PGET->g('id') ) {
      
      $id    = clean($PGET->g('id'));
      $MYSQL->where('id', $id);
      $query = $MYSQL->get('{prefix}forum_node');
      
      if( !empty($query) ) {
          
          if( isset($_POST['update']) ) {
              try {
                  
                  foreach( $_POST as $parent => $child ) {
                      $_POST[$parent] = clean($child);
                  }
                  
                  NoCSRF::check( 'csrf_token', $_POST, true, 60*10, true );
                  
                  $title          = $_POST['node_title'];
                  $desc           = $_POST['node_desc'];
                  $locked         = (isset($_POST['lock_node']))? '1' : '0';
                  
                  if( !$title or !$desc ) {
                      throw new Exception ('All fields are required!');
                  } else {
                      
                      $data = array(
                          'node_name' => $title,
                          'name_friendly' => title_friendly($title),
                          'node_desc' => $desc,
                          'node_locked' => $locked,
                          'in_category' => $_POST['node_parent']
                      );
                      $MYSQL->where('id', $id);
                      
                      if( $MYSQL->update('{prefix}forum_node', $data) ) {
                          header('Location: ' . SITE_URL . '/admin/manage_node.php/notice/edit_success');
                      } else {
                          throw new Exception ('Error updating node.');
                      }
                      
                  }
                  
              } catch (Exception $e) {
                  $notice .= $ADMIN->alert(
                      $e->getMessage(),
                      'danger'
                  );
              }
          }
          
          $token        = NoCSRF::generate('csrf_token');
          $lock_checked = ($query['0']['node_locked'] == "1")? ' checked' : '';
          echo $ADMIN->box(
              'Edit Node (' . $query['0']['node_name'] . ') <p class="pull-right"><a href="' . SITE_URL . '/admin/manage_node.php" class="btn btn-default btn-xs">Back</a></p>',
              $notice .
              '<form action="" method="POST">
                 <input type="hidden" name="csrf_token" value="' . $token . '">
                 <label for="cat_title">Title</label>
                 <input type="text" name="node_title" id="cat_title" value="' . $query['0']['node_name'] . '" class="form-control" />
                 <label for="cat_desc">Description</label>
                 <textarea name="node_desc" id="cat_desc" class="form-control">' . $query['0']['node_desc'] . '</textarea>
                 <label for="parent">Parent</label>
                 <select name="node_parent" id="parent" class="form-control">
                   ' . list_category($query['0']['in_category']) . '
                 </select>
                 <label for="additional_option">Additional Options</label><br />
                 <input type="checkbox" name="lock_node" value="1"' . $lock_checked . ' /> Lock Node
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