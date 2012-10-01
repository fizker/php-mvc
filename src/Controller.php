<?php 
namespace mvc;

require_once(__DIR__.'/SimpleView.php');
require_once(__DIR__.'/ComboView.php');
require_once(__DIR__.'/Router.php');
include(__DIR__.'/Request.php');
include(__DIR__.'/Response.php');
require_once(__DIR__.'/../libs/mustache.php/src/Mustache/Autoloader.php');
\Mustache_Autoloader::register();

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
		
		$m = new \Mustache_Engine;
		return $m->render($contents, $data);
	}
	
	protected function getPost() {
		return $_POST;
	}
	protected function getPut() {
		$data = file_get_contents('php://input');
		parse_str($data, $put);
		return $put;
	}
	protected function getGet() {
		return $_GET;
	}
	
	protected function is($method) {
		return $_SERVER['REQUEST_METHOD'] === strtoupper($method);
	}
	protected function isGet() {
		return $this->is('get');
	}
	protected function isPost() {
		return $this->is('post');
	}
	protected function isPut() {
		return $this->is('put');
	}
	protected function isDelete() {
		return $this->is('delete');
	}
}

function requestController($func) {
	$url = isset($_GET['__url'])?$_GET['__url']:'/';
	if(!isset($_GET['__decoded'])) {
		$url = urldecode($url);
	}
	$router = new Router($url);

	$controller = $func($router);
	
	$controller->render();
}

?>