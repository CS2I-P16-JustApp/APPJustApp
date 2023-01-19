<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With");

require_once 'includes/config.php';
require_once 'classes/JWT.php';
require_once 'helper/BBDHelper.php';

$postdata = file_get_contents("php://input");
$data = json_decode($postdata);

$BDDHelper = new BDDHelper();
$jwt = new JWT();

// On vérifie la validité
if(!is_null($data->token)){
    if(!$jwt->isValid($data->token) || $jwt->isExpired($data->token) || !$jwt->check($data->token, SECRET)){

        echo json_encode(["code" => 500, "msg" => "invalid token"]);
        die;
    }
}

if($BDDHelper->createChat($data)){
    echo json_encode(["code" => 200, "msg" => "chat inserted in bdd"]);
}else{
    echo json_encode(["code" => 500, "msg" => "chat error User in bdd"]);
}
