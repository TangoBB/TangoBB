<?php

/*
 * User Profile module for TangoBB
 * Everything that you want to display MUST be in the $content variable.
 */

if (!defined('BASEPATH')) {
    die();
}
$content = '';
$page_title = '';

if ($PGET->g('id')) {
    $id = clean($PGET->g('id'));
    $MYSQL->bind('id', $id);
    $query = $MYSQL->query("SELECT * FROM {prefix}users WHERE id = :id");

    $MYSQL->bind('username', $id);
    $u_query = $MYSQL->query("SELECT * FROM {prefix}users WHERE username = :username");

    $query = (empty($query)) ? $u_query : $query;

    if (!empty($query)) {

        $page_title .= $LANG['bb']['members']['profile_of'] . ' ' . $query['0']['username'];
        $userg = $TANGO->usergroup($query['0']['user_group']);
        $user = $TANGO->user($id);

        if ($TANGO->sess->isLogged && $TANGO->sess->data['id'] != $user['id']) {
            // Inserting a new visitor
            $MYSQL->bindMore(array(
                'profile_owner' => $user['id'],
                'visitor' => $TANGO->sess->data['id']
            ));
            $query = $MYSQL->query('SELECT * FROM  {prefix}user_visitors WHERE profile_owner = :profile_owner AND visitor = :visitor');
            if (empty($query)) {
                $MYSQL->bindMore(array(
                    'profile_owner' => $user['id'],
                    'visitor' => $TANGO->sess->data['id']
                ));
                try {
                    $MYSQL->query('INSERT INTO {prefix}user_visitors (profile_owner, visitor, timestamp) VALUES (:profile_owner, :visitor, UNIX_TIMESTAMP(NOW()))');
                } catch (mysqli_sql_exception $e) {
                    throw new Exception ($LANG['global_form_process']['error_creating_thread']);
                }
            } else {
                $MYSQL->bindMore(array(
                    'profile_owner' => $user['id'],
                    'visitor' => $TANGO->sess->data['id']
                ));
                try {
                    $MYSQL->query('UPDATE {prefix}user_visitors SET timestamp = UNIX_TIMESTAMP(NOW()) WHERE profile_owner = :profile_owner AND visitor = :visitor');
                } catch (mysqli_sql_exception $e) {
                    throw new Exception ($LANG['global_form_process']['error_creating_thread']);
                }

            }

        }
    }
} else {
    if ($TANGO->sess->isLogged) {
        $page_title .= $LANG['bb']['members']['profile_of'] . ' ' . $TANGO->sess->data['username'];
        $userg = $TANGO->usergroup($TANGO->sess->data['user_group']);
        $user = $TANGO->user($TANGO->sess->data['id']);
    } else {
        redirect(SITE_URL . '/404.php');
    }
}

