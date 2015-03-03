<?php

/*
 * Facebook Authentication Module.
 */
require_once(PATH_A . LIB . 'facebook.php');
$FACEBOOK = new Facebook(array(
    'appId' => $TANGO->data['facebook_app_id'],
    'secret' => $TANGO->data['facebook_app_secret'],
    'cookie' => true,
));
$FB_USER = $FACEBOOK->getUser();
if ($FB_USER) {
    try {
        $FB_PROFILE = $FACEBOOK->api('/me');
        $params = array('next' => SITE_URL . '/members.php/cmd/logout');
        $logout = $FACEBOOK->getLogoutUrl($params);
        $MYSQL->bind('facebook_id', $FB_PROFILE['id']);
        $query = $MYSQL->query('SELECT * FROM {prefix}users WHERE facebook_id =:facebook_id');
        if (empty($query)) {
            $time = time();
            if (emailTaken($FB_PROFILE['email'])) {
                $MYSQL->bind('facebook_id', $FB_PROFILE['id']);
                $MYSQL->bind('user_email', $FB_PROFILE['email']);
                $MYSQL->query('UPDATE {prefix}users SET facebook_id = :facebook_id WHERE user_email = :user_email');
            } else {
                $username = (isset($FB_PROFILE['username']) && !empty($FB_PROFILE['username'])) ? $FB_PROFILE['username'] : str_replace(' ', '_', $FB_PROFILE['name']);
                $MYSQL->bindMore(array(
                    'username' => $username,
                    'user_email' => $FB_PROFILE['email'],
                    'date_joined' => $time,
                    'facebook_id' => $FB_PROFILE['id']
                ));
                $MYSQL->query('INSERT INTO {prefix}users (username, user_email, date_joined, facebook_id) VALUES (:username, :user_email, :date_joined, :facebook_id)');
            }

        }
        if (!$TANGO->sess->isLogged) {
            $TANGO->sess->assign($FB_PROFILE['email'], true, true);
        }

    } catch (FacebookApiException $e) {
        error_log($e);
        $user = NULL;
    }
}
if ($FB_USER) {
    $FB_LOGOUT = $FACEBOOK->getLogoutUrl(array(
        'next' => SITE_URL . '/members.php/cmd/logout',  // Logout URL full path
    ));
    $TANGO->user->addUserLink(array(
        'Log Out' => $FB_LOGOUT
    ));
} else {
    if ($TANGO->sess->isLogged) {
        $TANGO->user->addUserLink(array(
            'Log Out' => SITE_URL . '/members.php/cmd/logout'
        ));
    } else {
        $FB_LOGIN = $FACEBOOK->getLoginUrl(array(
            'scope' => 'email', // Permissions to request from the user
        ));
        $TANGO->tpl->addParam('facebook_login_url', $FB_LOGIN);
    }
}

?>