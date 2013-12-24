<?php

  /*
   * Register Module for TangoBB.
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }

  $page = $PGET->g('page');
  $page = (!$page)? '1' : $PGET->g('page');
  $sort = $PGET->g('sort');

  $content = '';
  foreach(getMembers($page, $sort) as $user) {
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
              $p_count['user_avatar'],
              $p_count['username_style'],
              SITE_URL . '/members.php/cmd/user/id/' . $user['id'],
              date('M jS, Y', $user['date_joined']),
              $p_count['post_count']
          )
      );
  }

  $content = $TANGO->tpl->entity(
    'members_page_head',
    array(
      'members',
      //Sorting
      'sort_date_joined_asc',
      'sort_date_joined_desc',
      'sort_name_asc',
      'sort_name_desc'
    ),
    array(
      $content,
      //Sorting
      SITE_URL . '/members.php/sort/date_joined_asc',
      SITE_URL . '/members.php/sort/date_joined_desc',
      SITE_URL . '/members.php/sort/username_asc',
      SITE_URL . '/members.php/sort/username_desc'
    )
  );

  $total_pages = ceil(fetchTotalMembers() / 20);

  $pag = '';
  if( $total_pages > 1 ) {
      $i    = '';
      for( $i = 1; $i <= $total_pages; ++$i ) {
          $link = ($sort)? SITE_URL . '/members.php/sort/' . $sort . '/page/' . $i : SITE_URL . '/members.php/page/' . $i;
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
                      $link,
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