<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_moderation') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.
  $TANGO->tpl->getTpl('page');

  $content    = '';

  if( $PGET->g('post') ) {

    $MYSQL->bind('id', $PGET->g('post'));
    $query = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE id = :id");

      if( !empty($query) ) {

        // Check if the post is a starting post
        if( $query['0']['post_type'] == "1" ) {
        // If it is starting post delete the post and all its subposts
            $MYSQL->bind('id', $query['0']['id']);
            $MYSQL->query("DELETE FROM {prefix}forum_posts WHERE id = :id");
            $content .= $TANGO->tpl->entity(
              'success_notice',
              'content',
              $LANG['mod']['delete']['thread_deleted']
            );
        // Checking if there are subposts
            $MYSQL->bind('origin_thread', $query['0']['id']);
            $MYSQL->query("DELETE FROM {prefix}forum_posts WHERE origin_thread = :origin_thread");
            
        }
        else // if it is just a post
        {
            $MYSQL->bind('id', $query['0']['id']);
            $MYSQL->query("DELETE FROM {prefix}forum_posts WHERE id = :id");
            $content .= $TANGO->tpl->entity(
              'success_notice',
              'content',
              $LANG['mod']['delete']['post_deleted']
            );
        }
      } else {
          redirect(SITE_URL);
      }

  } else {
      redirect(SITE_URL);
  }

  $TANGO->tpl->addParam(
      array(
          'page_title',
          'content'
      ),
      array(
          $LANG['mod']['delete']['delete'],
          $content
      )
  );

  echo $TANGO->tpl->output();

?>