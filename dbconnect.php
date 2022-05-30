<?php
     /*$host = 'localhost';
	 $user = 'root';
	 $pw = '0523';		//root 계정 비밀번호 0523
	 $dbName = 'mysite';*/

	$host = 'cccd.co.kr';
	$user = 'jexjin6';
	$pw = 'Aden2017052!';
	$dbName = 'jexjin6';

	//  $host = '165.22';
	//  $user = 'cccd01';
	//  $pw = '0523';
	//  $dbName = 'Homepage_DB';
	
	 $conn = mysqli_connect($host, $user, $pw, $dbName);
	 
	//  $conn = new mysqli($db_host, $db_user, $db_password, $db_name); // 데이터베이스 접속
	if ($conn->connect_errno) { die('Connection Error : '.$conn->connect_error); } // 오류가 있으면 오류 메세지 출력