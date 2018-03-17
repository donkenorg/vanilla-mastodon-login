<?php
header("Content-Type: application/json; charset=utf-8");
http_response_code(200);
$header = getallheaders();
    $rawauth=$header['Authorization'];
    $auth=explode('_',$rawauth);
    $curl = curl_init();
    curl_setopt( $curl, CURLOPT_URL, "https://".$auth[1]."/api/v1/accounts/verify_credentials" );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: '.$auth[0]]);
    curl_setopt( $curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
    $buf = curl_exec( $curl );
    curl_close( $curl );
    $json=json_decode($buf);
    $display_name=$json->display_name;
    preg_match_all('/\s?(:[^:]+:)\s?/', $display_name, $emojis);
    if(isset($emojis) && isset($emojis[0])){
        foreach($emojis as $key => $emoji){
            if(isset($emoji) && isset($emoji[0])){
                $display_name=str_replace($emoji[0], "", $display_name);
            }
        }
    }
    $response=[];
    $json->stripped_name=$display_name;
    $at=$json->username;
    $response["user_id"]=str_replace(" ","",$at."-".str_replace(".","_",$auth[1]));
    $response["id"]=str_replace(" ","",$at."-".str_replace(".","_",$auth[1]));
    $response["username"]=str_replace(" ","",$at."-".str_replace(".","_",$auth[1]));
    $response["avatar"]=$json->avatar;
    echo json_encode($response);
    
?>