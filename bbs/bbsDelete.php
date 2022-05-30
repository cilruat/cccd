<?php
    session_start(); // 세션
    include $_SERVER["DOCUMENT_ROOT"]."/dbconnect.php";

    //$_POST['bno']이 있을 때만 $bno 선언

    if(isset($_POST['bno'])) {
        $bNo = $_POST['bno'];
    }

    //글 삭제
    if(isset($bNo)) {
        if($_SESSION['boardtype']=='notice') {
            $sql = 'select count(*) as cnt from board where idx = '.$bNo;
        } else {
            $sql = 'select count(*) as cnt from recruit_board where idx = '.$bNo;
        }

        $result = $conn -> query($sql);
        $row = $result->fetch_assoc();

        if($_SESSION['boardtype']=='notice') {
            $sqlfile = 'SELECT * FROM board where idx = '.$bNo;
        } else {
            $sqlfile = 'SELECT * FROM recruit_board where idx = '.$bNo;
        }
       
        $resultfile = $conn->query($sqlfile);
        $rowfile = $resultfile->fetch_assoc();
        echo "<script>console.log('".$rowfile['covfile']."');</script>";
        //첨부파일이 있으면 첨부파일도 삭제
        if($rowfile['covfile'] != null){
            echo "<script>console.log('삭제삭제삭제');</script>";
            echo "<script>console.log('".$file['tmp_name']."');</script>";
            unlink("uploads/".$rowfile['covfile']);
        }

        //삭제 후 auto_increment 재설정 함. 
        //재설성을 하지 않으면 삭제한 idx 번호가 그대로 남아있기 때문에 재설정 필요.
        if($row['cnt']) {
            if($_SESSION['boardtype']=='notice') {
                $sql = 'delete from board where idx ='.$bNo;
            } else {
                $sql = 'delete from recruit_board where idx ='.$bNo;
            }
            
            $result = $conn->query($sql);
            if($_SESSION['boardtype']=='notice') {
                $sql = 'alter table board AUTO_INCREMENT=1;';
            } else {
                $sql = 'alter table recruit_board AUTO_INCREMENT=1;';
            }
           
            $result = $conn->query($sql);
            $sql = 'set @COUNT = 0;';
            $result = $conn->query($sql);
            if($_SESSION['boardtype']=='notice') {
                $sql = 'update board set idx = @COUNT:=@COUNT+1;';
            } else {
                $sql = 'update recruit_board set idx = @COUNT:=@COUNT+1;';
            }            
            $result = $conn->query($sql);
        }
    }

    //$result = $conn->query($sql);
    if($result){
        $msg = '정상적으로 글이 삭제되었습니다.';
        if($_SESSION['boardtype']=='notice') {
            $replaceURL = '/bbs/bbsList.html';
        } else {
            $replaceURL = '/bbs/bbsListRecruit.html';
        }        
    }
    else{
        $msg = '글을 삭제하지 못했습니다.';
    ?>
        <script>
            alert("<?php echo $msg?>");
            history.back();
        </script>

        <?php
            exit;
    }
?>
<script>
    alert("<?php echo $msg?>");
    location.replace("<?php echo $replaceURL?>");
</script>