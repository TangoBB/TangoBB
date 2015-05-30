<?php

/*
 * Conversations module for TangoBB
 * Everything that you want to display MUST be in the $content variable.
 */
if (!defined('BASEPATH')) {
    die();
}

$page_title = '';
$content = '';

if ($PGET->g('v')) {

    $get = clean($PGET->g('v'));

    $MYSQL->bind('id', $get);
    $query = $MYSQL->query("SELECT * FROM {prefix}messages WHERE id = :id AND message_type = 1");
    if (!empty($query)) {
        $auth = array(
            $query['0']['message_sender'],
            $query['0']['message_receiver']
        );
        if (!in_array($TANGO->sess->data['id'], $auth)) {
            redirect(SITE_URL . '/404.php');
        }

        $page_title = $query['0']['message_title'];

        $user = $TANGO->user($query['0']['message_sender']);

        //Breadcrumbs
        $TANGO->tpl->addBreadcrumb(
            $LANG['bb']['forum'],
            SITE_URL . '/forum.php'
        );
        $TANGO->tpl->addBreadcrumb(
            $LANG['bb']['conversations']['page_conversations'],
            SITE_URL . '/conversations.php'
        );
        $TANGO->tpl->addBreadcrumb(
            $page_title,
            '#',
            true
        );

        $breadcrumb = $TANGO->tpl->breadcrumbs();

        $reply_button = '';
        $edit_thread = '';
        if ($TANGO->perm->check('reply_thread')) {
            $reply_button .= $TANGO->tpl->entity(
                'reply_thread',
                'link',
                SITE_URL . '/conversations.php/cmd/reply/id/' . $get,
                'buttons'
            );
        }
        if ($query['0']['receiver_viewed'] == 0 && $TANGO->sess->data['id'] == $query['0']['message_receiver']) { //Is this needed?

            $MYSQL->bind('id', $get);
            $MYSQL->query('UPDATE {prefix}messages SET receiver_viewed = 1 WHERE id = :id');
        }

        $thread_mod_tools = '';

        $starter = $TANGO->tpl->entity(
            'thread_starter',
            array(
                'breadcrumbs',
                'reply_button',
                'quote_post',
                'edit_post',
                'report_post',
                'user_avatar',
                'profile_url',
                'username',
                'date_joined',
                'postcount',
                'thread_content',
                'user_signature',
                'post_time',
                'mod_tools',
                'thread_notice',
                'watch_link',
                'flag',
                'poll'
            ),
            array(
                $breadcrumb,
                $reply_button,
                '',
                '',
                '',
                $user['user_avatar'],
                SITE_URL . '/members.php/cmd/user/id/' . $user['id'],
                $user['username_style'],
                date('M jS, Y', $user['date_joined']),
                $user['post_count'],
                $TANGO->lib_parse->parse($query['0']['message_content']),
                $TANGO->lib_parse->parse($user['user_signature']),
                date('F j, Y', $query['0']['message_time']),
                $thread_mod_tools,
                '',
                '',
                '<span class="flag-icon flag-icon-' . strtolower($user['location']) . '"></span>',
                ''
            )
        );

        $content = $starter;

        $MYSQL->bind('origin_message', $get);
        $rep = $MYSQL->query("SELECT * FROM {prefix}messages WHERE origin_message = :origin_message AND message_type = 2");
        foreach ($rep as $post) {

            if ($post['receiver_viewed'] == 0 && $TANGO->sess->data['id'] == $post['message_receiver']) {
                $MYSQL->bind('id', $post['id']);
                $MYSQL->query('UPDATE {prefix}messages SET receiver_viewed = 1 WHERE id = :id');
            }

            $ur = $TANGO->user($post['message_sender']);

            $content .= $TANGO->tpl->entity(
                'thread_reply',
                array(
                    'post_id',
                    'quote_post',
                    'edit_post',
                    'report_post',
                    'user_avatar',
                    'profile_url',
                    'username',
                    'date_joined',
                    'postcount',
                    'reply_content',
                    'user_signature',
                    'post_time',
                    'mod_tools',
                    'flag'
                ),
                array(
                    'post-' . $post['id'],
                    '',
                    '',
                    '',
                    $ur['user_avatar'],
                    SITE_URL . '/members.php/cmd/user/id/' . $ur['id'],
                    $ur['username_style'],
                    date('M jS, Y', $ur['date_joined']),
                    $ur['post_count'],
                    $TANGO->lib_parse->parse($post['message_content']),
                    $TANGO->lib_parse->parse($ur['user_signature']),
                    date('F j, Y', $post['message_time']),
                    '',
                    '<span class="flag-icon flag-icon-' . strtolower($user['location']) . '"></span>'
                )
            );
        }

        define('CSRF_TOKEN', NoCSRF::generate('csrf_token'));
        define('CSRF_INPUT', '<input type="hidden" name="csrf_token" value="' . CSRF_TOKEN . '">');
        //Reply textarea.
        if ($TANGO->perm->check('reply_thread')) {
            $content .= $TANGO->tpl->entity(
                'reply_thread',
                array(
                    'form_thread',
                    'csrf_input',
                    'textarea_name',
                    'reply_form_action',
                    'editor_id',
                    'submit_name'
                ),
                array(
                    'tango_form',
                    CSRF_INPUT,
                    'content',
                    SITE_URL . '/conversations.php/cmd/reply/id/' . $get,
                    'editor',
                    'reply'
                )
            );
        }

    } else {
        redirect(SITE_URL . '/404.php');
    }

} else {
    redirect(SITE_URL);
}

?>
