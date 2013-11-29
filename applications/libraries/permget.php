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
         */
        public function g($param) {
            if( stristr($_SERVER['REQUEST_URI'], '/'.$param.'/') ){         
                $value = $this->getBetween($_SERVER['REQUEST_URI'], '/'.$param.'/', '/');
                return $value;
            }else{
                $this->error[] = 'Undefined permGET parameter.';
                return false;  
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