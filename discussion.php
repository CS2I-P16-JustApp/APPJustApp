<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With");

require_once 'helper/BBDHelper.php';
require_once 'classes/JWT.php';

$BDDHelper = new BDDHelper();
$postdata = file_get_contents("php://input");
$data = json_decode($postdata);

$jwt = new JWT();

// On vérifie la validité
if(!is_null($data->token)){
    if($jwt->isValid($data->token) && !$jwt->isExpired($data->token) && $jwt->check($data->token, SECRET)){

        echo json_encode(["code" => 500, "msg" => "invalid token"]);
        die;
    }
}

$results = $BDDHelper->findAllDiscussion($data->id_user);

foreach ($results as $result) 
{
    $discussion[] = [
        'titre_discussion' => $result['titre_discussion']
    ];
}
echo json_encode([
    "code" => 200, 
    "discussion" => $discussion
]);
