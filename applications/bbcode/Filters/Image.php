<?php

/**
 * Tag IMG
 * @package Parser
 * @subpackage Filters
 * @author wookieb
 * @version 1.1
 */
class BbCodeFilterImage {
	/**
	 * Maksymalna szerokosc zdjecia
	 * @var int
	 */
	public $imageMaxWidth = 100;
	/**
	 * Maksymalna wysokosc zdjecia
	 * @var int
	 */
	public $imageMaxHeight = 100;
	/**
	 * Minimalna szerokosc zdjecia
	 * @var int
	 */
	public $imageMinWidth = 30;
	/**
	 * Minimalna wysokosc zdjecia
	 * @var int
	 */
	public $imageMinHeight = 60;
	/**
	 * Czy sprawdzac prawdziwy rozmiar zdjecia
	 * @var bool
	 */
	public $checkRealImageSize = false;
	/**
	 * Maksymalny czas pobierania pliku
	 * @var int
	 */
	public $socketTimeout = 5;
	public $tags = array(
		'img' => array(
			'open' => 'img',
			'close' => 'img',
			'notallowed_childs' => 'all',
			'hide_body_in_cut_text' => true,
			'parse_body' => 'checkImg',
			'attributes' => array(
				'img' => array(
					'attr' => '',
					'type' => 'string'
				),
				'src' => array(
					'attr' => 'src',
					'tag_no_show' => 1
				),
				'width' => array(
					'no_changeable' => 1,
					'attr' => 'width',
					'tag_no_show' => 1
				),
				'height' => array(
					'no_changeable' => 1,
					'attr' => 'height',
					'tag_no_show' => 1
				),
				'alt' => array(
					'attr' => 'alt',
					'default_value' => ''
				)
			)
		)
	);

