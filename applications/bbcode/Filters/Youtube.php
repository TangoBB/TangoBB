<?php

/**
 * Tag YOUTUBE
 * @package Parser
 * @subpackage Filters
 * @author wookieb
 * @version 1.1
 */
class BbCodeFilterYoutube {
	public $tags = array(
		'youtube' => array(
			'open' => 'youtube', // tak naprawde moze byc tutaj cokolwiek bo i tak potem to wywalimy
			'close' => 'youtube',
			'notallowed_child' => 'all', // zadnych innych tagow w srodku nie potrzebujemy
			'parse_body' => 'checkMovie',
			'hide_body_in_cut_text' => true
		)
	);

	/**
	 * Parsuje YOUTUBE
	 * @param array $tag wszystkie informacje o tagu
	 * @param array $openNode tag otwierajacy
	 * @param array $body zawertosc pomiedzy tagiem otwierajacym a zamykajacym
	 * @param array $closeNode tak zamykajacy
	 * @param BbCodeSettings $settings
	 */
	public function checkMovie($tag, &$openNode, &$body, &$closeNode, $settings) {
		require_once dirname(__FILE__).'/../DataValidator.php';
		// wyciagamy caly tekst
		$bodyStr = '';
		foreach ($body as &$el) {
			$bodyStr.=$el['text'];
			// wersja do wyswietlenia
			$el['text'] = '';
		}

		// sprawdzamy czy ciag jest urlem
		$str = DataValidator::checkUrl($bodyStr);

		// skoro nie jest urlem wiec ISTNIEJE prawdopodobienstwo, ze jest to id filmu
		if ($str == false) {
			$idMovie = htmlspecialchars($bodyStr);
		}
		elseif (preg_match('/watch\?v=([a-zA-Z0-9_\-]+)/i', $str, $matches)) {
			// szukanie id filmu
			$idMovie = $matches[1];
		}
		else {
			// nie ma id wiec lipa
			$openNode = $settings->removeNode($openNode);
			$closeNode = $settings->removeNode($closeNode);
			return false;
		}

		// zamkniecie nie jest nam potrzebne
		$closeNode['text'] = '';

		// Ustawiamy content
		$openNode['text'] = '<iframe title="YouTube video player" width="560" height="349"
src="http://www.youtube.com/embed/'.$idMovie.'?rel=0" frameborder="0">
</iframe>
';

		//ustawiamy link dla zaufanego bbcode
		reset($body);
		$body[key($body)]['tagText'] = $str;
	}
}

