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

$page_title = 'Change Theme';

if ($PGET->g('set')) {

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
        $LANG['bb']['profile']['change_theme'],
        '#',
        true
    );
    $content = $TANGO->tpl->breadcrumbs();


    $get     = ($PGET->g('set') == "default")? '0' : $PGET->g('set');

    $query   = $MYSQL->query("SELECT * FROM {prefix}themes");
    $t_names = array();
    foreach( $query as $t ) {
        $t_names[] = $t['id'];
    }
    $t_names[] = 0;

    //die(var_dump($get));

    if (in_array($get, $t_names)) {
        $MYSQL->bindMore(
            array(
                'chosen_theme' => $get,
                'id' => $TANGO->sess->data['id']
            )
        );

        if ($MYSQL->query("UPDATE {prefix}users SET chosen_theme = :chosen_theme WHERE id = :id") > 0) {
            $content .= $TANGO->tpl->entity(
                'success_notice',
                'content',
                $LANG['bb']['profile']['theme_set']
            );
            header('refresh:3;url=' . SITE_URL . '/forum.php');
        } else {
            $content .= $TANGO->tpl->entity(
                'danger_notice',
                'content',
                $LANG['bb']['profile']['theme_error']
            );
        }

    } else {
        $content .= $TANGO->tpl->entity(
            'danger_notice',
            'content',
            $LANG['bb']['profile']['theme_not_exist']
        );
    }

} else {
    redirect(SITE_URL . '/404.php');
}

?>
