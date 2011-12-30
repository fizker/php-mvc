<?php

include_once(__DIR__.'/../../src/StaticController.php');

use \mvc\StaticController;
use \mvc\Router;

class StaticControllerTest extends PHPUnit_Framework_TestCase {
	/**
	 * @test
	 * @dataProvider provider_getCSSMatcher_MatchingCSSFile_Matches
	 */
	public function getCSSMatcher_MatchingCSSFile_Matches($url) {
		$matcher = StaticController::getCSSMatcher();
		$router = new Router($url);
		
		$result = $router->isStatic($matcher);
		
		$this->assertTrue($result);
	}
	public function provider_getCSSMatcher_MatchingCSSFile_Matches() {
		return array(
			array('a/b.css'),
			array('a/b.css?c'),
			array('a/B.CSS')
		);
	}

	/**
	 * @test
	 * @dataProvider provider_getCSSMatcher_RouteIsNotCSS_DoesNotMatch
	 */
	public function getCSSMatcher_RouteIsNotCSS_DoesNotMatch($url) {
		$matcher = StaticController::getCSSMatcher();
		$router = new Router($url);
		
		$result = $router->isStatic($matcher);
		
		$this->assertFalse($result);
	}
	public function provider_getCSSMatcher_RouteIsNotCSS_DoesNotMatch() {
		return array(
			array('a/b.js'),
			array('a/b'),
			array('a/b.css/c'),
			array('a/b.css/c?d'),
		);
	}

	/**
	 * @test
	 * @dataProvider provider_getJSMatcher_MatchingJSFile_Matches
	 */
	public function getJSMatcher_MatchingJSFile_Matches($url) {
		$result = $this->getJSMatcherResult($url);
		
		$this->assertTrue($result);
	}
	public function provider_getJSMatcher_MatchingJSFile_Matches() {
		return array(
			array('a/b.js'),
			array('a/b.js?c'),
			array('a.JS')
		);
	}
	
	/**
	 * @test
	 * @dataProvider provider_getJSMatcher_RouteIsNotJS_DoesNotMatch
	 */
	public function getJSMatcher_RouteIsNotJS_DoesNotMatch($url) {
		$result = $this->getJSMatcherResult($url);
		
		$this->assertFalse($result);
	}
	public function provider_getJSMatcher_RouteIsNotJS_DoesNotMatch() {
		return array(
			array('a/b'),
			array('a/b.js/c'),
			array('a/b.js/c?d')
		);
	}
	
	private function getJSMatcherResult($url) {
		$matcher = StaticController::getJSMatcher();
		$router = new Router($url);
		
		$result = $router->isStatic($matcher);
		
		return $result;
	}
}
?>