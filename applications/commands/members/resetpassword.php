<?php

/*
 * Password Reset Module for TangoBB.
 * Everything that you want to display MUST be in the $content variable.
 */
if( !defined('BASEPATH') ){ die(); }

if( $TANGO->sess->isLogged ){ redirect(SITE_URL); } //If user is logged in.

$page_title = $LANG['bb']['members']['reset_password'];
$content    = '';
$notice     = '';

try {
    $token = $PGET->g('token');

    $user_id = null;

    if ( $token ) {
        $MYSQL->where('reset_token', hash('sha256', $token));
        $query = $MYSQL->get('{prefix}password_reset_requests');

        if ( $query ) {
            if ( !$query[0]['active'] ) {
                throw new Exception($LANG['bb']['members']['error_password_reset_token_used']);
            } elseif ( time() > $query[0]['request_time'] + 1 * 60 * 60 ) {
                throw new Exception($LANG['bb']['members']['error_password_reset_token_expired']);
            } else {
                $user_id = $query[0]['user'];
            }
        } else {
            throw new Exception($LANG['bb']['members']['error_password_reset_token_unknown']);
        }
    } else {
        throw new Exception($LANG['bb']['members']['error_password_reset_token_missing']);
    }

    if ( isset($_POST['reset']) ) {

        foreach( $_POST as $parent => $child ) {
            $_POST[$parent] = clean($child);
        }

        NoCSRF::check( 'csrf_token', $_POST );
        $password   = $_POST['password'];
        $a_password = $_POST['a_password'];

        if( !$password or !$a_password ) {
            throw new Exception ($LANG['global_form_process']['all_fields_required']);
        } elseif( $password !== $a_password ) {
            throw new Exception ($LANG['bb']['members']['password_different']);
        } else {
            // change password
            $data = array(
                'user_password' => encrypt($password),
            );

            $MYSQL->where('id', $user_id);
            if ( $MYSQL->update('{prefix}users', $data) ) {
                // deactivate token
                $data = array(
                    'active' => 0,
                );

                $MYSQL->where('reset_token', hash('sha256', $token));
                $MYSQL->update('{prefix}password_reset_requests', $data);

                $notice .= $TANGO->tpl->entity(
                    'success_notice',
                    'content',
                    $LANG['bb']['members']['password_reset_successful']
                );

                $MYSQL->where('id', $user_id);
                $query = $MYSQL->get('{prefix}users');

                $TANGO->sess->assign($query[0]['user_email']);
                header('refresh:3;url=' . SITE_URL);
            } else {
                throw new Exception ($LANG['bb']['members']['error_password_reset']);
            }
        }
    }
} catch( Exception $e ) {
    $notice .= $TANGO->tpl->entity(
        'danger_notice',
        'content',
        $e->getMessage()
    );
}


define('CSRF_TOKEN', NoCSRF::generate( 'csrf_token' ));

$content .= $TANGO->tpl->entity(
    'reset_password_form',
    array(
        'csrf_field',
        'password_field_name',
        'password_a_field_name',
        'submit_field_name'
    ),
    array(
        $FORM->build('hidden', '', 'csrf_token', array('value' => CSRF_TOKEN)),
        'password',
        'a_password',
        'reset'
    )
);

$content  = $notice . $content;
