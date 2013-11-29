<?php
/**
  Copyright (c) 2009 Wookieb
  All rights reserved.

  Redistribution and use in source and binary forms, with or without
  modification, are permitted provided that the following conditions
  are met:
  1. Redistributions of source code must retain the above copyright
  notice, this list of conditions and the following disclaimer.
  2. Redistributions in binary form must reproduce the above copyright
  notice, this list of conditions and the following disclaimer in the
  documentation and/or other materials provided with the distribution.
  3. The name of the author may not be used to endorse or promote products
  derived from this software without specific prior written permission.

  THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
  IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
  OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
  IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
  INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
  NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
  DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
  THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
  THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
require_once dirname(__FILE__).'/BbCodeSettings.php';

/**
 *  Główny parser
 *  @package Parser
 * 	@author wookieb
 * 	@version 1.2.1
 */
class BbCode {
	/**
	 * 	Lista aktualnie obsługiwanych tagów
	 * @var array
	 */
	private $tags = array();
	/**
	 * Lista elementów ciągu
	 * @var array
	 */
	private $_nodesArray = array();
	/**
	 * 	Aktualnie parsowany tekst
	 * @var string
	 */
	private $_text = false;
	/**
	 * 	Sparsowany do html-a tekst
	 * @var string
	 */
	private $_parseText = false;
	/**
	 * 	Lista instancji załadowanych filtrów
	 * @var array
	 */
	private $filters = array();
	/**
	 * 	Lista callbacków dla funkcjii nobody_parse
	 * @var array
	 */
	private $noBodyFunctionsCallbacks = array();
	/**
	 * 	Aktualne ustawienia parsera
	 * @var BbCodeSettings
	 */
	private $settings;

	/**
	 * Wartość domyślna jest wstawiana tylko wtedy kiedy użyto nazwy atrybutu, lecz jego wartość jest nieprawidłowa
	 * np [tag param="dd"] => [tag param="default_value"]
	 */
	const DEFAULT_MODE_NO_VALID = 'no_valid';

	/**
	 * Wartość domyślna jest wstawiana tylko wtedy kiedy nie używo w ogóle nazwy atrybutu
	 * np [tag] => [tag param="default_value"]
	 */
	const DEFAULT_MODE_NO_ENTER = 'no_enter';

	/**
	 * Wartość domyślna wstawiana, jeżeli parametr jest nieprawidłowy, badź parametr nie został w ogóle podany
	 */
	const DEFAULT_MODE_BOTH = 'both';

	/**
	 * Tryb sprawdzania rodzica {@link _checkAllowedTagName()}
	 */
	const CHECK_PARENT = 'parent';

	/**
	 * Tryb sprawdzania dziecko {@link _checkAllowedTagName()}
	 */
	const CHECK_CHILD = 'child';

	/**
	 * Typ elementu "tekst"
	 */
	const NODE_TYPE_TEXT = 0;

	/**
	 * Typ elementu "tag otwierający"
	 */
	const NODE_TYPE_OPEN = 1;

	/**
	 * Typ elementu "tag zamykający"
	 */
	const NODE_TYPE_CLOSE = 2;

	/**
	 * Typ elementu "tag samozamykający"
	 */
	const NODE_TYPE_SELF_CLOSE = 3;

	/**
	 * Tryb tworzenie tekstu wynikowego "html"
	 */
	const CREATE_HTML = 1;

	/**
	 * Tryb tworzenie tekstu wynikowego "bbcode"
	 */
	const CREATE_BBCODE = 2;

	/**
	 *
	 * @param BbCodeSettings $settings
	 */
	public function __construct(BbCodeSettings $settings = null) {
		if ($settings === null)
			$settings = new BbCodeSettings();
		$this->setSettings($settings);
	}

	/**
	 * 	Zmiana obiektu ustawień
	 * @param BbCodeSettings $settings
	 */
	public function setSettings(BbCodeSettings $settings) {
		$this->settings = $settings;
		$this->_loadDefaultFilters();
	}

	/**
	 * 	Zwraca aktualnie ustawienia bbcode
	 * @return BbCodeSettings
	 */
	public function getSettings() {
		return $this->settings;
	}

	/**
	 * Zwraca informacje na temat tagu bbcode.
	 * Null gdy taga nie ma.
	 * Funkcji tej używamy TYLKO do sprawdzenia czy tag istnieje.
	 * W reszcie kodu pobieramy informacje o tagu wprost z {@link $tags}
	 * @param string $tag nazwa taga
	 * @return array|null|false null gdy takiego taga nie ma, false gdy jest niedostepny
	 */
	private function _getTagInfo($tag) {
		if ($this->settings->availableTags === null) {
			if (isset($this->tags[$tag]))
				return $this->tags[$tag];
		}
		elseif (is_array($this->settings->availableTags)
				&& in_array($tag, $this->settings->availableTags)) {
			return $this->tags[$tag];
		}
		else {
			return false;
		}
	}

	/**
	 * Dodaje filtr do parsera
	 * @param string $filter
	 * @return bool czy dodawanie sie powiodlo
	 */
	public function addFilter($filter) {
		return $this->_loadFilter($filter);
	}

	/**
	 * 	Usuwa filtr
	 * 	@param $filter string nazwa filtra
	 */
	public function removeFilter($filter) {
		$filter = ucfirst(strtolower($filter));
		if (!isset($this->filters[$filter]))
			return;

		// usuwanie tagow filtra
		foreach (array_keys($this->filters[$filter]->tags) as $tag) {
			if (isset($this->tags[$tag])) {
				$tagInfo = $this->tags[$tag];

				// usuniecie nobodycallbacks
				if (isset($tagInfo['parse_no_body']) && $tagInfo['parse_no_body']) {
					unset($this->noBodyFunctionsCallbacks[$tag]);
				}

				unset($this->tags[$tag]);
			}
		}

		unset($this->filters[$filter]);
	}

