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

$page_title = $LANG['bb']['profile']['personal_details'];
$content = '';
$notice = '';

if (isset($_POST['edit'])) {

    try {

        foreach ($_POST as $parent => $child) {
            $_POST[$parent] = clean($child);
        }

        NoCSRF::check('csrf_token', $_POST);
        $email = $_POST['email'];
        $tz = $_POST['timezone'];
        $about = emoji_to_text($_POST['about']);
        $birthday = $_POST['birthday'];
        $location = $_POST['location'];
        $gender = $_POST['gender'];

        if (!$email or !$tz) {
            throw new Exception ($LANG['global_form_process']['all_fields_required']); // Email and Timezone required
        } elseif (!validEmail($email)) {
            throw new Exception ($LANG['global_form_process']['invalid_email']);
        } else {
            if ($email !== $TANGO->sess->data['user_email']) {

                if (!emailTaken($email)) {
                    $MYSQL->bindMore(
                        array(
                            'user_email' => $email,
                            'about_user' => $about,
                            'set_timezone' => $tz,
                            'user_birthday' => $birthday,
                            'location' => $location,
                            'gender' => $gender,
                            'id' => $TANGO->sess->data['id']
                        )
                    );
                    if ($MYSQL->query("UPDATE {prefix}users SET user_email = :user_email, about_user = :about_user, set_timezone = :set_timezone, user_birthday = :user_birthday, location = :location, gender = :gender WHERE id = :id") > 0) {
                        $notice .= $TANGO->tpl->entity(
                            'success_notice',
                            'content',
                            $LANG['global_form_process']['save_success']
                        );
                    } else {
                        throw new Exception ($LANG['global_form_process']['error_saving']);
                    }

                } else {
                    throw new Exception ($LANG['global_form_process']['email_used']);
                }

            } else {
                $MYSQL->bindMore(
                    array(
                        'set_timezone' => $tz,
                        'location' => $location,
                        'gender' => $gender,
                        'user_birthday' => $birthday,
                        'about_user' => $about,
                        'id' => $TANGO->sess->data['id']
                    )
                );

                if ($MYSQL->query("UPDATE {prefix}users SET set_timezone = :set_timezone, location = :location, gender = :gender, user_birthday = :user_birthday, about_user = :about_user WHERE id = :id") > 0) {
                    $notice .= $TANGO->tpl->entity(
                        'success_notice',
                        'content',
                        $LANG['global_form_process']['save_success']
                    );
                } else {
                    throw new Exception ($LANG['global_form_process']['error_saving']);
                }

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

$timezones = '<select id="timezone" name="timezone">';
foreach (timezones() as $timezone => $code) {
    if ($TANGO->sess->data['set_timezone'] == $code) {
        $timezones .= '<option value="' . $code . '" selected="selected">' . $timezone . '</option>';
    } else {
        $timezones .= '<option value="' . $code . '">' . $timezone . '</option>';
    }
}
$timezones .= '</select>';

$locations = '<select id="location" name="location">';
foreach ($LANG['location'] as $code => $location) {
    if ($TANGO->sess->data['location'] == $code) {
        $locations .= '<option value="' . $code . '" selected="selected">' . $location . '</option>';
    } else {
        $locations .= '<option value="' . $code . '">' . $location . '</option>';
    }
}
$locations .= '</select>';

$gender = '<select id="gender" name="gender">';
if ($TANGO->sess->data['gender'] == 0) {
    $gender .= '<option value="0" selected="selected">' . $LANG['bb']['profile']['not_telling'] . '</option>
                <option value="1">' . $LANG['bb']['profile']['female'] . '</option>
                <option value="2">' . $LANG['bb']['profile']['male'] . '</option>';
} elseif ($TANGO->sess->data['gender'] == 1) {
    $gender .= '<option value="0">' . $LANG['bb']['profile']['not_telling'] . '</option>
                <option value="1" selected="selected">' . $LANG['bb']['profile']['female'] . '</option>
                <option value="2">' . $LANG['bb']['profile']['male'] . '</option>';
} elseif ($TANGO->sess->data['gender'] == 2) {
    $gender .= '<option value="0">' . $LANG['bb']['profile']['not_telling'] . '</option>
                <option value="1">' . $LANG['bb']['profile']['female'] . '</option>
                <option value="2" selected="selected">' . $LANG['bb']['profile']['male'] . '</option>';
}
$gender .= '</select>';

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
    $LANG['bb']['profile']['personal_details'],
    '#',
    true
);
$bc = $TANGO->tpl->breadcrumbs();
if (isset($TANGO->sess->data['user_birthday']) && $TANGO->sess->data['user_birthday'] != '0000-00-00') {
    $val_birthday = $TANGO->sess->data['user_birthday'];
} else {
    $val_birthday = 'YYYY-MM-DD';
}

$content .= '<form id="tango_form" action="" method="POST">
                 ' . $FORM->build('hidden', '', 'csrf_token', array('value' => CSRF_TOKEN)) . '
                 ' . $FORM->build('text', $LANG['bb']['members']['form_email'], 'email', array('value' => $TANGO->sess->data['user_email'])) . '
                 <label for="gender">' . $LANG['bb']['profile']['gender'] . '</label>
                 ' . $gender . '
                 <label for="timezone">' . $LANG['bb']['profile']['timezone'] . '</label>
                 ' . $timezones . '
                 <br />
                 <label for="location">' . $LANG['bb']['profile']['location'] . '</label>
                 ' . $locations . '
                 <br />
                 ' . $FORM->build('text', $LANG['bb']['members']['birthday'], 'birthday', array('value' => $val_birthday)) . '
                 <label for="editor">' . $LANG['bb']['profile']['about_you'] . '</label><br />
                 <textarea name="about" id="editor" style="min-width:100%;max-width:100%;height:150px;">' . $TANGO->sess->data['about_user'] . '</textarea>
                 <br />
                 ' . $FORM->build('submit', '', 'edit', array('value' => $LANG['bb']['profile']['form_save'])) . '
               </form>';

$content = $bc . $notice . $content;

?>
