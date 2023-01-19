<!-- <?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With");

require_once 'includes/config.php';
require_once 'classes/JWT.php';
require_once 'helper/BBDHelper.php';

$BDDHelper = new BDDHelper();
$jwt = new JWT();
$postdata = file_get_contents("php://input");
$result = json_decode($postdata);


// On vérifie la validité
if(!is_null($result->token)){
    if(!$jwt->isValid($result->token) || $jwt->isExpired($result->token) || !$jwt->check($result->token, SECRET)){

        echo json_encode(["code" => 500, "msg" => "invalid token"]);
        die;
    }
}

if($jwt->isValid($result->token) && !$jwt->isExpired($result->token) && $jwt->check($result->token, SECRET)){
    $parts = explode(".", $result->token);
    $decode = json_decode(base64_decode($parts[1]));
    if($BDDHelper->sendMessageDiscussion($result->id_discussion, $result->contenu, $decode->id)){
        echo json_encode(["code" => 200, "msg" => "posted !"]);
    } else {
        echo json_encode(["code" => 500, "msg" => "error post"]);
    }
}else {
    echo json_encode(["code" => 500, "msg" => "invalid token"]);
    die;
}