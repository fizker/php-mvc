<?php

include_once(__DIR__.'/../../src/Controller.php');

use \mvc\SimpleView;

class unit_SimpleViewTest extends PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function render_Always_ReturnsValueGivenInConstructor() {
		$view = new SimpleView('a');
		
		$result = $view->render();
		
		$this->assertEquals('a', $result);
	}

	/**
	 * @test
	 */
	public function toString_Always_ReturnsValueGivenInConstructor() {
		$view = new SimpleView('a');

		$result = ''.$view;

		$this->assertEquals('a', $result);
	}
}
?>