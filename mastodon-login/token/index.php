<?php
header("Content-Type: application/json; charset=utf-8");
require("../http.php");
$postdata=file_get_contents('php://input');
preg_match('/code=([^&]+)/', $postdata, $m);
$code=$m[1];
//$code=$_GET["code"];
//Vanillaの設定ファイルを覗かせてもらいます。
define('APPLICATION', 'Vanilla');
require("../../conf/config.php");
$db=$Configuration["Database"];
//データベース設定ファイルを拝借
define('DB_HOST', $db["Host"]);
define('DB_NAME', $db["Name"]);
define('DB_USER', $db["User"]);
define('DB_PASSWORD', $db["Password"]);
define('DB_CHARSET', 'utf-8');
if(isset($code)){
    //データベース設定ファイルを拝借
	$link = new mysqli($db["Host"] , $db["User"] , $db["Password"] , $db["Name"]);
	if ($link->connect_error){
		$sql_error = $link->connect_error;
		error_log($sql_error);
		die($sql_error);
	} else {
		$link->set_charset(DB_CHARSET);
	}
    $getat = "SELECT * FROM vml_AutoLogin WHERE Code='$code' ORDER BY ID DESC";
		$result = $link->query($getat);
		if (!$result) {
		die('{"error":"error"}');
	}else{
		$del = "UPDATE vml_AutoLogin SET Code='' WHERE Code='$code' ORDER BY ID DESC";
		$delr = $link->query($del);
	}
	$rresult = $result->fetch_assoc();
    echo json_encode($rresult);
}else{
    echo'{"error":"error"}';
}