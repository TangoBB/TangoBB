<?php
  
  /*
   * TangoBB PermGET Class.
   */
   
  if( !defined('BASEPATH') ){ die(); }
   
  class Library_PermGET {
          
        private $file;
        public $error;
        
        /*
         * Basic configuration.
         */
        public function _construct() {
            $this->file = $_SERVER['REQUEST_URI'];
        }
        
        /*
         * The PermGET Function.
         * example.php/parameter/value
         */
        public function g($param, $callback = null) {
            if( stristr($_SERVER['REQUEST_URI'], '/'.$param.'/') ){    
                $value = $this->getBetween($_SERVER['REQUEST_URI'], '/'.$param.'/', '/');
                if( is_callable($callback) ) {
                    call_user_func($callback, $value);
                } else {
                    return $value;
                }
            }else{
                $this->error[] = 'Undefined permGET parameter.';
                return false;  
            }
        }

        /*
         * The PermGET function for single value and no parameter.
         * example.php/value
         */
        public function s($with_id = false) {
          list($page) = sscanf(
            'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            SITE_URL . '/%s'
          );
          //$value =  preg_replace('/(\w+).php\//', '', $page);
          $value = preg_replace('/(.*).php\//', '', $page);
          if( $with_id ) {
            preg_match_all('/(.*)\.([0-9]+)/', $value, $values);
            //die(var_dump($values));
            $values = array(
              //'value' => str_replace('.', '', $values['1']['0']),
              'value' => $values['1']['0'],
              'id' => $values['2']['0']
            );
            return $values;
          } else {
            return $value;
          }
        }
        
        /*
         * Get between strings.
         */
        private function getBetween($content, $start, $end) {
            $r = explode($start, $content);
            if( isset($r[1]) ){
                $r = explode($end, $r[1]);
                return $r[0];    
            }else{      
                return '';      
            }
        }
        
  }
  
?>