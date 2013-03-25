<?php

namespace helpers;

/**
 * Manipula os errors fatais do projeto, redirecionando para uma página de aviso 
 * e notificando os responsaveis
 * Explicação de como usar, no final do arquivo
 *
 * @author Alexsandro Souza
 */
class HandleError
{

	public $urlHandle = 'tratarErro.php';
	
	public function __construct()
	{
		if(!file_exists($this->urlHandle)){
			file_put_contents($this->urlHandle, '<?php $hErro = new HandleError(); $hErro->printInfoUser();?>');
		}
	}

	public function listener()
	{
		//habilitando os erros a ser tratados
		ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_WARNING);
		ini_set('display_errors', 'On');
		ini_set('display_startup_errors', 'On');

		ini_set('html_errors', false);
		ini_set('error_prepend_string', '<html><head><META http-equiv="refresh" content="0;URL=' . $this->urlHandle . '?pagina=' . $this->getCurrentUrl() . '&msgErro=');
		ini_set('error_append_string', '"></head></html>');
	}

	public function notify($dest, $cc, $rem, $subject, $msg = '')
	{
		if (isset($_REQUEST['msgErro'])) {
			$this->sendErroAdmin($dest, $rem, $subject, $cc, $msg);
			$this->printInfoUser();
		}
	}

	/**
	 * Retorna a url que estava sendo acessada no momento do erro
	 */
	private function getCurrentUrl()
	{
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on")
			$pageURL .= "s";
		$pageURL .= "://";

		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}

		return $pageURL;
	}

	public function sendErroAdmin($dest, $rem, $assunto, $cc = false, $msg = '')
	{
		$html = $msg ? $msg : $_GET['msgErro'];
		$html.= '<br /><br />página: ' . $_GET['pagina'];

		if (PATH_SEPARATOR == ";")
			$quebra_linha = "\r\n"; //Se for Windows
		else
			$quebra_linha = "\n"; //Se "não for Windows"

		$headers = "MIME-Version: 1.1" . $quebra_linha;
		$headers .= "Content-type: text/html; charset=iso-8859-1" . $quebra_linha;
		$headers .= "From: $rem" . $quebra_linha;
		$headers .= "To: $dest" . $quebra_linha;
		if ($cc)
			$headers .= "Cc: $cc" . $quebra_linha;

		if (!mail($dest, $assunto, $html, $headers, '-r' . $dest)) { // Se for Postfix
			$headers .= 'Return-Path: ' . $dest . $quebra_linha; // Se "não for Postfix"
			if (!mail($dest, $assunto, $html, $headers)) {
				echo 'erro ao enviar';
			}
		}
	}

	/**
	 * Prepara o redirecionamento para a Home do site, caso já não esteja lá
	 */
	private function getMetaRedirect()
	{
		$uri = end(explode('/', $_GET['pagina']));
		$meta_refresh = '';
		if ($uri != '') {
			$destino = 'http://' . $_SERVER['HTTP_HOST'];
			$timer = 5; //--> tempo (em segundos) para exibir a mensagem na tela
			$meta_refresh = '<META http-equiv="refresh" content="' . $timer . ';URL=' . $destino . '" />';
		}
		//TODO: rever esse metódo
		return '<META http-equiv="refresh" content="' . $timer . ';URL=index.php" />';
	}

	/**
	 * Printa o aviso ao visitante que houve erro no site
	 */
	public function printInfoUser()
	{
		$html = "
			<html>
				<head>
					{$this->getMetaRedirect()}
				</head>
				<body>
					<h1>Erro no site</h1>
					<p>Desculpe-nos pelo inconveniente. Já estamos trabalhando para resolvê-lo.</p>
				</body>
			</html>
			";
		echo $html;
	}

}


/**
 * Em todas as páginas a ser monitoradas adicione o seguinte:
 * $hErro = new HandleError();
 * $hErro->listener();
 * 
 * Crie uma página 'tratarErro.php' e adicine o seguinte código:
 * $hErro = new HandleError();
 * $hErro->notify('email de destino','email de copia', 'email remetente','assunto', 'mensagem acompanhando o ero' );
 */