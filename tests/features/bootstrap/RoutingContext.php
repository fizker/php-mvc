<?php

use Behat\Behat\Context\BehatContext,
	Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;

include_once(__DIR__.'/../../../src/Router.php');

class ControllerContext extends BehatContext {
	private $router;
	private $result;
	
	public function __construct(array $parameters) {
		// Initialize your context here
	}
	
	/***************
	***** GIVEN ****
	****************/
	
	/**
	 * @Given /^a new Router with the URL \'([^\']*)\'$/
	 */
	public function aNewRouterWithTheUrl($url) {
		$this->router = new mvc\Router($url);
	}
	
	/**
	 * @Given /^a matched Router$/
	 */
	public function aMatchedRouter() {
		$this->router = new mvc\Router('/issues/2');
		$this->router->match('/issues/:id');
	}
	
	/***************
	***** WHEN *****
	****************/
	
	/**
	 * @When /^the route \'([^\']*)\' is matched$/
	 */
	public function theRouteIsMatched($route) {
		$this->result = $this->router->match($route);
	}

	/**
	 * @When /^a non-existing parameter is requested from the Router$/
	 */
	public function aNonExistingParameterIsRequestedFromTheRouter() {
		$this->result = $this->router->get('this should not exist');
	}
	
	/***************
	***** THEN *****
	****************/

	/**
	 * @Then /^the Router should report success$/
	 */
	public function theRouterShouldReportSuccess() {
		if(!$this->result) {
			throw new Exception('Route was not parsed correctly');
		}
	}

	/**
	 * @Then /^the Router should report failure$/
	 */
	public function theRouterShouldReportFailure() {
		if($this->result) {
			throw new Exception('Route was parsed correctly');
		}
	}
	
	/**
	 * @Then /^the parameter \'([^\']*)\' should be \'([^\']*)\'$/
	 */
	public function theParameterShouldBe($param, $value) {
		$actualValue = $this->router->get($param);
		if($actualValue != $value) {
			throw new Exception('The value was not extracted correctly. '.
				'It was '.$actualArea.' instead');
		}
	}
	
	/**
	 * @Then /^the Router should return null$/
	 */
	public function theRouterShouldReturnNull() {
		if($this->result !== null) {
			throw new Exception('Null was not returned, '.$this->result.' was');
		}
	}
}
