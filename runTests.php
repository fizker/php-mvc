#!/usr/bin/env php
<?php

$path = get_include_path();
$dir = opendir('libs');
while($file = readdir($dir)) {
	if($file[0] == '.') {
		continue;
	}
	$path .= ':libs/' . $file;
}
set_include_path($path);

$argv[] = '--colors';
$argv[] = 'tests';
$_SERVER['argv'] = $argv;

include('libs/phpunit/phpunit.php');// --colors tests
