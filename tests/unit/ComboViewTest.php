<?php

include_once(__DIR__.'/../../src/ComboView.php');
include_once(__DIR__.'/../../src/SimpleView.php');

use \mvc\ComboView;
use \mvc\SimpleView;

class unit_ComboViewTest extends PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function render_ViewInConstructor_RenderRendersSubview() {
		$sub = new SimpleView('any content');
		$view = new ComboView($sub);
		
		$result = $view->render();
		
		$this->assertEquals('any content', $result);
	}

	/**
	 * @test
	 */
	public function render_MultipleViewsInCtor_AllAreRendered() {
		$view = new ComboView(
			new SimpleView('a'),
			new SimpleView('b'),
			new SimpleView('c')
		);
		
		$result = $view->render();
		
		$this->assertEquals('abc', $result);
	}

	/**
	 * @test
	 */
	public function addView_ViewIsAdded_ViewIsUsedInRender() {
		$view = new ComboView(
			new SimpleView('a'),
			new SimpleView('b')
		);
		
		$view->addView(
			new SimpleView('c')
		);
		
		$result = $view->render();
		
		$this->assertEquals('abc', $result);
	}
}
?>