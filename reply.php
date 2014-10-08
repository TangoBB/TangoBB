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
  //die(var_dump($PGET->s(true)));
  //$PGET->g('thread')
  if( $PGET->s(true) ) {

      //$thread = clean($PGET->g('thread'));
      $thread = $PGET->s(true);
      $MYSQL->where('post_type', '1');
      //$MYSQL->where('id', $thread);
      $MYSQL->where('id', $thread['id']);
      //$MYSQL->where('title_friendly', 'a_thread');
      $MYSQL->where('title_friendly', $thread['value']);
      $query = $MYSQL->get('{prefix}forum_posts');

      if( !empty($query) ) {
          $node        = node($query['0']['origin_node']);

          $allowed     = explode(',', $node['allowed_usergroups']);

          if( !in_array($TANGO->sess->data['user_group'], $allowed) ) {
            redirect(SITE_URL . '/404.php');
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
          if( $node['node_type'] == 2 ) {

            $parent_node = node($node['parent_node']);

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
                'link',
                'name'
              ),
              array(
                SITE_URL . '/node.php/' . $node['name_friendly'] . '.' . $node['id'],
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
                SITE_URL . '/node.php/' . $node['name_friendly'] . '.' . $node['id'],
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
              SITE_URL . '/thread.php/' . $query['0']['title_friendly'] . '.' . $query['0']['id'],
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

                  $cont    = emoji_to_text($_POST['content']);
                  $cont    = ( !empty($q_query) )? '[quote]' . $PGET->g('quote') . '[/quote]' . $cont : $cont;
                  $cont    = preg_replace('#\\[quote\\]Post ID: (.*?)\\[/quote\\]#uis', '[quote]\\1[/quote]', $cont);

                  $data = array($TANGO->sess->data['id']);
                  $c_query = $MYSQL->rawQuery(
                    "SELECT * FROM
                    {prefix}forum_posts
                    WHERE
                    post_user = ?
                    ORDER BY
                    post_time
                    DESC LIMIT
                    1",
                    $data
                  );

                  if( !$cont ) {
                      throw new Exception ($LANG['global_form_process']['all_fields_required']);
                  } elseif( $c_query['0']['post_content'] == $cont ) {
                      throw new Exception ($LANG['global_form_process']['different_message_previous']);
                  } else {
                    $origin  = thread($thread['id']);
//die(var_dump($thread));
                    /*
                         * Notify the watchers of the thread.
                          */
                        $watchers = array_filter(explode(',', $query['0']['watchers']));
                        //die(var_dump($watchers));
                        if( !empty($watchers) ) {
                          foreach( $watchers as $watcher ) {
                            $user = $TANGO->user($watcher);
                            if( !empty($user) ) {
                              $TANGO->user->notifyUser(
                                'reply',
                                $user['id'],
                                true,
                                array(
                                  'username' => $TANGO->sess->data['username'],
                                  'thread_title' => $query['0']['post_title'],
                                  'link' => SITE_URL . '/thread.php/' . $origin['title_friendly'] . '.' . $origin['id']
                                )
                              );
                            }
                          }
                        }

                        /*
                         * Mentions
                         */
                        preg_match_all('/@(\w+)/', $cont, $mentions);
                        $mentions = array_filter(array_unique($mentions['1']));
                        if( !empty($mentions['1']) ) {
                          foreach( $mentions['1'] as $mention ) {
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
                      //die(var_dump($query));
                      $time    = time();

                      //Double Posting
                      $o_data  = '1';
                      /*$o_query = $MYSQL->rawQuery(
                        "SELECT * FROM
                         {prefix}forum_posts
                         WHERE
                         origin_thread = ?
                         ORDER BY
                         post_time
                         DESC LIMIT 1",
                        $o_data
                      );
                      $p_query = $MYSQL->rawQuery(
                        "SELECT * FROM
                         {prefix}forum_posts
                         WHERE
                         origin_thread = ?
                         ORDER BY
                         post_time
                         DESC",
                        $o_data
                      );*/
                      $o_query = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE origin_thread = {$thread['id']} ORDER BY post_time DESC LIMIT 1");
                      $p_query = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE origin_thread = {$thread['id']} ORDER BY post_time DESC");
                      //die(var_dump($thread));
                      //die(var_dump($o_query));
                      if( $o_query && $o_query['0']['post_user'] == $TANGO->sess->data['id'] ) {
                        //die('First');
                        $t_cont = $o_query['0']['post_content'] . '
' . $LANG['flat']['merge_post'] . '
' . $cont;

                        $MYSQL->where('id', $o_query['0']['id']);
                        $n_cont = array(
                          'post_content' => $t_cont
                        );

                        try {
                          $MYSQL->update('{prefix}forum_posts', $n_cont);
                          $t_data = array(
                            'last_updated'=> $time
                          );
                          $MYSQL->where('id', $thread['id']);
                          try {
                            $MYSQL->update('{prefix}forum_posts', $t_data);
                            redirect(SITE_URL . '/thread.php/' . $origin['title_friendly'] . '.' . $origin['id']);
                          } catch (mysqli_sql_exception $e) {
                            redirect(SITE_URL . '/thread.php/' . $origin['title_friendly'] . '.' . $origin['id']);
                          }
                        } catch (mysqli_sql_exception $e) {
                          throw new Exception ($LANG['global_form_process']['error_replying_thread']);
                        }

                      } else {
//die('second');

                        $n_data = array(
                          'post_content' => $cont,
                          'post_time' => $time,
                          'post_user' => $TANGO->sess->data['id'],
                          'origin_node' => $origin['origin_node'],
                          'origin_thread' => $thread['id'],
                          'post_type' => '2'
                        );

                        try {
                          $MYSQL->insert('{prefix}forum_posts', $n_data);
                          $t_data = array(
                            'last_updated'=> $time
                          );
                          $MYSQL->where('id', $thread['id']);
                          try {

                            $page = '';
                            if( (count($p_query)/POST_RESULTS_PER_PAGE) > 1 ) {
                              $page .= '/page/' . ceil(count($p_query)/POST_RESULTS_PER_PAGE);
                            }

                            $MYSQL->update('{prefix}forum_posts', $t_data);
                            redirect(SITE_URL . '/thread.php/' . $origin['title_friendly'] . '.' . $origin['id'] . $page);
                          } catch (mysqli_sql_exception $e) {
                            $page = '';
                            if( (count($o_query)/POST_RESULTS_PER_PAGE) > 1 ) {
                              $page .= '/page/' . ceil(count($o_query)/POST_RESULTS_PER_PAGE);
                            }
                            redirect(SITE_URL . '/thread.php/' . $origin['title_friendly'] . '.' . $origin['id'] . $page);
                          }
                        } catch (mysqli_sql_exception $e) {
                          throw new Exception ($LANG['global_form_process']['error_replying_thread']);
                        }

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
                  SITE_URL . '/thread.php/' . $query['0']['title_friendly'] . '.' . $query['0']['id']
              )
          );

        foreach($ICONS as $category=>$icons_cat)
          {
            $icon_package[$category] = '';
            foreach($icons_cat as $code=>$html){
                $icon_package[$category] .= '<span style="font-size: 30px;" title="'.$code.'">'.$html.'</span> ';
            }
          }
          $content .= $TANGO->tpl->entity(
              'smiliy_list',
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