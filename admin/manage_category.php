<?php

define('BASEPATH', 'Staff');
require_once('../applications/wrapper.php');

if (!$TANGO->perm->check('access_administration')) {
    redirect(SITE_URL);
}//Checks if user has permission to create a thread.
require_once('template/top.php');
$notice = '';

/*
 * Additional notice.
 */
if ($PGET->g('notice')) {
    switch ($PGET->g('notice')) {
        case "create_success":
            $notice .= $ADMIN->alert(
                'Forum category has been created!',
                'success'
            );
            break;
        case "edit_success":
            $notice .= $ADMIN->alert(
                'Forum category has been successfully edited!',
                'success'
            );
            break;
    }
}

/*
 * Edit place.
 */
if (isset($_POST['change_place'])) {
    try {

        foreach ($_POST as $parent => $value) {
            $_POST[$parent] = clean($value);
        }

        NoCSRF::check('csrf_token', $_POST);

        $place = $_POST['cat_place'];
        $p_cat = $_POST['cat_id'];

        if (!$place or !$p_cat) {
            throw new Exception ('All fields are required!');
        } else {
            /*$data = array(
                'category_place' => $place
            );
            $MYSQL->where('id', $p_cat);*/
            $MYSQL->bind('id', $p_cat);
            $MYSQL->bind('category_place', $place);
            try {
                //$MYSQL->update('{prefix}forum_category', $data);
                $MYSQL->query('UPDATE {prefix}forum_category SET category_place = :category_place WHERE id = :id');
                $notice .= $ADMIN->alert(
                    'Category place has been updated!',
                    'success'
                );
            } catch (mysqli_sql_exception $e) {
                throw new Exception ('Error updating category place.');
            }
        }

    } catch (Exception $e) {
        $notice .= $ADMIN->alert(
            $e->getMessage(),
            'danger'
        );
    }
}

/*
 * Delete Category.
 */
if ($PGET->g('delete_category')) {
    $d_cat = clean($PGET->g('delete_category'));
    /*$MYSQL->where('id', $d_cat);
    $query = $MYSQL->get('{prefix}forum_category');*/
    $MYSQL->bind('id', $d_cat);
    $query = $MYSQL->query('SELECT * FROM {prefix}forum_category WHERE id = :id');

    if (!empty($query)) {

        //$MYSQL->where('id', $d_cat);
        $MYSQL->bind('id', $d_cat);
        try {
            //$MYSQL->delete('{prefix}forum_category');
            $MYSQL->query('DELETE FROM {prefix}forum_category WHERE id = :id');
            $notice .= $ADMIN->alert(
                'Category <strong>' . $query['0']['category_title'] . '</strong> has been deleted!',
                'success'
            );
        } catch (mysqli_sql_exception $e) {
            $notice .= $ADMIN->alert(
                'Error deleting category.',
                'danger'
            );
        }

    } else {
        $notice .= $ADMIN->alert(
            'Category does not exist!',
            'danger'
        );
    }
}

$query = $MYSQL->query("SELECT * FROM {prefix}forum_category ORDER BY category_place ASC");

$token = NoCSRF::generate('csrf_token');
$categories = '';
foreach ($query as $cat) {
    $categories .= '<tr>
                        <td>
                          <strong>' . $cat['category_title'] . '</strong><br />
                          <small>' . $cat['category_desc'] . '</small>
                        </td>
                        <td>
                          <form action="" method="POST">
                            <input type="hidden" name="csrf_token" value="' . $token . '">
                            <input type="hidden" name="cat_id" value="' . $cat['id'] . '" />
                            <input type="text" class="form-control" name="cat_place" value="' . $cat['category_place'] . '" />
                            <input type="submit" name="change_place" style="display:none;" />
                          </form>
                        </td>
                        <td>
                          <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                              Options <span class="caret"></span>
                            </button>
                            <span class="dropdown-arrow dropdown-arrow-inverse"></span>
                            <ul class="dropdown-menu dropdown-inverse" role="menu">
                              <li><a href="' . SITE_URL . '/admin/edit_category.php/id/' . $cat['id'] . '">Edit Category</a></li>
                              <li><a href="' . SITE_URL . '/admin/manage_category.php/delete_category/' . $cat['id'] . '">Delete Category</a></li>
                            </ul>
                          </div>
                        </td>
                      </tr>';
}

echo $ADMIN->box(
    'Forum Categories <p class="pull-right"><a href="' . SITE_URL . '/admin/new_category.php" class="btn btn-default btn-xs">New Category</a></p>',
    $notice .
    'You can manage the forum categories here.',
    '<table class="table table-hover">
         <thead>
           <tr>
              <th style="width:70%">Category</th>
              <th style="width:10%">Order</th>
              <th style="width:20%">Controls</th>
            </tr>
         </thead>
         <tbody>
           ' . $categories . '
        </tbody>
       </table>',
    '12'
);

require_once('template/bot.php');
?>