	/**
	 * 	Ładowanie domyślnych filtrów
	 */
	private function _loadDefaultFilters() {
		foreach ($this->settings->filters as $filter) {
			$this->_loadFilter($filter);
		}
	}

	/**
	 * Zwraca listę załadowanych tagów
	 * @return array
	 */
	public function getTagsList() {
		return array_keys($this->tags);
	}

	/**
	 * 	Ładuje filtr
	 * 	@param string $filter nazwa filtra
	 *  @return czy dodawania filtra powiodlo sie
	 */
	private function _loadFilter($filter) {
		$filter = ucfirst(strtolower($filter));
		if (isset($this->filters[$filter]))
			return true;

		$className = 'BbCodeFilter'.$filter;
		require_once dirname(__FILE__).'/Filters/'.$filter.'.php';

		if (!class_exists($className)) {
			trigger_error($className.' filter not exists', E_USER_NOTICE);
			return false;
		}

		$cl = new $className;
		foreach ($cl->tags as $tagName => &$tag) {
			$tag['filter'] = $filter;

			// nobody callbacks
			if (isset($tag['parse_no_body']) && $tag['parse_no_body']) {
				$this->noBodyFunctionsCallbacks[$tagName] = array(&$cl, $tag['parse_no_body']);
			}

			if (isset($tag['attributes'])) {
				foreach ($tag['attributes'] as $attributeName => &$attribute) {
					// szukanie wymaganych argumentow
					if (isset($attribute['required']) && $attribute['required'])
						$tag['required_attributes'][] = $attributeName;

					// szukanie domyslnych argumentow
					if (isset($attribute['default_value'])) {
						$tag['default_attributes'][] = $attributeName;
						if (!isset($attribute['default_mode']))
							$attribute['default_mode'] = 'both';
					}
				}
				// przyzwyczajenie
				unset($attribute);
			}
		}
		//dodanie tagow filtra
		$this->tags = array_merge($this->tags, $cl->tags);

		$this->filters[$filter] = $cl;

		return true;
	}

	/**
	 * Wykonuje funkcje parseBody oraz noBodyParse dla tekstu
	 * Operuje bezpośrednio na {@link _nodesArray}
	 */
	private function _filtersParseBody() {
		$noBodyParse = false;

		// Sprawdzenie czy możemy uruchomić noBodyParse
		if ($this->noBodyFunctionsCallbacks && $this->settings->noBodyParse) {
			$openTags = array();
			$noBodyTags = array_keys($this->noBodyFunctionsCallbacks);
			$noBodyParse = true;
		}

		foreach ($this->_nodesArray as $nKey => &$node) {
			// jazda z funkcja noBody
			if ($noBodyParse && !$this->settings->trustText) {
				if ($node['type'] == self::NODE_TYPE_TEXT) {
					foreach ($noBodyTags as $noBodyTag) {
						// czy tag jest dostępny
						if (is_array($this->settings->availableTags)
								&& !in_array($noBodyTag, $this->settings->availableTags))
							continue;

						if (!in_array($noBodyTag, $openTags)) {
							$lastParent = end($openTags);
							if ($lastParent !== false) {
								$lastParent = $this->tags[$lastParent];
							}

							call_user_func_array($this->noBodyFunctionsCallbacks[$noBodyTag], array(&$node, $lastParent, $this->settings));
						}
					}
				}

				// usuwanie z tablicy otwartych tagów jest konieczne tylko przy włączonym
				if ($node['type'] == self::NODE_TYPE_CLOSE) {
					$openTags = array_reverse($openTags, true);

					// usuniecie z tablicy otwartych tagow
					$keyToDelete = array_search($node['tagname'], $openTags);
					if ($keyToDelete !== false)
						unset($openTags[$keyToDelete]);
					$openTags = array_reverse($openTags, true);
				}
			}

			if ($node['type'] == self::NODE_TYPE_OPEN) {
				if ($noBodyParse)
					$openTags[$nKey] = &$node['tagname'];

				if (isset($this->tags[$node['tagname']]['parse_body'])) {
					// jezeli kod jest zaufany to nie wykonujemy funkcji
					if (isset($this->tags[$node['tagname']]['no_parse_body_on_trust_text'])
							&& $this->settings->trustText) {
						continue;
					}

					// przygotowujemy odpowiednie referencje do funkcji
					$filterFunc = $this->tags[$node['tagname']]['parse_body'];
					$filterInstance = $this->filters[$this->tags[$node['tagname']]['filter']];

					$innerNodes = array();
					$closeNode = false;
					$maxKey = count($this->_nodesArray);
					$openSameTagCounter = 1;
					// najpierw szukamy elementów znajdujacych się w ciele parsowanego tagu
					for ($i = $nKey + 1; $i < $maxKey; $i++) {
						if (isset($this->_nodesArray[$i])) {
							$tmpNode = $this->_nodesArray[$i];

							if ($tmpNode['type'] !== self::NODE_TYPE_TEXT &&
									$tmpNode['tagname'] == $node['tagname']) {
								// licznik
								if ($tmpNode['type'] == self::NODE_TYPE_OPEN)
									$openSameTagCounter++;
								else
									$openSameTagCounter--;
							}

							if (
									$tmpNode['type'] === self::NODE_TYPE_CLOSE
									&& $tmpNode['tagname'] === $node['tagname']
									&& $openSameTagCounter === 0
							) {
								$closeNode = &$this->_nodesArray[$i];
								break;
							}

							$innerNodes[] = &$this->_nodesArray[$i];
						}
					}
					call_user_func_array(
							array($filterInstance, $filterFunc),
							array(
								$this->tags[$node['tagname']], // informacje o parsowanym tagu
								&$node, // element otwierajacy
								&$innerNodes, // elementu "w srodku"
								&$closeNode, // element zamykajacy
								$this->settings)); // ustawienia parsera

					unset($closeNode);
					unset($innerNodes);
					unset($node);
				}
			}
		}
	}

