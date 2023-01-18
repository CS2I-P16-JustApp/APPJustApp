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

if(empty($data->email) || empty($data->lastname) || empty($data->firstname) || empty($data->username)){
    echo json_encode(['code' => 500,'msg' => 'no field must be empty']);
    exit();
}

if(!$BDDHelper->findUserByEmail($data->email)){
    if($BDDHelper->insertUser($data)){
        // On crée le header
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        // On crée le contenu (payload)
        $payload = [
            "id" => $result['id_user'],
            "username" => $result['username'],
            "mail" => $result['email']
        ];

        $jwt = new JWT();

        $token = $jwt->generate($header, $payload, SECRET);

        echo json_encode(["code" => 200, "msg" => $token = $jwt->generate($header, $payload, SECRET)]);
        die();
    }else{
        echo json_encode(["code" => 500, "msg" => "Error User in bdd"]);
        die();
    }
    
}else{
    echo json_encode(["code" => 500, "msg" => "already exist in base"]);
    die();
}


echo json_encode(["code" => 500, "msg" => "other"]);
die();