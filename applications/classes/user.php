<?php

/*
 * User class of TangoBB
 */
if (!defined('BASEPATH')) {
    die();
}

class Tango_User
{

    private $user_links = array();
    private $notice_type;

    public function __construct()
    {
        global $LANG;
        $this->notice_type = array(
            'mention',
            'reply',
            'quote'
        );
    }

    /*
     * Change user's usergroup.
     */
    public function changeUserGroup($user, $group)
    {
        global $MYSQL;
        $MYSQL->bind('id', $user);
        $user = $MYSQL->query("SELECT * FROM {prefix}users WHERE id = :id");
        $MYSQL->bind('id', $group);
        $group = $MYSQL->query("SELECT * FROM {prefix}usergroups WHERE id = :id");

        if (!empty($user) && !empty($group)) {

            $MYSQL->bind('user_group', $group['0']['id']);
            $MYSQL->bind('id', $user);
            $query = $MYSQL->query("INSERT INTO {prefix}users SET user_group :user_group WHERE id = :id");

            if ($query > 0) {
                return true;
            } else {
                return false;
            }


            // PDO here or shall this be like this?

            /*$data = array(
              'user_group' => $group
            );*/

            //$MYSQL->where('id', $user);

            /*try {
              $MYSQL->update('{prefix}users', $data);
              return true;
            } catch (mysqli_sql_exception $e) {
              return false;
            }*/

        } else {
            return false;
        }
    }

    /*
     * Change Username
     */
    public function changeUsername($user, $username)
    {
        global $MYSQL;
        $MYSQL->bind('id', $user);
        $query = $MYSQL->query('SELECT * FROM {prefix}users WHERE id = :id');
        if (!empty($query)) {

            $MYSQL->bind('username', $username);
            $MYSQL->bind('id', $user);
            try {
                $MYSQL->query('UPDATE {prefix}users SET username = :username WHERE id = :id');
                return true;
            } catch (mysqli_sql_exception $e) {
                return false;
            }

        } else {
            return false;
        }
    }

