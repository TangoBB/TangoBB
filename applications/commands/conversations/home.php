<?php

  /*
   * Conversations module for TangoBB
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }
  $content    = '';
  $page_title = $LANG['bb']['conversations']['page_conversations'];

  if( !$TANGO->sess->isLogged ){ redirect(SITE_URL); } //If user is not logged in.
  $data = array($TANGO->sess->data['id'], $TANGO->sess->data['id']);
  $query = $MYSQL->rawQuery("SELECT * FROM
                          {prefix}messages
                          WHERE
                          (message_sender = ? OR message_receiver = ?)
                          AND
                          message_type = 1
                          ORDER BY
                          message_time
                          DESC", $data);
  //Breadcrumbs
  $TANGO->tpl->addBreadcrumb(
    $LANG['bb']['forum'],
    SITE_URL . '/forum.php'
  );
  $TANGO->tpl->addBreadcrumb(
    $LANG['bb']['conversations']['page_conversations'],
    SITE_URL . '/conversations.php',
    true
  );

  $content .= $TANGO->tpl->breadcrumbs();
  $content .= '<div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><strong>' . $LANG['bb']['conversations']['my_conversations'] . '</strong></div>'; // N8boy
  if( !empty($query) ) {
    $content .= '<table class="table table-striped">'; // N8boy
      foreach( $query as $msg ) {
          
          $sender   = $TANGO->user($msg['message_sender']);
          $receiver = $TANGO->user($msg['message_receiver']);
          $message_time = simplify_time($msg['message_time']);
          /** Added by N8boy:
          *   TODO: - Creating a delete function
          */
          $content .= '<tr>
                        <td style="width: 55px;">
                            <img class="avatar_mini" src="' . $sender['user_avatar'] . '" />
                        </td>
                        <td>
                            <h4><a href="' . SITE_URL . '/conversations.php/cmd/view/v/' . $msg['id'] . '">' . $msg['message_title'] . '</a></h4>
                            ' . $LANG['bb']['conversations']['starter'] .' <a href="' . SITE_URL . '/members.php/cmd/user/id/' . $sender['id'] . '">' . $sender['username_style'] . '</a>, ' . $LANG['bb']['conversations']['reciever'] .' <a href="' . SITE_URL . '/members.php/cmd/user/id/' . $receiver['id'] . '">' . $receiver['username_style'] . '</a>
                        </td>
                        <td style="width: 250px;">
                            ' . amount_replies($msg['id']) . ' Replies<br />
                            ' . $message_time['time'] . '
                        </td>
                        <td style="width: 25px;">
                            <i class="glyphicon glyphicon-trash"></i>
                        </td>
                       </tr>';
         /* $content .= '<div style="border-bottom:1px solid #ccc;padding-bottom:10px;overflow:auto;">
                         <h4><a href="' . SITE_URL . '/conversations.php/cmd/view/v/' . $msg['id'] . '">' . $msg['message_title'] . '</a></h4>
                         ' . $LANG['bb']['conversations']['by'] . ' <a href="' . SITE_URL . '/members.php/cmd/user/id/' . $sender['id'] . '">' . $sender['username_style'] . '</a> on ' . date('F j, Y', $msg['message_time']) . '<br />
                         ' . $LANG['bb']['conversations']['for'] . ' <a href="' . SITE_URL . '/members.php/cmd/user/id/' . $receiver['id'] . '">' . $receiver['username_style'] . '</a>
                       </div>';
                       */
         
          
      }
      $content .= '</table>'; // N8boy
  } else {
      $content .= $TANGO->tpl->entity(
          'danger_notice',
          'content',
          $LANG['bb']['conversations']['no_conversations']
      );
  }
  $content .= '</div>'; // N8boy

?>