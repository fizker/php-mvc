<?php 
namespace mvc;

include(__DIR__.'/Router.php');
include(__DIR__.'/../libs/mustache.php/Mustache.php');

class SimpleView {
	public function render() {
		return '';
	}
}
abstract class Controller {
	private $emptyView;
	protected $params;
	public function __construct($parameters) {
		$this->params = $parameters;
		$this->emptyView = new SimpleView();
	}
	
	public function getHeaderView() {
		return $this->emptyView;
	}
	public function getFooterView() {
		return $this->emptyView;
	}
	public abstract function getView();
	
	protected function loadTemplate($templateName, $data = null) {
		$contents = file_get_contents(DIR_VIEWS."/$templateName.mustache");
		
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