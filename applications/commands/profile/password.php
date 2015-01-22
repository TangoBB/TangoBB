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

$page_title = $LANG['bb']['profile']['password'];
$content = '';
$notice = '';

if (isset($_POST['edit'])) {

    try {

        foreach ($_POST as $parent => $child) {
            $_POST[$parent] = clean($child);
        }

        NoCSRF::check('csrf_token', $_POST);
        $new_password = $_POST['new_password'];
        $con_password = $_POST['current_password'];

        if (!$new_password or !$con_password) {
            throw new Exception ($LANG['global_form_process']['all_fields_required']);
        } elseif (!userExists($TANGO->sess->data['user_email'], $con_password, false)) {
            throw new Exception ($LANG['global_form_process']['invalid_password']);
        } else {
            $MYSQL->bindMore(
                array(
                    'user_password' => encrypt($new_password),
                    'id' => $TANGO->sess->data['id']
                )
            );

            if ($MYSQL->query("UPDATE {prefix}users SET user_password = :user_password WHERE id = :id") > 0) {
                $notice .= $TANGO->tpl->entity(
                    'success_notice',
                    'content',
                    $LANG['global_form_process']['save_success']
                );
            } else {
                throw new Exception ($LANG['bb']['profile']['error_updating_password']);
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
$content .= '<form id="tango_form" action="" method="POST">
                 ' . $FORM->build('hidden', '', 'csrf_token', array('value' => CSRF_TOKEN)) . '
                 ' . $FORM->build('password', $LANG['bb']['profile']['current_password'], 'current_password') . '
                 ' . $FORM->build('password', $LANG['bb']['profile']['new_password'], 'new_password') . '
                 <br /><br />
                 ' . $FORM->build('submit', '', 'edit', array('value' => $LANG['bb']['profile']['form_save'])) . '
               </form>';

$content = $notice . $content;

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
    $LANG['bb']['profile']['password'],
    '#',
    true
);
$bc = $TANGO->tpl->breadcrumbs();

$content = $bc . $content;

?>