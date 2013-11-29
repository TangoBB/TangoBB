<?php

/**
 * Standardowe tagi
 * @package Parser
 * @subpackage Filters
 * @author wookieb
 * @version 1.2
 */
class BbCodeFilterBasic {
	public $tags = array(
		'b' => array(
			'open' => 'b',
			'close' => 'b',
			'notallowed_child' => array('b')
		),
		'i' => array(
			'open' => 'span style="font-style: italic"',
			'close' => 'span',
			'notallowed_child' => array('i')
		),
		's' => array(
			'open' => 'span style="text-decoration: line-through"', // podalismy w htmlu bo niepotrzebne jest uzywanie atrybutow jak np zakomentowanych nizej
			'close' => 'span',
			'notallowed_child' => array('s'),
		/* 'attributes'=>array(
		  'dec'=>array(
		  'no_changeable' => true,
		  'attr'			=> 'style',
		  'name'          => 'text-decoration:',
		  'default_value' => 'line-through'
		  )
		  ) */
		),
		'u' => array(
			'open' => 'span style="text-decoration: underline;"',
			'close' => 'span',
			'notallowed_child' => array('u')
		),
		'color' => array(
			'open' => 'span',
			'close' => 'span',
			'attributes' => array(
				'color' => array(
					'attr' => 'style',
					'type' => 'string',
					'name' => 'color:',
					'required' => true
				)
			)
		),
		'size' => array(
			'open' => 'span',
			'close' => 'span',
			'attributes' => array(
				'size' => array(
					'attr' => 'style',
					'type' => 'number',
					'name' => 'font-size:',
					'dimensions' => array(
						'px' => array(
							'min_value' => 10,
							'max_value' => 16
						),
						'pt' => array(
							'min_value' => 5,
							'max_value' => 14
						)
					),
					'default_dimension' => 'px'
				)
			)
		),
		'quote' => array(
			'open' => 'blockquote',
			'close' => 'blockquote',
			'parse_body' => 'parseQuote',
			'attributes' => array(
				'quote' => array(
					'type' => 'string'
				),
				'date' => array(
					'type' => 'string'
				)
			)
		)
	);

	/**
	 * Parsuje tag QUOTE
	 * @param array $tag
	 * @param array $openNode
	 * @param array $body
	 * @param array $closeNode
	 * @param BbCodeSettings $settings
	 */
	public function parseQuote($tag, &$openNode, &$body, &$closeNode, $settings) {
		$divText = '';
		if (isset($openNode['attributes'])) {
			if (isset($openNode['attributes']['tag_attributes']['quote'])) {
				$divText.=$openNode['attributes']['tag_attributes']['quote'].' ';
			}

			if (isset($openNode['attributes']['tag_attributes']['date'])) {
				$dateExpr = '/^[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}$/';
				if (preg_match($dateExpr, $openNode['attributes']['tag_attributes']['date'])) {
					$divText.='<small>('.$openNode['attributes']['tag_attributes']['date'].')</small> ';
				}
				else {
					unset($openNode['attributes']['tag_attributes']['date']);
					$openNode = BbCode::rebuildNode($tag, $openNode, $settings);
				}
			}
		}
		$openNode['text'].=''.$divText.'';
		$closeNode['text'] = ''.$closeNode['text'];
	}
}

?>