<?php

  /*
   * Account Activation Mailing Library of TangoBB
   * This library is officially obsolete since TangoBB 1.3
   */
  if( !defined('BASEPATH') ){ die(); }

  class Library_Mail {
      
      private $template = '';
      
      public function __construct() {
          global $TANGO;
          
          if( BASEPATH == "Staff" ) {
              $template_file  = '../public/email/template.php';
              $template_style = '../public/css/bootstrap-email.min.css';
            } elseif( BASEPATH == "Extension" ) {
              $template_file  = '../../../public/email/template.php';
              $template_style = '../../../public/css/bootstrap-email.min.css';
          } else {
              $template_file  = 'public/email/template.php';
              $template_style = 'public/css/bootstrap-email.min.css';
          }
          
          $this->template = file_get_contents($template_file);
          
          $style          = file_get_contents($template_style);
          
          $param = array(
              '%site_name%',
              '%css_style%',
              '%login_url%',
              '%site_url%'
          );
          $value = array(
              $TANGO->data['site_name'],
              $style,
              SITE_URL . '/members.php/cmd/signin',
              SITE_URL
          );
          
          $this->template = str_replace($param, $value, $this->template);
      }
      
      public function send($title, $content, $subject, $to) {
          global $TANGO;
          
          $template = str_replace(
              array(
                  '%title%',
                  '%contents%'
              ),
              array(
                  $title,
                  $content
              ),
              $this->template
          );
          
          $headers  = 'MIME-Version: 1.0' . "\r\n";
          $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
          
          mail($to, $subject, $template, $headers);
      }
      
  }

?>