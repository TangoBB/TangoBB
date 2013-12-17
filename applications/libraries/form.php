<?php

  /*
   * Form Builder Library of TangoBB
   */
  if( !defined('BASEPATH') ){ die(); }

  class Library_FormBuilder {

  	private $csrf;

  	public function __construct() {
  		//$this->csrf = NoCSRF::generate('csrf_token');
  	}

  	public function build($type, $label = "", $name = "", $additional = array()) {
  		$add     = '';
  		$display = '';
  		$ta_val  = '';
        if( isset($additional['value']) ) {
        	$add    .= ' value="' . $additional['value'] . '"';
        	$ta_val .= $additional['value'];
        }
        if( isset($additional['checked']) && $additional['checked'] ) {
        	$add .= ' CHECKED';
        }
        if( isset($additional['class']) ) {
        	$add .= ' class="' . $additional['class'] . '"';
        }
        if( isset($additional['id']) ) {
        	$add .= ' id="' . $additional['id'] . '"';
        }
        if( isset($additional['style']) ) {
        	$add .= ' style="' . $additional['style'] . '"';
        }
        if( isset($additional['display']) ) {
        	$display .' ' . $additional['display'];
        }

  		switch( $type ) {
  			case "text":
  			case "password":
  			case "email":
  			case "hidden":
  			  return '<label for="' . $name . '">' . $label . '</label>
  			          <input type="' . $type . '" name="' . $name . '" id="' . $name . '"' . $add . ' />' . $display;
  			break;
  			case "radio":
  			case "checkbox":
  			  return '<input type="' . $type . '" name="' . $name . '"' . $add . '>' . $label;
  			break;
  			case "textarea":
          $add = str_replace('value="' . isset($additional['value']) . '"', '', $add);
  			  return '<label for="' . $name . '">' . $label . '</label>
  			          <textarea name="' . $name . '"' . $add . '>' . $ta_val . '</textarea>';
  			break;
  			case "submit":
  			  return '<input type="' . $type . '" name="' . $name . '"' . $add . ' />' . $display;
  			break;
  			case "csrf":
  			  return '<input type="hidden" name="csrf_token" value="' . $this->csrf . '">';
  			break;
  		}
  	}

  }

?>