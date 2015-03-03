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
                'Forum node has been created!',
                'success'
            );
            break;
        case "edit_success":
            $notice .= $ADMIN->alert(
                'Forum node has been successfully edited!',
                'success'
            );
            break;
    }
}

/*
 * Toggle Lock
 */
if ($PGET->g('toggle_lock')) {
    $id = clean($PGET->g('toggle_lock'));
    /*$MYSQL->where('id', $id);
    $query = $MYSQL->get('{prefix}forum_node');*/
    $MYSQL->bind('id', $id);
    $query = $MYSQL->query('SELECT * FROM {prefix}forum_node WHERE id = :id');
    $lock = ($query['0']['node_locked'] == 1) ? '0' : '1';
    /*$data  = array(
        'node_locked' => $lock
    );*/
    $MYSQL->bind('id', $id);
    $MYSQL->bind('node_locked', $lock);
    try {
        //$MYSQL->update('{prefix}forum_node', $data);
        $MYSQL->query('UPDATE {prefix}forum_node SET node_locked = :node_locked WHERE id = :id');
        $notice .= $ADMIN->alert(
            'Lock has successfully been toggled on node <strong>' . $query['0']['node_name'] . '</strong>.',
            'success'
        );
    } catch (mysqli_sql_exception $e) {
        $notice .= $ADMIN->alert(
            'Error toggling lock on node <strong>' . $query['0']['node_name'] . '</strong>.',
            'danger'
        );
    }
}

/*
 * Delete Node
 */
if ($PGET->g('delete_node')) {
    $d_node = $PGET->g('delete_node');
    /*$MYSQL->where('id', $d_node);
    $query  = $MYSQL->get('{prefix}forum_node');*/
    $MYSQL->bind('id', $d_node);
    $query = $MYSQL->query('SELECT * FROM {prefix}forum_node WHERE id = :id');

    if (!empty($query)) {

        //$MYSQL->where('id', $d_node);
        $MYSQL->bind('id', $d_node);
        try {
            //$MYSQL->delete('{prefix}forum_node');
            $MYSQL->query('DELETE FROM {prefix}forum_node WHERE id = :id');
            $notice .= $ADMIN->alert(
                'Node <strong>' . $query['0']['node_name'] . '</strong> has been deleted!',
                'success'
            );
        } catch (mysqli_sql_exception $e) {
            $notice .= $ADMIN->alert(
                'Error deleting node.',
                'danger'
            );
        }

    } else {
        $notice .= $ADMIN->alert(
            'Node does not exist!',
            'danger'
        );
    }
}

/*
 * Increase sub-node order.
 */
