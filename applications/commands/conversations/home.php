<?php

  /*
   * Conversations module for TangoBB
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }
  $content    = '';
  $page_title = $LANG['bb']['conversations']['page_conversations'];

  if( !$TANGO->sess->isLogged ){ redirect(SITE_URL); } //If user is not logged in.
  /*$data = array($TANGO->sess->data['id'], $TANGO->sess->data['id']);
  $query = $MYSQL->rawQuery("SELECT * FROM
                          {prefix}messages
                          WHERE
                          (message_sender = ? OR message_receiver = ?)
                          AND
                          message_type = 1
                          ORDER BY
                          message_time
                          DESC", $data);*/
  $MYSQL->bindMore(
    array(
      'message_sender' => $TANGO->sess->data['id'],
      'message_receiver' => $TANGO->sess->data['id']
    )
  );
  $query = $MYSQL->query("SELECT * FROM {prefix}messages WHERE (message_sender = :message_sender OR message_receiver = :message_receiver) AND message_type = 1 ORDER BY message_time DESC");

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
  $table = '';
  if( !empty($query) ) {
    $table .= '<table class="table">'; // N8boy
    $counter = 0;
      foreach( $query as $msg ) {
          $badge      = '';
          // Getting the latest reply
          /*$data_latest_reply  =array($msg['id']);
          $last_reply = $MYSQL->rawQuery("SELECT * FROM 
                                                {prefix}messages 
                                                WHERE 
                                                origin_message = ? 
                                                ORDER BY 
                                                message_time 
                                                DESC 
                                                LIMIT 1", $data_latest_reply);*/
          $MYSQL->bind('origin_message', $msg['id']);
          $last_reply = $MYSQL->query("SELECT * FROM {prefix}messages WHERE origin_message = :origin_message ORDER BY message_time DESC LIMIT 1");
          //var_dump ($query_last_reply);
          
          $sender   = $TANGO->user($msg['message_sender']);
          $receiver = $TANGO->user($msg['message_receiver']);

          $message_time = simplify_time($msg['message_time']);
          
          /** Added by N8boy:
          *   TODO: - Creating a delete function
          */
          if($sender['id'] == $TANGO->sess->data['id'] && $msg['sender_deleted'] == 0) {
            $check = true;
            
          }
          elseif($receiver['id'] == $TANGO->sess->data['id'] && $msg['receiver_deleted'] == 0) {
            $check = true;
          }
          else {
            $check = false;
          }
          if($check == true)
          {
            $counter += 1;
          if(isset($last_reply['0'])) {
            if ($last_reply['0']['receiver_viewed'] == 0 && $last_reply['0']['message_receiver'] == $TANGO->sess->data['id']) {  // notification for Receiver: New message
              $badge .= $TANGO->tpl->entity('conversation_new_message');
            }
            elseif($last_reply['0']['receiver_viewed'] == 0 && $last_reply['0']['message_sender'] == $TANGO->sess->data['id']) { // Sender can see if the receiver has viewed the last message
              $badge .= $TANGO->tpl->entity('conversation_unread');
            }
            elseif($last_reply['0']['receiver_viewed'] == 1 && $last_reply['0']['message_sender'] == $TANGO->sess->data['id']) { // Sender can see if the receiver has viewed the last message
              $badge .= $TANGO->tpl->entity('conversation_read');
            }
          }
          elseif($msg['receiver_viewed'] == 0 && $receiver['id'] == $TANGO->sess->data['id']) {
            $badge .= $TANGO->tpl->entity('conversation_new_message');
          }
          elseif($msg['receiver_viewed'] == 0 && $sender['id'] == $TANGO->sess->data['id']) {
            $badge .= $TANGO->tpl->entity('conversation_unread');
          }
          elseif($msg['receiver_viewed'] == 1 && $sender['id'] == $TANGO->sess->data['id']) {
            $badge .= $TANGO->tpl->entity('conversation_read');
          }
          
          $table .= '<tr>
                        <td style="width: 55px;">
                            <img style="width:55px;height:55px;" src="' . $sender['user_avatar'] . '" />
                        </td>
                        <td><span style="float:right">'. $badge .'</span>
                            <h4><a href="' . SITE_URL . '/conversations.php/cmd/view/v/' . $msg['id'] . '">' . $msg['message_title'] . '</a></h4>
                            ' . $LANG['bb']['conversations']['starter'] .' <a href="' . SITE_URL . '/members.php/cmd/user/id/' . $sender['id'] . '">' . $sender['username'] . '</a>, ' . $LANG['bb']['conversations']['reciever'] .' <a href="' . SITE_URL . '/members.php/cmd/user/id/' . $receiver['id'] . '">' . $receiver['username_style'] . '</a>
                        </td>
                        <td style="width: 250px;">
                            ' . amount_replies($msg['id']) . ' Replies<br />
                            ' . $message_time['time'] . '
                        </td>
                        <td style="width: 25px;">';
         $table .= $TANGO->tpl->entity('conversation_delete', 'link', SITE_URL . '/conversations.php/cmd/delete/id/' . $msg['id']);
         $table .=    '</td>
                       </tr>';
         /* $content .= '<div style="border-bottom:1px solid #ccc;padding-bottom:10px;overflow:auto;">
                         <h4><a href="' . SITE_URL . '/conversations.php/cmd/view/v/' . $msg['id'] . '">' . $msg['message_title'] . '</a></h4>
                         ' . $LANG['bb']['conversations']['by'] . ' <a href="' . SITE_URL . '/members.php/cmd/user/id/' . $sender['id'] . '">' . $sender['username_style'] . '</a> on ' . date('F j, Y', $msg['message_time']) . '<br />
                         ' . $LANG['bb']['conversations']['for'] . ' <a href="' . SITE_URL . '/members.php/cmd/user/id/' . $receiver['id'] . '">' . $receiver['username_style'] . '</a>
                       </div>';
                       */
      }
      }
      $table .= '</table>'; // N8boy
      
      if ($counter == 0) {
        $table .= $TANGO->tpl->entity(
          'danger_notice',
          'content',
          $LANG['bb']['conversations']['no_conversations']
      );
      }
      
  } else {
      $table .= $TANGO->tpl->entity(
          'danger_notice',
          'content',
          $LANG['bb']['conversations']['no_conversations']
      );
  }
  $content .= $TANGO->tpl->entity(
    'conversation_overview',
    array(
      'overview_header',
      'conversations'
    ),
    array(
      $LANG['bb']['conversations']['my_conversations'],
      $table
    )
  );

?>