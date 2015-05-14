<?php
class Lang {
	
	protected $registry;
	private $lang;
	private $terms;
	
	function __construct($registry) {
		$this->registry = $registry;
		$this->changeLang("en_us");
	}
	
	public function changeLang($code) {
		$this->lang = $code;
		$this->getTerms();
	}
	
	private function getTerms() {
		$file = file_get_contents(__SITE_PATH . '/app/lang/'.$this->lang.'.lang.json');
		$this->terms = $this->json_clean_decode($file);
		$this->registry->Log->console($this->terms);
	}
	
	private function json_clean_decode($json, $assoc = false, $depth = 512, $options = 0) {
		// Remove comments
		$json = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t](//).*)#", '', $json);
		
		if (version_compare(phpversion(), '5.4.0', '>=')) {
			$json = json_decode($json, $assoc, $depth, $options);
		} else if (version_compare(phpversion(), '5.3.0', '>=')) {
			$json = json_decode($json, $assoc, $depth);
		} else {
			$json = json_decode($json, $assoc);
		}
		return $json;
	}
	
	public function __get($index) {
		$this->registry->Log->console($this->terms);
		return $this->terms[$index];
	}
}