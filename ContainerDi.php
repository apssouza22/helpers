<?php

namespace Helpers;

class ContainerDi
{

	protected static $registry = array();

	private static function getDb()
	{
		$db = new \PDO("mysql:host=localhost;dbname=phptdd", "root", "root");
		$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		return $db;
	}

	public static function getObject($name, $data = "")
	{
		if ($data)
			$objct = new $name(self::getDb(), $data);
		else
			$objct = new $name(self::getDb());
		return $objct;
	}

	/**
	 * Adicione um novo resolvedor para a matriz registro.
	 * @Param string $name O id
	 * @param $resolve que cria uma inst�ncia
	 * @Return void
	 */
	public static function register($name, Closure $resolve)
	{
		static::$registry[$name] = $resolve;
	}

	/**
	 * Criar a inst�ncia
	 * @ Param string $ name O id
	 * @ Return misturado
	 */
	public static function resolve($name)
	{
		if (static::registered($name)) {
			$name = static::$registry[$name];
			return $name();
		}

		throw new Exception('Nothing registered with that name, fool.');
	}

	/**
	 * Determinar se o ID � registrado
	 * @ Param string $name O id
	 * @ Return bool Se a ID existe ou n�o
	 */
	public static function registered($name)
	{
		return array_key_exists($name, static::$registry);
	}

}