	/**
	 * Parsuje IMG
	 * @param array $tag
	 * @param array $openNode
	 * @param array $body
	 * @param array $cNode
	 * @param BbCodeSettings $settings
	 */
	public function checkImg($tag, &$openNode, &$body, &$cNode, $settings) {
		require_once dirname(__FILE__).'/../DataValidator.php';

		$src = '';
		if (isset($openNode['attributes']['tag_attributes']['img'])) {
			$src = DataValidator::checkUrl($openNode['attributes']['tag_attributes']['img']);
		}

		$bodyStr = '';
		foreach ($body as $el)
			$bodyStr.=$el['text'];

		$bodyUrl = DataValidator::checkUrl($bodyStr);
		if ($bodyUrl) {
			$src = $bodyUrl;
		} else if ($bodyStr) {
			$openNode['attributes']['tag_attributes']['alt'] = $bodyStr;
		}

		if ($src == false) {
			$openNode = $settings->removeNode($openNode, $settings->removeInvalidTags);
			$cNode = $settings->removeNode($cNode, $settings->removeInvalidTags);
			return false;
		}

		$openNode['attributes']['tag_attributes']['src'] = $src;
		unset($el);

		if (isset($openNode['attributes']['tag_attributes']['img'])) {
			$tagSizes = explode('x', $openNode['attributes']['tag_attributes']['img']);

			if (is_array($tagSizes)) {
				$tagSizes = array_map('trim', $tagSizes);

				if (isset($tagSizes[0]) && is_numeric($tagSizes[0])) {
					$openNode['attributes']['tag_attributes']['width'] = round($tagSizes[0]);
				}
				if (isset($tagSizes[1]) && is_numeric($tagSizes[1])) {
					$openNode['attributes']['tag_attributes']['height'] = round($tagSizes[1]);
				}
			}
		}

		// wlaczona kontrola wielkosci zdjecia i czy zawsze mozemy taką kontrolę przeprowadzic
		if (($this->imageMaxWidth > 0
				|| $this->imageMaxHeight > 0
				|| $this->imageMinHeight > 0
				|| $this->imageMinHeight > 0
		)) {
			if (ini_get('allow_url_fopen') && $this->checkRealImageSize) {
				// ustawienie maksymalnego czasu pobierania info o zdjeciu
				$oldDefaultSocketTimeout = ini_get('default_socket_timeout');
				$socketTimeout = 5;
				if (isset($this->socketTimeout) && $this->socketTimeout >= 0)
					$socketTimeout = $this->socketTimeout;
				ini_set('default_socket_timeout', $socketTimeout);

				$size = @getimagesize($openNode['attributes']['tag_attributes']['src']);

				// z roznych przyczyn nie udalo sie pobrac zdjecia badz nie jest on plikiem graficznym
				if (!$size) {
					$openNode = $settings->removeNode($openNode);
					$cNode = $settings->removeNode($cNode);
					$openNode['text'] = '[Nieprawidłowe zdjęcie]';
					return false;
				}
			}
			elseif (
					isset($openNode['attributes']['tag_attributes']['width'])
					|| isset($openNode['attributes']['tag_attributes']['height'])
			) {
				$size = array();
				$size[0] = (isset($openNode['attributes']['tag_attributes']['width'])) ? $openNode['attributes']['tag_attributes']['width'] : false;
				$size[1] = (isset($openNode['attributes']['tag_attributes']['height'])) ? $openNode['attributes']['tag_attributes']['height'] : false;

				if ($size[0] === false && $size[1] === false)
					$size = false;
			}


			if (isset($size) && $size) {
				//pomocnicze zachowanie wymiarow
				$mainWidth = $size[0];
				$mainHeight = $size[1];

				$width = (isset($openNode['attributes']['tag_attributes']['width'])) ? $openNode['attributes']['tag_attributes']['width'] : $size[0];
				$height = (isset($openNode['attributes']['tag_attributes']['height'])) ? $openNode['attributes']['tag_attributes']['height'] : $size[1];

				// szerokosc
				if ($this->imageMaxWidth > 0 && $width > $this->imageMaxWidth) {
					$width = $this->imageMaxWidth;
					$height = ($this->imageMaxWidth * $height) / $width;
				}

				// wysokosc
				if ($this->imageMaxHeight > 0 && $height > $this->imageMaxHeight) {
					$height = $this->imageMaxHeight;
					$width = ($this->imageMaxHeight * $width) / $height;
				}

				$width = round($width);
				$height = round($height);

				if ($this->imageMinWidth > 0 && $this->imageMinWidth > $width) {
					$width = $this->imageMinWidth;
				}

				if ($this->imageMinHeight > 0 && $this->imageMinHeight > $height) {
					$height = $this->imageMinHeight;
				}

				if ($width != $mainWidth) {
					$openNode['attributes']['tag_attributes']['width'] = $width;
				}

				if ($height != $mainHeight) {
					$openNode['attributes']['tag_attributes']['height'] = $height;
				}
			}

			// przywrocenie domyslnego ustawienia
			if (ini_get('allow_url_fopen') && $this->checkRealImageSize) {
				ini_set('default_socket_timeout', $oldDefaultSocketTimeout);
			}
		}

		// ustawianie ostatecznie pobranych argumentow
		$imgValue = '';
		if (isset($openNode['attributes']['tag_attributes']['width'])) {
			$imgValue.=$openNode['attributes']['tag_attributes']['width'];
		}
		if (isset($openNode['attributes']['tag_attributes']['height'])) {
			$imgValue.='x'.$openNode['attributes']['tag_attributes']['height'];
		}

		if ($imgValue)
			$openNode['attributes']['tag_attributes']['img'] = $imgValue;

		// usuwamy zawartosc body
		foreach ($body as $key => &$el) {
			if ($el['type'] != 0) {
				$el = array('type' => 0, 'text' => '', 'tagText' => $el['text']);
			}
			else
				$el=array('type' => 0, 'text' => '', 'tagText' => $el['text']);
		}

		$cNode['text'] = '';
		$openNode = BbCode::rebuildNode($tag, $openNode, $settings);
		$openNode['text'] = substr($openNode['text'], 0, -1).'/>'; // domykamy img :)
	}
}

