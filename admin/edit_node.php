<?php

define('BASEPATH', 'Staff');
require_once('../applications/wrapper.php');

if (!$TANGO->perm->check('access_administration')) {
    redirect(SITE_URL);
}//Checks if user has permission to create a thread.
require_once('template/top.php');
$notice = '';

function list_category($check)
{
    global $MYSQL;
    //$query  = $MYSQL->get('{prefix}forum_category');
    $query = $MYSQL->query('SELECT * FROM {prefix}forum_category');
    $return = '';
    foreach ($query as $s) {
        /*$MYSQL->where('node_type', 1);
        $MYSQL->where('in_category', $s['id']);
        $query = $MYSQL->get('{prefix}forum_node'); */
        //$check   = ($s['id'] == $check)? ' selected' : '';
        $MYSQL->bind('node_type', 1);
        $MYSQL->bind('in_category', $s['id']);
        $query = $MYSQL->query('SELECT * FROM {prefix}forum_node WHERE node_type = :node_type AND in_category = :in_category');

        $return .= '<option value="' . $s['id'] . '"' . $check . '>' . $s['category_title'] . '</option>';
        foreach ($query as $n) {
            if ($s['id'] !== $check) {
                $check_2 = ('&' . $n['id'] == $check) ? ' checked' : '';
                $return .= '<option value="&' . $n['id'] . '"' . $check . '>&nbsp;&nbsp;&nbsp;&nbsp;-' . $n['node_name'] . '</option>';
            }
        }
    }
    return $return;
}

function allowed_usergroups($groups)
{
    global $TANGO, $MYSQL;
    $groups = explode(',', $groups);
    //$query  = $MYSQL->get('{prefix}usergroups');
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
    /*$MYSQL->where('id', $id);
    $query = $MYSQL->get('{prefix}forum_node');*/
    $MYSQL->bind('id', $id);
    $query = $MYSQL->query('SELECT * FROM {prefix}forum_node WHERE id = :id');

    if (!empty($query)) {

        if (isset($_POST['update'])) {
            try {

                /*foreach( $_POST as $parent => $child ) {
                    $_POST[$parent] = clean($child);
                }*/

                //die($_POST['node_parent']);

                NoCSRF::check('csrf_token', $_POST);

                $title = clean($_POST['node_title']);
                $desc = (!$_POST['node_desc']) ? '' : clean($_POST['node_desc']);
                $locked = (isset($_POST['lock_node'])) ? '1' : '0';

                $all_u = (isset($_POST['allowed_ug'])) ? implode(',', clean($_POST['allowed_ug'])) : '0';

                if (!$title) {
                    throw new Exception ('All fields are required!');
                } else {

                    if (substr_count($_POST['node_parent'], '&') > 0) {
                        $explode = explode('&', clean($_POST['node_parent']));
                        $parent = node($explode['1']);
                        $data = array(
                            'node_name' => $title,
                            'node_desc' => $desc,
                            'name_friendly' => title_friendly($title),
                            'in_category' => $parent['in_category'],
                            'node_type' => 2,
                            'parent_node' => $parent['id'],
                            'allowed_usergroups' => $all_u
                        );
                    } else {
                        /*$data = array(
                          'node_name' => $title,
                          'name_friendly' => title_friendly($title),
                          'node_desc' => $desc,
                          'node_locked' => $locked,
                          'in_category' => clean($_POST['node_parent']),
                          'node_type' => 1,
                          'allowed_usergroups' => $all_u
                        );*/
                        $MYSQL->bind('node_name', $title);
                        $MYSQL->bind('name_friendly', title_friendly($title));
                        $MYSQL->bind('node_desc', $desc);
                        $MYSQL->bind('node_locked', $locked);
                        $MYSQL->bind('in_category', $_POST['node_parent']);
                        $MYSQL->bind('node_type', 1);
                        $MYSQL->bind('allowed_usergroups', $all_u);
                    }

                    //$MYSQL->where('id', $id);
                    $MYSQL->bind('id', $id);

                    try {
                        //$MYSQL->update('{prefix}forum_node', $data);
                        $MYSQL->query('UPDATE {prefix}forum_node SET node_name = :node_name,
                                                                       name_friendly = :name_friendly,
                                                                       node_desc = :node_desc,
                                                                       node_locked = :node_locked,
                                                                       in_category = :in_category,
                                                                       node_type = :node_type,
                                                                       allowed_usergroups = :allowed_usergroups WHERE id = :id');
                        redirect(SITE_URL . '/admin/manage_node.php/notice/edit_success');
                    } catch (mysqli_sql_exception $e) {
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

        if ($query['0']['node_type'] !== 1) {
            $in_c = '&' . $query['0']['parent_node'];
        } else {
            $in_c = $query['0']['in_category'];
        }

        $token = NoCSRF::generate('csrf_token');
        $lock_checked = ($query['0']['node_locked'] == "1") ? ' checked' : '';
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