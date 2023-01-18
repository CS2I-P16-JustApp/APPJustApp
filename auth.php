<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With");


require_once 'includes/config.php';
require_once 'classes/JWT.php';
require_once 'helper/BBDHelper.php';
require_once 'classes/AES-256.php';


$postdata = file_get_contents("php://input");
$data = json_decode($postdata);

$BDDHelper = new BDDHelper();
$abCrypt = new abCrypt();

if(empty($data->email) || empty($data->password)){
    echo json_encode(['code' => 500,'msg' => 'no field must be empty']);
    exit();
}

$result = $BDDHelper->findUserByIdentifiant($data->email, $abCrypt->encrypt($data->password));
$jwt = new JWT();

// On vérifie la validité
if(!is_null($result->token)){
    if($jwt->isValid($result->token) && !$jwt->isExpired($result->token) && $jwt->check($result->token, SECRET)){

        echo json_encode(["code" => 200, "msg" => $result->token ]);
        die;
    }
}

if(isset($result) && !is_null($result) && $result){

    
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

    

}else{
    echo json_encode(["code" => 500, "msg" => "error in log in"]);
}
