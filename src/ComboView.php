<?php
namespace mvc;

class ComboView {
	private $views;
	public function __construct() {
		$this->views = func_get_args();
	}
	
	public function addView($view) {
		$this->views[] = $view;
	}
	
	public function render() {
		$value = '';
		foreach($this->views as $view) {
			$value .= $view->render();
		}
		return $value;
	}
}
?>