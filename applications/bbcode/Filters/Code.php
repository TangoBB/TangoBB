<?php

/**
 * Tag CODE
 * @package Parser
 * @subpackage Filters
 * @author wookieb
 * @version 1.1
 */
class BbCodeFilterCode {
	public $tags = array(
		'code' => array(
			'open' => 'pre',
			'close' => 'pre',
			'notallowed_child' => 'all',
			'leave_notallowed_child' => 1,
			'leave_notallowed_parent' => 1,
			'parse_body' => 'parseCode'
		)
	);

	/**
	 * Parsuje tag CODE
	 * @param array $tag
	 * @param array $openNode
	 * @param array $body
	 * @param array $closeNode
	 */
	public function parseCode($tag, &$openNode, &$body, &$closeNode) {
		// tutaj mozemy dodać geshi czy to tez sie podoba
		$openNode['text'].='';
		$closeNode['text'] = ''.$closeNode['text'];
	}
	/**
	 * Parsuje tag CODE używając GESHI
	 * @param array $tag
	 * @param array $openNode
	 * @param array $body
	 * @param array $closeNode
	 */
	/*
	  public function parseCode($tag, &$openNode, &$body, &$closeNode)
	  {
	  // laczymy tresc w całość

	  $content = '';
	  foreach($body as $key => &$node)
	  {
	  $content.= $node['text'];
	  $node['text'] = '';

	  // usuwamy zbedne elementy
	  if($key!=0) unset($body[$key]);
	  }

	  require_once 'class.geshi.php';


	  $geshi = new GeSHi($content, 'php');
	  $body[0]['tagText'] = $content;
	  $body[0]['text'] = $geshi->parseCode();
	  $body[0]['nohtmlspecialchars'] = 1;

	  $openNode['text'].='<div class="code_title">Kod</div><div class="code_area">';
	  $closeNode['text']='</div>'.$closeNode['text'];
	  }
	 */
}

