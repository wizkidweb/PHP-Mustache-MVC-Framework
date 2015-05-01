<?php
class portfolioController extends baseController {
	
	public function index() {		
		$this->registry->Template->css = array(
			array("url" => "portfolio.css")
		);
		$this->registry->Template->js = array(
			array("url" => "js/lazysizes.min.js", "async" => true),
			array("url" => "js/portfolio.js")
		);
		
		$category = ($this->action == "index") ? "website" : $this->action;
		$this->registry->Template->category = $category;
		
		switch ($category) {
			case "website":
				$this->registry->Template->isWebsite = true;
			break;
			case "film":
				$this->registry->Template->isFilm = true;
			break;
			case "project":
				$this->registry->Template->isProject = true;
			break;
		}
		
		$portfolio_result = $this->registry->DBase->Query("SELECT * FROM portfolio WHERE type = ? ORDER BY date DESC", "s", $category);
		if ($portfolio_result) {
			$this->registry->Template->portfolio_items = $portfolio_result;
		}
		
		// Load Template
		$this->registry->Template->show('portfolio');
	}
	
	function onAjax() {
		
	}
	
}