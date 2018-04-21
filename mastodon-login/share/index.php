<?php
if(!empty($_COOKIE["token"])){
    if(!empty($_POST["text"])){
        $token=$_COOKIE["token"];
    	//Vanillaの設定ファイルを覗かせてもらいます。
	    define('APPLICATION', 'Vanilla');
	    require("../../conf/config.php");
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
        require("../http.php");
        $auth=explode('_',$at);
        $url="https://".$auth[1]."/api/v1/statuses";
        $data=[
            "status"=>$_POST["text"]
        ];
        $curl = curl_init();
            curl_setopt( $curl, CURLOPT_URL, $url);
           curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
           curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.$auth[0]]);
           curl_setopt( $curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
         $buf = curl_exec( $curl );
          $message='<div class="alert alert-success" role="alert"><strong>トゥートしました。</strong></div>';
    }else{
        $message='';
    }
    $toot="block";
}else{
    $message='<div class="alert alert-warning" role="alert"><strong>ログイン情報がありません。</strong>トゥートするにはマストドンアカウントと連携するかその他サービスをご利用下さい。</div>';
    $toot="none";
}
function get_token() {
    $TOKEN_LENGTH = 16;//16*2=32桁
    $bytes = openssl_random_pseudo_bytes($TOKEN_LENGTH);
    return bin2hex($bytes);
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
<title>Mastodon Share</title>
<style>
.btn{
    width:100%;
    max-width:500px;
    margin-bottom:5px;
}
</style>
</head>
<body style="padding:5px;">
<?php echo $message; ?>
<h4>マストドンでトゥート</h4>
<div style="display:<?php echo $toot; ?>">
<form method="post">
<div class="form-group">
    <label for="exampleTextarea">トゥート内容</label>
    <textarea class="form-control" id="exampleTextarea" name="text"><?php echo $_GET["text"]; ?> - <?php echo $_GET["url"]; ?></textarea>
    <input type="submit" class="btn btn-primary" value="トゥート" style="margin-top:5px">
  </div>
</div>
</form>
その他サービスでトゥート<br>
<a href="https://mastportal.info/intent?text=<?php echo $_GET["text"] ?> - <?php echo $_GET["url"]; ?>" class="btn btn-primary" target="_blank">マストポータル</a>
<a href="https://masha.re/#<?php echo $_GET["text"] ?> - <?php echo $_GET["url"]; ?>" class="btn btn-primary" target="_blank">Mashare</a>
<a href="https://mastoshare.net/post.php?text=<?php echo $_GET["text"]; ?> - <?php echo $_GET["url"]; ?>" class="btn btn-primary" target="_blank">Mastoshare</a><br>
PCクライアント<a href="https://thedesk.top" target="_blank">TheDesk</a>でトゥート<br>
<a href="thedesk://share?code=<?php echo $_GET["text"] ?> - <?php echo $_GET["url"]; ?>" class="btn btn-primary">TheDeskでトゥート</a>
<br><br>
<h4>その他シェア</h4>
    <a class="btn btn-outline-info" href="http://twitter.com/intent/tweet?text=<?php echo $_GET["text"] ?>&url=<?php echo $_GET["url"]; ?>">Twitter</a>
    <a class="btn btn-outline-success" href="http://line.me/R/msg/text/?<?php echo $_GET["text"] ?> - <?php echo $_GET["url"]; ?>">LINE</a>
    <a class="btn btn-outline-primary" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $_GET["url"]; ?>">Facebook</a>
    <a class="btn btn-outline-danger" href="http://getpocket.com/edit?url=<?php echo $_GET["url"]; ?>&title=<?php echo $_GET["text"]; ?>">Pocket</a>
    <button type="button" class="btn btn-outline-primary" id="share">その他シェア(Androidのみ)</button>
<script>
document.querySelector('#share').addEventListener('click', (e) => {
    navigator.share({title:'<?php echo $_GET["text"] ?>', url:'<?php echo $_GET["url"]; ?>'});
});

</script>