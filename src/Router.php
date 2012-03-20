<?php
namespace mvc;

class Router {
	private $url;
	private $unscopedUrl;
	private $route;
	private $parameters;
	private $matchUrl;
	private $parsedParameters;
	
	public function __construct($url) {
		$this->unscopedUrl =
			$this->url = $url;
	}
	
	public function getUrl() {
		return $this->url;
	}
	
	public function isStatic($patterns) {
		if(!$patterns) {
			return false;
		}
		if(!is_array($patterns)) {
			$patterns = array($patterns);
		}
		$result = false;
		foreach($patterns as $pattern) {
			$result = !!preg_match($pattern, $this->url);
			if($result) {
				return $result;
			}
		}
		return $result;
	}

	public function get($name) {
		return isset($this->parsedParameters[$name])
			? $this->parsedParameters[$name]
			: null;
	}
	
	public function set($key, $value) {
		$this->parsedParameters[$key] = $value;
		return $this;
	}

	public function match($route) {
		return $this->matches($route);
	}
	public function matches($route) {
		$this->route = $route;
		return
			$this
			->createMatcher()
			->matchRoute();
	}
	
	private function createMatcher() {
		$parameters = array();

		$exploded = explode('/', trim($this->route, '/'));
		$matchUrl = array();
		foreach($exploded as $expl) {
			if($expl && $expl[0] === ':') {
				$parameters[] = substr($expl, 1);
				$expl = '([^/]+)';
			} else {
				$parameters[] = $expl;
				$expl = '('.$expl.')';
			}
			$matchUrl[] = $expl;
		}
		$this->matchUrl = '{^'.implode('/', $matchUrl).'$}';

		$this->parameters = $parameters;

		return $this;
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

	public function scopeUrl($part) {
		if($this->url === $part) {
			$this->url = '/';
			return $this;
		}
		if(strpos($this->url, $part) !== 0) {
			throw new \InvalidArgumentException('The part must match the start of the url');
		}

		$url = str_replace($part, '', $this->url);
		if($url[0] !== '/') {
			throw new \InvalidArgumentException('The scoping must be complete parts');
		}
		$this->url = $url;

		return $this;
	}

	public function unscopeUrl() {
		$this->url = $this->unscopedUrl;
		return $this;
	}
}
?>