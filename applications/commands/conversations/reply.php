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
$notice = '';

if ($PGET->g('id')) {

    $MYSQL->bind('id', $PGET->g('id'));
    $query = $MYSQL->query("SELECT * FROM {prefix}messages WHERE id = :id AND message_type = 1");

    if (!empty($query)) {

        if (isset($_POST['reply'])) {
            try {

                foreach ($_POST as $parent => $child) {
                    $_POST[$parent] = clean($child);
                }

                NoCSRF::check('csrf_token', $_POST);
                $cont = clean(emoji_to_text($_POST['content']));
                $time = time();

                if (!$cont) {
                    throw new Exception ($LANG['global_form_process']['all_fields_required']);
                } else {
                    if ($TANGO->sess->data['id'] == $query['0']['message_sender']) {
                        $receiver = $query['0']['message_receiver'];
                    } else {
                        $receiver = $query['0']['message_sender'];
                    }
                    $MYSQL->bindMore(
                        array(
                            'message_title' => 'RE: ' . $query['0']['message_title'],
                            'mssage_content' => $cont,
                            'message_time' => $time,
                            'origin_message' => $query['0']['id'],
                            'message_sender' => $TANGP->sess->data['id'],
                            'message_receiver' => $receiver
                        )
                    );

                    if ($MYSQL->query("INSERT INTO {prefix}messages (message_title, message_content, message_time, origin_message, message_sender, message_receiver, message_type) VALUES (:message_title, :message_content, :message_time, :origin_message, :message_sender, :message_receiver, 2)") > 0) {
                        redirect(SITE_URL . '/conversations.php/cmd/view/v/' . $query['0']['id']);
                    } else {
                        throw new Exception ($LANG['bb']['conversations']['error_sending_alt']);
                    }

                }

            } catch (Exception $e) {
                $notice .= $TANGO->tpl->entity(
                    'danger_notice',
                    'content',
                    $e->getMessage()
                );
            }
        }

        define('CSRF_TOKEN', NoCSRF::generate('csrf_token'));

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
            $LANG['bb']['conversations']['page_reply'] . ' ' . $query['0']['message_title'],
            '#',
            true
        );
        $content = $TANGO->tpl->breadcrumbs();

        $page_title = $LANG['bb']['conversations']['page_reply'] . ' ' . $query['0']['message_title'];
        $content .= $notice . '
                    <form action="" method="POST">
                      ' . $FORM->build('hidden', '', 'csrf_token', array('value' => CSRF_TOKEN)) . '
                      ' . $FORM->build('textarea', '', 'content', array('id' => 'editor', 'style' => 'width:100%;height:300px;max-width:100%;min-width:100%;')) . '
                      <br />
                      ' . $FORM->build('submit', '', 'reply', array('value' => $LANG['bb']['conversations']['form_reply'])) . '
                    </form>';

    } else {
        redirect(SITE_URL . '/404.php');
    }

} else {
    redirect(SITE_URL . '/404.php');
}

?>