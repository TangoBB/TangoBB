<?php

define('BASEPATH', 'Staff');
require_once('../applications/wrapper.php');

if (!$TANGO->perm->check('access_administration')) {
    redirect(SITE_URL);
}//Checks if user has permission to create a thread.
require_once('template/top.php');
$notice = '';

function list_category()
{
    global $MYSQL;
    $query = $MYSQL->query('SELECT * FROM {prefix}forum_category');
    $return = '';
    foreach ($query as $s) {
        $MYSQL->bind('in_category', $s['id']);
        $sub_qry = $MYSQL->query('SELECT * FROM {prefix}forum_node WHERE in_category = :in_category AND node_type = 1');
        $return .= '<option value="' . $s['id'] . '">' . $s['category_title'] . '</option>';
        foreach ($sub_qry as $n) {
            $return .= '<option value="&' . $n['id'] . '">&nbsp;&nbsp;&nbsp;&nbsp;-' . $n['node_name'] . '</option>';
        }
    }
    return $return;
}

function allowed_usergroups()
{
    global $TANGO, $MYSQL;
    $query = $MYSQL->query('SELECT * FROM {prefix}usergroups');
    $return = '<input type="checkbox" name="allowed_ug[]" value="0" CHECKED id="ug_0" /> <label style="font-weight: normal;" for="ug_0">Guest</label><br />';
    foreach ($query as $u) {
        $check = (BAN_ID != $u['id']) ? ('CHECKED') : ('');
        $return .= '<input type="checkbox" name="allowed_ug[]" value="' . $u['id'] . '" id="ug_' . $u['id'] . '" ' . $check . ' /> <label style="font-weight: normal;" for="ug_' . $u['id'] . '">' . $u['group_name'] . '</label><br />';
    }
    return $return;
}

if (isset($_POST['create'])) {
    try {
        NoCSRF::check('csrf_token', $_POST);

        $title = clean($_POST['node_title']);
        $desc = (!$_POST['node_desc']) ? '' : clean($_POST['node_desc']);
        $locked = (isset($_POST['lock_node'])) ? '1' : '0';
        $labels = trim($_POST['labels']);
        $labels = explode(PHP_EOL, $labels);
        foreach ($_POST['allowed_ug'] as $ug) {
            $_POST['allowed_ug'][] = clean($ug);
        }
        $all_u = (isset($_POST['allowed_ug'])) ? implode(',', $_POST['allowed_ug']) : '0';

        if (!$title) {
            throw new Exception ('Title is required!');
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
                $data = array(
                    'node_name' => $title,
                    'node_desc' => $desc,
                    'name_friendly' => title_friendly($title),
                    'in_category' => $in_category,
                    'node_type' => $node_type,
                    'parent_node' => $parent_node,
                    'allowed_usergroups' => $all_u
                );

                try {
                    $MYSQL->query('INSERT INTO {prefix}forum_node (node_name, node_desc, name_friendly, in_category, node_type, parent_node, allowed_usergroups) VALUES (:node_name, :node_desc, :name_friendly, :in_category, :node_type, :parent_node, :allowed_usergroups)', $data);
                    $query = $MYSQL->query("SELECT LAST_INSERT_ID(id) AS LAST_ID FROM {prefix}forum_node ORDER BY id DESC LIMIT 1");
                    $node_id = $query['0']['LAST_ID'];
                    foreach ($labels as $label) {
                        $MYSQL->bind('node_id', $node_id);
                        $MYSQL->bind('label', $label);
                        $MYSQL->query("INSERT INTO {prefix}labels (node_id, label) VALUES (:node_id, :label)");
                    }
                    redirect(SITE_URL . '/admin/manage_node.php/notice/create_success');
                } catch (mysqli_sql_exception $e) {
                    throw new Exception ('Error creating forum node.');
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
    'Create Node <p class="pull-right"><a href="' . SITE_URL . '/admin/manage_node.php" class="btn btn-default btn-xs">Back</a></p>',
    $notice .
    '<form action="" method="POST">
         <input type="hidden" name="csrf_token" value="' . $token . '">
         <label for="node_title">Title</label>
         <input type="text" name="node_title" id="node_title" class="form-control" />
         <label for="node_desc">Description</label>
         <textarea name="node_desc" id="node_desc" class="form-control"></textarea>
         <label for="parent">Parent</label><br />
         <select name="node_parent" id="parent">
           ' . list_category() . '
         </select>
         <br />
         <label for="additional_option">Additional Options</label><br />
         <input type="checkbox" name="lock_node" value="1" id="lock_node" /> <label style="font-weight: normal;" for="lock_node">Lock Node</label>
         <br />
         <label for="allowed_usergroups">Allowed Usergroups</label>
         <br />
         ' . allowed_usergroups() . '
         <label for="labels">Labels</label> <small>Each Line is a new label. HTML enabled.</small>
         <textarea name="labels" id="labels" class="form-control"></textarea><br />
         <input type="submit" name="create" value="Create Node" class="btn btn-default" />
       </form>',
    '',
    '12'
);

require_once('template/bot.php');
?>
