<?php

/**
 * Obsługa list (według standardu)
 * @package Parser
 * @subpackage Filters
 * @author wookieb
 * @version 1.1
 */
class BbCodeFilterList {
	public $tags = array(
		'ul' => array(
			'open' => 'ul',
			'close' => 'ul',
			'wrap_white_space' => true,
			'allowed_child' => array('li'),
			'notallowed_parent' => array('ol', 'ul'),
			'parse_body' => 'parseList'
		),
		'li' => array(
			'open' => 'li',
			'close' => 'li',
			'allowed_parent' => array('ul', 'ol')
		),
		'ol' => array(
			'open' => 'ol',
			'close' => 'ol',
			'wrap_white_space' => true,
			'allowed_child' => array('li'),
			'notallowed_parent' => array('ol', 'ul'),
			'parse_body' => 'parseList'
		),
	);

	/**
	 * Parsuje LISTY
	 * @param array $tag
	 * @param array $openNode
	 * @param array $body
	 * @param array $closeNode
	 * @param BbCodeSettings $settings
	 */
	public function parseList($tag, &$openNode, &$body, &$closeNode, $settings) {
		$good = false;
		foreach ($body as &$el) {
			if ($el['type'] == BbCode::NODE_TYPE_OPEN && $el['tagname'] == 'li') {
				$good = true;
				break;
			}
		}

		if (!$good) {
			$openNode = $settings->removeNode($openNode);
			$closeNode = $settings->removeNode($closeNode);
		}
	}
}

?>