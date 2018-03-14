<?php
function http_post ($url, $data, $method, $at)
{
    $curl = curl_init();
    curl_setopt( $curl, CURLOPT_URL, $url);
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: '.$at]);
    curl_setopt( $curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
    if($method=="post"){
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
    }
    $buf = curl_exec( $curl );
    print_r($curl);
    if ( curl_errno( $curl ) ) {
        exit;
    }
    curl_close( $curl );
    return $buf;
}
?>