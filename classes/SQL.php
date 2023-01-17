<?php

require_once 'config/bdd.php';

class Connexion {
	private $login;
	private $pass;
    private $db;
	private $host;
    private $port;
	private $connec;

	public function __construct(){
		$this->login = DB_USER;
		$this->pass = DB_PASS;
		$this->db = DB_HOST;
        $this->host = DB_NAME;
        $this->port = DB_PORT;
		$this->connexion();
	}

	private function connexion(){
		try
		{
	         $bdd = new PDO(
                            'pgsql:host='.$this->host.'; port='.$this->port.';dbname='.$this->db.';', 
                             $this->login, 
                             $this->pass
                 );
			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
			$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			$this->connec = $bdd;
		}
		catch (PDOException $e)
		{
			$msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
			die($msg);
		}
	}

	public function query($sql,Array $cond = null){
		$stmt = $this->connec->prepare($sql);

		if($cond){
			foreach ($cond as $v) {
				$stmt->bindParam($v[0],$v[1],$v[2]);
			}
		}

		$stmt->execute();

		return $stmt->fetchAll();
		$stmt->closeCursor();
		$stmt=NULL;
	}

    

}