	/**
	 * 	Główna funkcja rozbijąca tekst na części.
	 * Jej wynik przechowywany jest w {@link $_nodesArray}.
	 * <br/>Funkcja dodatkowo zamyka nie zamknięte tagi starając się respektować
	 * zasady "rodzicielstwa"
	 */
	private function _buildNodesArray() {
		$this->_nodesArray = array();
		$openNodes = array();

		$strPos = 0;
		$strLength = strlen($this->_text);

		while ($strPos < $strLength) {
			$node = array('type' => self::NODE_TYPE_TEXT);

			// otwierajacy znak [potencjalny_tag
			$openPos = strpos($this->_text, $this->settings->openChar, $strPos);
			if ($openPos === false) {
				// jezeli nie ma takiego znaku to koniec parsowania. Zadnych tagow bb
				$node['text'] = substr($this->_text, $strPos);
				$newStrPos = $strLength;
			}
			else {
				// zeby nie pominac tekstu przed tagiem
				if ($openPos == $strPos) {
					// szukanie znaku zamykajacego tag [potencjalny_tag]
					$closePos = strpos($this->_text, $this->settings->closeChar, $openPos);
					if ($closePos === false) {
						$node['text'] = substr($this->_text, $openPos);
						$newStrPos = $strLength;
					}
					else {
						// [?    [potencjalny_tag]
						$nextOpenPos = strrpos(substr($this->_text, $openPos + 1, $closePos - $openPos), $this->settings->openChar);

						if ($nextOpenPos !== false) {
							// jezeli pomiedzy [ ] wystepuje kolejny [ to zwraca text od [ do ostatniego [
							$node['text'] = substr($this->_text, $openPos, $nextOpenPos + 1);
							$newStrPos = $nextOpenPos + $openPos + 1;
						}
						else {
							$nodeText = substr($this->_text, $openPos, $closePos - $openPos + 1);
							$newNode = $this->_createNode($nodeText);

							$newStrPos = $closePos + 1;
							// jezeli z tagiem wszystko ok to go wrzucamy
							if ($newNode !== false) {
								if ($newNode['type'] !== self::NODE_TYPE_TEXT) {
									// info o aktualnym tagu
									$nodeInfo = $this->tags[$newNode['tagname']];

									// pobieramy ostatniego rodzica
									$parent = end($openNodes);

									if ($newNode['tagname'] == $parent && $newNode['type'] == self::NODE_TYPE_CLOSE) {
										array_pop($openNodes);
										$node = $newNode;
									}
									else {
										if ($parent) {
											$parentTagInfo = $this->tags[$parent];
											// czy aktualny element jest dozwolonym dzieckiem rodzica
											if ($newNode['tagname'] != $parent &&
													!self::_checkAllowedTagName($parentTagInfo, self::CHECK_CHILD, $newNode['tagname'])) {
												// czy pomimo to zostawiac kod elementu
												if (isset($parentTagInfo['leave_notallowed_child']) && $parentTagInfo['leave_notallowed_child']) {
													$newNode = array('type' => self::NODE_TYPE_TEXT, 'text' => $newNode['original_text']);
												}
												else
													$newNode=$this->settings->removeNode($newNode);
											}
										}
										// czy rodzic aktualnego elementu jest dozwolony
										if ($newNode['type'] != self::NODE_TYPE_CLOSE &&
												!self::_checkAllowedTagName($nodeInfo, self::CHECK_PARENT, $parent)) {
											// czy pomimo tego zostawiac kod elementu
											if (isset($nodeInfo['leave_notallowed_parent']) && $nodeInfo['leave_notallowed_parent']) {
												$newNode = array('type' => self::NODE_TYPE_TEXT, 'text' => $newNode['original_text']);
											}
											else
												$newNode=$this->settings->removeNode($newNode);
										}

										// dodajemy nowo otwarty tagss
										if ($newNode['type'] == self::NODE_TYPE_OPEN) {
											$openNodes[] = $newNode['tagname'];
										}

										// pora zamknac tag
										if ($newNode['type'] == self::NODE_TYPE_CLOSE) {

											// usuniecie z tablicy otwartych tagow jezeli mozna
											if (in_array($newNode['tagname'], $openNodes)) {
												$openNodes = array_reverse($openNodes, true);
												$findedKey = array_search($newNode['tagname'], $openNodes);
												unset($openNodes[$findedKey]);
												$openNodes = array_reverse($openNodes);
											}
											else {
												$newNode = $this->settings->removeNode($newNode);
											}
										}
									}
								}
								$node = $newNode;
							}
							else
								$node['text'] = $nodeText;
						}
					}
				}
				else {
					$node['text'] = substr($this->_text, $strPos, $openPos - $strPos);
					$newStrPos = $openPos;
				}
			}

			$strPos = $newStrPos;

			$parent = end($openNodes);
			if ($parent) {
				$parentTagInfo = $this->tags[$parent];
				if ($node['type'] == self::NODE_TYPE_TEXT && isset($parentTagInfo['wrap_white_space']) && $parentTagInfo['wrap_white_space']) {
					// jezeli zawijanie bialych znakow jest wlaczone wiec to robimy
					$node['nobr'] = 1;
				}
			}

			// aktualny i ostatni element sa zwyklym tekstem wiec laczymy
			if (count($this->_nodesArray)) {
				$last = end($this->_nodesArray);

				if ($node['type'] === self::NODE_TYPE_TEXT && $last['type'] === self::NODE_TYPE_TEXT) {
					array_pop($this->_nodesArray);
					$node['text'] = $last['text'].$node['text'];
				}
				elseif ((($last['type'] === self::NODE_TYPE_OPEN && $node['type'] === self::NODE_TYPE_CLOSE)) && $last['tagname'] == $node['tagname']) {
					$node = false;
					array_pop($this->_nodesArray);
				}
			}
			if ($node != false) {
				$this->_nodesArray[] = $node;
			}
		}

		// jezeli zostały jakies otwarte tagi to trzeba je zamknąć
		if ($openNodes) {
			$nodesToClose = $this->_closeUnclosedTags($openNodes);
			$this->_nodesArray = array_merge($this->_nodesArray, $nodesToClose);
		}
	}

