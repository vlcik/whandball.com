<?php

/*
 * SeoComponent
*
* @author Juraj Vlk juraj.vlk@gmail.com
*/
App::uses('Component', 'Controller');
class SeoComponent extends Component {

	private $uses = array('Category');
	private $controller;

	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}
	 
	public $prevodna_tabulka = Array(
			'ä'=>'a',
			'Ä'=>'A',
			'á'=>'a',
			'Á'=>'A',
			'à'=>'a',
			'À'=>'A',
			'ã'=>'a',
			'Ã'=>'A',
			'â'=>'a',
			'Â'=>'A',
			'č'=>'c',
			'Č'=>'C',
			'ć'=>'c',
			'Ć'=>'C',
			'ď'=>'d',
			'Ď'=>'D',
			'ě'=>'e',
			'Ě'=>'E',
			'é'=>'e',
			'É'=>'E',
			'ë'=>'e',
			'Ë'=>'E',
			'è'=>'e',
			'È'=>'E',
			'ê'=>'e',
			'Ê'=>'E',
			'í'=>'i',
			'Í'=>'I',
			'ï'=>'i',
			'Ï'=>'I',
			'ì'=>'i',
			'Ì'=>'I',
			'î'=>'i',
			'Î'=>'I',
			'ľ'=>'l',
			'Ľ'=>'L',
			'ĺ'=>'l',
			'Ĺ'=>'L',
			'ń'=>'n',
			'Ń'=>'N',
			'ň'=>'n',
			'Ň'=>'N',
			'ñ'=>'n',
			'Ñ'=>'N',
			'ó'=>'o',
			'Ó'=>'O',
			'ö'=>'o',
			'Ö'=>'O',
			'ô'=>'o',
			'Ô'=>'O',
			'ò'=>'o',
			'Ò'=>'O',
			'õ'=>'o',
			'Õ'=>'O',
			'ő'=>'o',
			'Ő'=>'O',
			'ř'=>'r',
			'Ř'=>'R',
			'ŕ'=>'r',
			'Ŕ'=>'R',
			'š'=>'s',
			'Š'=>'S',
			'ś'=>'s',
			'Ś'=>'S',
			'ť'=>'t',
			'Ť'=>'T',
			'ú'=>'u',
			'Ú'=>'U',
			'ů'=>'u',
			'Ů'=>'U',
			'ü'=>'u',
			'Ü'=>'U',
			'ù'=>'u',
			'Ù'=>'U',
			'ũ'=>'u',
			'Ũ'=>'U',
			'û'=>'u',
			'Û'=>'U',
			'ý'=>'y',
			'Ý'=>'Y',
			'ž'=>'z',
			'Ž'=>'Z',
			'ź'=>'z',
			'Ź'=>'Z'
	);
	
	/**
	 * generate seo optimazed string
	 *
	 * @param string $text string
	 * @param string $separator string which is inserted between words
	 * @return string $output seo optimazed string
	 *
	 * http://www.bitrepository.com/php-format-text-into-a-seo-friendly-string.html
	 */
	
	function format($text = "", $separator = "-") {
	
		$output = "";
	
		$text = trim($text);
		$output = strtr(strtolower($text), $this->prevodna_tabulka);
		//return $output;
		// Only space, letters, numbers and underscore are allowed
	
		$output = trim(ereg_replace("[^ A-Za-z0-9_]", " ", $output));
	
		/*
		 "t" (ASCII 9 (0x09)), a tab.
		"n" (ASCII 10 (0x0A)), a new line (line feed).
		"r" (ASCII 13 (0x0D)), a carriage return.
		*/
	
		$output = str_replace(" ", $separator, $output);
	
		$output = ereg_replace("[ -]+", "-", $output);
	
		return $output;
	
	}
}

?>