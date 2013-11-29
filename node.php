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
      $MYSQL->where('name_friendly', $node_name);
      $query = $MYSQL->get('{prefix}forum_node');
      if( !empty($query) ) {
          
          $page = ($PGET->g('page'))? clean($PGET->g('page')) : '1';
          
          $results = '';
          $t       = '';
          foreach(getThreads($node_id, $page) as $thread) {
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
                  'post_thread_button',
                  'threads'
              ),
              array(
                  '',
                  $new_thread,
                  $t
              )
          );
          
          $total_pages = ceil(fetchTotalThread($node_id) / THREAD_RESULTS_PER_PAGE);
          
          $pag = '';
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
                              SITE_URL . '/node.php/v/' . $PGET->g('v') . '/page/' . $i,
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
          header('Location: ' . SITE_URL . '/404.php');
      }
      
  } else {
      header('Location: ' . SITE_URL);
  }

  echo $TANGO->tpl->output();

?>