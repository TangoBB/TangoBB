<?php

define('BASEPATH', 'Staff');
require_once('../applications/wrapper.php');

if (!$TANGO->perm->check('access_administration')) {
    redirect(SITE_URL);
}//Checks if user has permission to create a thread.
require_once('template/top.php');
$notice = '';

if ($PGET->g('set_default')) {
    $default = clean($PGET->g('set_default'));

    $MYSQL->bind('id', $default);
    $query = $MYSQL->query("SELECT * FROM {prefix}themes WHERE id = :id");

    if ( $query ) {

        /*$data = array(
            'site_theme' => $default
        );
        $MYSQL->where('id', 1);*/
        $MYSQL->bind('site_theme', $default);

        try {
            //$MYSQL->update('{prefix}generic', $data);
            $MYSQL->query('UPDATE {prefix}generic SET site_theme = :site_theme WHERE id = 1');
            $notice .= $ADMIN->alert(
                'Theme <strong>' . $query['0']['theme_name'] . '</strong> has been set as default!',
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

if ($PGET->g('delete_theme')) {
    $theme = clean($PGET->g('delete_theme'));
    $MYSQL->bind('theme_name', $theme);
    $query = $MYSQL->query("DELETE FROM {prefix}themes WHERE id = :theme_name");

    if ( $query ) {
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
}

/*
 * List a theme.
 *
$themes = '';
foreach ($directory as $t) {
    if (is_dir('../public/themes/' . $t)) {
        $set = ($TANGO->data['site_theme'] == $t) ? ' class="success" title="Theme is currently set to default."' : '';
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
                              <li><a href="' . SITE_URL . '/admin/edit_theme.php/theme/' . $t . '">Edit Theme</a>
                              <li><a href="' . SITE_URL . '/admin/theme.php/delete_theme/' . $t . '">Delete Theme</a></li>
                            </ul>
                          </div>
                        </td>
                      </tr>';
    }
}*/

$query = $MYSQL->query("SELECT * FROM {prefix}themes");

$themes = '';
foreach( $query as $t ) {
    $set = ($TANGO->data['site_theme'] == $t['id']) ? ' class="success" title="Theme is currently set to default."' : '';
    $themes .= '<tr' . $set . '>
                  <td>' . $t['theme_name'] . '</td>
                    <td>
                      <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                          Options <span class="caret"></span>
                        </button>
                        <span class="dropdown-arrow dropdown-arrow-inverse"></span>
                          <ul class="dropdown-menu dropdown-inverse" role="menu">
                            <li><a href="' . SITE_URL . '/admin/theme.php/set_default/' . $t['id'] . '">Set as Default</a></li>
                            <li><a href="' . SITE_URL . '/admin/edit_theme.php/theme/' . $t['id'] . '">Edit Theme</a>
                            <li><a href="' . SITE_URL . '/admin/export_theme.php/theme/' . $t['id'] . '" target="_blank">Export Theme</a>
                            <li><a href="' . SITE_URL . '/admin/theme.php/delete_theme/' . $t['id'] . '">Delete Theme</a></li>
                          </ul>
                        </div>
                      </td>
                  </tr>';
}

echo $ADMIN->box(
    'Themes <span class="pull-right"><a href="' . SITE_URL . '/admin/new_theme.php" class="btn btn-default btn-xs">New Theme</a></span>',
    $notice .
    'You can manage the theme for your forum here.',
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
