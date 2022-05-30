<?php
session_start(); // 세션
  include $_SERVER["DOCUMENT_ROOT"]."/dbconnect.php";
 
  //$_POST['bno']이 있을 때만 $bno 선언
 if(isset($_POST['bno'])) {
   $bNo = $_POST['bno'];
   //echo "bno .... {$_POST['bno']}<br/>";
 }
  
  $title=$_POST['title'];//제목
  $name=$_POST['name'];//작성자
  $memo= nl2br($_POST['memo']);//내용
  $writeoption=$_POST['option'];//writeoption
  //file;
  $file_name = $_FILES['filelist']['name']; //받아온 파일을 임시파일명으로 바꿈
  $file_tmp_name = $_FILES['filelist']['tmp_name']; //원래 파일명을 넣어줌
  $file_size = $_FILES['filelist']['size'];//byte 로 저장됨.
  $mimeType = $_FILES['filelist']['type'];

  //파일 업로드 테스트*********************************************************************************************
  //*************************************************************************************************************
  $file = $_FILES['filelist'];
  $upload_directory = '../uploads/';
  $ext_str = "png,jpg";
  $allowed_extensions = explode(',', $ext_str);  

  $max_file_size = 5242880;
  $ext = substr($file['name'], strrpos($file['name'], '.') + 1);
  
  // 확장자 체크
  if(!in_array($ext, $allowed_extensions)) {
      echo "업로드할 수 없는 확장자 입니다.";
  }

  // 파일 크기 체크
  if($file['size'] >= $max_file_size) {
      echo "5MB 까지만 업로드 가능합니다.";
  }
  
  $path = md5(microtime()) . '.' . $ext;
  if(move_uploaded_file($file['tmp_name'], $upload_directory.$path)) {    
      $query = "INSERT INTO upload_file (file_id, name_orig, name_save, reg_time) VALUES(?,?,?,now())";
      $file_id = md5(uniqid(rand(), true));
      $name_orig = $file['name'];
      $name_save = $path;      

      $stmt = mysqli_prepare($conn, $query);
      $bind = mysqli_stmt_bind_param($stmt, "sss", $file_id, $name_orig, $name_save);
      $exec = mysqli_stmt_execute($stmt);    
      mysqli_stmt_close($stmt);
      
      echo "<script>console.log('업로드성공');</script>";
      echo"<h3>파일 업로드 성공</h3>";      
  }
 
//**********************************************************************************************************
//**********************************************************************************************************

  // echo "title ............. {$_POST['title']} <br />";
  // echo "name ............. {$_POST['name']} <br />";
  // echo "file ............. {$_FILES['filelist']} <br />";
  // echo "memo ............. {$_POST['memo']} <br /><br /><br />";
  // echo "filetmp ............. {$_FILES['filelist']['name']} <br />";
  // echo "filereal ............. {$_FILES['filelist']['tmp_name']} <br />";
  // echo "filesize ............. {$_FILES['filelist']['size']} <br />"; 
  //echo "writeoption ..........{$_POST['option']}";

  //업로드한 파일이 이동 될 수 있도록 폴더 경로를 지정하는 부분.
  //$save_dir = '../';

  //파일명 변경
  $real_name = $file_name;
  $arr = explode(".", $real_name);
  $arr1 = $arr[0];
  $arr2 = $arr[1];
  $arr3 = $arr[2];
  $arr4 = $arr[3];

  if($arr4){
    $file_exe = $arr4;
  }
  else if($arr3 && !$arr4){
    $file_exe = $arr3;
  }
  else if($arr2 && !$arr3){
    $file_exe = $arr2;
  }

  $file_time = time();
  $file_Name = "file_".$file_time.".".$file_exe;
  $change_file_name = $file_Name;
  $real_name = addslashes($real_name);
  $real_size = $file_size;

  $dest_url = $save_dir.$change_file_name;

  if($title == '' || $name == '' || $memo == ''){
    echo "빈칸 채워주세요";
    exit;
  }
  

//글수정하는 부분.
  if(isset($bNo)) {
    if($_SESSION['boardtype']=='notice') {
      $sql = 'select count(*) as cnt from board where idx = '.$bNo;
    } else {
      $sql = 'select count(*) as cnt from recruit_board where idx = '.$bNo;
    }
   
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
 
    if($row['cnt']) {//글수정게시물 db저장
      if($_SESSION['boardtype']=='notice') {
        $sql = 'update board set name="'.$name. '", title="'. $title . '", content ="'. $memo .'", writeoption ="'.$writeoption .'" where idx = ' .$bNo;
      } else {
        $sql = 'update recruit_board set name="'.$name. '", title="'. $title . '", content ="'. $memo .'", writeoption ="'.$writeoption .'" where idx = ' .$bNo;
      }
     
      $msgState = '수정';
    }
    $replaceURL = '/notice/infview.php?bno='.$bNo;
  }
  //새게시물 저장
  else{//db저장
    if($_SESSION['boardtype']=='notice') {
      mysqli_query($conn, "
      INSERT INTO board ( title, name, content, covfile, file, filesize, writeoption )
      VALUES (
              '$title', '$name', '$memo', '$file_id', '$real_name', '$file_size', '$writeoption')");
    } else {
      mysqli_query($conn, "
      INSERT INTO recruit_board ( title, name, content, covfile, file, filesize, writeoption )
      VALUES (
              '$title', '$name', '$memo', '$file_id', '$real_name', '$file_size', '$writeoption')");      
    }
 
    $msgState = '등록';
    $replaceURL = '/notice/view.php';
  }

  //오류가 없을경우
  $result = $conn->query($sql);

  //if ($result) {
   $msg = '글 ' .$msgState. ' 완료';
   if(empty($bNo)) {
     $bNo = $conn -> insert_id;
   }
   $replaceURL = '/notice/infview.php?bno='.$bNo;
 
 echo mysqli_error($conn);
 mysqli_close($conn);
 //echo "<script>window.alert($msg);</script>";
 //echo "<script>location.href= $replaceURL;</script>";

 

 // <meta http-equiv="refresh" content="0" url=/" />
// 업로드 파일 확장자 검사 (필요시 확장자 추가)
// if($mimeType=="html" || 
// $mimeType=="htm" || 
// $mimeType=="php" || 
// $mimeType=="js" || 
// $mimeType=="") { 
//  echo("<script> 
//  alert('업로드를 할 수 없는 파일형식입니다.'); 
//  document.location.href = './file_upload.html';    
//  </script>"); 
//  exit;
// } 

?>
<script>

alert("<?php echo $msg?>");
  <?php if($_SESSION['boardtype'] == 'notice'){ ?>
    location.replace("<?php echo '/bbs/bbsList.html'?>");
  <?php } else { ?>
    location.replace("<?php echo '/bbs/bbsListRecruit.html'?>");
  <?php } ?>  


</script>


