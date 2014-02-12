<?php

  /*
   * Conversations module for TangoBB
   * Everything that you want to display MUST be in the $content variable.
   */
  if( !defined('BASEPATH') ){ die(); }

  $page_title = '';
  $content    = '';

  if( $PGET->g('v') ) {
      
      $get = clean($PGET->g('v'));
      
      $MYSQL->where('id', $get);
      $MYSQL->where('message_type', 1);
      $query = $MYSQL->get('{prefix}messages');
      if( !empty($query) ) {
          $auth = array(
            $query['0']['message_sender'],
            $query['0']['message_receiver']
          );
          if( !in_array($TANGO->sess->data['id'], $auth) ) {
            /*if( $query['0']['message_receiver'] !== $TANGO->sess->data['id'] ) {
              redirect(SITE_URL . '/404.php');
            }*/
            redirect(SITE_URL . '/404.php');
          }

          $page_title = $query['0']['message_title'];
          
          $user       = $TANGO->user($query['0']['message_sender']);
          
          $breadcrumb = '';
          
          $reply_button  = '';
          $edit_thread   = '';
          if( $TANGO->perm->check('reply_thread') ) {
              $reply_button  .= $TANGO->tpl->entity(
                      'reply_thread',
                      'link',
                      SITE_URL . '/conversations.php/cmd/reply/id/' . $get,
                      'buttons'
              );
          }
          if( $query['0']['receiver_viewed'] == 0 ) {
            $data = array(
              'receiver_viewed' => 1
            );
            $MYSQL->where('id', $get);
            $MYSQL->update('{prefix}messages', $data);
          }

          $thread_mod_tools = '';
          
          $starter    = $TANGO->tpl->entity(
              'thread_starter',
              array(
                  'breadcrumbs',
                  'reply_button',
                  'quote_post',
                  'edit_post',
                  'report_post',
                  'user_avatar',
                  'profile_url',
                  'username',
                  'date_joined',
                  'postcount',
                  'thread_content',
                  'user_signature',
                  'post_time',
                  'mod_tools'
              ),
              array(
                  $breadcrumb,
                  $reply_button,
                  '',
                  '',
                  '',
                  $user['user_avatar'],
                  SITE_URL . '/members.php/cmd/user/id/' . $user['id'],
                  $user['username_style'],
                  date('M jS, Y', $user['date_joined']),
                  $user['post_count'],
                  $TANGO->lib_parse->parse($query['0']['message_content']),
                  $TANGO->lib_parse->parse($user['user_signature']),
                  date('F j, Y', $query['0']['message_time']),
                  $thread_mod_tools
              )
          );

          $content = $starter;
          
          $MYSQL->where('origin_message', $get);
          $MYSQL->where('message_type', 2);
          $rep = $MYSQL->get('{prefix}messages');
          foreach( $rep as $post ) {

              if( $post['receiver_viewed'] == 0 ) {
                $data = array(
                  'receiver_viewed' => 1
                  );
                $MYSQL->where('id', $post['id']);
                $MYSQL->update('{prefix}messages', $data);
              }

              $ur = $TANGO->user($post['message_sender']);
              
              //die($quote_template);
              //$post['post_content'] = $TANGO->lib_parse->parseQuote($post['post_content']);
              //die($post['post_content']);
              //die($quote_template);
             // $die = sscanf('<blockquote id="post_quote"><p>qweqdasdasfds</p><small class="quote_username">awidas_sdai83</small></blockquote>asdasfdsas', $quote_template);
              //die(var_dump($die));
              /*$post['post_content'] = str_replace(
                  array(
                      '&lt;blockquote&gt;',
                      '&lt;/blockquote&gt;',
                      '&lt;small&gt;',
                      '&lt;/small&gt;',
                      '&lt;p&gt;',
                      '&lt;/p&gt;'
                  ), 
                  array(
                      '<blockquote>',
                      '</blockquote>',
                      '<small>',
                      '</small>'
                  ), 
                  
              );*/
              //$post['message_content'] = $TANGO->lib_parse->parseQuote($TANGO->bb->parser->parse($post['message_content']));
              //die($post['post_content']);
              //$die = sscanf($post['post_content'], '<$blockquote$>%s </$blockquote$>');
              /*preg_match_all('/<blockquote>(.*?)<\/blockquote>/', $post['post_content'], $die);
              die(var_dump($die));
              $post['post_content'] = $TANGO->tpl->entity(
                  'quote_post',
                  array(
                      'quoted_post_content',
                      'quoted_post_user'
                  ),
                  array(
                      '%s',
                      '%s'
                  )
              );*/
              
              $content .= $TANGO->tpl->entity(
                  'thread_reply',
                  array(
                      'post_id',
                      'quote_post',
                      'edit_post',
                      'report_post',
                      'user_avatar',
                      'profile_url',
                      'username',
                      'date_joined',
                      'postcount',
                      'reply_content',
                      'user_signature',
                      'post_time',
                      'mod_tools'
                  ),
                  array(
                      'post-' . $post['id'],
                      '',
                      '',
                      '',
                      $ur['user_avatar'],
                      SITE_URL . '/members.php/cmd/user/id/' . $ur['id'],
                      $ur['username_style'],
                      date('M jS, Y', $ur['date_joined']),
                      $ur['post_count'],
                      $TANGO->lib_parse->parse($post['message_content']),
                      $TANGO->lib_parse->parse($ur['user_signature']),
                      date('F j, Y', $post['message_time']),
                      ''
                  )
              );
          }
          
          define('CSRF_TOKEN', NoCSRF::generate( 'csrf_token' ));
          define('CSRF_INPUT', '<input type="hidden" name="csrf_token" value="' . CSRF_TOKEN . '">');
          //Reply textarea.
          if( $TANGO->perm->check('reply_thread') ) {
              $content .= $TANGO->tpl->entity(
                  'reply_thread',
                  array(
                      'form_thread',
                      'csrf_input',
                      'textarea_name',
                      'reply_form_action',
                      'editor_id',
                      'submit_name'
                  ),
                  array(
                      'tango_form',
                      CSRF_INPUT,
                      'content',
                      SITE_URL . '/conversations.php/cmd/reply/id/' . $get,
                      'editor',
                      'reply'
                  )
              );
          }
          
      } else {
          redirect(SITE_URL . '/404.php');
      }
      
  } else {
      redirect(SITE_URL);
  }

?>