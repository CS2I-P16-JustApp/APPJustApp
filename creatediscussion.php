<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With");

require_once 'includes/config.php';
require_once 'classes/JWT.php';
require_once 'helper/BBDHelper.php';

$postdata = file_get_contents("php://input");
$result = json_decode($postdata);

$BDDHelper = new BDDHelper();
$jwt = new JWT();


// On vérifie la validité
if(!is_null($result->token)){
    if(!$jwt->isValid($result->token) && $jwt->isExpired($result->token) && !$jwt->check($result->token, SECRET)){

        echo json_encode(["code" => 500, "msg" => "error token" ]);
        die;
    }
}

$idDiscussion = null;
if($idDiscussion = $BDDHelper->createDiscussion($result)){
    if($BDDHelper->insertUserInDiscussion($idDiscussion,$result->userId1) && $BDDHelper->insertUserInDiscussion($idDiscussion,$result->userId2)){
        echo json_encode(["code" => 200, "msg" => "chat and user inserted in bdd"]);
    }
}else{
    echo json_encode(["code" => 500, "msg" => "chat error User in bdd"]);
}