	/**
	 * 	Tworzy sekwencję tagów do zamknięcia na podstawie podanej listy otwartych tagów
	 * 	@param $openNodes array - otwarte tagi
	 * 	@return array tablica elementów w odpowiedniej kolejności zamknięcia
	 */
	private function _closeUnclosedTags($openNodes) {
		$nodesToClose = array();
		$parent = false;

		foreach ($openNodes as $node) {
			$nodeTagInfo = $this->tags[$node];
			if (!self::_checkAllowedTagName($nodeTagInfo, self::CHECK_PARENT, $parent)) {
				if (!$this->settings->validHtml)
					$nodesToClose[] = $this->_createNode($this->settings->openChar.'/'.$node.$this->settings->closeChar);
				continue;
			}

			if ($parent) {
				$parentTagInfo = $this->tags[$parent];
				if (!self::_checkAllowedTagName($parentTagInfo, self::CHECK_CHILD, $node)) {
					$nodesToClose[] = $this->_createNode($this->settings->openChar.'/'.$node.$this->settings->closeChar);
					continue;
				}
			}

			array_unshift($nodesToClose, $this->_createNode($this->settings->openChar.'/'.$node.$this->settings->closeChar));
			$parent = $node;
		}
		return $nodesToClose;
	}

	/**
	 * 	Tworzy element bbcode od początku. Przebudowuje jego atrybuty a także potrafi zmienić typ.
	 * 	UWAGA! Podane dane muszą być zaufane i nie sprawdzane są poprawności atrybutów. W przypadku chęci ich kontrol należy użyc {@link _createNode()}
	 * 	@param $tagInfo array - tablica ustawień taga wyciągnięta z filtra bądź z {@link $tags}
	 * 	@param $node array - cały element bbcode do przebudowania. Wymagane wartości to <i>type</i>, <i>original_text</i>, <i>tagname</i>.<br/>W przypadku gdy chcemy przebudować parametry taga konieczne jest też <i>attributes/tag_attributes</i>.
	 * 	@param BbCodeSettings $settings
	 * 	@return array nowy element
	 */
	public static function rebuildNode($tagInfo, $node, $settings) {
		$newNode = array(
			'type' => $node['type'],
			'original_text' => $node['original_text'],
			'tagname' => $node['tagname']
		);

		$tagText = $settings->openChar;
		$nodeText = '<';

		$tagText.=$node['tagname'];
		$nodeText.=$tagInfo['open'];

		if ($node['type'] === self::NODE_TYPE_CLOSE) {
			$tagText.='/';
			$nodeText.='/';
		}

		if (isset($node['attributes']['tag_attributes'])) {
			$attributes = self::_buildAttributes($tagInfo, $node['attributes']['tag_attributes'], $node['tagname'], $settings);
			$newNode['attributes'] = $attributes;
			$tagText.=$attributes['tag_attributes_str'];
			if ($attributes['html_attributes']) {
				$nodeText.=' '.$attributes['html_attributes'];
			}
		}
		if ($node['type'] == self::NODE_TYPE_SELF_CLOSE) {
			$tagText.='/';
			$nodeText.='/';
		}

		$tagText.=$settings->closeChar;
		$nodeText.='>';

		$newNode['text'] = $nodeText;
		$newNode['tagText'] = $tagText;
		return $newNode;
	}

	/**
	 * 	Tworzy element na podstawie jego zawartości. Wykonywana jest kontrola poprawności atrybutów.
	 * 	@param $text string - tekst taga np. [b atrybut=hehe]
	 * 	@return array
	 */
	private function _createNode($text) {
		$node = array
			(
			'type' => self::NODE_TYPE_OPEN,
			'original_text' => $text
		);

		if ($text[1] == '/') {
			// tag zamykajacy
			$node['type'] = self::NODE_TYPE_CLOSE;
		}

		// wyszukujemy nazwę taga
		if (preg_match('/'.$this->settings->openCharQuoted.'\/?([^\s|=]+).*'.$this->settings->closeCharQuoted.'/i', $text, $matches)) {
			$tagName = strtolower($matches[1]);
		}
		else {
			// to nie jest tag bbcode
			$node['text'] = $text;
			$node['type'] = self::NODE_TYPE_TEXT;
			return $node;
		}
		$tagInfo = $this->_getTagInfo($tagName);

		if (!is_array($tagInfo)) {
			// to nie jest tag bbcode (prawdopodobnie zwykly tekst)
			$node['text'] = ($tagInfo == false && $this->settings->removeNotAvailableTags) ? '' : $text;
			$node['type'] = self::NODE_TYPE_TEXT;
			return $node;
		}

		$node['tagname'] = $tagName;

		// pisanie taga
		$nodeText = '<';
		$tagText = $this->settings->openChar;

		if ($node['type'] === self::NODE_TYPE_CLOSE && $tagInfo['close'] !== false) {
			$tagText.='/';
			$nodeText.='/';
		}

		$tagText.=$tagName;

		// otwieranie taga
		if ($node['type'] === self::NODE_TYPE_OPEN)
			$nodeText.=$tagInfo['open'];
		else
			$nodeText.= ( $tagInfo['close']) ? $tagInfo['close'] : $tagInfo['open']; // ktory tag do zamkniecia

			if (isset($tagInfo['attributes']) && $node['type'] == self::NODE_TYPE_OPEN) {
			// jedziemy... parsowanie atrybutu
			$attributes = $this->_parseAttributes($tagInfo, $text);
			//jezeli nie ma odpowiednich atrybutów to jako tekst
			if ($attributes === -1) {
				return $this->settings->removeNode($node);
			}
			elseif (is_array($attributes)) {
				$attributes = self::_buildAttributes($tagInfo, $attributes, $tagName, $this->settings);
				$node['attributes'] = $attributes;
				$tagText.=$attributes['tag_attributes_str'];

				if ($attributes['html_attributes'])
					$nodeText.=' '.$attributes['html_attributes'];
			}
		}

		// zamykanie taga który zamyka sie sam w sobie
		if ($tagInfo['close'] === false) {
			$nodeText.='/';
			$node['type'] = self::NODE_TYPE_SELF_CLOSE;
		}

		$tagText.=$this->settings->closeChar;
		$nodeText.='>';

		$node['tagText'] = $tagText;
		$node['text'] = $nodeText;

		return $node;
	}

