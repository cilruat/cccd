<?php

session_start(); // 세션
include ("connect.php"); // DB접속

$id = $_POST['id']; // 아이디
$pw = md5($pw = $_POST['pw']); // 패스워드

$query = "select * from member where memberid='$id' and memberpwd='$pw'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);

//echo "{$_POST['id']}";

if($id==$row['memberid'] && $pw==$row['memberpwd']){ // id와 pw가 맞다면 login

   $_SESSION['id']=$row['memberid'];
   $_SESSION['pw']=$row['memberpwd'];
   $_SESSION['name']=$row['membername'];
   echo "{$_POST['id']}";
   if($_SESSION['boardtype'] == 'notice'){
      echo "<script>location.href='/bbs/bbsList.html';</script>";
   } else {
      echo "<script>location.href='/bbs/bbsListRecruit.html';</script>";
   }
   

}
else{ // id 또는 pw가 다르다면 login 폼으로
   echo "<script>window.alert('invalid username or password');</script>"; // 잘못된 아이디 또는 비빌번호 입니다
   echo "<script>location.href='bbsLogin.html';</script>";
}

?>