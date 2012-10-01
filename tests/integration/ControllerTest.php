<?php

include_once(__DIR__.'/../../src/Controller.php');

define('DIR_VIEWS', __DIR__.'/../data');

class integration_ControllerTest extends PHPUnit_Framework_TestCase {
	/**
	 * @test
	 */
	public function loadTemplate_validTemplateRequested_templateParsedCorrectly() {
		$c = $this->createController();
		$result = $c->loadTemplate('view', array(
		  'data'=> 'a'
		, 'list'=> array(
		    array(
		      'a'=> 1
		    , 'b'=> 2
		    )
		  , array(
		      'a'=> 3
		    , 'b'=> 4
		    )
		  )
		));
		$this->assertEquals('a[1-2][3-4]', $result);
	}

	public function createController() {
		$tc = new TestableController();
		return $tc;
	}
}

class TestableController extends \mvc\Controller {
	public function __construct() {
		parent::__construct(null);
	}

	public function getView() {
	}
	public function loadTemplate($template, $data = null) {
		return parent::loadTemplate('view', $data);
	}
}
