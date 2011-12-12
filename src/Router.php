<?php
namespace mvc;

class Router {
	private $url;
	private $route;
	private $parameters;
	private $matchUrl;
	private $parsedParameters;
	
	public function __construct($url) {
		$this->url = $url;
	}

	public function get($name) {
		return isset($this->parsedParameters[$name])
			? $this->parsedParameters[$name]
			: null;
	}

	public function match($route) {
		$this->route = $route;
		$this->createMatcher();
		return $this->matchRoute();
	}
	
	private function createMatcher() {
		$parameters = array();

		$exploded = explode('/', trim($this->route, '/'));
		$matchUrl = array();
		foreach($exploded as $expl) {
			if($expl && $expl[0] === ':') {
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
	
	private function matchRoute() {
		$url = trim($this->url, '/');
		$matches = array();
		$params = $this->parameters;
		if(!preg_match($this->matchUrl, $url, $matches)) {
			return false;
		}
		
		$result = array();
		for($i = sizeof($matches)-1; $i>0; $i--) {
			$value = $matches[$i];
			$result[$params[$i-1]] = $value;
		}
		$this->parsedParameters = $result;
		
		return true;
	}
}
?>