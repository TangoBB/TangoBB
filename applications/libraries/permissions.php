<?php

/*
 * TangoBB Permissions Library
 */
if (!defined('BASEPATH')) {
    die();
}

class Library_Permissions
{

    /*
     * Checks if the user has a certain permission.
     * SUCCESS - return true;
     * FAILURE - return false;
     */
    public function check($permission)
    {
        global $TANGO;
        if (in_array($permission, $TANGO->sess->data['permissions'])) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Created a function.
     * Only available for developers.
     * Feature not available to create in ACP.
     * SUCCESS - return true;
     * FAILURE - return false;
     * $name will be converted to lowercase.
     */
    public function create($name)
    {
        global $MYSQL;
        $name = strtolower($name);
        //$MYSQL->where('permission_name', $name);
        //$query = $MYSQL->get('{prefix}permissions');
        $MYSQL->bind('permission_name', $name);
        $query = $MYSQL->query("SELECT * FROM {prefix}permissions WHERE permission_name = :permission_name");

        if (empty($query)) {
            /*$data = array(
              'permission_name' => $name
            );
            try {
                $MYSQL->insert('{prefix}permissions', $data);
              return true;
            } catch (mysqli_sql_exception $e) {
              return false;
            }*/
            $MYSQL->bind('permission_name', $name);
            if ($MYSQL->query("INSERT INTO {prefix}permissions (permission_name) VALUES (:permission_name)") > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
     * Checks information on permissions.
     */
    public function perm($perm_id)
    {
        global $MYSQL;
        //$MYSQL->where('id', $perm_id);
        //$query = $MYSQL->get("{prefix}permissions");
        $MYSQL->bind('id', $perm_id);
        $query = $MYSQL->query("SELECT * FROM {prefix}permissions WHERE id = :id");
        if ($query) {
            return $query['0'];
        } else {
            return false;
        }
    }

}

?>