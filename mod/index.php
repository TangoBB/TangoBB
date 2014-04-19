<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_moderation') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.
  $TANGO->tpl->getTpl('page');

  $content    = '';
  $page_title = '';

  $query = $MYSQL->query("SELECT * FROM
                          {prefix}reports
                          WHERE
                          report_close = 0
                          ORDER BY
                          reported_time
                          DESC");

  if( !empty($query) ) {
      $posts = '';
      $users = '';
      foreach( $query as $report ) {
          if( $report['reported_post'] !== 0 ) {
              
              $user  = $TANGO->user($report['reported_by']);
              $MYSQL->where('id', $report['reported_post']);
              $query = $MYSQL->get('{prefix}forum_posts');
              if( $query['0']['post_type'] == "1" ) {
                  $posts .= '<div style="overflow:auto;border-bottom:1px solid #ccc;">
                               <p>
                                 ' . $LANG['mod']['reports']['thread'] . ' <a href="' . SITE_URL . '/thread.php/v/' . $query['0']['title_friendly'] . '.' . $query['0']['id'] . '">' . $query['0']['post_title'] . '</a><br />
                                 ' . $LANG['mod']['reports']['reason'] . ' ' . $report['report_reason'] . '<br />
                                 ' . $LANG['mod']['reports']['reported_time'] . ' ' . date('F j, Y', $report['reported_time']) . '<br />
                                 [<a href="' . SITE_URL . '/mod/delete_report.php/id/' . $report['id'] . '">' . $LANG['mod']['del_report']['delete'] . '</a>]
                               </p>
                             </div>';
              } elseif( $query['0']['post_type'] !== "2" ) {
                  $t      = thread($query['0']['origin_thread']);
                  $posts .= '<div style="overflow:auto;border-bottom:1px solid #ccc;">
                               <p>
                                 ' . $LANG['mod']['reports']['thread'] . ' <a href="' . SITE_URL . '/thread.php/v/' . $t['title_friendly'] . '.' . $t['id'] . '#post-' . $report['reported_post'] . '">' . $t['post_title'] . '</a><br />
                                 Reason: ' . $report['report_reason'] . '<br />
                                 Reported Time: ' . date('F j, Y', $report['reported_time']) . '<br />
                                 [<a href="' . SITE_URL . '/mod/delete_report.php/id/' . $report['id'] . '">' . $LANG['mod']['del_report']['delete'] . '</a>]
                               </p>
                             </div>';
              }
              
          } elseif( $report['reported_user'] !== 0 ) {
              
              $user   = $TANGO->user($report['reported_user']);
              
              $users .= '<div style="overflow:auto;border-bottom:1px solid #ccc;">
                               <p>
                                 ' . $LANG['mod']['reports']['user'] . ' <a href="' . SITE_URL . '/members.php/cmd/user/id/' . $user['id'] . '">' . $user['username'] . '</a><br />
                                 ' . $LANG['mod']['reports']['reason'] . ' ' . $report['report_reason'] . '<br />
                                 ' . $LANG['mod']['reports']['reported_time'] . ' ' . date('F j, Y', $report['reported_time']) . '<br />
                                 [<a href="' . SITE_URL . '/mod/delete_report.php/id/' . $report['id'] . '">' . $LANG['mod']['del_report']['delete'] . '</a>]
                               </p>
                             </div>';;
              
          }
          
      }
      
      $content .= $TANGO->tpl->entity(
          'mod_reports',
          array(
              'reported_posts',
              'reported_users'
          ),
          array(
              $posts,
              $users
          )
      );
      
  } else {
      $content .= 'No reports yet.';
  }

  $TANGO->tpl->addParam(
      array(
          'page_title',
          'content'
      ),
      array(
          $LANG['mod']['reports']['reports'],
          $content
      )
  );

  echo $TANGO->tpl->output();

?>