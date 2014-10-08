<?php

  define('BASEPATH', 'Forum');
  require_once('applications/wrapper.php');

  if( !$TANGO->perm->check('create_thread') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.

  $TANGO->tpl->getTpl('page');

  if( $PGET->g('node') ) {

      $node  = clean($PGET->g('node'));
      $MYSQL->where('id', $node);
      $query = $MYSQL->get('{prefix}forum_node');

      if( !empty($query) ) {

          $allowed = explode(',', $query['0']['allowed_usergroups']);
          if( !in_array($TANGO->sess->data['user_group'], $allowed) ) {
            redirect(SITE_URL . '/404.php');
          }

          if( $query['0']['node_locked'] == 1 ) {
            if( !$TANGO->perm->check('access_moderation') ) {
              redirect(SITE_URL . '/404.php');
            }
          }

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
          if( $query['0']['node_type'] == 2 ) {
            $parent_node = node($query['0']['parent_node']);

            $breadcrumbs .= $TANGO->tpl->entity(
              'breadcrumbs_before',
              array(
                'link',
                'name'
              ),
              array(
                SITE_URL . '/node.php/' . $parent_node['name_friendly'] . '.' . $parent_node['id'],
                $parent_node['node_name']
                )
            );

            $breadcrumbs .= $TANGO->tpl->entity(
              'breadcrumbs_before',
              array(
                'name'
              ),
              array(
                $query['0']['node_name']
                )
              );

          } elseif( $query['0']['node_type'] == 1 ) {

            $breadcrumbs .= $TANGO->tpl->entity(
              'breadcrumbs_before',
              array(
                'link',
                'name'
              ),
              array(
                SITE_URL . '/node.php/' . $query['0']['name_friendly'] . '.' . $query['0']['id'],
                $query['0']['node_name']
                )
            );

          }

          $breadcrumbs .= $TANGO->tpl->entity(
            'breadcrumbs_current',
            'name',
            $LANG['bb']['new_thread_breadcrumb']
          );

          $breadcrumbs = $TANGO->tpl->entity(
            'breadcrumbs',
            'bread',
            $breadcrumbs
          );

          $notice  = '';
          $content = '';

          if( isset($_POST['create']) ) {
              try {

                  NoCSRF::check( 'csrf_token', $_POST );
                  $thread_title = clean($_POST['title']);
                  //die($_POST['content']);
                  $thread_cont  = emoji_to_text($_POST['content']);
                  //die($thread_title);

                  $data = array($TANGO->sess->data['id']);
                  $c_query      = $MYSQL->rawQuery("SELECT * FROM {prefix}forum_posts WHERE post_user = ? ORDER BY post_time DESC LIMIT 1", $data);
                  $c_query['0']['post_content'] = (isset($c_query['0']['post_content']))? $c_query['0']['post_content'] : '';


                  if( !$thread_title or !$thread_cont ) {
                      throw new Exception ($LANG['global_form_process']['all_fields_required']);
                  } elseif( $c_query['0']['post_content'] == $thread_cont ) {
                      throw new Exception ($LANG['global_form_process']['different_message_previous']);
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
                          'last_updated' => $time,
                          'watchers' => $TANGO->sess->data['id']
                      );

                      /*
                       * Mentions
                       */
                      preg_match_all('/@(\w+)/', $thread_cont, $mentions);
                      $mentions = array_filter(array_unique($mentions['1']));
                      if( !empty($mentions['1']) ) {
                        foreach( $mentions['1'] as $mention ) {
                          if( $TANGO->sess->data['username'] !== $mention ) {
                            $user = $TANGO->user($mention);
                            $TANGO->user->notifyUser(
                              'mention',
                              $user['id'],
                              true,
                              array(
                                'username' => $TANGO->sess->data['username'],
                                'link' => SITE_URL . '/thread.php/' . $origin['title_friendly'] . '.' . $origin['id']
                                )
                              );
                          }
                        }
                      }

                      try {
                          $MYSQL->insert('{prefix}forum_posts', $data);

                          $MYSQL->where('post_time', $time);
                          $tid = $MYSQL->get('{prefix}forum_posts');

                          //redirect(SITE_URL . '/thread.php/v/' . $friendly_url . '.' $tid['0']['id']);
                          $notice .= $TANGO->tpl->entity(
                              'success_notice',
                              'content',
                              $LANG['global_form_process']['thread_create_success']
                          );
                          $community = $MYSQL->get('{prefix}users');
                          foreach($community as $user){
                            $TANGO->node->thread_mark_unread($tid['0']['id'], $user['id'],'0');
                          }
                          //die(SITE_URL . '/thread.php/v/' . $friendly_url . '.' . $tid['0']['id']);
                          redirect(SITE_URL . '/thread.php/' . $friendly_url . '.' . $tid['0']['id']);

                      } catch (mysqli_sql_exception $e) {
                          throw new Exception ($LANG['global_form_process']['error_creating_thread']);
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
                  'breadcrumbs',
                  'form_id',
                  'csrf_input',
                  'create_thread_form_action',
                  'title_name',
                  'editor_id',
                  'textarea_name',
                  'submit_name'
              ),
              array(
                  $breadcrumbs,
                  'tango_form',
                  CSRF_INPUT,
                  SITE_URL . '/new.php/node/' . $node,
                  'title',
                  'editor',
                  'content',
                  'create'
              )
          );
          $icon_package = array();
          
          foreach($ICONS as $category=>$icons_cat)
          {
            $icon_package[$category] = '';
            foreach($icons_cat as $code=>$html){
                $icon_package[$category] .= '<span style="font-size: 30px;" title="'.$code.'">'.$html.'</span> ';
            }
          }
          $content .= $TANGO->tpl->entity(
              'smiley_list',
              array(
                  'smilies',
                  'misc',
                  'food',
                  'animals'
              ),
              array(
                  $icon_package['smilies'],
                  $icon_package['misc'],
                  $icon_package['food'],
                  $icon_package['animals']
              )
          );
          
          
            
          $TANGO->tpl->addParam(
              array(
                  'page_title',
                  'content'
              ),
              array(
                  $LANG['bb']['new_thread_in'] . ' ' . $query['0']['node_name'],
                  $notice . $content
              )
          );

      } else {
          redirect(SITE_URL);
      }

  } else {
      redirect(SITE_URL);
  }

  echo $TANGO->tpl->output();

?>