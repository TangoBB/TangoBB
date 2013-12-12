<?php

  /*
   * Admin class of TangoBB
   */
  if( !defined('BASEPATH') ){ die(); }

  class Tango_Admin {
      
      private $links = array();
      
      public function __construct() {
          //Adding default navigation for ACP.
          $this->addNav(
              'Configuration',
              array(
                  'General' => SITE_URL . '/admin/general.php',
                  'Extensions' => SITE_URL . '/admin/extensions.php'
              )
          );
          $this->addNav(
              'Forum',
              array(
                  'Manage Categories' => SITE_URL . '/admin/manage_category.php',
                  'Manage Nodes' => SITE_URL . '/admin/manage_node.php'
              )
          );
          $this->addNav(
              'Customization',
              array(
                  'Usergroups' => SITE_URL . '/admin/usergroups.php',
                  'Theme' => SITE_URL . '/admin/theme.php'
              )
          );
      }
      
      /*
       * Function for adding a navigation link in the ACP
       */
      public function addNav($name, $links = array()) {
          $this->links[$name] = array(
              'name' => $name,
              'links' => array()
          );
          foreach($links as $value => $href) {
              $this->links[$name]['links'][] = array(
                  'value' => $value,
                  'href' => $href
              );
          }
      }
      
      /*
       * Adding a content box in ACP.
       */
      public function box($header, $content, $table = "", $column = "6") {
          $columns = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
          $column  = (in_array($column, $columns))? $column : '6';
          $return  = '<div class="col-md-' . $column . '">
                          <div class="panel panel-default">
                              <div class="panel-heading"><strong>' . $header . '</strong></div>
                              <div class="panel-body">
                                ' . $content . '
                              </div>
                              ' . $table . '
                          </div>
                      </div>';
          return $return;
      }
      
      /* 
       * Notification.
       */
      public function alert($content, $type = "info") {
          $types = array('success', 'info', 'warning', 'danger');
          $type  = (in_array($type, $types))? $type : 'info';
          return '<div class="alert alert-' . $type . '">' . $content . '</div>';
      }
      
      /*
       * Display the ACP navigation.
       */
      public function navigation() {
          $return = '';
          foreach( $this->links as $link ) {
              $return .= '<li class="dropdown">
                            <a href="javascript:return false;" class="dropdown-toggle" data-toggle="dropdown">' . $link['name'] . ' <span class="caret pull-right" style="margin-top:10px;"></span></a>
                             <span class="dropdown-arrow dropdown-arrow-inverse"></span>
                            <ul class="dropdown-menu dropdown-inverse">';
              foreach( $link['links'] as $page ) {
                  $return .= '<li><a href="' . $page['href'] . '">' . $page['value'] . '</a></li>';
              }
              
              $return .= '</ul>
                          </li>';
          }
          return $return;
      }
      
  }

?>