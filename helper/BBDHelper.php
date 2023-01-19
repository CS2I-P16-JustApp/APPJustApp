<?php

require_once 'classes/SQL.php';
require_once 'classes/AES-256.php';
require_once 'config/bdd.php';
require_once 'classes/AES-256.php';

$bdd = new Connexion();

class BDDHelper {
    private $login;
	private $pass;
    private $db;
	private $host;
    private $port;
    
	private $bdd;

	public function __construct(){
        $this->login = DB_USER;
		$this->pass = DB_PASS;
		$this->db = DB_HOST;
        $this->host = DB_NAME;
        $this->port = DB_PORT;
        $this->bdd = new PDO(
            'pgsql:host='.$this->host.'; port='.$this->port.';dbname='.$this->db.';', 
             $this->login, 
             $this->pass
        );
	}

    function findAllUser(){
        return $this->bdd->query("select * from utilisateur");
    }

    function findUserByIdentifiant($email, $passwd){
        $request = $this->bdd->query("select * from utilisateur where email = '".$email."' and password = '".$passwd."'");
        return $request->fetch();
    }

    function findUserByEmail($email){
        $request = $this->bdd->query("select * from utilisateur where email = '".$email."'");
        return $request->fetch();
    }

    function findUserById($id){
        $request = $this->bdd->query("select * from utilisateur where id_user = '".$id."'");
        return $request->fetch();
    }

    function insertUser($user){
        // $user["password"]
        $abCrypt = new abCrypt();
        $password = $abCrypt->encrypt($user->password);
        $create = date("Y-m-d H:i:s");

        $this->bdd->query("INSERT INTO utilisateur (lastname, firstname, username, password, email, created_on, last_login) VALUES ('".$user->lastname."', '".$user->firstname."','".$user->username ."','".$password."','".$user->email."','".$create."','".$create."')");
        return true;
    }

    function createChat($msg){
        $create = date("Y-m-d H:i:s");

        $this->bdd->query("INSERT INTO user_discussion (id_discussion, id_user) VALUES ('".$msg->id_discussion."', '".$msg->id_user."')");
        return true;
    }

    function createDiscussion($data){
        $this->bdd->query("INSERT INTO discussion (titre_discussion) VALUES ('".$data->titre."')");
        return $this->bdd->lastInsertId();
    }

    function insertUserInDiscussion($id_discussion, $id_user){
        return $this->bdd->query("INSERT INTO user_discussion (id_discussion, id_user) VALUES ('".$id_discussion."', '".$id_user."')");
    
    }

    function findAllPost(){
        $request = $this->bdd->query(
            "SELECT po.contenu,po.id_post, u.username, po.date, rea.like, com.contenu as contenu_com, img.image  FROM post po
            LEFT JOIN reaction rea ON po.id_post = rea.id_post
            LEFT JOIN utilisateur u ON po.id_user = u.id_user
            LEFT JOIN post_img img ON po.id_post = img.id_post
            LEFT JOIN commentaires com ON po.id_post = com.id_post
            ORDER BY po.date DESC"
        );
        
        return $request->fetchAll();
    }

    function insertPost($contenu, $id_user){
        $create = date("Y-m-d H:i:s");
        return $this->bdd->query("INSERT INTO post (contenu, date, id_user) VALUES ('".$contenu."', '".$create."', '".$id_user."')");
        
    }

    function findPostByUserId($userId){
        $request = $this->bdd->query("SELECT * from post WHERE id_user= '".$userId."'");
        return $request->fetch();
        
    }

    function insertImage($image, $id){
        $create = date("Y-m-d H:i:s");
        return $this->bdd->query("INSERT INTO post_img (id_post, image) VALUES ('".$id."', '".$image."')");
    }

    // findAll() table reaction
    function findAllReaction($post){
        $request = $this->bdd->query("SELECT * FROM reaction WHERE id_post = " . $post);
        
        return $request->fetchAll();
    }

    // findAll() table message
    function findAllMessage($id_discussion){
        $request = $this->bdd->query("SELECT contenu, id_user FROM message m
        INNER JOIN discussion d ON m.id_discussion = d.id_discussion
        WHERE d.id_discussion = " . $id_discussion . "ORDER BY date ASC");
        
        return $request->fetchAll();
    }

    // findAll() table discussion
    function findAllDiscussion($id_user){
        $request = $this->bdd->query("SELECT * FROM discussion d INNER JOIN user_discussion us ON d.id_discussion = us.id_discussion WHERE us.id_user = '" . $id_user . "'");
        
        return $request->fetchAll();
    }

    function sendMessageDiscussion($id_discussion, $contenu, $id_user){
        return $this->bdd->query("INSERT INTO message
        (id_discussion, contenu, id_user)
        VALUES(".$id_discussion.", '".$contenu."', ".$id_user.");");
    }
}