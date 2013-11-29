<?php

  define('BASEPATH', 'Forum');
  require_once('applications/wrapper.php');

  if( !$TANGO->perm->check('reply_thread') ) { header('Location:' . SITE_URL); }//Checks if user has permission to create a thread.
  $TANGO->tpl->getTpl('page');

  if( $PGET->g('post') ) {
      
      $post_id = clean($PGET->g('post'));
      $MYSQL->where('id', $post_id);
      $query = $MYSQL->get('{prefix}forum_posts');
      
      if( !empty($query) ) {
          
          if( $TANGO->perm->check('access_moderation') ) {
          } elseif(  $query['0']['post_user'] !== $TANGO->sess->data['id'] ) {
              header('Location: ' . SITE_URL);
          }
          
          $notice        = '';
          $content       = '';
          $origin_thread = '';
          if( $query['0']['post_type'] == "1" ) {
              $page_title     = $query['0']['post_title'];
              $origin_thread .= $query['0']['title_friendly'] . '.' . $query['0']['id'];
          } else {
              $thread         = thread($query['0']['origin_thread']);
              $page_title     = $thread['post_title'];
              $origin_thread .= $thread['title_friendly'] . '.' . $thread['id'];
          }
          
          if( isset($_POST['edit']) ) {
              try {
                  
                  NoCSRF::check( 'csrf_token', $_POST, true, 60*10, true );
                  
                  $con = $_POST['content'];
                  
                  if( !$con ) {
                      throw new Exception ('All fields are required!');
                  } else {
                      
                      $data = array(
                          'post_content' => $con
                      );
                      $MYSQL->where('id', $post_id);
                      
                      if( $MYSQL->update('{prefix}forum_posts', $data) ) {
                          header('Location: ' . SITE_URL . '/thread.php/v/' . $origin_thread);
                      } else {
                          throw new Exception ('Error updating post. Try again later.');
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
          
          $content = '<form id="tango_form" action="" method="POST">
                        ' . CSRF_INPUT . '
                        <textarea id="editor" name="content" style="width:100%;height:300px;max-width:100%;min-width:100%;">' . $query['0']['post_content'] . '</textarea>
                        <br />
                        <input type="submit" name="edit" value="Edit Post" />
                      </form>';
          
          $TANGO->tpl->addParam(
              array(
                  'page_title',
                  'content'
              ),
              array(
                  'Edit Post in: ' . $page_title,
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