	/**
	 * 	Buduje parametry taga na podstawie jego listy parametrów oraz nazwy taga.<br/>Funkcja obsługuje domyślne wartości tagów.
	 * 	@param array $tagInfo tablica ustawień taga wyciągnięta z filtra bądź z {@link $tags}
	 * 	@param array $attributes tablica atrybutów taga gdzie klucz jest nazwą atrybutu
	 * 	@param string $tagName nazwa taga dla którego budujemy atrybuty
	 * 	@return array tablica atrybutów o naśtępujacych kluczach
	 *   html_attributes - string reprezentujący atrybuty dla tagu html
	 *   tag_attributes - tablica atrybutów taga
	 *   tag_attributes_str - string reprezentujący atrybuty dla tagu bb
	 */
	public static function _buildAttributes($tagInfo, $attributes, $tagName, $settings) {
		$htmlAttrs = array();
		$newTagAttributes = array();

		foreach ($attributes as $attrName => $attr) {

			$attrOptions = $tagInfo['attributes'][$attrName];
			if (
					!(
					( isset($attrOptions['default_value'])
					&& $attr == $attrOptions['default_value']
					&& $attrOptions['default_mode'] == 'no_enter' )
					||
					( isset($attrOptions['tag_no_show'])
					&& $attrOptions['tag_no_show'] )
					)
			) {
				// jezeli ciag zawiera spacje to dajemy w apostrofy
				$tmpAttr = $attr;
				if (strpos($tmpAttr, ' ') !== false)
					$tmpAttr = '\''.$tmpAttr.'\'';

				// atrybut == nazwa taga to na sam poczatek
				if ($attrName === $tagName)
					array_unshift($newTagAttributes, '='.$tmpAttr);
				else {
					// sry ale do kolejki
					$newTagAttributes[] = $attrName.'='.$tmpAttr;
				}
			}

			//jezeli atrybut html bedzie mogl przyjmowac wiecej argumentow
			if (isset($attrOptions['attr']) && $attrOptions['attr'] != null) {
				if (isset($settings->attributesSeparators[$attrOptions['attr']])) {
					$htmlAttrs[$attrOptions['attr']][] = (isset($attrOptions['name'])) ? $attrOptions['name'].' '.$attr : $attr;
				}
				else
					$htmlAttrs[$attrOptions['attr']] = (isset($attrOptions['name'])) ? $attrOptions['name'].' '.$attr : $attr;
			}
		}


		if (!isset($attributes[$tagName]))
			array_unshift($newTagAttributes, '');

		foreach ($htmlAttrs as $htmlAttrName => &$htmlAttr) {
			if (is_array($htmlAttr)) {
				$htmlAttr = $htmlAttrName.'="'.implode($settings->attributesSeparators[$htmlAttrName], $htmlAttr).'"';
			}
			else
				$htmlAttr=$htmlAttrName.'="'.$htmlAttr.'"';
		}


		return array
			(
			'html_attributes' => implode(' ', $htmlAttrs),
			'tag_attributes' => $attributes,
			'tag_attributes_str' => implode(' ', $newTagAttributes)
		);
	}

	/**
	 * 	Sprawdza czy można dodać domyślną wartość atrybutu w zależności od podanego trybu wartości domyślnej, jeżeli tak to odrazu dodaje ją do $tagAttributes
	 * 	@param string $attributeName nazwa atrybutu
	 * 	@param array $attributeOptions opcje atrybutu
	 * 	@param string $defaultMode tryb wartości domyślnej dla jakiej chcemy wykonac sprawdzenie. Możliwe wartości to 'no_valid' lub 'no_enter'
	 * 	@param array $tagAttributes tablica atrybutów elementu bb
	 */
	private function _parseDefaultAttribute($attributeName, $attributeOptions, $defaultMode, &$tagAttributes) {
		if (isset($attributeOptions['default_mode']) &&
				($defaultMode == $attributeOptions['default_mode']
				|| $attributeOptions['default_mode'] == self::DEFAULT_MODE_BOTH)) {
			$tagAttributes[$attributeName] = $attributeOptions['default_value'];
		}
	}

