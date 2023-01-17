<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once 'includes/config.php';
require_once 'classes/JWT.php';
require_once 'helper/BBDHelper.php';

// On interdit toute méthode qui n'est pas POST
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(405);
    echo json_encode(['message' => 'Méthode non autorisée']);
    exit;
}
$postdata = file_get_contents("php://input");
$data = json_decode($postdata);

$BDDHelper = new BDDHelper();
echo $BDDHelper->findAllUser();
exit;
if($data->username == "benjamin" && $data->password == "test"){

    // On crée le header
    $header = [
        'typ' => 'JWT',
        'alg' => 'HS256'
    ];

    // On crée le contenu (payload)
    $payload = [
        "username" => "benjamin",
        "password" => "test"
    ];

    $jwt = new JWT();

    echo $token = $jwt->generate($header, $payload, SECRET);

}else{
    echo 'wrong username or password';
}