<?php

require_once 'classes/SQL.php';

$bdd = new Connexion();

class BDDHelper {
	private $bdd;


	public function __construct(){
        $bdd = new Connexion();
	}

    function findAllUser(){
        return $this->bdd->query("select * from utilisateur");
    }
}