	/**
	 * 	Sprawdza i buduję listę parametrów taga na podstawie podanego tekstu
	 * 	@param array $tagInfo tablica ustawień taga wyciągnięta z filtra bądź z {@link $tags}
	 * 	@param string $text tekst z którego należy wyciągnąć atrybuty
	 * 	@return mixed - false w przypadku braku atrybutów, -1 w przypadku brak wymaganych atrybutów, tablica parametrów w przypadku prawidłowego odczytania parametrów
	 */
	private function _parseAttributes($tagInfo, $text) {
		$attr = $tagInfo['attributes']; // ustawienia atrybutów taga
		$tagAttributes = array(); // atrybuty taga

		$text = substr($text, 1, -1);
		preg_match_all('/\s*([a-z0-9-_]+)=(\'.+?\'|".+?"|\S*)\s*/i', $text, $matches, PREG_SET_ORDER);

		if (count($matches) == 0) {
			// nie ma wymaganego argumentu
			if (isset($tagInfo['required_attributes']))
				return -1;
			elseif (isset($tagInfo['default_attributes'])) {
				foreach ($tagInfo['default_attributes'] as $attrName) {
					$attrOptions = $tagInfo['attributes'][$attrName];
					$this->_parseDefaultAttribute($attrName, $attrOptions, 'no_enter', $tagAttributes);
				}
				return $tagAttributes;
			}
			else
				return false;
		}

		foreach ($matches as $attribute) {
			$tagAttr = strtolower($attribute[1]);
			if (strlen($attribute[2]) == 0)
				continue;
			// jezelo koncza sie na ' lub " to trzeba to usunac
			$attribute[2] = trim($attribute[2], '"\'');
			/*
			  if(in_array($attribute[2][0], array('\'', '"'))) $attribute[2]=substr($attribute[2],1);
			  if(in_array($attribute[2][strlen($attribute[2])-1], array('\'', '"'))) $attribute[2]=substr($attribute[2],0, -1);
			 */
			if (!$this->settings->trustText) {
				require_once dirname(__FILE__).'/DataValidator.php';
				// sprawdzanie w liscie mozliwych atrybutow ale takze czy atrybut sie nei powtarza
				if (!isset($attr[$tagAttr]) || isset($tagAttributes[$tagAttr]))
					continue;

				$options = $attr[$tagAttr];

				if (isset($options['no_changeable']) && $options['no_changeable'])
					continue;

				switch ($options['type']) {
					case 'number':
						$str = @DataValidator::parseNumber($attribute[2],
										(isset($options['dimensions'])) ? $options['dimensions'] : null,
										(isset($options['default_dimension'])) ? $options['default_dimension'] : null, true);
						break;

					case 'url':
						$str = @DataValidator::checkUrl($attribute[2]);
						break;

					default:
					case 'string':
						$str = @DataValidator::checkStringValues($attribute[2],
										(isset($options['values'])) ? $options['values'] : null,
										(isset($options['replace'])) ? $options['replace'] : null);
						break;
				}
				if ($str == false && isset($tagInfo['default_attributes']) && in_array($tagAttr, $tagInfo['default_attributes'])) {
					$this->_parseDefaultAttribute($tagAttr, $options, 'no_valid', $tagAttributes);
					continue;
				}
				elseif ($str == false)
					continue;
				else
					$str=htmlspecialchars($str);
			}
			else {
				$str = $attribute[2];
			}

			$tagAttributes[$tagAttr] = $str;
		}

		if (isset($tagInfo['default_attributes'])) {
			foreach ($tagInfo['default_attributes'] as $dAttribute) {
				if (!isset($tagAttributes[$dAttribute])) {
					$this->_parseDefaultAttribute($dAttribute, $tagInfo['attributes'][$dAttribute], 'no_enter', $tagAttributes);
				}
			}
		}

		if (isset($tagInfo['required_attributes'])) {
			//sprawdzanie czy wystapily wszystkie wymagane atrybuty
			foreach ($tagInfo['required_attributes'] as $rAttribute) {
				if (!isset($tagAttributes[$rAttribute]))
					return -1;
			}
		}

		return ($tagAttributes) ? $tagAttributes : false;
	}

	/**
	 * Sprawdza rodzicielstwo (dziecko dopuszczone dla danego rodzica i odwrotnie)
	 * <code>
	 * // czy b moze byc w i
	 * self::_checkAllowedTagName($this->tags['i'], self::CHECK_CHILD, 'b');
	 * // czy b moze miec za rodzica i
	 * self::_checkAllowedTagName($this->tags['b'], self::CHECK_PARENT, 'i');
	 * </code>
	 *
	 * @param array $tagInfo ustawienia taga
	 * @param string $checkType tryb sprawdzania child - dzieci {@link CHECK_CHILD} , parent - rodzice {@link CHECK_PARENT}
	 * @param string $tagName nazwa sprawdzanego taga
	 * @return boolean
	 */
	public static function _checkAllowedTagName($tagInfo, $checkType, $tagName) {
		if (
				( isset($tagInfo['allowed_'.$checkType]) &&
				(
				!(is_array($tagInfo['allowed_'.$checkType]) && in_array($tagName, $tagInfo['allowed_'.$checkType]) )
				|| $tagInfo['allowed_'.$checkType] == 'none'
				)
				)
				||
				( isset($tagInfo['notallowed_'.$checkType]) &&
				(
				(is_array($tagInfo['notallowed_'.$checkType]) && in_array($tagName, $tagInfo['notallowed_'.$checkType]) )
				|| $tagInfo['notallowed_'.$checkType] == 'all'
				)
				)
		) {
			return false;
		}
		return true;
	}

