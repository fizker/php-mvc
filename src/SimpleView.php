<?php
namespace mvc;

class SimpleView {
	private $value;
	public function __construct($value = '') {
		$this->value = $value;
	}
	public function render() {
		return $this->value;
	}
	public function __toString() {
		return $this->value;
	}
}
?>