<?php
/**
 * Created by PhpStorm.
 * User: aloneneutrino@gmail.com
 * Date: 2018/9/30
 * Time: 6:47
 */
define("SERVER_URL", "172.26.50.21");

function login($username, $password)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://".SERVER_URL.":8443/rest/auth?username=$username&password=$password");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYSTATUS, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //自签发证书你叫我怎么验证
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response);
    return (string)$response->data->token;
}
