<?php
//Vanillaの設定ファイルを覗かせてもらいます。
define('APPLICATION', 'Vanilla');
require("../conf/config.php");
$db=$Configuration["Database"];
//データベース設定ファイルを拝借
define('DB_HOST', $db["Host"]);
define('DB_NAME', $db["Name"]);
define('DB_USER', $db["User"]);
define('DB_PASSWORD', $db["Password"]);
define('DB_CHARSET', 'utf-8');
function get_token() {
    $TOKEN_LENGTH = 16;//16*2=32桁
    $bytes = openssl_random_pseudo_bytes($TOKEN_LENGTH);
    return bin2hex($bytes);
  }
function register_token($code,$at, $token) {
    $link = new mysqli(DB_HOST , DB_USER , DB_PASSWORD , DB_NAME);
    if ($link->connect_error){
        $sql_error = $link->connect_error;
        error_log($sql_error);
        die($sql_error);
    } else {
        $link->set_charset(DB_CHARSET);
    }
    //テーブル存在チェック vml=Vanilla Mastodon Login
    if ($result = $link->query("SHOW TABLES LIKE 'vml_AutoLogin'"))
    {
      if (!$result->num_rows)
      {
        $sql = 'CREATE TABLE vml_AutoLogin (
            ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            Code VARCHAR(100),
            Token VARCHAR(100),
            access_token VARCHAR(100),
            Reg_Time DATETIME
        ) engine=innodb default charset=utf8';
        
        // SQL実行
        $res = $link->query($sql);
      }
      
      // 結果セットの開放
      $result->close();
    }
  $date=date('Y-m-d H:i:s');
  $sql = "INSERT INTO vml_AutoLogin (Code, Token, access_token, Reg_Time) VALUES ('$code','$token','$at','$date');";
  $stmt = $link->query($sql);
  //2週間後に切れます
  setCookie("token", $token, time()+60*60*24*14, "/", null, FALSE, TRUE);
}
?>