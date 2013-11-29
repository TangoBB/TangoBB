<?php

/**
 * Ustawienia dla parsera bbcode
 * @package Parser
 * @author wookieb
 * @version 1.0
 *
 * @property-read $openChar
 * @property-read $closeChar
 * @property-read $openCharQuoted
 * @property-read $closeCharQuoted
 */
class BbCodeSettings {
	/**
	 * 	Znak rozpoczynający tag
	 * @var string
	 */
	protected $openChar = '[';
	/**
	 * Znak rozpoczynajacy potraktowany preg_quote
	 * @var string
	 */
	protected $openCharQuoted;
	/**
	 * 	Znak kończący tag
	 * @var string
	 */
	protected $closeChar = ']';
	/**
	 * Znak konczacy tag potraktowany preg_quote
	 * @var string
	 */
	protected $closeCharQuoted;
	/**
	 * 	Lista filtrów do uruchomienia
	 * @var array
	 */
	public $filters = array('basic', 'code', 'url', 'list', 'image', 'youtube');
	/**
	 * 	Kodowanie używane przy skracaniu tekstu
	 * @var string
	 */
	public $charset = 'utf-8';
	/**
	 * 	Czy usuwać nieprawidłowo użyte tagi (złe atrybuty). Parametr ignorowany przy kontroli kolejności użycia.
	 * @var bool
	 */
	public $removeInvalidTags = true;
	/**
	 * 	Czy parsowany tekst jest zaufanym kodem
	 * @var bool
	 */
	public $trustText = false;
	/**
	 * 	Czy zezwalać na dodatkowe parsowanie tekstu przez funkcję nobody_parse dla tagów
	 * @var string
	 */
	public $noBodyParse = true;
	/**
	 * 	Czy poprawiać poprawność użycia kolejności tagów. Np. [b]tekst[i] kursywą[/b] zostanie zamienione na [b]tekst[i] kursywą[/i][/b]
	 * @var bool
	 */
	public $validHtml = true;
	/**
	 * 	Lista atrybutów dla których możemy podać więcej niż jedną wartość.
	 * 	Klucz jest nazwą atrybutu, wartość separatorem oddzielający kolejne wartości
	 * 	@var array
	 */
	public $attributesSeparators = array(
		'style' => ';',
		'class' => ' '
	);
	/**
	 * Lista dostępnych tagów. Null jeżeli wszystkie.
	 * INFORMACJA! Dostępność oznacza możliwość użycia tagu w całym tekście.
	 * Tak więc np, jeżeli ustawimy tylko i wyłącznie dostępność tagu "i" to
	 * Linki nie będą automatycznie wyszukiwane (niedostępność taga URL)
	 * Nie obsługiwanie zdjęć (niedostępność taga IMG) itd.
	 *
	 * Aby ustawienie "hide_body_in_cut_text" działało poprawnie wymagana jest
	 * dostępność takiego taga. Tak więc np jeżeli wyłączymy IMG to link bo
	 * obrazka zostanie pokazany, ponieważ parser potraktuje taki tag jako zwykły tekst;
	 * 
	 * @var array
	 */
	public $availableTags = null;
	/**
	 * @var bool czy usuwać tagi, które istnieją lecz nie są dostępne do użycia
	 * @see $availableTags
	 */
	public $removeNotAvailableTags = true;

	public function __construct() {
		$this->setBbcodeChars($this->openChar, $this->closeChar);
	}

	/**
	 * Ustawia nowe znaki otwierajace i zamykajace kod bbcode
	 * @param string $open
	 * @param string $close
	 */
	public function setBbcodeChars($open = null, $close = null) {
		if ($open) {
			$this->openChar = (string)$open;
			$this->openCharQuoted = preg_quote($this->openChar);
		}

		if ($close) {
			$this->closeChar = (string)$close;
			$this->closeCharQuoted = preg_quote($this->closeChar);
		}
	}

	/**
	 * Standardowy getter
	 * @param string $name
	 * @return string
	 */
	public function __get($name) {
		return $this->$name;
	}

	/**
	 * Usuwa element, a dokładniej sprawdza czy MOŻE usunąć element czy
	 * tylko pozbawić go wszelkich wartości
	 * @param array $node element do usuniecia
	 * @return array nowy element
	 */
	public function removeNode($node) {
		if ($this->removeInvalidTags)
			return array('type' => BbCode::NODE_TYPE_TEXT, 'text' => '');
		else
			return array('type' => BbCode::NODE_TYPE_TEXT, 'text' => $node['original_text']);
	}
}
