<?php

define('BASEPATH', 'Staff');
require_once('../applications/wrapper.php');

if (!$TANGO->perm->check('access_administration')) {
    redirect(SITE_URL);
}//Checks if user has permission to create a thread.
require_once('template/top.php');
$notice = '';

function allowed_usergroups($groups)
{
    global $TANGO, $MYSQL;
    $groups = explode(',', $groups);
    $query = $MYSQL->query('SELECT * FROM {prefix}usergroups');
    $return = '<input type="checkbox" name="allowed_ug[]" value="0" CHECKED /> Guest<br />';
    foreach ($query as $u) {
        if (in_array($u['id'], $groups)) {
            $return .= '<input type="checkbox" name="allowed_ug[]" value="' . $u['id'] . '" CHECKED /> ' . $u['group_name'] . '<br />';
        } else {
            $return .= '<input type="checkbox" name="allowed_ug[]" value="' . $u['id'] . '" /> ' . $u['group_name'] . '<br />';
        }
    }
    return $return;
}

if ($PGET->g('id')) {

    $id = clean($PGET->g('id'));
    $MYSQL->bind('id', $id);
    $query = $MYSQL->query('SELECT * FROM {prefix}forum_category WHERE id = :id');
    /*$MYSQL->where('id', $id);
    $query = $MYSQL->get('{prefix}forum_category');*/

    if (!empty($query)) {

        if (isset($_POST['update'])) {
            try {

                /*foreach( $_POST as $parent => $child ) {
                    $_POST[$parent] = clean($child);
                }*/

                NoCSRF::check('csrf_token', $_POST);

                $title = clean($_POST['cat_title']);
                $desc = (!$_POST['cat_desc']) ? '' : clean($_POST['cat_desc']);

                foreach ($_POST['allowed_ug'] as $ug) {
                    $_POST['allowed_ug'][] = clean($ug);
                }

                $all_u = (isset($_POST['allowed_ug'])) ? implode(',', $_POST['allowed_ug']) : '0';

                if (!$title) {
                    throw new Exception ('All fields are required!');
                } else {

                    /*$data = array(
                        'category_title' => $title,
                        'category_desc' => $desc,
                        'allowed_usergroups' => $all_u
                    );*/
                    $MYSQL->bind('category_title', $title);
                    $MYSQL->bind('category_desc', $desc);
                    $MYSQL->bind('allowed_usergroups', $all_u);
                    $MYSQL->bind('id', $id);
                    //$MYSQL->where('id', $id);

                    try {
                        //$MYSQL->update('{prefix}forum_category', $data);
                        $MYSQL->query('UPDATE {prefix}forum_category SET category_title = :category_title, category_desc = :category_desc, allowed_usergroups = :allowed_usergroups WHERE id = :id');
                        redirect(SITE_URL . '/admin/manage_category.php/notice/edit_success');
                    } catch (mysqli_sql_exception $e) {
                        throw new Exception ('Error updating category.');
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
            'Edit Category (' . $query['0']['category_title'] . ') <p class="pull-right"><a href="' . SITE_URL . '/admin/manage_category.php" class="btn btn-default btn-xs">Back</a></p>',
            $notice .
            '<form action="" method="POST">
                 <input type="hidden" name="csrf_token" value="' . $token . '">
                 <label for="cat_title">Title</label>
                 <input type="text" name="cat_title" id="cat_title" value="' . $query['0']['category_title'] . '" class="form-control" />
                 <label for="cat_desc">Description</label>
                 <textarea name="cat_desc" id="cat_desc" class="form-control">' . $query['0']['category_desc'] . '</textarea>
                 <br />
                 <label for="allowed_usergroups">Allowed Usergroups</label><br />
                 ' . allowed_usergroups($query['0']['allowed_usergroups']) . '
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
