<?php

/*
 * Profile edit module for TangoBB.
 */
if (!defined('BASEPATH')) {
    die();
}
if (!$TANGO->sess->isLogged) {
    redirect(SITE_URL . '/404.php');
}//Check if user is logged in.

$page_title = $LANG['bb']['profile']['signature'];
$content = '';
$notice = '';

if (isset($_POST['edit'])) {

    try {

        foreach ($_POST as $parent => $child) {
            $_POST[$parent] = clean($child);
        }

        NoCSRF::check('csrf_token', $_POST);
        $sig = emoji_to_text($_POST['sig']);
        $MYSQL->bindMore(
            array(
                'user_signature' => $sig,
                'id' => $TANGO->sess->data['id']
            )
        );
        if ($MYSQL->query("UPDATE {prefix}users SET user_signature = :user_signature WHERE id = :id") > 0) {
            $notice .= $TANGO->tpl->entity(
                'success_notice',
                'content',
                $LANG['global_form_process']['save_success']
            );
        } else {
            throw new Exception ($LANG['bb']['profile']['error_updating_signature']);
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
$content .= '<form id="tango_form" action="" method="POST">
                 ' . $FORM->build('hidden', '', 'csrf_token', array('value' => CSRF_TOKEN)) . '
                 ' . $FORM->build('textarea', '', 'sig', array('value' => $TANGO->sess->data['user_signature'], 'id' => 'editor', 'style' => 'width:100%;height:300px;max-width:100%;min-width:100%;')) . '
                 <br /><br />
                 ' . $FORM->build('submit', '', 'edit', array('value' => $LANG['bb']['profile']['form_save'])) . '
               </form>';

//Breadcrumbs
$TANGO->tpl->addBreadcrumb(
    $LANG['bb']['forum'],
    SITE_URL . '/forum.php'
);
$TANGO->tpl->addBreadcrumb(
    $LANG['bb']['members']['home'],
    SITE_URL . '/conversations.php'
);
$TANGO->tpl->addBreadcrumb(
    $LANG['bb']['profile']['signature'],
    '#',
    true
);
$bc = $TANGO->tpl->breadcrumbs();

$content = $bc . $notice . $content;

?>