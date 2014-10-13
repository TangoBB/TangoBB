<?php

  define('BASEPATH', 'Forum');
  require_once('applications/wrapper.php');

  $TANGO->tpl->getTpl('page');
  //$PGET->g('v')

  if( $PGET->s(true) ) {
      
      //$get = clean($PGET->g('v'));
      //$get = explode('.', $get);
      $get = $PGET->s(true);
      
      //Node
      //$node_id   = $get['1'];
      //$node_name = $get['0'];
      $node_id   = $get['id'];
      $node_name = $get['value'];
      
      //$MYSQL->where('id', $node_id);
      //$MYSQL->where('name_friendly', $node_name);
      //$query = $MYSQL->get('{prefix}forum_node');
      $MYSQL->bind('id', $node_id);
      $MYSQL->bind('name_friendly', $node_name);
      $query = $MYSQL->query("SELECT * FROM {prefix}forum_node WHERE id = :id AND name_friendly = :name_friendly");
      if( !empty($query) ) {

          $allowed = explode(',', $query['0']['allowed_usergroups']);
          if( !in_array($TANGO->sess->data['user_group'], $allowed) ) {
            redirect(SITE_URL . '/404.php');
          }

          if( $query['0']['node_type'] == 1 ) {
            $sub_forums = $TANGO->bb->subForums($query['0']['id']);
          } else {
            $sub_forums = '';
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
              'breadcrumbs_current',
              array(
                'name'
              ),
              array(
                $query['0']['node_name']
              )
            );

          } elseif( $query['0']['node_type'] == 1 ) {

            $breadcrumbs .= $TANGO->tpl->entity(
              'breadcrumbs_current',
              array(
                'name'
              ),
              array(
                $query['0']['node_name']
              )
            );

          }

          $breadcrumbs = $TANGO->tpl->entity(
            'breadcrumbs',
            'bread',
            $breadcrumbs
          );

          $page = ($PGET->g('page'))? clean($PGET->g('page')) : '1';
          
          $results = '';
          $t       = '';
          foreach(getThreads($node_id, $page, $PGET->g('sort')) as $thread) {
              $t .= $TANGO->node->threads($thread['id']);
          }
          
          $new_thread = '';
          if( $query['0']['node_locked'] == "0" ) {
              if( $TANGO->perm->check('create_thread') ) {
                  $new_thread .= $TANGO->tpl->entity(
                      'create_thread',
                      'url',
                      SITE_URL . '/new.php/node/' . $node_id,
                      'buttons'
                  );
              }
          } else {
              if( $TANGO->perm->check('access_moderation') ) {
                  $new_thread .= $TANGO->tpl->entity(
                      'create_thread',
                      'url',
                      SITE_URL . '/new.php/node/' . $node_id,
                      'buttons'
                  );
              }
          }
          
          $results .= $TANGO->tpl->entity(
              'forum_listings_node_threads',
              array(
                  'breadcrumbs',
                  'sub_forums',
                  'post_thread_button',
                  'threads',
                  //Sorting
                  'sort_latest_created',
                  'sort_name_desc',
                  'sort_name_asc',
                  'sort_last_updated'
              ),
              array(
                  $breadcrumbs,
                  $sub_forums,
                  $new_thread,
                  $t,
                  //Sorting
                  SITE_URL . '/node.php/' . $query['0']['name_friendly'] . '.' . $query['0']['id'] . '/sort/latest_created',
                  SITE_URL . '/node.php/' . $query['0']['name_friendly'] . '.' . $query['0']['id'] . '/sort/name_desc',
                  SITE_URL . '/node.php/' . $query['0']['name_friendly'] . '.' . $query['0']['id'] . '/sort/name_asc',
                  SITE_URL . '/node.php/' . $query['0']['name_friendly'] . '.' . $query['0']['id'] . '/sort/last_updated'
              )
          );
          
          $total_pages = ceil(fetchTotalThread($node_id) / THREAD_RESULTS_PER_PAGE);
          
          $sort = $PGET->g('sort');
          $pag  = '';
          if( $total_pages > 1 ) {
              $i   = '';
              for( $i = 1; $i <= $total_pages; ++$i ) {
                  $link = ($sort)? SITE_URL . '/node.php/' . $node_name . '.' . $node_id . '/sort/' . $sort . '/page/' . $i : SITE_URL . '/node.php/' . $PGET->g('v') . '/page/' . $i;
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
                              SITE_URL . '/node.php/' . $node_name . '.' . $node_id . '/page/' . $i,
                              $i
                          )
                      );
                  }
              }
          }
          
          $results .= $TANGO->tpl->entity(
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
                  $query['0']['node_name'],
                  $results
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