	/**
	 * Sprawdza i poprawia poprawność użycia kolejności tagów
	 * Funkcja pracuje bezpośrednio na {@link $_nodesArray}
	 */
	private function _checkValidHtml() {
		$newNodeArr = array();
		$openTags = array();
		$openTagsWithAttr = array();


		foreach ($this->_nodesArray as $key => $node) {
			$newNode = array();

			switch ($node['type']) {
				// tag otwierajacy
				case self::NODE_TYPE_OPEN:
					$openTags[count($newNodeArr) - 1] = $node['tagname'];
					if (isset($node['attributes'])) {
						$openTagsWithAttr[count($newNodeArr) - 1] = $node;
					}
					$newNode = $node;
					break;

				// tag zamykajacy
				case self::NODE_TYPE_CLOSE:

					// nie bylo tagu otwierajacego to papa
					if (!in_array($node['tagname'], $openTags)) {
						$newNode = $this->settings->removeNode($node);
						break;
					}

					// aktualny ostatni otwarty tag
					$parent = end($openTags);

					if ($parent != $node['tagname']) {
						$tmpCloseNodeArr = array();
						$tmpOpenNodeArr = array();

						// szukamy niedomknietych tagow
						$openTagsKeys = array_keys($openTags);
						$openTagsSearchKey = array_search($node['tagname'], array_reverse($openTags, true));
						$sliceKey = array_search($openTagsSearchKey, $openTagsKeys);

						// nie zamkniete tagi
						$noClosed = array_slice($openTags, $sliceKey + 1, null, true);

						// rodzic dla tagow otwieranych
						if ($sliceKey > 0)
							$validOpenTagsParent = $openTags[$openTagsKeys[($sliceKey - 1)]];
						else
							$validOpenTagsParent=false;

						$parent = $node['tagname'];
						foreach ($noClosed as $tKey => $tag) {
							$nodeTagInfo = $this->tags[$tag];
							$parentTagInfo = $this->tags[$parent];

							// zamykanie
							if (
									self::_checkAllowedTagName($nodeTagInfo, self::CHECK_PARENT, $node['tagname'])
									&&
									self::_checkAllowedTagName($parentTagInfo, self::CHECK_CHILD, $tag)
							) {
								$last = end($newNodeArr);

								if ($last['type'] == self::NODE_TYPE_OPEN && $last['tagname'] == $tag) {
									array_pop($newNodeArr);
								}
								else {
									$tmpCloseNodeArr[] = $this->_createNode($this->settings->openChar.'/'.$tag.$this->settings->closeChar);
									$parent = $tag;
								}
							}

							// otwieranie
							if (!self::_checkAllowedTagName($nodeTagInfo, self::CHECK_PARENT, $validOpenTagsParent)) {
								continue;
							}

							if ($validOpenTagsParent) {
								$vParentTagInfo = $this->tags[$validOpenTagsParent];
								if (!self::_checkAllowedTagName($vParentTagInfo, self::CHECK_CHILD, $tag)) {
									continue;
								}
							}

							if (isset($openTagsWithAttr[$tKey]))
								$tmpOpenNodeArr[] = $openTagsWithAttr[$tKey];
							else {
								$tmpOpenNodeArr[] = $this->_createNode($this->settings->openChar.$tag.$this->settings->closeChar);
							}
						}

						unset($openTags[$openTagsSearchKey]);

						$newNodeArr = array_merge($newNodeArr, array_reverse($tmpCloseNodeArr));
						//$node=false;
					}
					else {
						array_pop($openTags);

						$parent = end($openTags);

						/*
						  // niedozwolony rodzic?
						  $nodeTagInfo=$this->tags[$node['tagname']];
						  if(!self::_checkAllowedTagName($nodeTagInfo, self::CHECK_PARENT, $parent))
						  {
						  //usuwamy
						  continue;
						  }

						  // niedozwolony rodzic?
						  if($parent && !self::_checkAllowedTagName($this->tags[$parent], self::CHECK_CHILD, $node['tagname']))
						  {
						  //usuwamy
						  //continue;
						  } */

						$lastNode = end($newNodeArr);
						if ($lastNode['type'] == self::NODE_TYPE_OPEN && $lastNode['tagname'] == $node['tagname']) {
							array_pop($newNodeArr);
							$node = false;
						}
					}

					$newNode = $node;
					break;

				// tag samozamykajacy się lub tekst
				case self::NODE_TYPE_TEXT:
				case self::NODE_TYPE_SELF_CLOSE:
					$newNode = $node;
					break;
			}

			$lastElement = end($newNodeArr);

			if ($newNode) {
				if ($lastElement['type'] === self::NODE_TYPE_OPEN && $newNode['type'] === self::NODE_TYPE_CLOSE && $newNode['tagname'] == $lastElement['tagname']) {
					array_pop($newNodeArr);
				}
				else
					$newNodeArr[] = $newNode;
			}

			if (isset($tmpOpenNodeArr) && $tmpOpenNodeArr) {
				$newNodeArr = array_merge($newNodeArr, $tmpOpenNodeArr);
				$tmpOpenNodeArr = array();
			}
		}

		$this->_nodesArray = $newNodeArr;
	}

