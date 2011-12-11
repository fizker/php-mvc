<?php

use Behat\Behat\Context\BehatContext,
	Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;

class ControllerContext extends BehatContext {
	private $controller;
	private $parsedUrl;
	
	public function __construct(array $parameters) {
		// Initialize your context here
	}
	
	/***************
	***** GIVEN ****
	****************/
	
	/**
	 * @Given /^an implementation of BaseController$/
	 */
	public function anImplementationOfBasecontroller() {
		$this->controller = new TestableController();
	}
	
	/**
	 * @Given /^the route \'([^\']*)\' is present$/
	 */
	public function theRouteIsPresent($route) {
		$this->controller->addRoute($route);
	}
	
	/***************
	***** WHEN *****
	****************/
	
	/**
	 * @When /^the url \'([^\']*)\' is parsed$/
	 */
	public function theUrlIsParsed($url) {
		$this->parsedUrl = $this->controller->parse($url);
	}
	
	
	/***************
	***** THEN *****
	****************/
	
	/**
	 * @Then /^the controller for \'([^\']*)\' should be loaded$/
	 */
	public function theControllerForShouldBeLoaded($area) {
		if(!$this->parsedUrl) {
			throw new Exception('Url was not parsed');
		}
		$actualArea = $this->parsedUrl->area;
		if($actualArea != $area) {
			throw new Exception('Area was not found in the route. Found '.
				$actualArea.' instead');
		}
	}
	
	/**
	 * @Then /^the parameter \'([^\']*)\' should be \'([^\']*)\'$/
	 */
	public function theParameterShouldBe($param, $value) {
		$actualValue = $this->parsedUrl->get($param);
		if($actualValue != $value) {
			throw new Exception('The value was not extracted correctly. '.
				'It was '.$actualArea.' instead');
		}
	}
}

include_once(__DIR__.'/../../../src/BaseController.php');
class TestableController extends mvc\BaseController {
	public function __construct() {
		parent::__construct();
	}
	public function addRoute($route) {
		parent::addRoute($route);
	}
	//public function
}