<?php

namespace helpers;

/**
 * Cuida dos uploads do site
 *
 * @author apssouza
 */
class UserFile
{

	const folderTemp = 'userfiles/temp/';

	public function getNewName($oldName)
	{
		$aName = explode('.', $oldName);
		$ext = end($aName);
		return date('Ymdhis') . rand(0, 1000) . '.' . $ext;
	}

	public function move($temp, $destination)
	{
		if (copy($temp, $destination)) {
			unlink($temp);
			return true;
		}

		return false;
	}

	/**
	 * Recebe o upload da ferramenta jquery upload e move para uma pasta temporaria
	 */
	public function upload()
	{
		$name = $_FILES['files']['name'];
		$temp = $_FILES['files']['tmp_name'];
		$newName = $this->getNewName($name);
		$destination = self::folderTemp . $newName;

		if (move_uploaded_file($temp, $destination)) {
			if ($this->isImage($destination)) {
				$this->createThumb($destination);
			}
			return $this->getResponse($newName);
		} else {
			return $this->getResponse($newName, 'Erro ao salvar o arquivo.');
		}
	}

	public function createThumb($img)
	{
		$image = new ImageEdit($img);
		$image->resizeAndCrop(300, 300);
		$image->getOutputImage(self::folderTemp . 't_' . end(explode('/', $img)));
	}

	public function isImage($file)
	{
		if (preg_match('/\.[jJpPgG][pPiInN][gGgG]$/', $file)) {
			return true;
		}
	}

	public function save($destination, $filename = null)
	{
		if (!$filename) {
			$aName = explode('/', $destination);
			$filename = end($aName);
		}
		$temp = self::folderTemp . $filename;
		$thumb = self::folderTemp . 't_' . $filename;
		$destinationPath = explode('/', $destination);
		unset($destinationPath[count($destinationPath) - 1]);

		if ($this->move($temp, $destination)) {
			if (file_exists($thumb)) {
				$this->move($thumb, implode('/', $destinationPath) . '/' . 't_' . $filename);
			}
			$this->clearFolderTemp();
			return true;
		}

		return false;
	}

	public function clearFolderTemp()
	{
		$files = scandir('./' . self::folderTemp);
		$currentTime = time();
		$past = 86400; //um dia atás
		foreach ($files as $filename) {
			$filename = self::folderTemp . $filename;
			if (file_exists($filename)) {
				if (filemtime($filename) < ($currentTime - $past)) {
					@unlink($filename);
				}
			}
		}
	}

	private function getResponse($name, $erro = '')
	{
		$response = array(
			'name' => $name
		);

		if ($erro) {
			$response['error'] = $erro;
		}

		return json_encode($response);
	}

	/**
	 * Realiza o download de um arquivo para o servidor
	 */
	public static function download($remoteFile, $savePath)
	{
		$aName = explode('/', $remoteFile);
		$filename = end($aName);
		if (!ini_get('allow_url_fopen')) {
			ini_set("allow_url_fopen", true);
		}
		$ctx = stream_context_create(array('http' => array('timeout' => 60)));
		$sImagem = file_get_contents($remoteFile, 0, $ctx);
		$hndSave = fopen($savePath . $filename, "w");
		fwrite($hndSave, $sImagem);
		fclose($hndSave);
		return $filename;
	}

}

?>
