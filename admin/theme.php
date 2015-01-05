<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_administration') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.
  require_once('template/top.php');
  $notice = '';

  $directory = scandir('../public/themes');
  unset($directory['0']); unset($directory['1']); //unset($directory['2']);//Remove ".", ".." and "index.html"

  if( $PGET->g('set_default') ) {
      $default = clean($PGET->g('set_default'));
      if( in_array($default, $directory) ) {

          $data = array(
              'site_theme' => $default
          );
          $MYSQL->where('id', 1);

          try {
              $MYSQL->update('{prefix}generic', $data);
              $notice .= $ADMIN->alert(
                  'Theme <strong>' . $default . '</strong> has been set as default!',
                  'success'
              );
          } catch (mysqli_sql_exception $e) {
              $notice .= $ADMIN->alert(
                  'Error setting theme as default.',
                  'danger'
              );
          }

      } else {
          $notice .= $ADMIN->alert(
              'Theme does not exist.',
              'danger'
          );
      }
  }

  if( $PGET->g('delete_theme') ) {
      $theme = clean($PGET->g('delete_theme'));
      if( in_array($theme, $directory) ) {

          if( rrmdir('../public/themes/' . $theme) ) {
              $notice .= $ADMIN->alert(
                  'Theme <strong>' . $theme . '</strong> has been deleted!',
                  'success'
              );
          } else {
              $notice .= $ADMIN->alert(
                  'Error deleting theme.',
                  'danger'
              );
          }

      } else {
          $notice .= $ADMIN->alert(
              'Theme does not exist.',
              'danger'
          );
      }
  }

  /*
   * List a theme.
   */
  $themes = '';
  foreach( $directory as $t ) {
      if( is_dir('../public/themes/' . $t) ) {
          $set     = ($TANGO->data['site_theme'] == $t)? ' class="success" title="Theme is currently set to default."' : '';
          $themes .= '<tr' . $set . '>
                        <td>' . $t . '</td>
                        <td>
                          <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                              Options <span class="caret"></span>
                            </button>
                            <span class="dropdown-arrow dropdown-arrow-inverse"></span>
                            <ul class="dropdown-menu dropdown-inverse" role="menu">
                              <li><a href="' . SITE_URL . '/admin/theme.php/set_default/' . $t . '">Set as Default</a></li>
                              <li><a href="' . SITE_URL . '/admin/theme.php/delete_theme/' . $t . '">Delete Theme</a></li>
                            </ul>
                          </div>
                        </td>
                      </tr>';
      }
  }

  echo $ADMIN->box(
      'Themes',
      $notice .
      'You can manage the theme for your forum here. You can customize the template files for the theme in <code>public/themes/&lt;theme&gt;</code>.',
      '<table class="table table-hover">
         <thead>
           <tr>
              <th style="width:80%;">Theme</th>
              <th style="width:20%;">Controls</th>
            </tr>
         </thead>
         <tbody>
           ' . $themes . '
        </tbody>
       </table>',
      '12'
  );

  require_once('template/bot.php');
?>