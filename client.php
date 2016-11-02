<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header('Access-Control-Allow-Headers: Content-Type, x-xsrf-token'); 
ini_set('display_errors', 1);

if (version_compare(PHP_VERSION, '5.3', '>=')){
  error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);}
   else{error_reporting(E_ALL);}

$param = $_POST;
if(count($param) == 0) {
	$param = (object)json_decode(rtrim(trim(file_get_contents("php://input")),':'));
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://localhost:8080/SKRIPSI/api-elsa/API/" . $param->action . ".php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $param->data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);

curl_close ($ch);

header("Content-Type", "application/json");

if(curl_error($ch))
{
    echo(json_encode(array('status' => false, "message" => $ch)));
}else{
	echo($server_output);
}
?>