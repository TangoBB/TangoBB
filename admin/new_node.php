<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_administration') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.
  require_once('template/top.php');
  $notice = '';

  function list_category() {
      global $MYSQL;
      $query  = $MYSQL->get('{prefix}forum_category');
      $return = '';
      foreach( $query as $s ) {
          $MYSQL->where('node_type', 1);
          $MYSQL->where('in_category', $s['id']);
          $query = $MYSQL->get('{prefix}forum_node');
          $return .= '<option value="' . $s['id'] . '">' . $s['category_title'] . '</option>';
          foreach( $query as $n ) {
            $return .= '<option value="&' . $n['id'] . '">&nbsp;&nbsp;&nbsp;&nbsp;-' . $n['node_name'] . '</option>';
          }
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
          $desc   = (!$_POST['node_desc'])? '' : $_POST['node_desc'];
          $locked = (isset($_POST['lock_node']))? '1' : '0';
          
          if( !$title ) {
              throw new Exception ('All fields are required!');
          } else {
              
              if( substr_count($_POST['node_parent'], '&amp;') > 0 ) {
                $explode = explode('&amp;', $_POST['node_parent']);
                $parent  = node($explode['1']);
                $data = array(
                  'node_name' => $title,
                  'node_desc' => $desc,
                  'name_friendly' => title_friendly($title),
                  'in_category' => $parent['in_category'],
                  'node_type' => 2,
                  'parent_node' => $parent['id']
                );
              } else {
                $data = array(
                  'node_name' => $title,
                  'node_desc' => $desc,
                  'name_friendly' => title_friendly($title),
                  'in_category' => $_POST['node_parent'],
                  'node_type' => 1
                );
              }
              
              if( $MYSQL->insert('{prefix}forum_node', $data) ) {
                  redirect(SITE_URL . '/admin/manage_node.php/notice/create_success');
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
         <label for="parent">Parent</label><br />
         <select name="node_parent" id="parent">
           ' . list_category() . '
         </select>
         <br />
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