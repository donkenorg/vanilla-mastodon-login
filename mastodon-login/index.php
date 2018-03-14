<?php
session_start();
//require("encrypt.php");
//ログインデータが返ってきた
require("token.php");
$runfile=$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"];
if(isset($_GET["code"]) && isset($_GET["state"])){
	require("http.php");
	$array=explode(' ',$_GET["state"]." http://".$runfile);
	$data = [
        "client_id"=>$array[1],
        "client_secret"=>$array[2],
        "grant_type"=>"authorization_code",
        "redirect_uri"=>$array[3],
        "code"=>$_GET["code"]
    ];
    $curl = curl_init();
    curl_setopt( $curl, CURLOPT_URL, "https://".$array[0]."/oauth/token" );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
    curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
    $buf = curl_exec( $curl );
    if ( curl_errno( $curl ) ) {
        exit;
    }
    curl_close( $curl );
	$json=json_decode($buf);
    $at=$json->access_token;
	$at=$at."_".$array[0];
	$token=get_token();
	register_token($_GET["code"],$at,$token);
	header("Location: ../index.php?p=/entry/oauth2&code=".$_GET["code"]);
}elseif(!empty($_COOKIE["token"])){
	$token=$_COOKIE["token"];
	//Vanillaの設定ファイルを覗かせてもらいます。
	define('APPLICATION', 'Vanilla');
	require("../conf/config.php");
	$db=$Configuration["Database"];
	//データベース設定ファイルを拝借
	$link = new mysqli($db["Host"] , $db["User"] , $db["Password"] , $db["Name"]);
	if ($link->connect_error){
		$sql_error = $link->connect_error;
		error_log($sql_error);
		die($sql_error);
	} else {
		$link->set_charset("utf-8");
	}
	$getat = "SELECT * FROM vml_AutoLogin WHERE Token='$token' ORDER BY ID DESC";
		$result = $link->query($getat);
		if (!$result) {
		die('{"error":"error"}');
	}
	$rresult = $result->fetch_assoc();
	$at=$rresult["access_token"];
	$code=get_token();
	$del = "UPDATE vml_AutoLogin SET Code='$code' WHERE Token='$token' ORDER BY ID DESC";
	$delr = $link->query($del);
	//トークンを上書き
	$token=get_token();
	setCookie("token", $token, time()+60*60*24*14, "/", null, FALSE, TRUE);
	$tup = "UPDATE vml_AutoLogin SET Token='$token' WHERE Code='$code' ORDER BY ID DESC";
	$tkr = $link->query($tup);
	setCookie("token", $token, time()+60*60*24*14, "/", null, FALSE, TRUE);
	header("Location: ../index.php?p=/entry/oauth2&code=".$code);
}
?>
<!doctype html>
<html lang="ja">
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<meta content="width=device-width,initial-scale=1.0" name="viewport">
<meta charset="utf-8">
<title>Mastodon Login</title>
</head>
<body style="padding:5px;">
<h4>マストドンログイン</h4>
<span id="mess"></span><br>
<label class="sr-only" for="url">インスタンスのURL</label>
  <div class="input-group" style="max-width:calc(100% - 10px); width:400px;">
    <div class="input-group-addon">https://</div>
	<input type="text" class="form-control" id="url" placeholder="ex)mstdn.jp">
  </div>
<div id="suggest"></div>
<br>
「マストドンでシェア」を使用するために，書き込み権限が要求されます。<br>
ブラウザ内にログインデータが保存されます。<br>
<button onclick="instance()" class="btn btn-primary">ログイン</button>
<script>
function instance() {
	var url = $("#url").val()
	login(url);
}
function login(url) {
    var elem = document.getElementById('mess');
    elem.textContent = 'Please Wait...';
	var red = 'http://<?php echo $runfile; ?>';
	var start = "https://" + url + "/api/v1/apps";
	fetch(start, {
		method: 'POST',
		headers: {
			'content-type': 'application/json'
		},
		body: JSON.stringify({
			scopes: 'read write',
			client_name: "Vanilla Mastdon Login",
			redirect_uris: red
		})
	}).then(function(response) {
		return response.json();
	}).catch(function(error) {
		console.error(error);
	}).then(function(json) {
		localStorage.setItem("last",url);
		var auth = "https://" + url + "/oauth/authorize?client_id=" + json[
				"client_id"] + "&client_secret=" + json["client_secret"] +
            "&response_type=code&redirect_uri="+red+"&scope=read+write&state="+url+"+"+ json["client_id"]+"+"+json[
				"client_secret"];
        location.href=auth;
	});
}
function load(){
	var last=localStorage.getItem("last");
	if(last){
		$("#suggest").html('最後に使ったインスタンス:<a onclick="login(\''+last+'\')" style="cursor:pointer;"><u>'+last+'</u></a>');
	}
}
load();

</script>
</body>
</html>