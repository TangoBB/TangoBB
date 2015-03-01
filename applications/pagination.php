<?php

/*
 * Standard Pagination Functions of TangoBB.
 */
if (!defined('BASEPATH')) {
    die();
}

/* Pagination for threads */
function getThreads($id, $page, $sort, $per_page = THREAD_RESULTS_PER_PAGE)
{
    global $MYSQL;

    $start = (int)($page - 1) * $per_page;
    $per_page = (int)$per_page;

    //$data = array($id);
    $MYSQL->bind('origin_node', $id);
    $MYSQL->bind('start', $start);
    $MYSQL->bind('per_page', $per_page);
    switch ($sort) {
        case "latest_created":
            $query = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE post_type = 1 AND origin_node = :origin_node ORDER BY post_time DESC LIMIT :start, :per_page");
            break;

        case "name_desc":
            $query = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE post_type = 1 AND origin_node = :origin_node ORDER BY post_title DESC LIMIT :start, :per_page");
            break;

        case "name_asc":
            $query = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE post_type = 1 AND origin_node = :origin_node ORDER BY post_title ASC LIMIT :start, :per_page");
            break;

        default:
        case "last_updated":
            $query = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE post_type = 1 AND origin_node = :origin_node ORDER BY post_sticky DESC, last_updated DESC, post_time DESC LIMIT :start, :per_page");
            break;
            break;
    }

    return $query;
}

function fetchTotalThread($id)
{
    global $MYSQL;

    $MYSQL->bind('origin_node', $id);
    $query = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE post_type = 1 AND origin_node = :origin_node");
    return count($query);
}

/* Pagination for posts */
function getPosts($id, $page, $per_page = POST_RESULTS_PER_PAGE)
{
    global $MYSQL;

    $start = (int)($page - 1) * $per_page;
    $per_page = (int)$per_page;

    $MYSQL->bind('origin_thread', $id);
    $MYSQL->bind('start', $start);
    $MYSQL->bind('per_page', $per_page);
    $query = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE post_type = 2 AND origin_thread = :origin_thread ORDER BY post_time ASC LIMIT :start, :per_page");
    return $query;
}

function fetchTotalPost($id)
{
    global $MYSQL;

    $MYSQL->bind('origin_thread', $id);
    $query = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE post_type = 2 AND origin_thread = :origin_thread");
    return count($query);
}

/* Pagination for members. */
function getMembers($page, $sort = "", $per_page = "20")
{
    global $MYSQL;

    $start = (int)($page - 1) * $per_page;
    $per_page = (int)$per_page;

    $MYSQL->bind('start', $start);
    $MYSQL->bind('per_page', $per_page);
    switch ($sort) {
        case "date_joined_asc":
            $query = $MYSQL->query("SELECT * FROM {prefix}users ORDER BY date_joined ASC LIMIT :start, :per_page");
            break;

        case "date_joined_desc":
            $query = $MYSQL->query("SELECT * FROM {prefix}users ORDER BY date_joined DESC LIMIT :start, :per_page");
            break;

        case "username_desc":
            $query = $MYSQL->query("SELECT * FROM
                                  {prefix}users
                                  ORDER BY
                                  username
                                  DESC LIMIT
                                  {$start}, {$per_page}");
            break;

        default:
        case "username_asc":
            $query = $MYSQL->query("SELECT * FROM {prefix}users ORDER BY username ASC LIMIT :start, :per_page");
            break;
            break;
    }

    return $query;
}

function fetchTotalMembers()
{
    global $MYSQL;

    $query = $MYSQL->query("SELECT * FROM {prefix}users");
    return count($query);
}

?>
