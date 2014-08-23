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
      $posts = '<table class="table"><tr><th style="width: 20%">' . $LANG['mod']['reports']['thread'] . '</th><th style="width: 50%">' . $LANG['mod']['reports']['reason'] . '</th><th>' . $LANG['mod']['reports']['reported_time'] . '</th><th></th></tr>';
      $users = '';
      foreach( $query as $report ) {
      $reported_time = simplify_time($report['reported_time'], @$TANGO->sess->data['location']);
          if( $report['reported_post'] !== 0 ) {
              
              $user  = $TANGO->user($report['reported_by']);
              $MYSQL->where('id', $report['reported_post']);
              $query = $MYSQL->get('{prefix}forum_posts');
              if( $query['0']['post_type'] == "1" ) {
                  $posts .= '<tr>
                                <td><a href="' . SITE_URL . '/thread.php/' . $query['0']['title_friendly'] . '.' . $query['0']['id'] . '">' . $query['0']['post_title'] . '</a></td>
                                <td>' . $report['report_reason'] . '</td>
                                <td>' . $reported_time['time'] . '</td>
                                <td><a href="' . SITE_URL . '/mod/delete_report.php/id/' . $report['id'] . '">' . $LANG['mod']['del_report']['delete'] . '</a></td>
                             </tr>';
                  
                  /*$posts .= '<div style="overflow:auto;border-bottom:1px solid #ccc;">
                               <p>
                                 ' . $LANG['mod']['reports']['thread'] . ' <a href="' . SITE_URL . '/thread.php/' . $query['0']['title_friendly'] . '.' . $query['0']['id'] . '">' . $query['0']['post_title'] . '</a><br />
                                 ' . $LANG['mod']['reports']['reason'] . ' ' . $report['report_reason'] . '<br />
                                 ' . $LANG['mod']['reports']['reported_time'] . ' ' . date('F j, Y', $report['reported_time']) . '<br />
                                 [<a href="' . SITE_URL . '/mod/delete_report.php/id/' . $report['id'] . '">' . $LANG['mod']['del_report']['delete'] . '</a>]
                               </p>
                             </div>';*/
              } elseif( $query['0']['post_type'] !== "2" ) {
                  $t      = thread($query['0']['origin_thread']);
                  
                  $posts .= '<tr>
                                <td><a href="' . SITE_URL . '/thread.php/' . $t['title_friendly'] . '.' . $t['id'] . '#post-' . $report['reported_post'] . '">' . $t['post_title'] . '</a></td>
                                <td>' . $report['report_reason'] . '</td>
                                <td>' . $reported_time['time'] . '</td>
                                <td><a href="' . SITE_URL . '/mod/delete_report.php/id/' . $report['id'] . '">' . $LANG['mod']['del_report']['delete'] . '</a></td>
                             </tr>';
                  /*$posts .= '<div style="overflow:auto;border-bottom:1px solid #ccc;">
                               <p>
                                 ' . $LANG['mod']['reports']['thread'] . ' <a href="' . SITE_URL . '/thread.php/' . $t['title_friendly'] . '.' . $t['id'] . '#post-' . $report['reported_post'] . '">' . $t['post_title'] . '</a><br />
                                 Reason: ' . $report['report_reason'] . '<br />
                                 Reported Time: ' . date('F j, Y', $report['reported_time']) . '<br />
                                 [<a href="' . SITE_URL . '/mod/delete_report.php/id/' . $report['id'] . '">' . $LANG['mod']['del_report']['delete'] . '</a>]
                               </p>
                             </div>';*/
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
      $posts .= '</table>';
      
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
      $content .= $TANGO->tpl->entity(
                    'warning_notice',
                    'content',
                    $LANG['mod']['reports']['no_reports']
      );   
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