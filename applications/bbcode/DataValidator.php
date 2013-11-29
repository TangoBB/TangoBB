<?php

/**
 * Walidator dla parsera bbcode
 * @package Parser
 * @author Wookieb
 * @version 1.2
 */
class DataValidator {
	/**
	 *  Jednostka użyta w ostatniej operacji funkcji {@link parseNumber()}
	 * @var string
	 */
	public static $parseNumberDimension = false;

	/**
	 * Sprawdza wartosc tekstowa.
	 * Przepuszcza wszystkie wartosci, jezeli nie podano parametrow $values i $replace
	 *
	 * @param string $str ciag do sprawdzenia
	 * @param array $values dopuszczalne wartosci wraz z mozliwosciami zamian
	 * @param array $replace czy dopuszczac do zamiany ciagu
	 * @return string
	 */
	public static function checkStringValues($str, $values=null, $replace=null) {
		// jezeli dopuszczane są wszystkie wartości to lepiej zabezpieczyć się przez jakimś xss-em
		if ($values === null) {
			return htmlspecialchars($str);
		}

		if (!is_array($values)) {
			$values = array();
		}

		if (in_array($str, $values)) {
			return $str;
		}
		elseif ($replace && array_key_exists($str, $values)) {
			return $values[$str];
		}
		else {
			return false;
		}
	}

	/**
	 * Sprawdza numer pod kątem podanej jednostki i podanych do niej opcji
	 * @param string $text tekst do sprawdzenia
	 * @param array $dimensions tablica możliwych jednostek. Nazwy jednostek powinno być kluczami owej tablicy natomiast wartośći, tablicą.
	 * Oto możliwe wartości ustawień:
	 * 	<strong>min_value</strong> - wartość minimalna
	 * 	<strong>max_value</strong> - wartość maksymalna
	 * 	<strong>values</strong> - tablica dopuszczalnych wartości. Podanie tej tablicy spowoduje zignorowanie ustawień min_value oraz max_values
	 * 	<strong>round_places</strong> - liczba miejsc po przecinku do których zaokrąglić liczbę
	 * 	<strong>absolute</strong> - Czy liczba ma być wartośćią bezwzględną
	 *
	 * @param string $defaultDimension domyślna jednostka
	 * @param bool $retDimension czy zwracana liczba ma zawierać jednostkę?
	 * @return string|bool
	 * @uses self::$parseNumberDimension nazwa jednostki uzytej podczas ostatniego sprawdzania numeru
	 */
	public static function parseNumber($text, $dimensions, $defaultDimension=false, $retDimension=false) {
		$text = trim($text);
		// brak tekstu
		if (strlen($text) == 0) {
			trigger_error('Empty string to check', E_USER_NOTICE);
		}

		//niepoprawny format
		if (!preg_match('/([0-9]+(?:(?:\.|\,)[0-9]+)?)\s*([a-z]*)/i', $text, $matches))
			return -1;

		$number = (float)$matches[1];
		$dimension = trim(strtolower($matches[2]));

		//have dimension
		if ($dimension == '') {
			// nie ma domyslnego to pobiera pierwszy z brzegu
			if ($defaultDimension == false)
				$dimension = reset(array_keys($dimensions));
			else
				$dimension=$defaultDimension;
		}

		self::$parseNumberDimension = $dimension;

		// nie ma jednostki badz nie ustawien jednostki to wywala numer
		if ($dimension === false) return $number;
		if (!isset($dimensions[$dimension])) return $number;

		$options = $dimensions[$dimension];
		// zaokraglanie
		if (isset($options['round_places']) && is_numeric($options['round_places']) && $options['round_places'] >= 0) {
			$roundPlaces = $options['round_places'];
			$number = round($number, $roundPlaces);
		}

		// wartosc bezwzgledna
		if (isset($options['absolute']) && $options['absolute']) {
			$number = abs($number);
		}

		if (isset($options['values']) && is_array($options['values'])) {
			if (in_array($number, $options['values'])) {
				return ($retDimension) ? $number.$dimension : $number;
			}
			else {
				// nie jest w podanych wartosciach
				trigger_error('The number is not in valid array', E_USER_NOTICE);
			}
		}

		if (isset($options['min_value']) && is_numeric($options['min_value'])) {
			if ($number < $options['min_value']) {
				trigger_error('Out of range (minimal)', E_USER_NOTICE); // wartosc za mala
				return false;
			}
		}

		if (isset($options['max_value']) && is_numeric($options['max_value'])) {
			if ($number > $options['max_value']) {
				trigger_error('Out of range (maximal)', E_USER_NOTICE); // wartosc za duza
				return false;
			}
		}

		return ($retDimension) ? $number.$dimension : $number;
	}

	/**
	 * Sprawdza adres url i wycina z niego
	 * @param string $text
	 * @return string|bool false w przypadku nieprawidlowego urla
	 */
	public static function checkUrl($text) {
		if (filter_var($text, FILTER_VALIDATE_URL) === false) return false;
		return preg_replace('/(javascript:)/is', '', $text);
	}
}

