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
    if(!$jwt->isValid($data->token) || $jwt->isExpired($data->token) || !$jwt->check($data->token, 'projetP16Cs2i')){

        echo json_encode(["code" => 500, "msg" => "invalid token"]);
        die;
    }
}


$parts = explode(".", $data->token);
$decode = json_decode(base64_decode($parts[1]));

$results = $BDDHelper->findAllDiscussion($decode->id);

foreach ($results as $result) 
{
    $discussion[] = [
        'id_discussion' => $result['id_discussion'],
        'titre_discussion' => $result['titre_discussion']

    ];
}
echo json_encode([
    "code" => 200, 
    "discussion" => $discussion
]);
