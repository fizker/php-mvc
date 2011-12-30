<?php

include_once(__DIR__.'/../../src/StaticController.php');

define('DIR_STATIC', __DIR__.'/../data');
use \mvc\StaticController;
use \mvc\Router;

class integration_StaticControllerTest extends PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function render_FileIsJS_ContentsAreReturned() {
		$router = new Router('static-file.js');
		$controller = new StaticController(DIR_STATIC, $router);
		
		$this->expectOutputString($this->readFile('static-file.js'));
		$result = $controller->render();
	}
	
	/**
	 * @test
	 */
	public function render_FileIsPHP_ContentsAreReturnedNotParsed() {
		$router = new Router('static-file.php');
		$controller = new StaticController(DIR_STATIC, $router);
		
		$this->expectOutputString($this->readFile('static-file.php'));
		$result = $controller->render();
	}
	
	private function readFile($file) {
		return file_get_contents(DIR_STATIC.'/'.$file);
	}
}
?>