<?php

  define('BASEPATH', 'Forum');
  require_once('applications/wrapper.php');

  $TANGO->tpl->getTpl('page');

  if( $PGET->g('v') ) {
      
      $get = clean($PGET->g('v'));
      $get = explode('.', $get);
      
      //Node
      $node_id   = $get['1'];
      $node_name = $get['0'];
      
      $MYSQL->where('id', $node_id);
      $MYSQL->where('title_friendly', $node_name);
      $MYSQL->where('post_type', 1);
      $query = $MYSQL->get('{prefix}forum_posts');
      if( !empty($query) ) {
          
          $user        = $TANGO->user($query['0']['post_user']);
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
            'breadcrumbs_current',
            'name',
            $query['0']['post_title']
          );

          $breadcrumb = $TANGO->tpl->entity(
              'breadcrumbs',
              'bread',
              //'<li><a href="' . SITE_URL . '">Forum</a></li><li><a href="' . SITE_URL . '/node.php/v/' . $node['name_friendly'] . '.' . $node['id'] . '">' . $node['node_name'] . '</a></li><li class="active">' . $query['0']['post_title'] . '</a>'
              $breadcrumbs
          );
          
          $reply_button  = '';
          $quote_thread  = '';
          $edit_thread   = '';
          $report_thread = '';
          if( $TANGO->perm->check('reply_thread') && ($query['0']['post_locked'] == "0") ) {
              $reply_button  .= $TANGO->tpl->entity(
                      'reply_thread',
                      'link',
                      SITE_URL . '/reply.php/thread/' . $node_id,
                      'buttons'
                  );
              $report_thread .= $TANGO->tpl->entity(
                      'report_post',
                      'url',
                      SITE_URL . '/report.php/post/' . $node_id,
                      'buttons'
                  );
              $quote_thread  .= $TANGO->tpl->entity(
                  'quote_post',
                  'url',
                  SITE_URL . '/reply.php/thread/' . $node_id . '/quote/' . $node_id,
                  'buttons'
              );
              if( $query['0']['post_user'] == $TANGO->sess->data['id'] ) {
                  $edit_thread .= $TANGO->tpl->entity(
                      'edit_post',
                      'url',
                      SITE_URL . '/edit.php/post/' . $node_id,
                      'buttons'
                  );
              }
          }
          
          $thread_mod_tools = '';
          if( $TANGO->perm->check('access_moderation') ) {
              $stick_thread      = ($query['0']['post_sticky'] == "0")? 'Stick Thread' : 'Unstick Thread';
              $stick_thread_url  = ($query['0']['post_sticky'] == "0")? SITE_URL . '/mod/stick.php/thread/' . $query['0']['id'] : SITE_URL . '/mod/unstick.php/thread/' . $query['0']['id'];
              $close_thread      = ($query['0']['post_locked'] == "0")? 'Close Thread' : 'Open Thread';
              $close_thread_url  = ($query['0']['post_locked'] == "0")? SITE_URL . '/mod/close.php/thread/' . $query['0']['id'] : SITE_URL . '/mod/open.php/thread/' . $query['0']['id'];
              
              $thread_mod_tools .= $TANGO->tpl->entity(
                  'mod_tools',
                  array(
                      'stick_thread',
                      'stick_thread_url',
                      'close_thread',
                      'close_thread_url',
                      'edit_post_url'
                  ),
                  array(
                      $stick_thread,
                      $stick_thread_url,
                      $close_thread,
                      $close_thread_url,
                      SITE_URL . '/edit.php/post/' . $query['0']['id']
                  ),
                  'buttons'
              );
          }
          
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
                  $quote_thread,
                  $edit_thread,
                  $report_thread,
                  $user['user_avatar'],
                  SITE_URL . '/members.php/cmd/user/id/' . $user['id'],
                  $user['username_style'],
                  date('M jS, Y', $user['date_joined']),
                  $user['post_count'],
                  //$TANGO->lib_parse->parseQuote($TANGO->bb->parser->parse($query['0']['post_content'])),
                  //$TANGO->lib_parse->parseQuote($TANGO->bb->parser->parse($query['0']['post_content'])->get_html()),
                  $TANGO->lib_parse->parse($query['0']['post_content']),
                  //html_entity_decode(html_entity_decode($TANGO->lib_parse->parseQuote($TANGO->bb->parser->parse($user['user_signature'])))),
                  $TANGO->lib_parse->parse($user['user_signature']),
                  date('F j, Y', $query['0']['post_time']),
                  $thread_mod_tools
              )
          );
          
          $content = $starter . '';
          
          $page = ($PGET->g('page'))? clean($PGET->g('page')) : '1';
          foreach( getPosts($node_id, $page) as $post ) {
              $ur       = $TANGO->user($post['post_user']);
              $quote_p  = '';
              $edit_p   = '';
              $report_p = '';
              if( $TANGO->perm->check('reply_thread') && ($query['0']['post_locked'] == "0") ) {
                  $quote_p .= $TANGO->tpl->entity(
                      'quote_post',
                      'url',
                      SITE_URL . '/reply.php/thread/' . $node_id . '/quote/' . $post['id'],
                      'buttons'
                  );
                  $report_p .= $TANGO->tpl->entity(
                      'report_post',
                      'url',
                      SITE_URL . '/report.php/post/' . $post['id'],
                      'buttons'
                  );
                  if( $post['post_user'] == $TANGO->sess->data['id'] ) {
                      $edit_p .= $TANGO->tpl->entity(
                          'edit_post',
                          'url',
                          SITE_URL . '/edit.php/post/' . $post['id'],
                      'buttons'
                      );
                  }
              }
              
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
              //$post['post_content'] = $TANGO->lib_parse->parseQuote($TANGO->bb->parser->parse($post['post_content'])->get_html());
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
              
              $post_mod_tools = '';
              if( $TANGO->perm->check('access_moderation') ) {
                  $post_mod_tools = $TANGO->tpl->entity(
                      'mod_tools_posts',
                      array(
                          'edit_post_url'
                      ),
                      array(
                          SITE_URL . '/edit.php/post/' . $post['id']
                      ),
                      'buttons'
                  );
              }
              
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
                      $quote_p,
                      $edit_p,
                      $report_p,
                      $ur['user_avatar'],
                      SITE_URL . '/members.php/cmd/user/id/' . $ur['id'],
                      $ur['username_style'],
                      date('M jS, Y', $ur['date_joined']),
                      $ur['post_count'],
                      $TANGO->lib_parse->parse($post['post_content']),
                      //$TANGO->lib_parse->parseQuote($TANGO->bb->parser->parse($post['post_content'])->get_html()),
                      //html_entity_decode(html_entity_decode($TANGO->lib_parse->parseQuote($TANGO->bb->parser->parse($ur['user_signature'])))),
                      //$TANGO->lib_parse->parseQuote($TANGO->bb->parser->parse($ur['user_signature'])->get_html()),
                      $TANGO->lib_parse->parse($ur['user_signature']),
                      date('F j, Y', $post['post_time']),
                      $post_mod_tools
                  )
              );
          }
          
          $total_pages = ceil(fetchTotalPost($node_id) / POST_RESULTS_PER_PAGE);
          $pag         = '';
          if( $total_pages > 1 ) {
              $i   = '';
              for( $i = 1; $i <= $total_pages; ++$i ) {
                  if( $i == $page ) {
                      $pag .= $TANGO->tpl->entity(
                          'pagination_link_current',
                          'page',
                          $i
                      );
                  } else {
                      $pag .= $TANGO->tpl->entity(
                          'pagination_links',
                          array(
                              'url',
                              'page'
                          ),
                          array(
                              SITE_URL . '/thread.php/v/' . $PGET->g('v') . '/page/' . $i,
                              $i
                          )
                      );
                  }
              }
          }
          
          define('CSRF_TOKEN', NoCSRF::generate( 'csrf_token' ));
          define('CSRF_INPUT', '<input type="hidden" name="csrf_token" value="' . CSRF_TOKEN . '">');
          //Reply textarea.
          if( $TANGO->sess->isLogged && $TANGO->perm->check('reply_thread') && ($query['0']['post_locked'] == "0") ) {
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
                      SITE_URL . '/reply.php/thread/' . $node_id,
                      'editor',
                      'reply'
                  )
              );
          }
          
          $content .= $TANGO->tpl->entity(
              'pagination',
              'pages',
              $pag
          );
          
          $TANGO->tpl->addParam(
              array(
                  'page_title',
                  'content'
              ),
              array(
                  $query['0']['post_title'],
                  $content
              )
          );
          
      } else {
          redirect(SITE_URL . '/404.php');
      }
      
  } else {
      redirect(SITE_URL);
  }

  echo $TANGO->tpl->output();

?>