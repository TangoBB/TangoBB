<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_administration') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.
  require_once('template/top.php');
  $notice = '';

  function list_permissions_as_checkbox() {
      global $MYSQL;
      $query   = $MYSQL->get('{prefix}permissions');
      $return  = '';
      foreach( $query as $u ) {
          $return .= '<input type="checkbox" name="permissions[]" value="' . $u['id'] . '"> ' . $u['permission_name'] . '<br />';
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

  if( isset($_POST['new']) ) {
      try {
          
          NoCSRF::check( 'csrf_token', $_POST, true, 60*10, true );
          $name  = clean($_POST['g_name']);
          $style = $_POST['g_style'];
          
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
              
              if( $MYSQL->insert('{prefix}usergroups', $data) ) {
                  redirect(SITE_URL . '/admin/usergroups.php/notice/create_success');
              } else {
                  throw new Exception ('Error creating usergroup.');
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
       'New Usergroup <p class="pull-right"><a href="' . SITE_URL . '/admin/usergroups.php" class="btn btn-default btn-xs">Back</a></p>',
       $notice .
       '<form action="" method="POST">
          <input type="hidden" name="csrf_token" value="' . $token . '">
          <label for="g_name">Name</label>
          <input type="text" name="g_name" id="g_name"  class="form-control" />
          <label for="g_style">Style <small><code>%username%</code> will be replaced with the user\'s username.</small></label>
          <textarea name="g_style" id="g_style" class="form-control"><span>%username%</span></textarea>
          <label for="permissions">Permissions</label><br />
          ' . list_permissions_as_checkbox() . '
          <br />
          <input type="submit" name="new" value="Create Usergroup" class="btn btn-default" />
        </form>',
              '',
              '12'
          );

  require_once('template/bot.php');
?>