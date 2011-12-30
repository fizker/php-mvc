<?php
namespace mvc;

require_once(__DIR__.'/Controller.php');

class StaticController extends Controller {
	private $staticDir;
	public function __construct($staticDir, $params) {
		parent::__construct($params);
		
		$this->staticDir = $staticDir;
	}
	
	public function getView() {
		$contents = file_get_contents($this->staticDir.'/'.$this->params->getUrl());
		return new SimpleView($contents);
	}
	
	public static function getJSMatcher() {
		return '/\.js(\?.*)?$/i';
	}
	
	public static function getCSSMatcher() {
		return '/\.css(\?.*)?$/i';
	}
}
?>