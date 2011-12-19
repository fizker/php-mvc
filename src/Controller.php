<?php 
namespace mvc;

include(__DIR__.'/Router.php');
include(__DIR__.'/../libs/mustache.php/Mustache.php');

abstract class Controller {
	protected $params;
	public function __construct($parameters) {
		$this->params = $parameters;
	}
	
	public abstract function getView();
	
	protected function loadTemplate($templateName, $data = null) {
		$contents = file_get_contents(__DIR__."/../views/$templateName.mustache");
		
		return new \Mustache($contents, $data);
	}
}

function requestController($func) {
	$url = isset($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:'/';
	$router = new Router($url);

	$controller = $func($router);
	
	$view = $controller->getView();
	
	print $view->render();
}

?>