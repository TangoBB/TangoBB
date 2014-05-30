<?php

   /*
   * Conversations module for TangoBB
   * Delete script by N8boy
   * Everything that you want to display MUST be in the $content variable.
   */
   
   if( !defined('BASEPATH') ){ die(); }
   
   $content = '';
   $page_title = '';
   
   if($PGET->g('id')) {
    $data_temp = array($PGET->g('id'));
    $msg = $MYSQL->rawQuery("SELECT * FROM 
                             {prefix}messages 
                             WHERE 
                             id = ?  
                             LIMIT 1", $data_temp);
                             
    // store the data from database
    $sender_deleted   = $msg['0']['sender_deleted'];
    $receiver_deleted = $msg['0']['receiver_deleted'];
    // If user is sender                         
    if($msg['0']['message_sender'] == $TANGO->sess->data['id']) {
        // and the sender didn't delete the message
        if($msg['0']['sender_deleted'] == 0) {
            $sender_deleted = 1;
        }
    }
    if($msg['0']['message_receiver'] == $TANGO->sess->data['id']) {
        if($msg['0']['receiver_deleted'] == 0) {
            $receiver_deleted = 1;
        }
    }
    
    $data = array(
    'receiver_deleted'  =>  $receiver_deleted,
    'sender_deleted'    =>  $sender_deleted
    ); 
    
    $MYSQL->where('id', $PGET->g('id'));
    $MYSQL->update('{prefix}messages', $data);
    
    
    if($receiver_deleted == 1 && $sender_deleted == 1) {
        // Deleting the main message
        $MYSQL->where('id',$PGET->g('id'));
        $MYSQL->delete('{prefix}messages');
    
        // Deleting the answers
        $MYSQL->where('origin_message',$PGET->g('id'));
        $MYSQL->delete('{prefix}messages');
    }
    
    
    redirect(SITE_URL . '/conversations.php');
   }
   
?>