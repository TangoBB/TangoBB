<?php

/**
 * Obsługa URL
 * @package Parser
 * @subpackage Filters
 * @author wookieb
 * @version 1.1
 */
class BbCodeFilterUrl {
	/**
	 * Ciag do wstawienia przy szukaniu linku
	 * @var string
	 */
	private $_findUrlCallbackBB;
	/**
	 * Ciag do zamiany na bbcode badz link
	 * @var string
	 */
	private $_findUrlCallbackBBFindString = false;
	/**
	 *
	 * @var BbCodeSettings
	 */
	private $settings;

	/**
	 * Maksymalna dlugosc ciagu adresu url
	 */
	const URL_LENGTH = 40;

	public $tags = array(
		'url' => array(
			'open' => 'a',
			'close' => 'a',
			'allowed_child' => array('img'),
			'parse_body' => 'parseUrl',
			'no_parse_body_on_trust_text' => true,
			'parse_no_body' => 'findUrl',
			'attributes' => array(
				'url' => array(
					'attr' => 'href',
					'type' => 'url'
				)
			)
		)
	);

	/**
	 * Callback dla preg_replace_callback
	 * @param array $matches
	 * @return strings
	 */
	public function findUrlCallback($matches) {
		$oc = $this->settings->openChar;
		$cc = $this->settings->closeChar;

		$this->_findUrlCallbackBB = $oc.'url=\''.$matches[0].'\''.$cc.$this->shortUrl($matches[0], self::URL_LENGTH).$oc.'/url'.$cc;
		$this->_findUrlCallbackBBFindString = $matches[0];

		return '<'.$this->tags['url']['open'].' href="'.$matches[0].'">'.$this->shortUrl($matches[0], self::URL_LENGTH).'</'.$this->tags['url']['close'].'>';
	}

	/**
	 * Skraca url do podanej dlugosci
	 * @param string
	 * @return string
	 */
	public function shortUrl($url, $length) {
		$strlen = strlen($url);
		if ($strlen > $length) {
			$min = floor($length / 2) - 3;
			$max = $strlen - $length + 3;
			$url = substr_replace($url, '...', $min, $max);
		}
		return $url;
	}

	/**
	 * Szuka urla w ciagu
	 * @param string $body
	 * @param array $parent tag rodzica
	 * @param BbCodeSettings $settings
	 */
	public function findUrl($body, $parent, $settings) {
		$this->settings = $settings;

		if (is_array($parent) && !BbCode::_checkAllowedTagName($parent, BbCode::CHECK_CHILD, 'url'))
			return false;

		//zabezpieczamy tekst
		if (!(isset($body['nohtmlspecialchars']) && $body['nohtmlspecialchars'])) {
			$newNodeBBText = htmlspecialchars($body['text']);
		}

		$newNodeBBText = preg_replace_callback('/((?:https?|ftp):\/\/[\w\d:#@%\/;$()*~_?\+\-=\.&!\'\[\]@,]+)/i', array($this, 'findUrlCallback'), $newNodeBBText);

		if ($newNodeBBText != $body['text']) {
			$body['nohtmlspecialchars'] = 1;
			// w miejsce linku wstawiamy bbcode
			$body['tagText'] = str_replace($this->_findUrlCallbackBBFindString, $this->_findUrlCallbackBB, $body['text']);
			$body['text'] = $newNodeBBText;
		}
	}

	/**
	 * Parsuje URL
	 * @param array $tag
	 * @param array $openNode
	 * @param array $body
	 * @param array $cNode
	 * @param BbCodeSettings $settings
	 */
	public function parseUrl($tag, &$openNode, &$body, &$cNode, $settings) {
		if (isset($openNode['attributes']['tag_attributes']['url']))
			return false;

		require_once dirname(__FILE__).'/../DataValidator.php';
		$str = false;
		$inImg = false;
		foreach ($body as &$el) {
			// szukamy urla w tekscie
			if ($el['type'] == BbCode::NODE_TYPE_TEXT) {
				$str = DataValidator::checkUrl($el['text']);
				if ($str !== false) {
					if (!$inImg)
						$str = $this->shortUrl($el['text'], self::URL_LENGTH);
					break;
				}
			}

			// jezeli jest obrazek i posiada adres obrazka to adres jest przepisywany do [URL]
			if ($el['type'] == BbCode::NODE_TYPE_OPEN && $el['tagname'] == 'img') {
				if (isset($el['attributes']['tag_attributes']['img'])) {
					$inImg = true;
					$str = $el['attributes']['tag_attributes']['img'];
					break;
				}
			}

			if ($el['type'] == BbCode::NODE_TYPE_CLOSE && $el['tagname'] == 'img')
				$inImg = false;
		}

		if ($str === false) {
			$openNode = $settings->removeNode($openNode);
			$cNode = $settings->removeNode($cNode);

			return false;
		}

		$openNode['attributes'] = array(
			'tag_attributes' => array('url' => $str)
		);
		$openNode = BbCode::rebuildNode($tag, $openNode, $settings);
	}
}

