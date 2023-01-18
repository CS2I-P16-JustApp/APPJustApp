<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With");

require_once 'includes/config.php';
require_once 'classes/JWT.php';
require_once 'helper/BBDHelper.php';
require_once 'classes/AES-256.php';

echo $_SERVER['REQUEST_METHOD'];
// $postdata = file_get_contents("php://input");
// $data = json_decode($postdata);


$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTYsInVzZXJuYW1lIjoiMXVzZXJiYW1lIiwibWFpbCI6IjF0ZXN0QGdtYWlsLmNvbSIsImlhdCI6MTY3NDA0OTA2NSwiZXhwIjoxNjc0MTM1NDY1fQ.nOaf25rnXwDHWdU2OcVty2YRxsTHZrr7BiDRRy_fpPI";
$jwt = new JWT();
var_dump($jwt->isExpired($token));
var_dump($jwt->check($token,"projetP16Cs2i"));


// //split into 3 parts with . delimiter
// $parts = explode(".", $token);
// $decode = json_decode(base64_decode($parts[1]));
// var_dump($decode);