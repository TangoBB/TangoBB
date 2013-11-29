<?php

  define('BASEPATH', 'Forum');
  require_once('applications/wrapper.php');

  if( !$TANGO->perm->check('reply_thread') ) { header('Location:' . SITE_URL); }//Checks if user has permission to create a thread.

  $TANGO->tpl->getTpl('page');
  $notice  = '';
  $content = '';

  function alt_csrf() {
      $return = sha1(sha1(uniqid() . time() . uniqid()));
      $_SESSION['tango_alt_csrf'] = $return;
      return $return;
  }

  if( $PGET->g('thread') ) {
      
      $thread = clean($PGET->g('thread'));
      $MYSQL->where('post_type', '1');
      $MYSQL->where('id', $thread);
      $query = $MYSQL->get('{prefix}forum_posts');
      
      if( !empty($query) ) {
          
          $q_query = false;
          if( $PGET->g('quote') ) {
              $MYSQL->where('id', $PGET->g('quote'));
              $q_query = $MYSQL->get('{prefix}forum_posts');
          }
          
          if( isset($_POST['reply']) ) {
              try {
                  
                  //echo $_POST['csrf_token'] . '<br />' . $_SESSION['csrf_csrf_token'];
                  
                  if( !empty($q_query) ) {
                      if( $_POST['csrf_token'] !== $_SESSION['tango_alt_csrf'] ) {
                          throw new Exception ('Invalid CSRF token!');
                      }
                  } else {
                      NoCSRF::check('csrf_token', $_POST, true, 60*10, true);
                  }
                  
                  $cont    = $_POST['content'];
                  $cont    = ( !empty($q_query) )? '[quote]' . $PGET->g('quote') . '[/quote]' . $cont : $cont;
                  
                  $c_query = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE post_user = {$TANGO->sess->data['id']} ORDER BY post_time DESC LIMIT 1");
                  
                  if( !$cont ) {
                      throw new Exception ('All fields are required!');
                  } elseif( $c_query['0']['post_content'] == $cont ) {
                      throw new Exception ('Please write a different message from your last post.');
                  } else {
                      
                      $origin = thread($thread);
                      $time   = time();
                      
                      $data = array(
                          'post_content' => $cont,
                          'post_time' => $time,
                          'post_user' => $TANGO->sess->data['id'],
                          'origin_node' => $origin['origin_node'],
                          'origin_thread' => $thread,
                          'post_type' => '2'
                      );
                      
                      if( $MYSQL->insert('{prefix}forum_posts', $data) ) {
                          $t_data = array(
                              'last_updated'=> $time
                          );
                          $MYSQL->where('id', $thread);
                          if( $MYSQL->update('{prefix}forum_posts', $t_data) ) {
                              header('Location: ' . SITE_URL . '/thread.php/v/' . $origin['title_friendly'] . '.' . $origin['id']);
                          } else {
                              header('Location: ' . SITE_URL . '/thread.php/v/' . $origin['title_friendly'] . '.' . $origin['id']);
                          }
                      } else {
                          throw new Exception ('Error replying to thread. Try again later.');
                      }
                      
                  }
                  
              } catch ( Exception $e ) {
                  $notice .= $TANGO->tpl->entity(
                      'danger_notice',
                      'content',
                      $e->getMessage()
                  );
              }
          }
          
          if( !empty($q_query) ) {
              define('CSRF_TOKEN', alt_csrf());
          } else {
              define('CSRF_TOKEN', NoCSRF::generate( 'csrf_token' ));
          }
          
          $quote_user = ( !empty($q_query) )? $TANGO->user($q_query['0']['post_user']) : '';
          $quote_post = ( !empty($q_query) )? $TANGO->tpl->entity(
              'quote_post',
              array(
                  'quoted_post_content',
                  'quoted_post_user'
              ),
              array(
                  $TANGO->bb->parser->parse($TANGO->lib_parse->removeQuote($q_query['0']['post_content'])),
                  $quote_user['username']
              )
          ) : '';
             
          $content = $TANGO->tpl->entity(
              'reply_thread_page',
              array(
                  'quote_post',
                  'form_id',
                  'csrf_input',
                  'create_thread_form_action',
                  'editor_id',
                  'textarea_name',
                  'submit_name',
                  'thread_url'
              ),
              array(
                  $quote_post,
                  'tango_form',
                  '<input type="hidden" name="csrf_token" value="' . CSRF_TOKEN . '" />',
                  '',
                  'editor',
                  'content',
                  'reply',
                  SITE_URL . '/thread.php/v/' . $query['0']['title_friendly'] . '.' . $query['0']['id']
              )
          );
          
          
          
          $TANGO->tpl->addParam(
              array(
                  'page_title',
                  'content'
              ),
              array(
                  'New reply in: ' . $query['0']['post_title'],
                  $notice . $content
              )
          );
          
      } else {
          header('Location: ' . SITE_URL . '/404.php');
      }
      
  } else {
      header('Location: ' . SITE_URL . '/404.php');
  }

  echo $TANGO->tpl->output();

?>