    /*
     * Add permission to a user.
     */
    public function givePermission($user, $permission)
    {
        global $MYSQL, $TANGO;
        $user = $TANGO->user($user);
        if (!empty($user)) {
            $perm = $TANGO->perm->perm($permission);
            if ($user['additional_permissions'] == "0") {
                $MYSQL->bind('additional_permissions', $perm['permission_name']);
            } else {
                $ap_array = array();
                foreach ($user['additional_permissions'] as $ap) {
                    $MYSQL->bind('permission_name', $ap);
                    $ap_query = $MYSQL->query('SELECT * FROM {prefix}permissions WHERE permission_name = :permission_name');
                    if ($ap_query) {
                        $ap_array[] = $ap_query['0']['id'];
                    }
                }
                $additional_permissions = implode(',', $ap_array);
                $MYSQL->bind('additional_permissions', $additional_permissions . ',' . $perm['permission_name']);
            }
            $MYSQL->bind('id', $user['id']);
            if ($MYSQL->query('UPDATE {prefix}users SET additional_permissions = :additional_permissions WHERE id = :id')) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
     * Remove additional permission from a user.
     */
    public function removeAddPermission($user, $permission)
    {
        global $MYSQL, $TANGO;
        $user = $TANGO->user($user);
        if (!empty($user)) {
            $current_perms = array();
            foreach ($user['additional_permissions'] as $ap) {
                $current_perms[$ap] = $ap;
            }
            unset($current_perms[$permission]);
            if (!empty($current_perms)) {
                $new_perms = array();
                foreach ($current_perms as $parent => $child) {
                    $MYSQL->bind('permission_name', $child); //$id_perms not defined?
                    $p_query = $MYSQL->query('SELECT * FROM {prefix}permissions WHERE permission_name = :permission_name');
                    $new_perms[] = $p_query['id'];
                }
                $new_perms = implode(',', $new_perms);
                $MYSQL->bind('additional_permissions', $new_perms);
            } else {
                $MYSQL->bind('additional_permissions', '0');
            }

            $MYSQL->bind('id', $user['id']);
            if ($MYSQL->query('UPDATE {prefix}users SET additional_permissions = :additional_permissions WHERE id = :id')) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
     * Return user links as an array.
     * For template use.
     */
    function userLinks()
    {
        return $this->user_links;
    }

    /*
     * Add link to the user links.
     */
    public function addUserLink($link = array())
    {
        foreach ($link as $name => $href) {
            $this->user_links[$name] = $href;
        }
    }

    /*
     * User messages.
     */
    public function userMessages()
    {
        global $MYSQL, $TANGO;
        $return = array();
        $MYSQL->bind('message_receiver', $TANGO->sess->data['id']);
        $MYSQL->bind('receiver_viewed', 0);
        $query = $MYSQL->query("SELECT * FROM {prefix}messages WHERE message_receiver = :message_receiver AND receiver_viewed = :receiver_viewed");
        foreach ($query as $msg) {
            if ($msg['message_type'] == 1) {
                $receiver = $TANGO->user($msg['message_receiver']);
                $sender = $TANGO->user($msg['message_sender']);
                $msg['message_receiver'] = $receiver['username'];
                $msg['message_sender'] = $sender['username'];
                $msg['view_url'] = SITE_URL . '/conversations.php/cmd/view/v/' . $msg['id'];
                $return[] = $msg;
            } else {
                $MYSQL->bind('id', $msg['origin_message']);
                $origin = $MYSQL->query('SELECT * FROM {prefix}messages WHERE id = :id');
                $receiver = $TANGO->user($msg['message_receiver']);
                $sender = $TANGO->user($msg['message_sender']);
                $msg['message_receiver'] = $receiver['username'];
                $msg['message_sender'] = $sender['username'];
                $msg['view_url'] = SITE_URL . '/conversations.php/cmd/view/v/' . $origin['0']['id'];
                $return[] = $msg;
            }
        }
        return $return;
    }

    /*
     * Notification
     */
    public function notifications()
    {
        global $MYSQL, $TANGO;
        $return = array();
        if ($TANGO->sess->isLogged) {
            $query = $MYSQL->query("SELECT * FROM {prefix}notifications WHERE user = {$TANGO->sess->data['id']} AND viewed = 0 ORDER BY time_received ASC");
            foreach ($query as $note) {
                $note['notice_link'] = ($query['0']['notice_link'] == "0") ? '#' : $query['0']['notice_link'];
                $return[] = $note;
            }
        } else {
            unset($return);
        }
        return $return;
    }

    public function clearNotification()
    {
        global $MYSQL, $TANGO;
        $MYSQL->bind('user', $TANGO->sess->data['id']);
        $MYSQL->query("UPDATE {prefix}notifications SET viewed = 1 WHERE user = :user");
    }

    public function notifyUser($type, $user, $email = false, $extra = array())
    {
        global $MYSQL, $TANGO, $LANG, $MAIL;
        $time = time();
        $notice = '';
        if (in_array($type, $this->notice_type)) {
            switch ($type) {
                //Mention notification.
                case "mention":
                    $notice = str_replace(
                        '%username%',
                        $extra['username'],
                        $LANG['notification']['mention']
                    );

                    $MYSQL->bindMore(
                        array(
                            'notice_content' => $notice,
                            'notice_link' => $extra['link'],
                            'user' => $user,
                            'time_received' => $time
                        )
                    );
                    break;

                //Reply notification
                case "reply":
                    $notice = str_replace(
                        array(
                            '%username%',
                            '%thread_title%'
                        ),
                        array(
                            $extra['username'],
                            $extra['thread_title']
                        ),
                        $LANG['notification']['reply']
                    );

                    $MYSQL->bindMore(
                        array(
                            'notice_content' => $notice,
                            'notice_link' => $extra['link'],
                            'user' => $user,
                            'time_received' => $time
                        )
                    );
                    break;

                //Quote notification.
                case "quote":
                    $notice = str_replace(
                        array(
                            '%username%',
                            '%thread_title%'
                        ),
                        array(
                            $extra['username'],
                            $extra['thread_title']
                        ),
                        $LANG['notification']['quoted']
                    );

                    $MYSQL->bindMore(
                        array(
                            'notice_content' => $notice,
                            'notice_link' => $extra['link'],
                            'user' => $user,
                            'time_received' => $time
                        )
                    );
                    break;
            }
        } else {
            //Uncategorized notification.
            $link = (isset($extra['link'])) ? $extra['link'] : '';
            $extra['link'] = $link;
            $notice .= $type;

            $MYSQL->bindMore(
                array(
                    'notice_content' => $notice,
                    'notice_link' => $link,
                    'user' => $user,
                    'time_received' => $time
                )
            );
        }

        try {
            $MYSQL->query("INSERT INTO {prefix}notifications (notice_content, notice_link, user, time_received) VALUES (:notice_content, :notice_link, :user, :time_received)");
            $info = str_replace(
                '%url%',
                $extra['link'],
                $LANG['email']['notify']['more_info']
            );
            if ($email) {
                $user = $TANGO->user($user);
                //Setting up email
                $MAIL->to($user['user_email']);
                $MAIL->from($TANGO->data['site_email']);
                $MAIL->subject($notice);
                $MAIL->body($notice . $info);
                if ($MAIL->send()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } catch (mysqli_sql_exception $e) {
            throw new Exception ('FAIL: ' . $e);
        }
        $notice .= $TANGO->tpl->entity(
            'danger_notice',
            'content',
            $e->getMessage()
        );
    }


}

?>
