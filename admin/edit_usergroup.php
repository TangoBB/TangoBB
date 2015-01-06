<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_administration') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.
  require_once('template/top.php');
  $notice = '';

  function list_permissions_as_checkbox($checked) {
      global $MYSQL;
      //$query   = $MYSQL->get('{prefix}permissions');
      $query   = $MYSQL->query('SELECT * FROM {prefix}permissions');
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
      //$query   = $MYSQL->get('{prefix}permissions');
      $query   = $MYSQL->query('SELECT * FROM {prefix}permissions');
      $return  = array();
      foreach( $query as $g ) {
          $return[] = $g['id'];
      }
      return $return;
  }

  if( $PGET->g('id') ) {

      $id    = clean($PGET->g('id'));
      /*$MYSQL->where('id', $id);
      $query = $MYSQL->get('{prefix}usergroups');*/
      $MYSQL->bind('id', $id);
      $query = $MYSQL->query('SELECT * FROM {prefix}usergroups WHERE id = :id');

      if( !empty($query) ) {

          if( isset($_POST['update']) ) {
              try {

                  NoCSRF::check( 'csrf_token', $_POST );

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

                      /*$data = array(
                          'group_name' => $name,
                          'group_style' => $style,
                          'group_permissions' => $permissions
                      );
                      $MYSQL->where('id', $id);*/
                      $MYSQL->bindMore(array(
                          'group_name' => $name,
                          'group_style' => $style,
                          'group_permissions' => $permissions,
                          'id' => $id));


                      try {
                          //$MYSQL->update('{prefix}usergroups', $data);
                          $MYSQL->query('UPDATE {prefix}usergroups SET group_name = :group_name, group_style = :group_style, group_permissions = :group_permissions WHERE id = :id');
                          redirect(SITE_URL . '/admin/usergroups.php/notice/edit_success');
                      } catch (mysqli_sql_exception $e) {
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
          $staff_check = ($query['0']['is_staff'] == "1")? ' CHECKED' : '';

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
                 <input type="checkbox" name="is_staff" value="1"' . $staff_check . ' /> This Usergroup is staff.
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