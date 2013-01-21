<?php
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

	const DB_TIPO = 'mysql';
	const DB_PORTA = '';
	const DB_NOME = '';
	const DB_USUARIO = '';
	const DB_SENHA = '';
	const DB_HOST = '';
	const TB_NAME = 'store_email';
	const PG_LISTAR = 'suporte/listar_emails.php';

	public $id;

	public function __construct($id = 0)
	{
		if ($id) {
			$self = $this->getAll(' WHERE id=' . $id);
			
			foreach( $self[0] as $property => $value ) {
				$this -> $property = $value;
			}
		}
	}

	public function store()
	{
		if ($this->id) {
			return $this->update();
		}
		
		$pdo = $this->connect();
		try {
			$stmte = $pdo->prepare("INSERT INTO " . self::TB_NAME . "(formulario, assunto, mensagem ,remetente_nome,remetente_email) VALUES 
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
		$pdo = $this->connect();
		$sql = "UPDATE  " . self::TB_NAME . " SET bool_exibir = " . $this->bool_exibir . " WHERE id = " . $this->id;

		try {
			$stmte = $pdo->prepare($sql);
			return $stmte->execute();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function delete()
	{
		$pdo = $this->connect();
		$sql = "DELETE FROM  " . self::TB_NAME . "  WHERE id = " . $this->id;

		try {
			$stmte = $pdo->prepare($sql);
			return $stmte->execute();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	
	public function getAll($complemento = '')
	{
		$sql = "SELECT * FROM " . self::TB_NAME . " $complemento";
		$conn = $this->connect();
		$result = $conn->query($sql);

		while ($objeto = $result->fetchObject(__CLASS__)) {
			$aObjetos[] = $objeto;
		}
		return $aObjetos;
	}

	public function connect()
	{
		$db_nome = self::DB_NOME;
		$db_senha = self::DB_SENHA;
		$db_usuario = self::DB_USUARIO;
		$db_host = self::DB_HOST;
		$db_porta = self::DB_PORTA;


		switch (self::DB_TIPO) {
			case 'pgsql':
				$db_porta = $db_porta ? $db_porta : '5432';
				$conn = new PDO("pgsql:dbname={$db_nome}; user={$db_usuario}; password={$db_senha}; host={$db_host}; port={$db_porta}");
				break;

			case 'mysql':
				$db_porta = $db_porta ? $db_porta : '3306';
				$conn = new PDO("mysql:host={$db_host}; dbname={$db_nome}; port={$db_porta}", $db_usuario, $db_senha);
				break;

			case 'sqlite':
				$conn = new PDO("sqlite:{$db_nome}");
				break;

			case 'ibase':
				$conn = new PDO("firebird:dbname={$db_nome}", $db_usuario, $db_senha);
				break;

			case 'oci8':
				$conn = new PDO("oci:dbname={$db_nome}", $db_usuario, $db_senha);
				break;

			case 'mssql':
				$conn = new PDO("mssql:host={$db_host}, 1433; dbname={$db_nome}", $db_usuario, $db_senha);
				break;
		}

		// define o atributo error mode para lançar exceções em caso de erro
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $conn;
	}

}

?>
