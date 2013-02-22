<?php

namespace helpers;

class Loader
{

	public function __invoke($className)
	{
		$this->load($className);
	}

	public function load($className)
	{
		$vetor_pastas[] = CONTROLLER;
		$vetor_pastas[] = VIEWS;
		$vetor_pastas[] = MODEL;
		$vetor_pastas[] = HELPER;
		$vetor_pastas[] = LIB . "dataBase/";
		$vetor_pastas[] = LIB . 'Respect/';
		$vetor_pastas[] = LIB . 'facebook/';
		$vetor_pastas[] = LIB . 'cache/';
		$vetor_pastas[] = LIB . 'image/';
		foreach ($vetor_pastas as $pasta) {

			if (file_exists(PATH_APP . $pasta . $className . '.php')) {
				include_once( PATH_APP . $pasta . $className . '.php');
			} else {
				$this->loadClassByNamespace($className);
			}
		}
	}

	public function loadClassByNamespace($className)
	{
		$fileParts = explode('\\', ltrim($className, '\\'));

		if (false !== strpos(end($fileParts), '_'))
			array_splice($fileParts, -1, 1, explode('_', current($fileParts)));

		$fileName = implode(DIRECTORY_SEPARATOR, $fileParts) . '.php';
		if (file_exists($fileName)) {
			require_once $fileName;
		}
	}

}

if (!defined('RESPECT_DO_NOT_RETURN_AUTOLOADER'))
	return new Loader;
