<?php

  /*
   * Additional Parsing Library of TangoBB
   */
  if( !defined('BASEPATH') ){ die(); }

  class Library_Parse {
      
      /*
       * Additional parsing on forum content.
       */
      public function parseQuote($string) {
          global $MYSQL, $TANGO;
          preg_match_all('/<blockquote>(.*?)<\/blockquote>/', $string, $quotes);
          $return = '';
          foreach( $quotes['1'] as $id ) {
              
              $MYSQL->where('id', $id);
              $query = $MYSQL->get('{prefix}forum_posts');
              $user  = $TANGO->user($query['0']['post_user']);
              $quote = $TANGO->tpl->entity(
                  'quote_post',
                  array(
                      'quoted_post_content',
                      'quoted_post_user'
                  ),
                  array(
                      $TANGO->bb->parser->parse($this->removeQuote($query['0']['post_content'])),
                      $user['username']
                  )
              );
              //$quote =  html_entity_decode(html_entity_decode($quote));
              
              $string = str_replace('<blockquote>' . $id . '</blockquote>', $quote, $string);
              
          }
          //$string = str_replace('&amp;', '&', $string);
          //return html_entity_decode($string);
          return $string;
      }
      
      public function removeQuote($string) {
          $quotes = preg_match_all("|\[quote\](.*)\[/quote\]|U", $string, $out, PREG_PATTERN_ORDER);
          
          foreach( $out['1'] as $post_id ) {
              $string = str_replace('[quote]' . $post_id . '[/quote]', '', $string);
          }
          return $string;
      }
      
  }

?>