<?php

namespace helpers;

/**
 * Classe helper de stream
 *
 * @author Alexsandro Souza
 */
class Stream
{

	/**
	 * Realiza o download de um arquivo para o servidor
	 * @param string $remoteFile Endereço http do arquivo a ser baixado
	 * @param string $savePath Caminho no servidor local onde será salvo o arquivo
	 * @return string Nome do arquivo salvo na pasta informada
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

	/**
	 * Retorna uma url curta, utilizando um serviço do migreme
	 * @return String string com a url curta
	 */
	public static function getShortUrl($url)
	{
		$url = urlencode(trim($url));
		return self::getContentUrlByCurl('http://migre.me/api.txt?url=' . $url);
	}

	/**
	 * Retorna o conteúdo de uma url usando curl
	 * @return String string com o contéudo da página
	 */
	public static function getContentUrlByCurl($url)
	{
		$url = trim($url);
		$cURL = curl_init($url);
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($cURL);
		curl_close($cURL);
		if (!$result) {
			$cURL = curl_init($url);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($cURL);
		}
		return $result;
	}

	/**
	 * Analisa se a url informada existe  
	 * */
	public function fileExist($file)
	{
		$cURL = curl_init($file);
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, true);
		$result = curl_exec($cURL);
		$response = curl_getinfo($cURL, CURLINFO_HTTP_CODE);
		curl_close($cURL);

		if ($response != 200) {
			return false;
		}
		return true;
	}

}

?>
