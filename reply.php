<?php

  define('BASEPATH', 'Forum');
  require_once('applications/wrapper.php');

  if( !$TANGO->perm->check('reply_thread') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.

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

          $node        = node($query['0']['origin_node']);
          $breadcrumbs = $TANGO->tpl->entity(
            'breadcrumbs_before',
            array(
              'link',
              'name'
            ),
            array(
              SITE_URL . '/forum.php',
              $LANG['bb']['forum']
            )
          );
          if( $node['node_type'] == 2 ) {

            $parent_node = node($node['parent_node']);

            $breadcrumbs .= $TANGO->tpl->entity(
              'breadcrumbs_before',
              array(
                'link',
                'name'
              ),
              array(
                SITE_URL . '/node.php/v/' . $parent_node['name_friendly'] . '.' . $parent_node['id'],
                $parent_node['node_name']
              )
            );

            $breadcrumbs .= $TANGO->tpl->entity(
              'breadcrumbs_before',
              array(
                'link',
                'name'
              ),
              array(
                SITE_URL . '/node.php/v/' . $node['name_friendly'] . '.' . $node['id'],
                $node['node_name']
              )
            );

          } elseif( $node['node_type'] == 1 ) {
            $breadcrumbs .= $TANGO->tpl->entity(
              'breadcrumbs_before',
              array(
                'link',
                'name'
              ),
              array(
                SITE_URL . '/node.php/v/' . $node['name_friendly'] . '.' . $node['id'],
                $node['node_name']
              )
            );
          }
          $breadcrumbs .= $TANGO->tpl->entity(
            'breadcrumbs_before',
            array(
              'link',
              'name'
            ),
            array(
              SITE_URL . '/thread.php/v/' . $query['0']['title_friendly'] . '.' . $query['0']['id'],
              $query['0']['post_title']
            )
          );
          $breadcrumbs .= $TANGO->tpl->entity(
            'breadcrumbs_current',
            'name',
            $LANG['bb']['new_reply_breadcrumb']
          );

          $breadcrumbs = $TANGO->tpl->entity(
              'breadcrumbs',
              'bread',
              //'<li><a href="' . SITE_URL . '">Forum</a></li><li><a href="' . SITE_URL . '/node.php/v/' . $node['name_friendly'] . '.' . $node['id'] . '">' . $node['node_name'] . '</a></li><li class="active">' . $query['0']['post_title'] . '</a>'
              $breadcrumbs
          );

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
                      NoCSRF::check('csrf_token', $_POST);
                  }

                  $cont    = $_POST['content'];
                  $cont    = ( !empty($q_query) )? '[quote]' . $PGET->g('quote') . '[/quote]' . $cont : $cont;

				  $data = array($TANGO->sess->data['id']);
                  $c_query = $MYSQL->rawQuery("SELECT * FROM {prefix}forum_posts WHERE post_user = ? ORDER BY post_time DESC LIMIT 1", $data);

                  if( !$cont ) {
                      throw new Exception ($LANG['global_form_process']['all_fields_required']);
                  } elseif( $c_query['0']['post_content'] == $cont ) {
                      throw new Exception ($LANG['global_form_process']['different_message_previous']);
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

                      try {
                          $MYSQL->insert('{prefix}forum_posts', $data);
                          $t_data = array(
                              'last_updated'=> $time
                          );
                          $MYSQL->where('id', $thread);
                          try {
                              $MYSQL->update('{prefix}forum_posts', $t_data);
                              redirect(SITE_URL . '/thread.php/v/' . $origin['title_friendly'] . '.' . $origin['id']);
                          } catch (mysqli_sql_exception $e) {
                              redirect(SITE_URL . '/thread.php/v/' . $origin['title_friendly'] . '.' . $origin['id']);
                          }
                      } catch (mysqli_sql_exception $e) {
                          throw new Exception ($LANG['global_form_process']['error_replying_thread']);
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
                  $TANGO->lib_parse->parse($q_query['0']['post_content']),
                  $quote_user['username']
              )
          ) : '';

          $content = $TANGO->tpl->entity(
              'reply_thread_page',
              array(
                  'breadcrumbs',
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
                  $breadcrumbs,
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
                  $LANG['bb']['new_reply_in'] . ' ' . $query['0']['post_title'],
                  $notice . $content
              )
          );

      } else {
          redirect(SITE_URL . '/404.php');
      }

  } else {
      redirect(SITE_URL . '/404.php');
  }

  echo $TANGO->tpl->output();

?>