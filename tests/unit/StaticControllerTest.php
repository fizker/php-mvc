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

	/**
	 * @test
	 * @dataProvider provider_getImgMatcher_RouteIsImage_Matches
	 */
	public function getImgMatcher_RouteIsImage_Matches($url = 'png') {
		$matcher = StaticController::getImgMatcher();
		$router = new Router($url);
		
		$result = $router->isStatic($matcher);
		
		$this->assertTrue($result);
	}
	public function provider_getImgMatcher_RouteIsImage_Matches() {
		return array(
			array('a.png')
			, array('a.jpg')
			, array('a.jpeg')
			, array('a.gif')
			, array('a.svg')
			, array('a/b.png')
			, array('a/b.jpg')
			, array('a/b.jpeg')
			, array('a/b.gif')
			, array('a/b.svg')
			, array('a/b.png?c')
			, array('a/b.jpg?c')
			, array('a/b.jpeg?c')
			, array('a/b.gif?c')
			, array('a/b.svg?c')
		);
	}

	/**
	 * @test
	 * @dataProvider provider_getMimetype_FileIsLoaded_CorrectMimetype
	 */
	public function getMimetype_FileIsLoaded_CorrectMimetype($file, $expected) {
		$router = new Router($file);
		$someStaticDir = 'b';
		$controller = new StaticController($someStaticDir, $router);
		
		$result = $controller->getMimetype();
		
		$this->assertEquals($expected, $result);
	}
	public function provider_getMimetype_FileIsLoaded_CorrectMimetype() {
		return array(
			array('a.js', 'text/javascript')
			, array('a.css', 'text/css')
			, array('a.jpg', 'image/jpeg')
			, array('a.jpeg', 'image/jpeg')
			, array('a.png', 'image/png')
			, array('a.gif', 'image/gif')
			, array('a.svg', 'image/svg+xml')
		);
	}

	/**
	 * @test
	 */
	public function setMimetype_MimetypeIsOverridden_CorrectMimetypeIsReturned() {
		$router = new Router('a.js');
		$someStaticDir = 'b';
		$controller = new StaticController($someStaticDir, $router);
		
		$controller->setMimetype('c');

		$result = $controller->getMimetype();
		$this->assertEquals('c', $result);
	}

	private function getJSMatcherResult($url) {
		$matcher = StaticController::getJSMatcher();
		$router = new Router($url);
		
		$result = $router->isStatic($matcher);
		
		return $result;
	}
}
?>