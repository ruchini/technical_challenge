<?php
require_once('request.php');

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->auth('REDIS_PASSWORD');

$key = 'SERVICE_TOKEN';

if (!$redis->exists($key)) {
    // Get token from API request
    $rootDir = realpath("token.txt");
    $url     = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/token.txt";

    $response = SimpleJsonRequest::get($url);
    $token    = $response->token;

    $redis->set($key, $token);
    $redis->expire($key, 3600); // key expire after one hour

    echo "Token is getting from API request : ".$token;

} else {
    // Get token from Redis DB
    $token = $redis->get($key);
    echo "Token is getting from Redis DB : ".$token;

}
