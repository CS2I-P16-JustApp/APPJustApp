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
$data = json_decode($postdata);

// On vérifie la validité
if(!is_null($data->token)){
    if(!$jwt->isValid($data->token) || $jwt->isExpired($data->token) || !$jwt->check($data->token, SECRET)){

        echo json_encode(["code" => 500, "msg" => "invalid token"]);
        die;
    }
}

$token = $data->token;
$parts = explode(".", $token);
$decode = json_decode(base64_decode($parts[1]));

$user = $BDDHelper->findUserById($decode->id);

$post = $BDDHelper->findPostByUserId($user['id_user']);

if($BDDHelper->insertImage($data->image, $post['id_post'])){
    echo json_encode(["code" => 200, "msg" => "image inserted"]);
}else{
    echo json_encode(["code" => 500, "msg" => "errror image"]);
}