if (isset($user) && isset($userg) && isset($page_title)) {

    if (isset($_POST['comment_submit'])) {
        $comment_insert = clean($_POST['comment']);
        $MYSQL->bind('comment', $comment_insert);
        $MYSQL->bind('writer', $TANGO->sess->data['id']);
        $MYSQL->bind('profile_owner', $user['id']);
        $MYSQL->query("INSERT INTO {prefix}user_comments (comment, writer, profile_owner, timestamp) VALUES (:comment, :writer, :profile_owner, UNIX_TIMESTAMP(NOW()))");
    }

    //Recent activity protocol
    $recent_activity = '';
    $MYSQL->bind('post_user', $id);
    $query = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE post_user = :post_user ORDER BY post_time DESC LIMIT 15");
    foreach ($query as $ac) {
        //User created thread
        if ($ac['post_type'] == "1") {
            $recent_activity .= str_replace(
                array(
                    '%url%',
                    '%title%',
                    '%date%'
                ),
                array(
                    SITE_URL . '/thread.php/' . $ac['title_friendly'] . '.' . $ac['id'],
                    $ac['post_title'],
                    date('F j, Y', $ac['post_time'])
                ),
                $LANG['bb']['members']['posted_thread']
            );
        } else {
            //User replied to thread
            $thread = thread($ac['origin_thread']);
            $recent_activity .= str_replace(
                array(
                    '%url%',
                    '%title%',
                    '%date%'
                ),
                array(
                    SITE_URL . '/thread.php/' . $thread['title_friendly'] . '.' . $thread['id'] . '#post-' . $thread['id'],
                    $thread['post_title'],
                    date('F j, Y', $ac['post_time'])
                ),
                $LANG['bb']['members']['replied_to']
            );
        }
    }

    //Moderation tools
    $mod_tools = '';
    if ($TANGO->perm->check('access_moderation')) {
        if ($user['is_banned'] == "1") {
            $mod_tools .= $TANGO->tpl->entity(
                'mod_tools_profile',
                array(
                    'ban_user',
                    'ban_user_url'
                ),
                array(
                    'Unban User',
                    SITE_URL . '/mod/unban.php/id/' . $id
                ),
                'buttons'
            );
        } else {
            $mod_tools .= $TANGO->tpl->entity(
                'mod_tools_profile',
                array(
                    'ban_user',
                    'ban_user_url'
                ),
                array(
                    'Ban User',
                    SITE_URL . '/mod/ban.php/id/' . $id
                ),
                'buttons'
            );
        }
    }

    //profile comments
    $comments = '';
    $MYSQL->bind('profile_owner', $user['id']);
    $query = $MYSQL->query("SELECT writer,comment,timestamp FROM {prefix}user_comments WHERE profile_owner = :profile_owner ORDER BY timestamp DESC LIMIT 10");
    foreach ($query as $entry) {

        /*
         * ToDo: - date[format]
         */
        $writer = $TANGO->user($entry['writer']);
        $comment = $TANGO->lib_parse->parse($entry['comment']);
        $date_temp = simplify_time($entry['timestamp']);
        $date = $date_temp['time'];
        $comments .= $TANGO->tpl->entity(
            'user_profile_comments',
            array(
                'writer',
                'comment',
                'date'
            ),
            array(
                $writer['username_style'],
                $comment,
                $date
            )
        );
    }

    //profile visitors
    $visitors = '<div><ul class="visitors_framed">';
    $MYSQL->bind('profile_owner', $user['id']);
    $query = $MYSQL->query("SELECT visitor FROM {prefix}user_visitors WHERE profile_owner = :profile_owner ORDER BY timestamp DESC LIMIT 10");
    foreach ($query as $entry) {
        $visitor = $TANGO->user($entry['visitor']);
        $visitors .= '<li><a href="' . SITE_URL . '/members.php/cmd/user/id/' . $visitor['id'] . '" title="' . $visitor['username'] . '"><img src="' . $visitor['user_avatar'] . '" class="img-thumbnail" style="width:45px;height:45px;" /></a></li>';
    }
    $visitors .= '</ul></div>';


    //Breadcrumbs
    $TANGO->tpl->addBreadcrumb(
        $LANG['bb']['forum'],
        SITE_URL . '/forum.php'
    );
    $TANGO->tpl->addBreadcrumb(
        $LANG['bb']['members']['home'],
        SITE_URL . '/members.php'
    );
    $TANGO->tpl->addBreadcrumb(
        $LANG['bb']['members']['profile_of'] . ' ' . $user['username'],
        '#',
        true
    );
    $content .= $TANGO->tpl->breadcrumbs();

    //user profile
    $content .= $TANGO->tpl->entity(
        'user_profile_page',
        array(
            'username',
            'user_avatar',
            'usergroup',
            'registered_date',
            'user_signature',
            'about_user',
            'location',
            'flag',
            'gender',
            'age',
            'recent_activity',
            'mod_tools',
            'visitors',
            'comments',
            'comments_action'
        ),
        array(
            $user['username_style'],
            $user['user_avatar'],
            $userg['group_name'],
            localized_date($user['date_joined'], @$TANGO->sess->data['location']),
            $TANGO->lib_parse->parse($user['user_signature']),
            $TANGO->lib_parse->parse($user['about_user']),
            $LANG['location'][$user['location']],
            '<span class="flag-icon flag-icon-' . strtolower($user['location']) . '"></span>',
            gender($user['gender']),
            birthday_to_age($user['user_birthday']),
            $recent_activity,
            $mod_tools,
            $visitors,
            $comments,
            ''
        )
    );

}

?>
