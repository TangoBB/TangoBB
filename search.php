<?php

define('BASEPATH', 'Forum');
require_once('applications/wrapper.php');

$TANGO->tpl->getTpl('page');

$content = '';
$notice = '';
$page_title = $LANG['bb']['search'];

$content .= $TANGO->tpl->entity('advanced_search', '', '');
if (isset($_POST['search_submit'])) {
    try {

        foreach ($_POST as $parent => $child) {
            $_POST[$parent] = clean($child);
        }

        $search_query = $_POST['search_query'];

        if (!$search_query) {
            throw new Exception ($LANG['global_form_process']['enter_search_query']);
        } else {
            (isset($_POST['boolean_mode'])) ? ($mode = "IN BOOLEAN MODE") : ($mode = "IN NATURAL LANGUAGE MODE");

            $searched_threads = '';
            $searched_users = '';
            $sql_from = '';
            $sql_to = '';
            $search_query_threads = "'" . $search_query . "'";
            $MYSQL->bind('search_query_one', $search_query_threads);
            $MYSQL->bind('search_query_two', $search_query_threads);
            if (isset($_POST['time_from']) && !empty($_POST['time_from'])) {
                $time_from = clean($_POST['time_from']);
                $from_array = explode("-", $time_from);
                $time_from = mktime(0, 0, 0, $from_array[1], $from_array[0], $from_array[2]);
                $sql_from = "AND post_time > :time_from";
                $MYSQL->bind('time_from', $time_from);
            }
            if (isset($_POST['time_to']) && !empty($_POST['time_to'])) {
                $time_to = clean($_POST['time_to']);
                $to_array = explode("-", $time_to);
                $time_to = mktime(59, 59, 23, $to_array[1], $to_array[0], $to_array[2]);
                $sql_to = "AND post_time < :time_to";
                $MYSQL->bind('time_to', $time_to);
            }
            $sql = "SELECT *, MATCH (post_title, post_content) AGAINST (:search_query_one $mode) AS score
                                        FROM
                                        {prefix}forum_posts
                                        WHERE
                                        MATCH (post_title, post_content) AGAINST (:search_query_two $mode) $sql_from $sql_to;";
            $query = $MYSQL->query($sql);
            $threads = array();

            foreach ($query as $re) {
                $user = $TANGO->user($re['post_user']);
                if ($re['post_type'] == 2) {
                    $origin = $re['origin_thread'];
                    $MYSQL->bind('origin', $origin);
                    $qry = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE id = :origin");
                    $title = 'Answer on thread ' . $qry['0']['post_title'];
                    $threads[] .= '<a href="' . SITE_URL . '/thread.php/' . $qry['0']['title_friendly'] . '.' . $qry['0']['id'] . '">' . $title . '</a> <small>' . $LANG['global_form_process']['search_by'] . ' <a href="' . SITE_URL . '/members.php/cmd/user/id/' . $user['id'] . '">' . $user['username'] . '</a> (' . date('F j, Y', $re['post_time']) . ')</small><hr size="1" />';
                } else {
                    $threads[] .= '<a href="' . SITE_URL . '/thread.php/' . $re['title_friendly'] . '.' . $re['id'] . '">' . $re['post_title'] . '</a> <small>' . $LANG['global_form_process']['search_by'] . ' <a href="' . SITE_URL . '/members.php/cmd/user/id/' . $user['id'] . '">' . $user['username'] . '</a> (' . date('F j, Y', $re['post_time']) . ')</small><hr size="1" />';
                }

            }
            if (!empty($threads)) {
                foreach ($threads as $thread) {
                    $searched_threads .= $thread;
                }
            } else {
                $searched_threads .= $LANG['global_form_process']['search_no_result'];
            }
            if ((isset($_POST['user_search']) && isset($_POST['search_type'])) || !isset($_POST['search_type'])) {
                $MYSQL->bind('search_query', $search_query);
                $query = $MYSQL->query("SELECT * FROM
                                        {prefix}users
                                        WHERE
                                        username LIKE CONCAT('%',:search_query,'%');");
                $users = array();
                foreach ($query as $re) {
                    $users[] .= '<a href="' . SITE_URL . '/members.php/cmd/user/id/' . $re['id'] . '">' . $re['username'] . '</a><hr size="1" />';;
                }

                if (!empty($users)) {
                    foreach ($users as $u) {
                        $searched_users .= $u;
                    }
                } else {
                    $searched_users .= $LANG['global_form_process']['search_no_result'];
                }
            } else {
                $searched_users .= $LANG['global_form_process']['search_no_user'];
            }

            $content .= $TANGO->tpl->entity(
                'search_page',
                array(
                    'searched_threads',
                    'searched_users'
                ),
                array(
                    $searched_threads,
                    $searched_users
                )
            );

        }

    } catch (Exception $e) {
        $notice .= $TANGO->tpl->entity(
            'danger_notice',
            'content',
            $e->getMessage()
        );
    }
}

//Breadcrumbs
$TANGO->tpl->addBreadcrumb(
    $LANG['bb']['forum'],
    SITE_URL . '/forum.php'
);
$TANGO->tpl->addBreadcrumb(
    $LANG['bb']['search'],
    '#',
    true
);
$bc = $TANGO->tpl->breadcrumbs();

$TANGO->tpl->addParam(
    array(
        'page_title',
        'content'
    ),
    array(
        $page_title,
        $bc . $notice . $content
    )
);

echo $TANGO->tpl->output();

?>