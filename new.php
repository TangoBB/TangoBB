<?php

  define('BASEPATH', 'Forum');
  require_once('applications/wrapper.php');

  if( !$TANGO->perm->check('create_thread') ) { header('Location:' . SITE_URL); }//Checks if user has permission to create a thread.

  $TANGO->tpl->getTpl('page');

  if( $PGET->g('node') ) {
      
      $node  = clean($PGET->g('node'));
      $MYSQL->where('id', $node);
      $query = $MYSQL->get('{prefix}forum_node');
      
      if( !empty($query) ) {
          
          $notice  = '';
          $content = '';
          
          if( isset($_POST['create']) ) {
              try {
                  
                  NoCSRF::check( 'csrf_token', $_POST, true, 60*10, true );
                  $thread_title = clean($_POST['title']);
                  //die($_POST['content']);
                  $thread_cont  = $_POST['content'];
                  //die($thread_title);
                  
                  $c_query      = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE post_user = {$TANGO->sess->data['id']} ORDER BY post_time DESC LIMIT 1");
                  
                  if( !$thread_title or !$thread_cont ) {
                      throw new Exception ('All fields are required!');
                  } elseif( $c_query['0']['post_content'] == $thread_cont ) {
                      throw new Exception ('Please write a different message from your last post.');
                  } else {
                      
                      $friendly_url = title_friendly($thread_title);
                      $tags         = explode('_', $friendly_url);
                      $tags         = implode(',', $tags);
                      $time         = time();
                      
                      $data = array(
                          'post_title' => $thread_title,
                          'title_friendly' => $friendly_url,
                          'post_content' => $thread_cont,
                          'post_tags' => $tags,
                          'post_time' => $time,
                          'post_user' => $TANGO->sess->data['id'],
                          'origin_node' => $node,
                          'post_type' => '1',
                          'last_updated' => $time
                      );
                      
                      if( $MYSQL->insert('{prefix}forum_posts', $data) ) {
                          
                          $MYSQL->where('post_time', $time);
                          $tid = $MYSQL->get('{prefix}forum_posts');
                          
                          //header('Location: ' . SITE_URL . '/thread.php/v/' . $friendly_url . '.' $tid['0']['id']);
                          $notice .= $TANGO->tpl->entity(
                              'success_notice',
                              'content',
                              'Successfully created thread! Redirecting you...'
                          );
                          //die(SITE_URL . '/thread.php/v/' . $friendly_url . '.' . $tid['0']['id']);
                          header('Location: ' . SITE_URL . '/thread.php/v/' . $friendly_url . '.' . $tid['0']['id']);
                          
                      } else {
                          throw new Exception ('Error creating thread. Try again later.');
                      }
                      
                  }
                  
              } catch( Exception $e ) {
                  $notice .= $TANGO->tpl->entity(
                      'danger_notice',
                      'content',
                      $e->getMessage()
                  );
              }
          }
          
          define('CSRF_TOKEN', NoCSRF::generate( 'csrf_token' ));
          define('CSRF_INPUT', '<input type="hidden" name="csrf_token" value="' . CSRF_TOKEN . '">');
          
          $content .= $TANGO->tpl->entity(
              'create_thread',
              array(
                  'form_id',
                  'csrf_input',
                  'create_thread_form_action',
                  'title_name',
                  'editor_id',
                  'textarea_name',
                  'submit_name'
              ),
              array(
                  'tango_form',
                  CSRF_INPUT,
                  SITE_URL . '/new.php/node/' . $node,
                  'title',
                  'editor',
                  'content',
                  'create'
              )
          );
          
          $TANGO->tpl->addParam(
              array(
                  'page_title',
                  'content'
              ),
              array(
                  'New thread in: ' . $query['0']['node_name'],
                  $notice . $content
              )
          );
          
      } else {
          header('Location: ' . SITE_URL);
      }
      
  } else {
      header('Location: ' . SITE_URL);
  }

  echo $TANGO->tpl->output();

?>