<?php

namespace helpers;

/**
 * Helper de strings
 *
 * @author Alexsandro
 */
class String
{

	const REGEX_URL_LIMPA = '/[^A-Za-z0-9_|\\-]/'; // para retornar uma URL limpa

	/**
	 * Retorna a extensão de um arquivo 
	 * @param string $nome_arquivo
	 */

	public static function getExtensao($nome_arquivo)
	{
		$vetor = explode('.', $nome_arquivo);
		return strtolower($vetor[sizeof($vetor) - 1]);
	}

	/**
	 * corta uma string se ela tem mais caracteres que $tamanho 
	 * @param string $string string a ser cortada
	 * @param int $tamanho limite de caracteres da string
	 * @param string $string_add é a string que pode ser adicionada a STRING, caso ela seja maior que TAMANHO (ex.: 3 pontinhos)
	 * @param bool $cortar_palavra define se a palavra será cortada no meio (default: false)
	 */
	public static function cutString($string, $tamanho, $string_add = "", $cortar_palavra = false)
	{
		if (strlen($string) > $tamanho) {
			$pos_ultimo_espaco = $cortar_palavra ? $tamanho : self::strposReverse($string, " ", $tamanho);
			$string = $pos_ultimo_espaco === false ? substr($string, 0, $tamanho) . $string_add : substr($string, 0, $pos_ultimo_espaco) . $string_add;
		}
		$string = trim($string);
		return $string;
	}

	/**
	 * Acha a posição da última ocorrência do caractere procurado
	 * Enter description here ...
	 * @param string $str texto original
	 * @param string $search texto procurado
	 * @param $pos última posição (tamanho da string)
	 */
	public static function strposReverse($str, $search, $pos)
	{
		$str = strrev($str);
		$search = strrev($search);
		$pos = (strlen($str) - 1) - $pos;

		$posRev = strpos($str, $search, $pos);
		return (strlen($str) - 1) - $posRev - (strlen($search) - 1);
	}

	/**
	 * Remove os acentos de uma string
	 * @param string $string 
	 */
	public static function removeAccent($string)
	{
		return strtr($string, "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏĞÑÒÓÔÕÖØÙÚÛÜİßàáâãäåæçèéêëìíîïğñòóôõöøùúûüıÿ", "AAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
	}

	/**
	 * Cria uma string que possa ser usada como URL
	 * @param string $string
	 */
	public static function getUrlFriendly($string)
	{
		return preg_replace(self::REGEX_URL_LIMPA, '', self::removeAccent(strtolower(str_replace(' ', '_', trim($string)))));
	}

	/**
	 * Encripta a senha para um padrão numérico
	 * @param string $senha Senha desejada
	 * @return string $s Senha encriptada  
	 */
	public static function pwenc($senha)
	{
		if (trim($senha) == "" || !preg_match('/^([a-zA-Z0-9]+)$/', $senha)) {
			return false;
		} else {
			for ($i = 0; $i < strlen($senha); $i++) {
				$v[] = 48;
				$v[] = 57;
				$v[] = 65;
				$v[] = 90;
				$v[] = 97;
				$v[] = 122;
				$la = ord($senha[$i]);
				$g = $la == ($la % ($v[1] + 1)) ? 1 : 0;
				$g = $g ? $g : floor($la / ($v[3] + 1)) + 2;

				$f = floor($g - 1) ? $v[$g + $g % 2] : $v[floor($g - 1)];
				$o = $la - $f + 1;

				$o = $o < 10 ? "0" . $o : $o;
				$s.= $o . $g;
			}
			return $s;
		}
	}

	/**
	 * Decripta a senha no padrão numérico usado em self::pwenc()
	 * @param int $numero Senha encriptada numericamente
	 * @return string $la Senha decriptada  
	 */
	public static function pwdec($numero)
	{
		for ($i = 0; $i < strlen($numero) / 3; $i++) {
			$o = intval(substr($numero, $i * 3, 2));
			$g = substr($numero, $i * 3 + 2, 1);
			$v[] = 48;
			$v[] = 57;
			$v[] = 65;
			$v[] = 90;
			$v[] = 97;
			$v[] = 122;
			$f = floor($g - 1) ? $v[$g + $g % 2] : $v[floor($g - 1)];
			$n = $f + $o - 1;
			$la.=chr($n);
		}
		return $la;
	}

}

?>
