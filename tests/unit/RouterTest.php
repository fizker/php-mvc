<?php

include_once(__DIR__.'/../../src/Router.php');

use \mvc\Router;

class RouterTest extends PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function isStatic_RouteIsStatic_ReturnsTrue() {
		$router = new Router('a.b');
		
		$result = $router->isStatic('/\.b$/');
		
		$this->assertTrue($result);
	}

	/**
	 * @test
	 */
	public function isStatic_RouteIsNotStatic_ReturnsFalse() {
		$router = new Router('/a/b');
		
		$result = $router->isStatic('/\.b$/');
		
		$this->assertFalse($result);
	}
	
	/**
	 * @test
	 * @dataProvider provider_isStatic_MatcherIsArray_MatchesAgainstAll
	 */
	public function isStatic_MatcherIsArray_MatchesAgainstAll($patterns) {
		$router = new Router('a.js');
		
		$result = $router->isStatic($patterns);
		
		$this->assertTrue($result);
	}
	/**
	 * Should return the following parameters: $patterns (array)
	 */
	public function provider_isStatic_MatcherIsArray_MatchesAgainstAll() {
		return array(
			array(array(
				'/css/',
				'/js/'
			)),
			array(array(
				'/js/',
				'/css/'
			))
		);
	}
	
	/**
	 * @test
	 */
	public function isStatic_MatcherIsEmptyArray_ReturnsFalse() {
		$router = new Router('a.js');
		
		$result = $router->isStatic(array());
		
		$this->assertFalse($result);
	}
	
	/**
	 * @test
	 */
	public function isStatic_MatcherIsEmptyString_ReturnsFalse() {
		$router = new Router('a.js');
		
		$result = $router->isStatic('');
		
		$this->assertFalse($result);
	}
}
?>