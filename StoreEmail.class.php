<?php

namespace helpers;

//=======================================================//
//Query sql da tabela
//=======================================================//
/*
 * CREATE TABLE IF NOT EXISTS `store_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `formulario` varchar(255) NOT NULL,
  `assunto` varchar(255) NOT NULL,
  `remetente_email` varchar(255) NOT NULL,
  `remetente_nome` varchar(255) NOT NULL,
  `mensagem` text NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;
 */

/**
 * Classe genérica que armazena no banco todos os emails enviados apartir do site
 *
 * @author Alexsandro Souza
 */
class StoreEmail
{

	const TB_NAME = 'store_email';
	const PG_LISTAR = 'suporte/listar_emails.php';

	public $id;
	private $pdo;

	public function __construct($pdo = null, $db_host = null, $db_nome = null, $db_usuario = null, $db_senha = null, $db_porta = null)
	{
		$this->pdo = $pdo;
		if (!$pdo) {
			$db_porta = $db_porta ? $db_porta : '3306';
			$this->pdo = new PDO("mysql:host={$db_host}; dbname={$db_nome}; port={$db_porta}", $db_usuario, $db_senha);
		}
	}

	public function getById($id)
	{
		if ($id) {
			$self = $this->getAll(' WHERE id=' . $id);

			foreach ($self[0] as $property => $value) {
				$this->$property = $value;
			}
		}
	}

	public function store()
	{
		if ($this->id) {
			return $this->update();
		}

		try {
			$stmte = $this->pdo->prepare("INSERT INTO " . self::TB_NAME . "(formulario, assunto, mensagem ,remetente_nome,remetente_email) VALUES 
											(:form, :assunto, :msg,:nome,:email)");

			$stmte->bindParam(":form", $this->formulario, PDO::PARAM_STR);
			$stmte->bindParam(":assunto", $this->assunto, PDO::PARAM_STR);
			$stmte->bindParam(":nome", $this->remetente_nome, PDO::PARAM_STR);
			$stmte->bindParam(":email", $this->remetente_email, PDO::PARAM_STR);
			$stmte->bindParam(":msg", $this->mensagem, PDO::PARAM_STR);

			$executa = $stmte->execute();

			return $executa;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function update()
	{
		$sql = "UPDATE  " . self::TB_NAME . " SET bool_exibir = " . $this->bool_exibir . " WHERE id = " . $this->id;

		try {
			$stmte = $this->pdo->prepare($sql);
			return $stmte->execute();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function delete()
	{
		$sql = "DELETE FROM  " . self::TB_NAME . "  WHERE id = " . $this->id;

		try {
			$stmte = $this->pdo->prepare($sql);
			return $stmte->execute();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function getAll($complemento = '')
	{
		$sql = "SELECT * FROM " . self::TB_NAME . " $complemento";
		$result = $this->pdo->query($sql);

		while ($objeto = $result->fetchObject(__CLASS__)) {
			$aObjetos[] = $objeto;
		}
		return $aObjetos;
	}

}

?>
