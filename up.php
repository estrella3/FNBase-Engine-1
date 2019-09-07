<?php
#불러오기
$is_file = file_exists('./setting.php');
if($is_file == TRUE){
require 'function.php';
}else{
echo '<script>window.location.href = "/install/index.php";</script>';
exit;
}
if(empty($_SESSION['userid'])){
  $is_logged = FALSE;
}else{
  $is_logged = TRUE;
}
$board = Filt($_GET['b']); 
?>
<!doctype html>
<html lang="ko">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?php echo $fnSiteFab;?>" type="image/x-icon">
    <meta name="description" content="<?php echo $fnSiteDesc;?>">
    <meta name="theme-color" content="<?php echo $fnSiteColor;?>">
    <title><?php echo $fnSiteTitle;?></title>

    <!-- CSS 불러오기 -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css'/>
    <link rel="stylesheet" href="/skins/primary.css"/> <!-- 스킨 적용 -->
  </head>
  <body style="background-color: <?php echo $fnSiteSubColor;?>">

<header style="display: none">
  <!-- 구글 사이트 관리 -->
<?php echo $fnSite_google; ?>
</header>
<!-- 상단바 시작 -->
<nav class="navbar navbar-expand" style="background-color: <?php echo $fnSiteColor;?>">
  <div class="container">
    <a class="navbar-brand text-white" href="<?php echo $fnSite;?>"><?php echo $fnSiteName;?></a>

    <div class="collapse navbar-collapse" id="navbarsExample07">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle text-white" data-toggle="dropdown" aria-haspopupage="true" aria-expanded="false">공설</a>
          <div class="dropdown-menu">
          <a class="dropdown-item" href="/b/board">방명록</a>
            <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="/b/maint">운영실</a>
              <a class="dropdown-item" href="/b/social">사회 담론</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="/b/trash">휴지통</a>
              <a class="dropdown-item" href="/b/recommend">추천 글 목록</a>
              <a class="dropdown-item" href="/r">신고된 글 목록</a>
            </div>
        </li>
        <?php
    if($is_logged === TRUE){
      echo '<li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle text-white" data-toggle="dropdown" aria-haspopupage="true" aria-expanded="false">구독</a>
          <div class="dropdown-menu">';
            
              #구독 처리 코드가 들어갈 자리
              $u_id = $_SESSION['userid'];
              $sql = "SELECT * from `_userSetting` WHERE `id` like '$u_id'";
              $result = mysqli_query($conn, $sql);
              while($row = mysqli_fetch_array($result)){
                  $subList = $row['subList'];
              }
              if(empty($subList)){
                echo '<small><span class="text-muted">구독중인 게시판이 없습니다.</span></small>';
              }
              $arr = explode(",", $subList);
              foreach ($arr as $value) 
              {
                $b = substr($value, 1, -1);
                  $sql = "SELECT * FROM `_board` WHERE `id` like '$b'";
                  $result = mysqli_query($conn, $sql);
                  while($row = mysqli_fetch_array($result)){
                    $n = $row['name'];
                    $s = ' '.$row['suffix'];
                  }
                echo '<a class="dropdown-item" href="/b/'.$b.'">'.$n.$s.'</a>';
              }
            
      echo '</div>
      </li>';
  }
        ?>
        <li class="nav-item">
          <a class="nav-link text-white" href="/p/">전체</a>
        </li>
      </ul>
  </div>
<?php
if($is_logged === TRUE){
      echo '<button class="btn btn-outline-primary" data-toggle="modal" data-target="#Modal" style="background-color: #fff"
      data-toggle="dropdown" id="dropdown08"><a href="#">'.$_SESSION['userck'].'</a></button>';
      echo '<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content"><div class="modal-body"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <a class="gray" href="/user.php?a='.$_SESSION['userid'].'"><button class="btn btn-outline-dark" style="width: 6em"><span class="text-dark">회원 정보</span></button></a>
      <a class="gray" href="/tools.php"><button class="btn btn-outline-primary" style="width: 6em"><span class="text-primary">계정 활용</span></button></a>
      <a href="/login.php?log=out"><button class="btn btn-outline-danger" style="width: 6em"><span class="text-danger">로그아웃</span></button></a>
        </div></div></div></div>';
}else{
      echo '<a class="btn btn-outline-secondary" style="background-color: #fff" href="/login.php">로그인</a>';
}
?>
</nav>
  <main style="background-color: transparent" role="main">
    <div id="topDiv"></div>
<div class="container">
<?php
include_once 'id_ban.php';
?>