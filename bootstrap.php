<?php

spl_autoload_register(function($class) use ($basedir) {
	$parts = explode('\\', $class);

	$path = __DIR__ . '/lib/' . implode('/', $parts) . '.php';

	if (!file_exists($path))
		return false;

	require $path;
});