	/**
	 * Łączy elementy w ciąg znaków według podanego trybu
	 * W elementach textowych (typu {@link NODE_TYPE_TEXT}) mozna stosowac dodatkowe opcje o wartości TRUE:
	 * nohtmlspecialchars - tekst nie zostanie potraktowany funkcja htmlspecialchars<br/>
	 * nobr - tekst nie zostanie potraktowany funkcja nl2br oraz nie zostana zamienione tabulatory
	 *
	 * @param int $type typ ciągu wynikowego. Możliwe opcje to {@link CREATE_HTML} oraz {@link CREATE_BBCODE}
	 * @param array $nodes elementy które łączyć w tekst
	 * @return string
	 */
	private function _createParseText($type=self::CREATE_HTML, $nodes=false) {
		if ($nodes == false)
			$nodes = $this->_nodesArray;


		$mainText = '';
		switch ($type) {
			default:
			case self::CREATE_HTML:
				$key = 'text';
				break;

			case self::CREATE_BBCODE:
				$key = 'tagText';
				break;
		}

		foreach ($nodes as $nKey => $node) {

			if ($node['type'] == self::NODE_TYPE_TEXT && $key == 'text') {
				$addText = $node['text'];
				//takie oznaczenie mowi nam ze
				if (!isset($node['nohtmlspecialchars']) ||
						(isset($node['nohtmlspecialchars']) && !$node['nohtmlspecialchars'])) {
					$addText = htmlspecialchars($addText, ENT_NOQUOTES, $this->settings->charset);
				}

				if (!isset($node['nobr'])
						|| (isset($node['nobr']) && !$node['nobr'])) {
					$addText = nl2br($addText);
					// TABULATOR
					$addText = str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $addText);
				}
				$mainText.=$addText;
			}
			else
				$mainText.= ( isset($node[$key])) ? $node[$key] : $node['text'];
		}
		return $mainText;
	}

	/**
	 * Poprawiony bbcode z parsowanego tekstu
	 * @return string
	 */
	public function getBbcode() {
		return $this->_createParseText(self::CREATE_BBCODE);
	}

	/**
	 * HTML sparsowanego tekstu
	 * @return string
	 */
	public function getHtml() {
		return $this->_createParseText(self::CREATE_HTML);
	}

	/**
	 * Tworzy wycinek tekstu o podanej maksymalnej dlugosci oraz domyka w nim niezamkniete tagi
	 * @param int $length maksymalna dlugosc skracanego tekstu
	 * @param bool $toBb czy zwracac bbcode
	 * @param string $addText tekst dodawany na koncu skracanego tekstu
	 * @return string
	 */
	public function cutText($length, $toBb=false, $addText='…') {
		$text = '';
		$nodes = array();

		// lista otwartych tagów
		$openTags = array();
		// lista tagów do ukrycia
		$tagsToHide = array();

		foreach ($this->_nodesArray as $node) {

			if ($node['type'] === self::NODE_TYPE_OPEN) {
				// sprawdzamy czy tag jest ukryty
				$tagInfo = $this->tags[$node['tagname']];
				if (isset($tagInfo['hide_body_in_cut_text']) && $tagInfo['hide_body_in_cut_text'] == true) {
					$tagsToHide[] = $node['tagname'];
					continue;
				}

				$openTags[] = $node['tagname'];
			}
			elseif ($node['type'] === self::NODE_TYPE_CLOSE) {
				// sprawdzamy czy tag jest ukryty
				$tagInfo = $this->tags[$node['tagname']];
				if (isset($tagInfo['hide_body_in_cut_text']) && $tagInfo['hide_body_in_cut_text'] == true) {
					$keys = array_keys($tagsToHide);
					if ($keys) {
						$keys = array_reverse($keys);
						unset($tagsToHide[$keys[0]]);
					}
					unset($keys);
					continue;
				}

				$keys = array_keys($openTags, $node['tagname']);
				if ($keys) {
					$keys = array_reverse($keys);
					unset($openTags[$keys[0]]);
				}
				unset($keys);
			}

			// czy nadal jestesmy w tekscie do ukrycia
			if ($tagsToHide)
				continue;
			if ($node['type'] === self::NODE_TYPE_TEXT) {
				$lastOpenTag = end($openTags);
				if ($lastOpenTag) {
					$tagInfo = $this->_getTagInfo($lastOpenTag);
					if (isset($tagInfo['wrap_white_space']) && $tagInfo['wrap_white_space']) {
						$node['text'] = preg_replace('/\s+/', '', $node['text']);
					}
				}
				$text.=$node['text'];
				$textLength = mb_strlen($text, $this->settings->charset);
				if ($textLength >= $length) {
					$nodeTextLength = mb_strlen($node['text'], $this->settings->charset);
					$textLengthWithoutNodeText = $textLength - $nodeTextLength;
					$node['text'] = mb_substr($node['text'], 0, $length - $textLengthWithoutNodeText, $this->settings->charset);
					if (mb_substr($node['text'], $length, 1, $this->settings->charset) !== ' ') {
						$spaceLastPos = mb_strrpos($node['text'], ' ', null, $this->settings->charset);
						if ($spaceLastPos !== false) {
							$node['text'] = mb_substr($node['text'], 0, $spaceLastPos, $this->settings->charset);
						}
					}
					
					$nodes[] = $node;
					$saveNodes = $nodes;

					$nodes = array_reverse($nodes);
					foreach ($nodes as $key => &$tmpNode) {
						if ($tmpNode['type'] === self::NODE_TYPE_TEXT) {
							if ($addText != false)
								$tmpNode['text'].=$addText;
							break;
						}
						else
							unset($nodes[$key]);
					}
					$nodes = array_reverse($nodes);
					if (count($nodes) == 0)
						$nodes = $saveNodes;
					break;
				}
			}
			$nodes[] = $node;
		}

		if ($nodes == $this->_nodesArray) {
			if ($toBb)
				return $this->getBbcode();
			else
				return $this->getHtml();
		}

		$closeNodes = $this->_closeUnclosedTags($openTags);
		if ($closeNodes) {
			// usuwanie niepotrzebnych tagow typu [i][/i]
			$lastOpenTag = end($nodes);
			foreach ($closeNodes as $closeNodeKey => $closeNode) {
				if ($lastOpenTag !== false &&
						$lastOpenTag['type'] == self::NODE_TYPE_OPEN &&
						$closeNode['tagname'] == $lastOpenTag['tagname']) {
					unset($closeNodes[$closeNodeKey]);
					array_pop($nodes);
				}
				else
					break;
			}
			$nodes = array_merge($nodes, $closeNodes);
		}

		$createType = self::CREATE_HTML;
		if ($toBb)
			$createType = self::CREATE_BBCODE;

		$text = $this->_createParseText($createType, $nodes);
		return $text;
	}

	/**
	 * Parsuje podany kod bbcode. Funkcja zwraca sparsowany kod html tylko w przypadku gdy ustawimy $buildText na true
	 * @param string $text tekst do sparsowania
	 * @param boolean $buildText czy tworzyć sparsowany tekst html
	 * @return string tylko wtedy kiedy $buildText jest true
	 */
	public function parse($text=false, $buildText=true) {
		if ($text != false) {
			$this->_text = $text;
			$this->_parseText = false;
		}

		if ($this->_text == false || strlen($text) == 0)
			return;

		$this->_buildNodesArray();

		// nie walidujemy htmla skoro kod jest zaufany
		if (!$this->settings->trustText) {
			if ($this->settings->validHtml)
				$this->_checkValidHtml();
		}
		// parsowanie funkcji noBodyParse i parseBody
		$this->_filtersParseBody();

		if ($buildText) {
			$this->_parseText = $this->_createParseText();
			return $this->_parseText;
		}
	}
}
