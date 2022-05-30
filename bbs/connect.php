<?php

/*$db_host = "localhost";
$db_user = "root";
$db_password = "0523";
$db_name = "mysite";*/
$host = 'cccd.co.kr';
$user = 'jexjin6';
$pw = 'Aden2017052!';
$dbName = 'jexjin6';

$con = new mysqli($host, $user, $pw, $dbName); // 데이터베이스 접속

if ($con->connect_errno) { die('Connection Error : '.$con->connect_error); } // 오류가 있으면 오류 메세지 출력

?>