<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_administration') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.
  require_once('template/top.php');
  $notice = '';

  function list_category($check) {
      global $MYSQL;
      $query  = $MYSQL->get('{prefix}forum_category');
      $return = '';
      foreach( $query as $s ) {
          $MYSQL->where('node_type', 1);
          $MYSQL->where('in_category', $s['id']);
          $query = $MYSQL->get('{prefix}forum_node');
          //$check   = ($s['id'] == $check)? ' selected' : '';
          $return .= '<option value="' . $s['id'] . '"' . $check . '>' . $s['category_title'] . '</option>';
          foreach( $query as $n ) {
            if( $s['id'] !== $check ) {
              $check_2 = ('&' . $n['id'] == $check)? ' checked' : '';
            $return .= '<option value="&' . $n['id'] . '"' . $check . '>&nbsp;&nbsp;&nbsp;&nbsp;-' . $n['node_name'] . '</option>';
            }
          }
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
                  
                  NoCSRF::check( 'csrf_token', $_POST );
                  
                  $title          = $_POST['node_title'];
                  $desc           = (!$_POST['node_desc'])? '' : $_POST['node_desc'];
                  $locked         = (isset($_POST['lock_node']))? '1' : '0';
                  
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
                        'name_friendly' => title_friendly($title),
                        'node_desc' => $desc,
                        'node_locked' => $locked,
                        'in_category' => $_POST['node_parent'],
                        'node_type' => 1
                      );
                    }
                      
                      $MYSQL->where('id', $id);
                      
                      if( $MYSQL->update('{prefix}forum_node', $data) ) {
                          redirect(SITE_URL . '/admin/manage_node.php/notice/edit_success');
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

          if( $query['0']['node_type'] !== 1 ) {
            $in_c = '&' . $query['0']['parent_node'];
          } else {
            $in_c = $query['0']['in_category'];
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
                 <label for="parent">Parent</label><br />
                 <select name="node_parent" id="parent" style="width:100%;">
                   ' . list_category($in_c) . '
                 </select>
                 <br />
                 <label for="additional_option">Additional Options</label><br />
                 <input type="checkbox" name="lock_node" value="1"' . $lock_checked . ' /> Lock Node
                 <br />
                 <input type="submit" name="update" value="Save Changes" class="btn btn-default" />
               </form>',
              '',
              '12'
          );
          
      } else {
          redirect(SITE_URL . '/admin');
      }
      
  } else {
      redirect(SITE_URL . '/admin');
  }

  require_once('template/bot.php');
?>