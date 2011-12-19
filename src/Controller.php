<?php 
namespace mvc;

include(__DIR__.'/Router.php');
include(__DIR__.'/Request.php');
include(__DIR__.'/Response.php');
include(__DIR__.'/../libs/mustache.php/Mustache.php');

class SimpleView {
	public function render() {
		return '';
	}
}
abstract class Controller {
	private $emptyView;
	protected $params;
	protected $request, $response;
	public function __construct($parameters) {
		$this->params = $parameters;
		$this->request = new Request();
		$this->response = new Response();
		$this->emptyView = new SimpleView();
	}
	
	protected function getEmptyView() {
		return $this->emptyView;
	}
	public function getHeaderView() {
		return $this->getEmptyView();
	}
	public function getFooterView() {
		return $this->getEmptyView();
	}
	public abstract function getView();
	public function render() {
		$this->response->write($this->getHeaderView()->render());
		$this->response->write($this->getView()->render());
		$this->response->write($this->getFooterView()->render());
	}
	
	protected function loadTemplate($templateName, $data = null) {
		$contents = file_get_contents(DIR_VIEWS."/$templateName.mustache");
		
		return new \Mustache($contents, $data);
	}
}

function requestController($func) {
	$url = isset($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:'/';
	$router = new Router($url);

	$controller = $func($router);
	
	$controller->render();
}

?>