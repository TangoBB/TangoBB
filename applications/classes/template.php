<?php

  /*
   * Template class of TangoBB
   */
  if( !defined('BASEPATH') ){ die(); }

  class Tango_Template {
      
      private $output;
      private $params = array();
      
      function __construct() {
          global $TANGO;
          $this->addParam('site_url', SITE_URL);
          $this->addParam('site_name', $TANGO->data['site_name']);
          
          $this->addParam('bb_stat_threads', stat_threads());
          $this->addParam('bb_stat_posts', stat_posts());
          $this->addParam('bb_stat_users', stat_users());
          $this->addParam('users_online', users_online());
          
          /*$this->addParam(
              'editor_settings',
              '<link rel="stylesheet" href="' . SITE_URL . '/public/css/tangobb.css" />
               <link rel="stylesheet" href="' . SITE_URL . '/public/js/sceditor/themes/modern.min.css" type="text/css" media="all" />
               <script src="' . SITE_URL . '/public/js/jquery.min.js"></script>
               <script>var SITE_URL = \'' . SITE_URL . '\';</script>
               <script type="text/javascript" src="' . SITE_URL . '/public/js/autosaveform.js"></script>
               <script type="text/javascript" src="' . SITE_URL . '/public/js/tangobb.js"></script>
               <script type="text/javascript" src="' . SITE_URL . '/public/js/typeahead.min.js"></script>
               <script type="text/javascript" src="' . SITE_URL . '/public/js/sceditor/jquery.sceditor.bbcode.min.js"></script>
               <script>
                 $(function() {
                   $(\'#editor\').sceditor({
                     plugins: "bbcode",
                     toolbar: "bold,italic,underline,strike|left,center,right|size,color|bulletlist,orderedlist|code|image|link,unlink|source",
                     style: "' . SITE_URL . '/public/js/sceditor/jquery.sceditor.default.min.css"
                   });
                   var emo = $(\'textarea\').sceditor(\'instance\').emoticons(false);
                 });
                 var formsave1=new autosaveform({
                   formid: \'tango_form\',
                   pause: 1000 //<--no comma following last option!
                 });
               </script>'
          );*/
          $this->addParam(
              'editor_settings',
              '<link rel="stylesheet" href="' . SITE_URL . '/public/css/tangobb.css" />
               <link rel="stylesheet" href="' . SITE_URL . '/public/js/wysibb/theme/default/wbbtheme.css" />
               <link rel="stylesheet" href="' . SITE_URL . '/public/js/highlighter/styles/github.css" />
               <script src="' . SITE_URL . '/public/js/jquery.min.js"></script>
               <script>var SITE_URL = \'' . SITE_URL . '\';</script>
               <script type="text/javascript" src="' . SITE_URL . '/public/js/autosaveform.js"></script>
               <script type="text/javascript" src="' . SITE_URL . '/public/js/tangobb.js"></script>
               <script type="text/javascript" src="' . SITE_URL . '/public/js/typeahead.min.js"></script>
               <script type="text/javascript" src="' . SITE_URL . '/public/js/wysibb/jquery.wysibb.min.js"></script>
               <script type="text/javascript" src="' . SITE_URL . '/public/js/highlighter/highlight.pack.js"></script>
               <script>
                 $(document).ready(function() {
                  $(\'#editor\').wysibb({
                    buttons :  \'bold,italic,underline,strike,|,fontcolor,fontsize,fontfamily,|,justifyleft,justifycenter,justifyright,|,bullist,numlist,|,img,link,|,code,quote\',
                    tabInsert: false
                  });
                  $(\'pre\').each(function(i, e) {hljs.highlightBlock(e)});
                 });
                 var formsave1=new autosaveform({
                   formid: \'tango_form\',
                   pause: 1000 //<--no comma following last option!
                 });
               </script>'
          );
      }
      
      /*
       * Add template parameter.
       */
      public function addParam($param, $value) {
          if( is_array($param) or is_array($value) ) {
              foreach( array_combine($param, $value) as $p => $v  ) {
                  $this->params['%' . $p . '%'] = $v;
              }
          } else {
              $this->params['%' . $param . '%'] = $value;
          }
      }
      
      /*
       * Echo a string without using the "echo" PHP function which will return an error on the template system.
       */
      public function push($string) {
          $this->output .= $string;
      }
      
      /*
       * Getting information from the theme's entities file.
       */
      public function entity($entity, $param = "", $value = "", $parent ="theme_entity_file") {
          global $TANGO;
          
          switch($parent) {
              case "theme_entity_file":
                $tpl = file_get_contents(TEMPLATE . 'public/themes/' . $TANGO->data['site_theme'] . '/entities.php');
              break;
                  
              case "buttons":
                $tpl = file_get_contents(TEMPLATE . 'public/themes/' . $TANGO->data['site_theme'] . '/buttons.php');
              break;
              
              default:
                $tpl = file_get_contents(TEMPLATE . 'public/themes/' . $TANGO->data['site_theme'] . '/entities.php');
              break;
          }
          
          $start  = $TANGO->getBetween(
              $tpl,
              '<!--- parent:' . $parent . ':start -->',
              '<!--- tpl:' . $entity . ':start -->'
          );
          $end    = $TANGO->getBetween(
              $tpl,
              '<!--- tpl:' . $entity . ':end -->',
              '<!--- parent:' . $parent . ':end -->'
          );
          $result = $TANGO->getBetween(
              $tpl,
              '<!--- parent:' . $parent . ':start -->' . $start . '<!--- tpl:' . $entity . ':start -->',
              '<!--- tpl:' . $entity . ':end -->' . $end . '<!--- parent:' . $parent . ':end -->'
          );
          
          $params = array();
          $values = array();
          if( is_array($param) or is_array($value) ) {
              foreach( array_combine($param, $value) as $p => $v  ) {
                  $params[] = '%' . $p . '%';
                  $values[] = $v;
              }
          } else {
              $params[] = '%' . $param . '%';
              $values[] = $value;
          }
          $result = str_replace($params, $values, $result);
          
          return $result;
      }
      
      /*
       * Blade template Syntax.
       * Thanks to https://github.com/loic-sharma/laravel-template
       */
      private function bladeSyntax($string) {
          $syntax_blade = array(
              '/{{ (.*) }}/',
              '/{{-v (.*) = (.*) }}/',
              '/(\s*)@(if|elseif|foreach|for|while)(\s*\(.*\))/',
              '/(\s*)@(endif|endforeach|endfor|endwhile)(\s*)/',
              '/(\s*)@(else)(\s*)/',
              '/(\s*)@unless(\s*\(.*\))/'
          );
          $syntax_php   = array(
              '<?php echo $1; ?>',
              '<?php $1 = $2; ?>',
              '$1<?php $2$3: ?>',
              '$1<?php $2; ?>$',
              '$1<?php $2: ?>$3',
              '$1<?php if( ! ($2)): ?>'
          );
          $string = preg_replace($syntax_blade, $syntax_php, $string);
          
          //@empty
          $string = str_replace('@empty', '<?php endforeach; ?><?php else: ?>', $string);
          //@forelse
          $string = str_replace('@endforelse', '<?php endif; ?>', $string);
          //@endunless
          $string = str_replace('@endunless', '<?php endif; ?>', $string);
          
          return $string;
      }
      
      /*
       * Get the template file.
       */
      public function getTpl($template, $ret = false) {
          global $TANGO;
          $dir = TEMPLATE . 'public/themes/' . $TANGO->data['site_theme'] . '/' . $template . '.php';
          if( file_exists($dir) ) {
              $return = '';
              ob_start();
              include($dir);
              $return .= ob_get_contents();
              ob_end_clean();
              
              ob_start();
              $return = $this->bladeSyntax($return);
              eval(' ?>' . $return . '<?php ');
              $return = ob_get_clean();
              ob_end_clean();
              
              $return = str_replace('$', '', $return);
              
              if( !$ret ) {
                  $this->output .= $return;
              } else {
                  return $return;
              }
          } else {
              die('Template file doesn\'t exist!');
          }
      }
      
      /*
       * Replacing all parameters with the values and outputs them.
       */
      public function output() {
          $params = array();
          $values = array();
          foreach( $this->params as $param => $value ) {
              $params[] = $param;
              $values[] = $value;
          }
          
          $return = str_replace($params, $values, $this->output);
          return $return;
      }
      
  }

?>