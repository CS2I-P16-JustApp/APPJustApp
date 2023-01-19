<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With");

require_once 'helper/BBDHelper.php';
require_once 'classes/JWT.php';
//require_once 'includes/config.php';

echo "test index.php";