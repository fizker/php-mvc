<?php
namespace mvc;

class BaseController {
	private $routes;
	
	protected function __construct() {
		$this->routes = array();
	}
	
	protected function addRoute($route) {
		$this->routes[] = new Route($route);
	}
	
	public function parse($url) {
		foreach($this->routes as $route) {
			$parsed = $route->parse($url);
			if($parsed) {
				return $parsed;
			}
		}
		return null;
	}
}

class Route {
	private $url;
	private $matchUrl;
	
	private $parameters;
	public function __construct($url) {
		$this->url = $url;

		$parameters = array();

		$exploded = explode('/', trim($url, '/'));
		$matchUrl = array();
		foreach($exploded as $expl) {
			if($expl[0] === ':') {
				$parameters[] = substr($expl, 1);
				$expl = '([^\/]+)';
			} else {
				$parameters[] = $expl;
				$expl = '('.$expl.')';
			}
			$matchUrl[] = $expl;
		}
		$this->matchUrl = '/^'.implode('\/', $matchUrl).'$/';
		
		$this->parameters = $parameters;
	}
	
	public function parse($url) {
		$url = trim($url, '/');
		$matches = array();
		$params = $this->parameters;
		if(!preg_match($this->matchUrl, $url, $matches)) {
			return false;
		}
		
		$results = new RouteParameters($params[0]);
		for($i = sizeof($matches)-1; $i>0; $i--) {
			$value = $matches[$i];
			$results->set($params[$i-1], $value);
		}
		
		return $results;
	}
}

class RouteParameters {
	private $parameters;
	public $area;
	
	public function __construct($area) {
		$this->area = $area;
		$this->parameters = array();
	}
	
	public function set($key, $value) {
		$this->parameters[$key] = $value;
	}
	
	public function get($name) {
		return $this->parameters[$name];
	}
}
?>
