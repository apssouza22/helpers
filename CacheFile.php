<?php

namespace helpers;

class CacheFile
{

	protected $folder;
	protected $timeout;
	protected $ext;

	public function __construct($timeout = 60, $folder = 'cache', $ext = 'txt')
	{
		$this->timeout = $timeout;
		$this->folder = $folder;
		$this->ext = $ext;
	}

	public function getPathFileName($key)
	{
		return sprintf('%s/%s.%s', $this->folder, $key, $this->ext);
	}

	public function isCache($key)
	{
		$filename = $this->getPathFileName($key);
		if (file_exists($filename)) {
			$filemtime = filemtime($filename);
			if (time() < ($filemtime + (60 * $this->timeout))) {
				return true;
			}
		}
		return false;
	}

	public function write($key, $value)
	{
		$filename = $this->getPathFileName($key);
		if (!file_put_contents($filename, $value)) {
			throw new Exception('Erro ao salvar o arquivo', $key);
		}
		return true;
	}

	public function read($key)
	{
		$filename = $this->getPathFileName($key);
		if (file_exists($filename)) {
			if (!$result = file_get_contents($filename)) {
				throw new Exception('Erro ao ler o arquivo', $key);
			}
			return $result;
		}
	}

	public function clearAllCache()
	{
		$files = scandir($this->folder);
		foreach ($files as $filename) {
			if (file_exists($this->folder . '/' . $filename)) {
				@unlink($this->folder . '/' . $filename);
			}
		}
	}

}