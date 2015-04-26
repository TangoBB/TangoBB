<?php

/*
 * Admin Terminal Commands.
 */
if (!defined('BASEPATH')) {
    die();
}

function terminal_cugroup($username, $usergroup)
{
    global $MYSQL, $ADMIN;
    if (!$g = usergroupExists($usergroup)) {
        throw new Exception ('Usergroup does not exist.');
    } else {
        $MYSQL->bind('user_group', $g['id']);
        $MYSQL->bind('username', $username);
        try {
            $MYSQL->query('UPDATE {prefix}users SET user_group = :user_group WHERE username = :username');
            return $ADMIN->alert(
                'User\'s usergroup has been changed!',
                'success'
            );
        } catch (mysqli_sql_exception $e) {
            throw new Exception ('Error changing user\'s usergroup.');
        }
    }
}

function terminal_dugroup($username, $usergroup)
{
    global $MYSQL, $ADMIN;
    if (!$g = usergroupExists($usergroup)) {
        throw new Exception ('Usergroup does not exist.');
    } else {
        $MYSQL->bind('display_group', $g['id']);
        $MYSQL->bind('username', $username);
        try {
            $MYSQL->query('UPDATE {prefix}users SET display_group = :display_group WHERE username = :username');
            return $ADMIN->alert(
                'User\'s display group has been changed!',
                'success'
            );
        } catch (mysqli_sql_exception $e) {
            throw new Exception ('Error changing user\'s usergroup.');
        }
    }
}

function terminal_ban($username)
{
    global $MYSQL, $ADMIN;
    $MYSQL->bind('user_group', BAN_ID);
    $MYSQL->bind('username', $username);
    try {
        $MYSQL->query('UPDATE {prefix}users SET is_banned = 1, user_group = :user_group WHERE username = :username');
        return $ADMIN->alert(
            'User has been banned!',
            'success'
        );
    } catch (mysqli_sql_exception $e) {
        throw new Exception ('Error banning user.');
    }
}

function terminal_unban($username)
{
    global $MYSQL, $ADMIN;
    $MYSQL->bind('username', $username);
    try {
        $MYSQL->query('UPDATE {prefix}users SET is_banned = 0, user_group = 1 WHERE username = :username');
        return $ADMIN->alert(
            'User has been unbanned!',
            'success'
        );
    } catch (mysqli_sql_exception $e) {
        throw new Exception ('Error unbanning user.');
    }
}

?>
