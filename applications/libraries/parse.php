<?php

  /*
   * Additional Parsing Library of TangoBB
   */
  if( !defined('BASEPATH') ){ die(); }

  class Library_Parse {

      private $custom_codes = array();

      /*
       * Adding a BBCode
       */
      public function addCode($search, $replace) {
        $this->custom_codes[$search] = $replace;
      }

      /*
       * Parsing BBCode into HTML.
       * Replaces have to be done seperately. Hopefully we'll find a solution soon enough.
       * htmlentities() not working when there is any HTML codes in the [code][/code] tags. Devised a temporary fix.
       */
      public function parse($string) {
          //$string = htmlentities($string);
          //Temporary fix where replace "<" and ">" with "&lt;" and "&gt;".
          //Which negates all HTML tags.
          $string = str_replace(
            array(
              '<',
              '>'
            ),
            array(
              '&lt;',
              '&gt;'
            ),
            $string
          );

          $search = array( 
            '/\[b\](.*?)\[\/b\]/is', 
            '/\[i\](.*?)\[\/i\]/is', 
            '/\[u\](.*?)\[\/u\]/is',
            '/\[s\](.*?)\[\/s\]/is',
            '/\[img\](.*?)\[\/img\]/is', 
            '/\[url\](.*?)\[\/url\]/is', 
            '/\[url\=(.*?)\](.*?)\[\/url\]/is',
            '/\[ul\](.*?)\[\/ul\]/is',
            '/\[ol\](.*?)\[\/ol\]/is',
            '/\[li\](.*?)\[\/li\]/is',
            '/\[list\](.*?)\[\/list\]/is',
            '/\[list=1\](.*?)\[\/list\]/is',
            '/\[left\](.*?)\[\/left\]/is',
            '/\[center\](.*?)\[\/center\]/is',
            '/\[right\](.*?)\[\/right\]/is',
            '/\[size\=(.*?)\](.*?)\[\/size\]/is',
            '/\[color\=(.*?)\](.*?)\[\/color\]/is',
            '/\[code\](.*?)\[\/code\]/is',
            '/\[font\=(.*?)\](.*?)\[\/font\]/is',
            '/\[quote\](.*?)\[\/quote\]/is'
          ); 
          $replace = array(
            '<strong>$1</strong>',
            '<i>$1</i>',
            '<u>$1</u>',
            '<span style="text-decoration:line-through;">$1</span>',
            '<img src="$1" />',
            '<a href="$1" target="_blank">$1</a>',
            '<a href="$1" target="_blank">$2</a>',
            '<ul>$1</ul>',
            '<ol>$1</ol>',
            '<li>$1</li>',
            '<ul>$1</ul>',
            '<ol></ol>',
            '<p align="left">$1</p>',
            '<p align="center">$1</p>',
            '<p align="right">$1</p>',
            '<font size="$1">$2</font>',
            '<span style="color:$1;">$2</span>',
            '<pre>$1</pre>',
            '<span style="font-style:$1;">$2</span>',
            '<blockquote>$1</blockquote>'
          );

          foreach( $this->custom_codes as $s => $r ) {
            $search[]  = $s;
            $replace[] = $r;
          }

          $var = preg_replace($search, $replace, $string);

          $search  = array(
            '</li><br />',
            '</ul><br />',
            '</ol><br />',
            //HTML Elements
            '&amp;',
            //ASCII Codes
            //'\r\n',
            //Additional BBCodes
            '[*]',
            '[/*]',
            //'[quote]',
            //'[/quote]'
          );
          $replace = array(
            '</li>',
            '</ul>',
            '</ol>',
            //HTML Elements,
            '&',
            //ASCII Codes,
            //'<br />',
            //Additional BBCodes
            '<li>',
            '</li>',
            //'<blockquote>',
            //'</blockquote>'
          );

          $var = str_replace($search, $replace, nl2br($var));
          //die($var);
          $var = $this->parseQuote($var);
          $var = str_replace(
            array(
              //'[quote]',
              //'[/quote]',
              '&amp;'
            ),
            array(
              //'<blockquote>',
              //'</blockquote>',
              '&'
            ),
            $var
          );
          return $var; 
      }

      /*
       * Additional parsing on forum content.
       */
      private function parseQuote($string) {
          global $MYSQL, $TANGO;
          //die($string);
          //die(var_dump($string));
          preg_match_all('/<blockquote>(.*?)<\/blockquote>/', $string, $quotes);
          //die(var_dump($quotes));
          $return = '';
          //die(var_dump($quotes));
          foreach( $quotes['1'] as $id ) {
              //die($id);
              $id    = preg_replace('/\s+/', '', $id);
              //die($id);
              $MYSQL->where('id', $id);
              $query = $MYSQL->get('{prefix}forum_posts');
              $user  = (!empty($query))? $TANGO->user($query['0']['post_user']) : array(
                'username' => ''
              );
              $q_c   = (!empty($query))? $query['0']['post_content'] : $string;
              $quote = $TANGO->tpl->entity(
                  'quote_post',
                  array(
                      'quoted_post_content',
                      'quoted_post_user'
                  ),
                  array(
                      $this->removeQuote($q_c),
                      $user['username']
                  )
              );
              //$quote =  html_entity_decode(html_entity_decode($quote));
              
              if( !empty($query) ) {
                $string = str_replace('<blockquote>' . $id . '</blockquote>', $quote, $string);
              } else {
                $string = $string;  
              }
              
          }
          //$string = str_replace('&amp;', '&', $string);
          //return html_entity_decode($string);
          //die($string);
          return $string;
      }
      
      private function parseHTML($string) {
        global $MYSQL, $TANGO;
          //die($string);
          //die(var_dump($string));
          preg_match_all('/<pre>(.*?)<\/pre>/', $string, $code);
          $return = '';
          //die(var_dump($quotes));
          foreach( $code['1'] as $syntax ) {
              
              //$id    = preg_replace('/\s+/', '', $id);

              $return .= htmlentities($syntax);
              
          }
          //$string = str_replace('&amp;', '&', $string);
          //return html_entity_decode($string);
          return $return;
      }

      private function removeQuote($string) {
          $quotes = preg_match_all("|\[quote\](.*)\[/quote\]|U", $string, $out, PREG_PATTERN_ORDER);
          
          foreach( $out['1'] as $post_id ) {
              $string = str_replace('[quote]' . $post_id . '[/quote]', '', $string);
          }
          return $string;
      }
      
  }

?>