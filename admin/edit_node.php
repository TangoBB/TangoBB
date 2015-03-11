<?php

define('BASEPATH', 'Staff');
require_once('../applications/wrapper.php');

if (!$TANGO->perm->check('access_administration')) {
    redirect(SITE_URL);
}//Checks if user has permission to create a thread.
require_once('template/top.php');
$notice = '';

function list_category($check, $id)
{
    global $MYSQL;
    $query = $MYSQL->query('SELECT * FROM {prefix}forum_category');
    $return = '';
    foreach ($query as $s) {
        $checked = ($s['id'] == $check) ? ' selected' : '';
        $return .= '<option value="' . $s['id'] . '"' . $checked . '>' . $s['category_title'] . '</option>';
        $MYSQL->bind('node_type', 1);
        $MYSQL->bind('in_category', $s['id']);
        $query = $MYSQL->query('SELECT * FROM {prefix}forum_node WHERE node_type = :node_type AND in_category = :in_category');
        foreach ($query as $n) {
            if ($n['id'] != $id) {
                $checked2 = ('&' . $n['id'] == $check) ? ' selected' : '';
                $return .= '<option value="&' . $n['id'] . '"' . $checked2 . '>&nbsp;&nbsp;&nbsp;&nbsp;-' . $n['node_name'] . '</option>';
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
    $return = '<input type="checkbox" name="allowed_ug[]" value="0" id="ug_0" CHECKED /> <label style="font-weight: normal" for="ug_0">Guest</label><br />';
    foreach ($query as $u) {
        if (in_array($u['id'], $groups)) {
            $return .= '<input type="checkbox" name="allowed_ug[]" value="' . $u['id'] . '" id="ug_' . $u['id'] . '" CHECKED /> <label style="font-weight: normal;" for="ug_' . $u['id'] . '" >' . $u['group_name'] . '</label><br />';
        } else {
            $return .= '<input type="checkbox" name="allowed_ug[]" value="' . $u['id'] . '" id="ug_' . $u['id'] . '" /> <label style="font-weight: normal;" for="ug_' . $u['id'] . '" >' . $u['group_name'] . '</label><br />';
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
                NoCSRF::check('csrf_token', $_POST);

                $title = clean($_POST['node_title']);
                $desc = (!$_POST['node_desc']) ? '' : clean($_POST['node_desc']);
                $locked = (isset($_POST['lock_node'])) ? '1' : '0';
                $labels = trim($_POST['labels']);
                $labels = explode(PHP_EOL, $labels);
                $all_u = (isset($_POST['allowed_ug'])) ? implode(',', $_POST['allowed_ug']) : '0';

                if (!$title) {
                    throw new Exception ('All fields are required!');
                } else {
                    if (substr_count($_POST['node_parent'], '&') > 0) {
                        $explode = explode('&', $_POST['node_parent']);
                        $parent = node($explode['1']);
                        $in_category = $parent['in_category'];
                        $node_type = 2;
                        $parent_node = $parent['id'];
                    } else {
                        $in_category = clean($_POST['node_parent']);
                        $node_type = 1;
                        $parent_node = 0;
                    }
                    $MYSQL->bind('node_name', $title);
                    $MYSQL->bind('name_friendly', title_friendly($title));
                    $MYSQL->bind('node_desc', $desc);
                    $MYSQL->bind('node_locked', $locked);
                    $MYSQL->bind('in_category', $in_category);
                    $MYSQL->bind('node_type', $node_type);
                    $MYSQL->bind('parent_node', $parent_node);
                    $MYSQL->bind('allowed_usergroups', $all_u);
                    $MYSQL->bind('id', $id);

                    $u_query = $MYSQL->query('UPDATE {prefix}forum_node SET node_name = :node_name,
                                                                       name_friendly = :name_friendly,
                                                                       node_desc = :node_desc,
                                                                       node_locked = :node_locked,
                                                                       in_category = :in_category,
                                                                       node_type = :node_type,
                                                                       parent_node = :parent_node,
                                                                       allowed_usergroups = :allowed_usergroups
                                                                       WHERE id = :id');
                    $MYSQL->bind('node_id', $id);
                    $MYSQL->query("DELETE FROM {prefix}labels WHERE node_id = :node_id");
                    if (!empty($labels) && $labels[0] != "") {
                        foreach ($labels as $label) {
                            $MYSQL->bind('node_id', $id);
                            $MYSQL->bind('label', $label);
                            $MYSQL->query("INSERT INTO {prefix}labels (node_id, label) VALUES (:node_id, :label)");
                        }
                    }

                    if ($u_query) {
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

        if ($query['0']['node_type'] !== 1) {
            $in_c = '&' . $query['0']['parent_node'];
        } else {
            $in_c = $query['0']['in_category'];
        }
        $node_id = $query['0']['id'];

        $MYSQL->bind('node_id', $query['0']['id']);
        $qry_lbl = $MYSQL->query('SELECT * FROM {prefix}labels WHERE node_id = :node_id');
        $labels = '';
        foreach ($qry_lbl as $label) {
            $labels .= $label['label'];
            $labels .= "\n";
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
                ' . list_category($in_c, $node_id) . '
                </select>
                <br />
                <label for="additional_option">Additional Options</label><br />
                <input type="checkbox" name="lock_node" value="1"' . $lock_checked . ' id="lock_node" /> <label style="font-weight: normal;" for="lock_node">Lock Node</label>
                <br />
                <label for="allowed_usergroups">Allowed Usergroups</label><br />
                ' . allowed_usergroups($query['0']['allowed_usergroups']) . '
                <label for="labels">Labels</label> <small>Each Line is a new label. HTML enabled.</small>
                <textarea name="labels" id="labels" class="form-control">' . $labels . '</textarea><br />
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
