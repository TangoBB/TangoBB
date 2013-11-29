<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_administration') ) { header('Location: ' . SITE_URL); }//Checks if user has permission to create a thread.
  require_once('template/top.php');
  $notice = '';

  function list_permissions_as_checkbox($checked) {
      global $MYSQL;
      $query   = $MYSQL->get('{prefix}permissions');
      $checked = explode(',', $checked);
      $return  = '';
      foreach( $query as $u ) {
          $check   = (in_array($u['id'], $checked) or in_array('*', $checked))? ' checked' : '';
          $return .= '<input type="checkbox" name="permissions[]" value="' . $u['id'] . '"' . $check . '> ' . $u['permission_name'] . '<br />';
      }
      return $return;
  }
  function list_permissions() {
      global $MYSQL;
      $query   = $MYSQL->get('{prefix}permissions');
      $return  = array();
      foreach( $query as $g ) {
          $return[] = $g['id'];
      }
      return $return;
  }

  if( $PGET->g('id') ) {
      
      $id    = clean($PGET->g('id'));
      $MYSQL->where('id', $id);
      $query = $MYSQL->get('{prefix}usergroups');
      
      if( !empty($query) ) {
          
          if( isset($_POST['update']) ) {
              try {
                  
                  NoCSRF::check( 'csrf_token', $_POST, true, 60*10, true );
                  
                  $name   = clean($_POST['g_name']);
                  $style  = $_POST['g_style'];
                  
                  if( list_permissions() == $_POST['permissions'] ) {
                      $permissions = '*';
                  } elseif( empty($_POST['permissions']) ) {
                      $permissions = '0';
                  } else {
                      $permissions = implode(',', $_POST['permissions']);
                  }
                  
                  $permissions = clean($permissions);
                  
                  if( !$name or !$style ) {
                      throw new Exception ('All fields are required!');
                  } else {
                      
                      $data = array(
                          'group_name' => $name,
                          'group_style' => $style,
                          'group_permissions' => $permissions
                      );
                      $MYSQL->where('id', $id);
                      
                      if( $MYSQL->update('{prefix}usergroups', $data) ) {
                          header('Location: ' . SITE_URL . '/admin/usergroups.php/notice/edit_success');
                      } else {
                          throw new Exception ('Error updating usergroup.');
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
              'Edit Usergroup (' . $query['0']['group_name'] . ') <p class="pull-right"><a href="' . SITE_URL . '/admin/usergroups.php" class="btn btn-default btn-xs">Back</a></p>',
              $notice .
              '<form action="" method="POST">
                 <input type="hidden" name="csrf_token" value="' . $token . '">
                 <label for="g_name">Name</label>
                 <input type="text" name="g_name" id="g_name" value="' . $query['0']['group_name'] . '" class="form-control" />
                 <label for="g_style">Style <small><code>%username%</code> will be replaced with the user\'s username.</small></label>
                 <textarea name="g_style" id="g_style" class="form-control">' . $query['0']['group_style'] . '</textarea>
                 <label for="permissions">Permissions</label><br />
                 ' . list_permissions_as_checkbox($query['0']['group_permissions']) . '
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