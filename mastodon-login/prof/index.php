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
    foreach($emojis as $key => $emoji){
        $display_name=str_replace($emoji[0], "", $display_name);
    }
    $json->stripped_name=$display_name;
    $at=$json->username;
    $json->user_id=$at."-".str_replace(".","_",$auth[1]);
    $json->id=$at."-".str_replace(".","_",$auth[1]);
    $json->username=$at."-".str_replace(".","_",$auth[1]);
    echo json_encode($json);
?>