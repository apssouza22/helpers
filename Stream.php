<?php

/**
 * Classe helper de stream
 *
 * @author Alexsandro Souza
 */
class Stream {

	/**
	 * Realiza o download de um arquivo para o servidor
	 * @param string $remoteFile Endereço do arquivo remoto
	 * @param string $savePath endereço onde será salvo o arquivo baixado
	 * @return string Nome do arquivo baixado e salvo no endereço informado
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
	 * @return String string com o conteúdo da página
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
	 * @param string $file endereço remoto do arquivo
	 * @return boolean resultado da consulta
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

	/**
	 * Realiza um post para a url usando Curl
	 * @param string $apiCall  url para onde será enviado os dados 
	 * @param array $param Array contendo os paramentros a serem postas
	 * @param string $ssl Caminho do certificado ssl 
	 * @return string o retorno do post
	 */
	public static function post($apiCall, $params, $ssl = false)
	{
		if (isset($params) && is_array($params)) {
			$paramString = '&' . http_build_query($params);
		} else {
			$paramString = null;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $apiCall);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		if ($ssl) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_CAINFO, $ssl);
		}
		curl_setopt($ch, CURLOPT_POST, count($params));
		curl_setopt($ch, CURLOPT_POSTFIELDS, ltrim($paramString, '&'));
		$result = curl_exec($ch);
		curl_close($ch);

		if (!$result) {
			throw new Exception(curl_error($ch), curl_errno($ch));
		}
		return $result;
	}

}

?>
