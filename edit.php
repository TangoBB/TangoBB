<?php

  define('BASEPATH', 'Forum');
  require_once('applications/wrapper.php');

  if( !$TANGO->perm->check('reply_thread') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.
  $TANGO->tpl->getTpl('page');

  if( $PGET->g('post') ) {

      $post_id = clean($PGET->g('post'));
      $MYSQL->where('id', $post_id);
      $query = $MYSQL->get('{prefix}forum_posts');

      if( !empty($query) ) {

          if( $TANGO->perm->check('access_moderation') ) {
          } elseif(  $query['0']['post_user'] !== $TANGO->sess->data['id'] ) {
              redirect(SITE_URL);
          }

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

          if( $query['0']['post_type'] == 1 ) {
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
          } elseif( $query['0']['post_type'] == 2 ) {
            $t = thread($query['0']['origin_thread']);
            $breadcrumbs .= $TANGO->tpl->entity(
              'breadcrumbs_before',
              array(
                'link',
                'name'
              ),
              array(
                SITE_URL . '/thread.php/' . $t['title_friendly'] . '.' . $t['id'],
                $t['post_title']
              )
            );
          }

          $breadcrumbs .= $TANGO->tpl->entity(
            'breadcrumbs_current',
            'name',
            $LANG['bb']['edit_post_breadcrumb']
          );

          $breadcrumb = $TANGO->tpl->entity(
              'breadcrumbs',
              'bread',
              //'<li><a href="' . SITE_URL . '">Forum</a></li><li><a href="' . SITE_URL . '/node.php/v/' . $node['name_friendly'] . '.' . $node['id'] . '">' . $node['node_name'] . '</a></li><li class="active">' . $query['0']['post_title'] . '</a>'
              $breadcrumbs
          );

          $notice        = '';
          $origin_thread = '';
          $friendly_url  = '';
          if( $query['0']['post_type'] == "1" ) {
              $page_title     = $query['0']['post_title'];
              $thread_id      = $query['0']['id'];
              $input_type     = 'text'; 
          } else {
              $thread         = thread($query['0']['origin_thread']);
              $page_title     = $thread['post_title'];
              $thread_id      = $thread['id'];
              $input_type     = 'hidden'; 
          }

          if( isset($_POST['edit']) ) {
              try {

                  NoCSRF::check( 'csrf_token', $_POST );

                  //$con = $MYSQL->escape($_POST['content']);
                  //die($con);
                  $con = emoji_to_text($_POST['content']);
                  $thread_title = clean($_POST['title']);
                  

                  if( !$con ) {
                      throw new Exception ($LANG['global_form_process']['all_fields_required']);
                  } else {
                    
                      $friendly_url = title_friendly($thread_title);                      
                      $origin_thread .= $friendly_url . '.' . $thread_id;
                      
                      if( $query['0']['post_type'] == "1" ) {
                        $data = array(
                            'post_title' => $thread_title,
                            'title_friendly' => $friendly_url,
                            'post_content' => $con
                        );
                      }
                      else {
                        $data = array(
                            'post_content' => $con
                        );
                      }
                      $MYSQL->where('id', $post_id);

                      try {
                          $MYSQL->update('{prefix}forum_posts', $data);
                          redirect(SITE_URL . '/thread.php/' . $origin_thread);
                      } catch (mysqli_sql_exception $e) {
                          throw new Exception ($LANG['global_form_process']['error_updating_post']);
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
          //define('CSRF_INPUT', '<input type="hidden" name="csrf_token" value="' . CSRF_TOKEN . '">');

          /*$content = '<form id="tango_form" action="" method="POST">
                        ' . CSRF_INPUT . '
                        <textarea id="editor" name="content" style="width:100%;height:300px;max-width:100%;min-width:100%;">' . $query['0']['post_content'] . '</textarea>
                        <br />
                        <input type="submit" name="edit" value="Edit Post" />
                      </form>';*/
          //$FORM->build('textarea', '', 'content', array('id' => 'editor', 'style' => 'width:100%;height:300px;max-width:100%;min-width:100%;', 'value' => $query['0']['post_content']))
          $content = $breadcrumb .
                     '<form id="tango_form" action="" method="POST">
                        ' . $FORM->build('hidden', '', 'csrf_token', array('value' => CSRF_TOKEN)) . '
                        <input id="title" name="title" type="' . $input_type . '" value="' . $page_title  . '" /><br />
                        <textarea id="editor" name="content" style="width:100%;height:300px;max-width:100%;min-width:100%;">' . $query['0']['post_content'] . '</textarea>
                        <br />
                        ' . $FORM->build('submit', '', 'edit', array('value' => $LANG['bb']['form']['edit_post'])) . '
                      </form>';
                      
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
                  $LANG['bb']['edit_post_in'] . ' ' . $page_title,
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