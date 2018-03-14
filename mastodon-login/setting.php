<?php
if(!empty($_COOKIE["token"])){
    if(!empty($_GET) && $_GET["del"]=="true"){
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
        $getat = "DELETE FROM vml_AutoLogin WHERE Token='$token'";
            $result = $link->query($getat);
            if (!$result) {
            die('{"error":"error"}');
        }
        $message='<div class="alert alert-success" role="alert"><strong>自動ログインは無効になりました。</strong></div>';
        setcookie('token', '', time() - 1800);
        header("Location: ../?p=/entry/signout");
    }else{
        $message='<div class="alert alert-info" role="alert"><strong>自動ログインが設定されています。</strong></div>';
    }
}else{
    $message='<div class="alert alert-warning" role="alert"><strong>自動ログインは設定されていません。</strong></div>';
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
<title>Mastodon Login設定</title>
</head>
<body style="padding:5px;">
<?php echo $message; ?>
<h4>マストドンログイン設定</h4>
ログインできない場合は一度無効にしてからもう一度お試し下さい。<br>
<a href="?del=true" class="btn btn-danger">自動ログインを無効にする(自動的にサインアウトされます)</a>