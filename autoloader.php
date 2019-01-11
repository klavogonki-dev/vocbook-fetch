<?php
spl_autoload_register(function ($className)
{
	$namespace = str_replace("\\", DIRECTORY_SEPARATOR, __NAMESPACE__);
	$className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
	$class = __DIR__ . DIRECTORY_SEPARATOR .
		(empty($namespace) ? "" : $namespace . DIRECTORY_SEPARATOR) .
		"{$className}.php";
	include_once $class;
});
