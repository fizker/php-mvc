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

	/**
	 * @test
	 */
	public function scopeUrl_FirstPartOfUrlGiven_PartIsRemoved() {
		$router = new Router('/a/b/c/d');

		$router->scopeUrl('/a/b');

		$this->assertEquals('/c/d', $router->getUrl());
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function scopeUrl_GivenPartDoesNotMatch_ErrorIsRaised() {
		$router = new Router('/a/b/c/d');

		$router->scopeUrl('/b/c');
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function scopeUrl_GivenPartIsNotCompletePart_ErrorIsRaised() {
		$router = new Router('/aa/bb/cc/dd');

		$router->scopeUrl('/aa/b');
	}

	/**
	 * @test
	 */
	public function scopeUrl_EntireUrlIsScoped_UrlIsRoot() {
		$router = new Router('/a');

		$router->scopeUrl('/a');

		$this->assertEquals('/', $router->getUrl());
	}

	/**
	 * @test
	 */
	public function unscopeUrl_UrlWasScoped_UrlIsRestored() {
		$router = new Router('/a/b/c/d');

		$router->scopeUrl('/a/b');
		$router->unscopeUrl();

		$this->assertEquals('/a/b/c/d', $router->getUrl());
	}

	/**
	 * @test
	 */
	public function scopeUrl_UrlIsScopedBeforeMatching_ScopedUrlIsUsed() {
		$router = new Router('/a/b/c/d');

		$router->scopeUrl('/a/b');

		$this->assertTrue($router->matches('/c/d'));
	}

	/**
	 * @test
	 */
	public function scopeUrl_UrlIsScopedMultipleTimes_ScopesAreAdditive() {
		$router = new Router('/a/b/c/d');

		$router->scopeUrl('/a');
		$router->scopeUrl('/b');

		$this->assertEquals('/c/d', $router->getUrl());
	}

	/**
	 * @test
	 */
	public function unscopeUrl_UrlWasScopedMultipleTImes_UrlIsOriginal() {
		$router = new Router('/a/b/c');
		
		$router->scopeUrl('/a');
		$router->scopeUrl('/b');
		$router->unscopeUrl();

		$this->assertEquals('/a/b/c', $router->getUrl());
	}

	/**
	 * @test
	 */
	public function scopeUrl_Always_ReturnsTheRouter() {
		$router = new Router('/a/b');

		$result = $router->scopeUrl('/a');

		$this->assertSame($router, $result);
	}

	/**
	 * @test
	 */
	public function unscopeUrl_Always_ReturnsTheRouter() {
		$router = new Router('/a/b');

		$result = $router->unscopeUrl();

		$this->assertSame($router, $result);
	}
}
?>