<?php
	 include $_SERVER["DOCUMENT_ROOT"]."/dbconnect.php";
	 
	
	/* 페이징 시작 */
	//페이지 get 변수가 있다면 받아오고, 없다면 1페이지를 보여준다.
	if(isset($_GET['page'])) {
		$page = $_GET['page'];
	} else {
		$page = 1;
	}
	
	if($_SESSION['boardtype']=='notice') {
		$sql = 'select count(*) as cnt from board';
		$listHtml = '/bbs/bbsList.html';
	} else {
		$sql = 'select count(*) as cnt from recruit_board';
		$listHtml = '/bbs/bbsListRecruit.html';
	}

	
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	
	$allPost = $row['cnt']; //전체 게시글의 수
	
	$onePage = 10; // 한 페이지에 보여줄 게시글의 수.
	$allPage = ceil($allPost / $onePage); //전체 페이지의 수
	
	if($page < 1 && $page > $allPage) {
?>
		<script>
			alert("존재하지 않는 페이지입니다.");
			history.back();
		</script>
<?php
		exit;
	}
	
	$oneSection = 10; //한번에 보여줄 총 페이지 개수(1 ~ 10, 11 ~ 20 ...)
	$currentSection = ceil($page / $oneSection); //현재 섹션
	$allSection = ceil($allPage / $oneSection); //전체 섹션의 수
	
	$firstPage = ($currentSection * $oneSection) - ($oneSection - 1); //현재 섹션의 처음 페이지
	
	if($currentSection == $allSection) {
		$lastPage = $allPage; //현재 섹션이 마지막 섹션이라면 $allPage가 마지막 페이지가 된다.
	} else {
		$lastPage = $currentSection * $oneSection; //현재 섹션의 마지막 페이지
	}
	
	$prevPage = (($currentSection - 1) * $oneSection); //이전 페이지, 11~20일 때 이전을 누르면 10 페이지로 이동.
	$nextPage = (($currentSection + 1) * $oneSection) - ($oneSection - 1); //다음 페이지, 11~20일 때 다음을 누르면 21 페이지로 이동.
	
	$paging = '<ul>'; // 페이징을 저장할 변수
	
	//첫 페이지가 아니라면 처음 버튼을 생성
	if($page != 1) { 
		$paging .= '<li class="page page_start"><a href="' . $listHtml . '?page=1">first</a></li>';
	}
	//첫 섹션이 아니라면 이전 버튼을 생성
	if($currentSection != 1) { 
		$paging .= '<li class="page page_prev"><a href="' . $listHtml . '?page=' . $prevPage . '">prev</a></li>';
	}
	
	for($i = $firstPage; $i <= $lastPage; $i++) {
		if($i == $page) {
			$paging .= '<li class="page current">' . $i . '</li>';
		} else {
			$paging .= '<li class="page"><a href="' . $listHtml . '?page=' . $i . '">' . $i . '</a></li>';
		}
	}
	
	//마지막 섹션이 아니라면 다음 버튼을 생성
	if($currentSection != $allSection) { 
		$paging .= '<li class="page page_next"><a href="' . $listHtml . '?page=' . $nextPage . '">next</a></li>';
	}
	
	//마지막 페이지가 아니라면 끝 버튼을 생성
	if($page != $allPage) { 
		$paging .= '<li class="page page_end"><a href="' . $listHtml . '?page=' . $allPage . '">last</a></li>';
	}
	$paging .= '</ul>';
	
	/* 페이징 끝 */
	
	
	$currentLimit = ($onePage * $page) - $onePage; //몇 번째의 글부터 가져오는지
	$sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage; //limit sql 구문
	
	if($_SESSION['boardtype']=='notice') {
		$sql = 'select * from board order by idx desc' . $sqlLimit; //원하는 개수만큼 가져온다. (0번째부터 20번째까지
	} else {
		$sql = 'select * from recruit_board order by idx desc' . $sqlLimit; //원하는 개수만큼 가져온다. (0번째부터 20번째까지
	}
	$result = $conn->query($sql);
?>