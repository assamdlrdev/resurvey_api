<?php
define('ESIGN_ROOT_DIRECTORY',  $_SERVER["DOCUMENT_ROOT"] . "/" . "chithaentry/esign/");
require ESIGN_ROOT_DIRECTORY ."library/XMLSecurityDSig.php";
require ESIGN_ROOT_DIRECTORY ."library/XMLSecurityKey.php";
require ESIGN_ROOT_DIRECTORY .'TCPDF/tcpdf.php';
defined('APP_PATH') or define('APP_PATH', $_SERVER["DOCUMENT_ROOT"]. "/" . "chithaentry");
defined('FILE_LOCATION_PATH') or define('FILE_LOCATION_PATH', APP_PATH . '/uploads/certificate/');
define("ESIGN_SERVER_ROOT", 'localhost/chithaentry/esign/');
define('MAIN_APPLICATION_SERVER','../../index.php/');
define('IS_PRODUCTION', 0);
date_default_timezone_set("Asia/Kolkata");

//get
$getParams = $_GET['param'];
// $params = json_decode(base64_decode($getParams));
$params = json_decode(openssl_decrypt(base64_decode(urldecode($getParams)), "AES-128-CTR", "singleENCRYPT", 0, '1234567893032221'));

$dist_code = $params->dist_code;
$subdiv_code = $params->subdiv_code;
$cir_code = $params->cir_code;
$mouza_pargona_code = $params->mouza_pargona_code;
$lot_no = $params->lot_no;
$file_name = $params->file_name;
$file_path_tobe_signed = FILE_LOCATION_PATH . $file_name;

$user_code = $params->user_code;
$user_desig_code = $params->user_desig_code;

$sign_users = $params->sign_users;
$redirect = $params->redirect;
$is_final = $params->is_final;

$dynamic_file_path = $file_path_tobe_signed;

define("FILE_NAME", $file_name);

$project_path = ESIGN_ROOT_DIRECTORY;
$tmp_path = $project_path.'temp/';
define('SIGNED_CHITHA_CERT_PDF', FILE_LOCATION_PATH);


const RESPONSE_URL = "http://localhost/chithaentry/esign/verify_chitha_certificate/resp.php";
// const PRIVATEKEY = 'cert/dlrs_private_key.pem';
if(IS_PRODUCTION == 0) {
    define('ASPID', 'DLRA-900');
    defined('PRIVATEKEY') or define('PRIVATEKEY', ESIGN_ROOT_DIRECTORY .'cert/dlrs_private_key.pem');
    defined('ESIGN_URL') or define('ESIGN_URL', 'https://es-staging.cdac.in/esignlevel2/2.1/form/signdoc');
}
else if(IS_PRODUCTION == 1){
    define('ASPID', 'DLRA-001');
    defined('PRIVATEKEY') or define('PRIVATEKEY', ESIGN_ROOT_DIRECTORY .'cert/dlrs_private_key_prod.pem');
    defined('ESIGN_URL') or define('ESIGN_URL', 'https://esignservice.cdac.in/esign2.1/2.1/form/signdoc');
}
// const ESIGN_URL = 'https://es-staging.cdac.in/esignlevel2/2.1/form/signdoc';


const DATABASES = [
    '21'=>'ckarimganj',
    '22'=>'chailakandi',
    '23'=>'ccachar',
    '25'=>'cdhemaji'
];

function print_pdf($name, $file) {
    header('Content-Type: application/pdf');
    header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
    header('Pragma: public');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Content-Disposition: inline; filename="' . basename($name) . '"');
    header('Content-Length: ' . strlen($file));
    echo $file;
}

$file_name_array = explode('.', FILE_NAME);
$ext = end($file_name_array);
unset($file_name_array[count($file_name_array) - 1]);
$file_name_wo_ext = implode('.', $file_name_array);




