<?php

  /*
   * Iko Form Validator Class.
   * Dependencies: ErrorHandler Class (error_handler.php)
   */
  if( !defined('BASEPATH') ){ die(); }

  class Iko_Validator {

    protected $errorHandler;

    protected $itme;

    protected $rules = array();

    public $messages = array();

  	public function __construct(ErrorHandler $errorHandler) {
  		$this->errorHandler = $errorHandler;

  		$this->rules = array(
  			'required',
  			'minlength',
  			'maxlength',
  			'email',
  			'alnum',
  			'match',
  			//'unique'
  		);

  		$this->messages = array(
  			'required' => 'The :field field is required.',
  			'minlength' => 'The :field field must be a minimum of :satisfier length.',
  			'maxlength' => 'The :field field must be a maximum of :satisfier length.',
  			'email' => 'That is not a valid email address.',
  			'alnum' => 'The :field field must be alphanumeric.',
  			'match' => 'The :field field must match the :satisfier field.',
  			//'unique' => 'That :field is already taken'
  		);
  	}

  	public function check($items, $rules) {
  		$this->items = $items;
  		foreach( $items as $item => $value ) {
  			if( in_array($item, array_keys($rules)) ) {
  				/*$this->validate([
  					'field' => $item,
  					'value' => $value,
  					'rules' => $rules[$item]
  				]);*/
          $this->validate(
            array(
              'field' => $item,
              'value' => $value,
              'rules' => $rules[$item]
            )
          );
  			}
  		}
  		return $this;
  	}

  	protected function validate($item) {
  		$field = $item['field'];

  		foreach( $item['rules'] as $rule => $satisfier ) {
  			if( in_array($rule, $this->rules) ) {
  				if( !call_user_func_array(array($this, $rule), array($field, $item['value'], $satisfier)) ) {
  					$this->errorHandler->addError(
  						str_replace(
  							array(
  								':field',
  								':satisfier'
  							),
  							array(
  								$field,
  								$satisfier
  							),
  							$this->messages[$rule]
  						),
  						$field
  					);
  				}
  			}
  		}
  	}

  	public function fails() {
  		return $this->errorHandler->hasErrors();
  	}

  	public function errors() {
  		return $this->errorHandler;
  	}

  	protected function required($field, $value, $satisfier) {
  		return !empty(trim($value));
  	}

  	protected function minlength($field, $value, $satisfier) {
  		return mb_strlen($value) >= $satisfier;
  	}

  	protected function maxlength($field, $value, $satisfier) {
  		return mb_strlen($value) <= $satisfier;
  	}

  	protected function email($field, $value, $satisfier) {
  		return filter_var($value, FILTER_VALIDATE_EMAIL);
  	}

  	protected function alnum($field, $value, $satisfier) {
  		return ctype_alnum($value);
  	}

  	protected function match($field, $value, $satisfier) {
  		return $value === $this->items[$satisfier];
  	}

  	/*protected function unique($field, $value, $satisfier) {
  		return !$this->db->table($satisfier)->exists(
  			array(
  				$field => $value;
  			)
  		);
  	}*/

  }

?>