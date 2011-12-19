<?php
namespace mvc;

class Response {
	public function set($header, $value) {}
	public function end() {}
	public function write($text) {
		print $text;
	}
}