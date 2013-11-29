<?php

  /*
   * Register Module for TangoBB.
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }

  $page = $PGET->g('page');
  $page = (!$page)? '1' : $PGET->g('page');

  $content = '';
  foreach(getMembers($page) as $user) {
      $p_count   = $TANGO->user($user['id']);
      $content  .= $TANGO->tpl->entity(
          'members_page',
          array(
              'avatar',
              'username',
              'profile_url',
              'date_joined',
              'postcount'
          ),
          array(
              SITE_URL . '/public/img/avatars/' . $user['user_avatar'],
              $p_count['username_style'],
              SITE_URL . '/members.php/cmd/user/id/' . $user['id'],
              date('M jS, Y', $user['date_joined']),
              $p_count['post_count']
          )
      );
  }

  $total_pages = ceil(fetchTotalMembers() / 20);

  $pag = '';
  if( $total_pages > 1 ) {
      $i   = '';
      for( $i = 1; $i <= $total_pages; ++$i ) {
          if( $i == $page ) {
              $pag .= $TANGO->tpl->entity(
                  'pagination_link_current',
                  'page',
                  $i
              );
          } else {
              $pag .= $TANGO->tpl->entity(
                  'pagination_links',
                  array(
                      'url',
                      'page'
                  ),
                  array(
                      SITE_URL . '/members.php/page/' . $i,
                      $i
                  )
              );
          }
      }
  }

  $content .= $TANGO->tpl->entity(
      'pagination',
      'pages',
      $pag
  );

?>