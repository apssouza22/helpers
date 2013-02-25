<?php

namespace helpers;

class Loader {

	private $includePath;
	
	/**
	 * Create class for load classe
	 * @param array $includePath Array of string with path of the class
	 */
	public function __construct($includePath)
	{
		$this->includePath = $includePath;
	}

	public function __invoke($className)
	{
		$this->load($className);
	}

	public function load($className)
	{
		foreach ($this->includePath  as $folder) {
			if (file_exists( $folder . $className . '.php')) {
				include_once $folder . $className . '.php';
			} else {
				$this->loadClassByNamespace($folder.$className);
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

	/**
	 * Installs this class loader on the SPL autoload stack.
	 */
	public function register()
	{
		spl_autoload_register(array($this, 'load'));
	}

}

