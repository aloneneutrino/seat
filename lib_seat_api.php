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
    return $response->data->token;
}

function test_path($path, $username, $password, $post = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://".SERVER_URL.":8443$path");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYSTATUS, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    $token = login($username, $password);

    $header = array(
        "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
        "token: $token",
        "Host: ".SERVER_URL.":8443",
        "Connection: Keep-Alive"
    );

    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    $response = curl_exec($ch);
    curl_close($ch);
    echo $response;
}

function book($username, $password, $startTime, $endTime, $seat, $date)
{
    $post = "\"t=1&startTime=$startTime&endTime=$endTime&seat=$seat&date=$date&t2=2\"";
    test_path("/rest/v2/freeBook", $username, $password, $post);
}