if( $PGET->g('increase_order') ) {
    $MYSQL->bind('id', $PGET->g('increase_order'));
    $query = $MYSQL->query("SELECT * FROM {prefix}forum_node WHERE id = :id");

    if( !empty($query) ) {

        $place = $query['0']['node_place'] - 1;

        $MYSQL->bind('place', $query['0']['node_place']);
        $MYSQL->bind('old_place', $place);
        $u_1 = $MYSQL->query("UPDATE {prefix}forum_node SET node_place = :place WHERE node_place = :old_place");

        $MYSQL->bind('new_place', $place);
        $MYSQL->bind('node', $query['0']['id']);
        $u_2 = $MYSQL->query("UPDATE {prefix}forum_node SET node_place = :new_place WHERE id = :node");

        if( $u_1 && $u_2 ) {
            $notice .= $ADMIN->alert(
                'Node place has been updated.',
                'success'
            );
        } else {
            $notice .= $ADMIN->alert(
                'Error updating the place of node!',
                'danger'
            );
        }

    } else {
        $notice .= $ADMIN->alert(
            'Node does not exist!',
            'danger'
        );
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

        $place = $_POST['node_place'];
        $p_node = $_POST['node_id'];

        if (!$place or !$p_node) {
            throw new Exception ('All fields are required!');
        } else {
            /*$data = array(
                'node_place' => $place
            );*/
            //$MYSQL->where('id', $p_node);
            $MYSQL->bind('id', $p_node);
            $MYSQL->bind('node_place', $place);
            try {
                //$MYSQL->update('{prefix}forum_node', $data);
                $MYSQL->query('UPDATE {prefix}forum_node SET node_place = :node_place WHERE id = :id');
                $notice .= $ADMIN->alert(
                    'Node place has been updated!',
                    'success'
                );
            } catch (mysqli_sql_exception $e) {
                throw new Exception ('Error updating node place.');
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

function list_manage_node($category)
{
    global $MYSQL, $token;
    /*$data = array($category);
    $query = $MYSQL->rawQuery("SELECT * FROM
                            {prefix}forum_node
                            WHERE
                            in_category = ?
                            AND
                            node_type = 1
                            ORDER BY
                            node_place", $data);*/
    $MYSQL->bind('in_category', $category);
    $query = $MYSQL->query("SELECT * FROM {prefix}forum_node WHERE in_category = :in_category AND node_type = 1 ORDER BY node_place");
    $return = '';
    foreach ($query as $n) {

        /*$MYSQL->where('parent_node', $n['id']);
        $MYSQL->where('node_type', 2);
        $s_q     = $MYSQL->get('{prefix}forum_node');*/
        $MYSQL->bind('parent_node', $n['id']);
        $s_q = $MYSQL->query('SELECT * FROM {prefix}forum_node WHERE parent_node = :parent_node AND node_type = 2 ORDER BY node_place ASC');
        $s_q_a = array();
        foreach ($s_q as $s_f) {
            $locked = ($s_f['node_locked'] == 1) ? ' class="text-danger" title="Node Locked"' : '';
            $s_q_a[] = '<a href="' . SITE_URL . '/node.php/' . $s_f['name_friendly'] . '.' . $s_f['id'] . '" target="_blank"' . $locked . '>
                          ' . $s_f['node_name'] . '
                          (<a href="' . SITE_URL . '/admin/edit_node.php/id/' . $s_f['id'] . '" title="Edit (' . $s_f['node_name'] . ')"><i class="glyphicon glyphicon-edit"></i></a>)
                          (<a href="' . SITE_URL . '/admin/manage_node.php/delete_node/' . $s_f['id'] . '" title="Delete (' . $s_f['node_name'] . ')"><i class="glyphicon glyphicon-trash"></i></a>)
                          (<a href="' . SITE_URL . '/admin/manage_node.php/toggle_lock/' . $s_f['id'] . '" title="Toggle Lock (' . $s_f['node_name'] . ')"><i class="glyphicon glyphicon-warning-sign"></i></a>)
                        </a>
                        (<a href="' . SITE_URL . '/admin/manage_node.php/increase_order/' . $s_f['id'] . '" title="Increase Sub-Node Order (' . $s_f['node_name'] . ')"><i class="fa fa-level-up"></i></a>)';
        }

        $locked = ($n['node_locked'] == "1") ? ' style="border-left:2px solid #e84040;" title="Node is locked."' : '';
        $return .= '<tr' . $locked . '>
                        <td>
                          <strong><a href="' . SITE_URL . '/node.php/' . $n['name_friendly'] . '.' . $n['id'] . '" target="_blank">' . $n['node_name'] . '</a></strong><br />
                          <small>' . $n['node_desc'] . '</small><br />
                          <small>Sub-Forums: ' . implode(',', $s_q_a) . '</small>
                        </td>
                        <td>
                          <form action="" method="POST">
                            <input type="hidden" name="csrf_token" value="' . $token . '">
                            <input type="hidden" name="node_id" value="' . $n['id'] . '" />
                            <input type="text" class="form-control" name="node_place" value="' . $n['node_place'] . '" />
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
                              <li><a href="' . SITE_URL . '/admin/edit_node.php/id/' . $n['id'] . '">Edit Node</a></li>
                              <li><a href="' . SITE_URL . '/admin/manage_node.php/delete_node/' . $n['id'] . '">Delete Node</a></li>
                              <li><a href="' . SITE_URL . '/admin/manage_node.php/toggle_lock/' . $n['id'] . '">Toggle Lock</a></li>
                            </ul>
                          </div>
                        </td>
                      </tr>';
    }
    return $return;
}

function list_inactive_nodes()
{
    global $MYSQL, $token;
    $query = $MYSQL->query("SELECT * FROM {prefix}forum_node WHERE in_category = 0 AND node_type = 1 ORDER BY node_place");
    $return = '';
    foreach ($query as $n) {

        /*$MYSQL->where('parent_node', $n['id']);
        $MYSQL->where('node_type', 2);
        $s_q     = $MYSQL->get('{prefix}forum_node');*/
        $MYSQL->bind('parent_node', $n['id']);
        $s_q = $MYSQL->query('SELECT * FROM {prefix}forum_node WHERE parent_node = :parent_node AND node_type = 2');
        $s_q_a = array();
        foreach ($s_q as $s_f) {
            $locked = ($s_f['node_locked'] == 1) ? ' class="text-danger" title="Node Locked"' : '';
            $s_q_a[] = '<a href="' . SITE_URL . '/node.php/' . $s_f['name_friendly'] . '.' . $s_f['id'] . '" target="_blank"' . $locked . '>
                          ' . $s_f['node_name'] . '
                          (<a href="' . SITE_URL . '/admin/edit_node.php/id/' . $s_f['id'] . '" title="Edit (' . $s_f['node_name'] . ')"><i class="glyphicon glyphicon-edit"></i></a>)
                          (<a href="' . SITE_URL . '/admin/manage_node.php/delete_node/' . $s_f['id'] . '" title="Delete (' . $s_f['node_name'] . ')"><i class="glyphicon glyphicon-trash"></i></a>)
                          (<a href="' . SITE_URL . '/admin/manage_node.php/toggle_lock/' . $s_f['id'] . '" title="Toggle Lock (' . $s_f['node_name'] . ')"><i class="glyphicon glyphicon-warning-sign"></i></a>)
                        </a>';
        }

        $locked = ($n['node_locked'] == "1") ? ' style="border-left:2px solid #e84040;" title="Node is locked."' : '';
        $return .= '<tr' . $locked . '>
                        <td>
                          <strong><a href="' . SITE_URL . '/node.php/' . $n['name_friendly'] . '.' . $n['id'] . '" target="_blank">' . $n['node_name'] . '</a></strong><br />
                          <small>' . $n['node_desc'] . '</small><br />
                          <small>Sub-Forums: ' . implode(',', $s_q_a) . '</small>
                        </td>
                        <td>
                          <form action="" method="POST">
                            <input type="hidden" name="csrf_token" value="' . $token . '">
                            <input type="hidden" name="node_id" value="' . $n['id'] . '" />
                            <input type="text" class="form-control" name="node_place" value="' . $n['node_place'] . '" />
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
                              <li><a href="' . SITE_URL . '/admin/edit_node.php/id/' . $n['id'] . '">Edit Node</a></li>
                              <li><a href="' . SITE_URL . '/admin/manage_node.php/delete_node/' . $n['id'] . '">Delete Node</a></li>
                              <li><a href="' . SITE_URL . '/admin/manage_node.php/toggle_lock/' . $n['id'] . '">Toggle Lock</a></li>
                            </ul>
                          </div>
                        </td>
                      </tr>';
    }
    return $return;
}

$query = $MYSQL->query("SELECT * FROM {prefix}forum_category ORDER BY category_place ASC");
$category = '';
foreach ($query as $cat) {
    $category .= $ADMIN->box(
        $cat['category_title'] . '<br /><small>' . $cat['category_desc'] . '</small>',
        '',
        '<table class="table table-hover">
                      <thead>
                        <tr>
                          <th style="width:70%">Node</th>
                          <th style="width:10%">Order</th>
                          <th style="width:20%">Controls</th>
                        </tr>
                      </thead>
                      <tbody>
                        ' . list_manage_node($cat['id']) . '
                      </tbody>
                    </table>',
        '12'
    );
}

echo $ADMIN->box(
    'Forum Nodes <p class="pull-right"><a href="' . SITE_URL . '/admin/new_node.php" class="btn btn-default btn-xs">New Node</a></p>',
    $notice .
    'You can manage the forum nodes here.',
    '',
    '12'
);

echo $ADMIN->box(
    'Inactive Nodes',
    'These are nodes that are not in any category or parent node.',
    '<table class="table table-hover">
       <thead>
         <tr>
           <th style="width:70%">Node</th>
           <th style="width:10%">Order</th>
           <th style="width:20%">Controls</th>
         </tr>
       </thead>
         <tbody>
           ' . list_inactive_nodes() . '
         </tbody>
     </table>',
    '12'
);

echo $category;

require_once('template/bot.php');
?>
