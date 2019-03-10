<?php
$is_file = file_exists('./setting.php');
if($is_file == TRUE){
include 'setting.php';
}else{
echo '<script>window.location.href = "./install/index.php";</script>';
}
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
    <link rel="stylesheet" href="assets/minified/themes/default.min.css" />
<script src="assets/minified/sceditor.min.js"></script>
<link href="/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    <link href="navbar.css" rel="stylesheet">
  </head>
  <body style="background-color: <?php echo $fnSiteSubColor;?>">
<!-- 사전 정의 -->
<header style="display: none">
  <!-- 구글 사이트 관리 -->
<?php echo $fnSite_google; ?>
 <!-- PHP DB 연결 및 세션 시작 -->
                                <?php
session_start();
$conn = mysqli_connect("$fnSiteDB", "$fnSiteDBuser", "$fnSiteDBpw", "$fnSiteDBname");
//    PHP 차단 여부 체크
if(!empty($_SESSION['userid'])){
  $sql = "SELECT * FROM `_account` WHERE `id` LIKE '".$_SESSION['userid']."'";
  $result = mysqli_query($conn, $sql);
  while($raw = mysqli_fetch_array($result)){
  $stat = $raw['ban'];
  }
  }else{
  $stat = '0';
  }
if($stat == 1){
  echo '<div class="jumbotron"><p class="display-4">당신은 차단당했습니다...<br>';
  echo '<a href="#">차단소명 하러가기</a></p></div>';
}
                                ?>
</header>
<!-- 상단바 시작 -->
<nav class="navbar navbar-expand" style="background-color: <?php echo $fnSiteColor;?>">
  <div class="container">
    <a class="navbar-brand text-white" href="<?php echo $fnSite;?>"><?php echo $fnSiteName;?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
      <span class="text-white">메뉴</span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExample07">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item dropdown">
          <?=$fnSiteNav1?>
        </li>
        <li class="nav-item dropdown">
          <?=$fnSiteNav2?>
        </li>
      </ul>
  </div>
<?php
if(!empty($_SESSION['userid'])){
      echo '<button class="btn btn-outline-primary" data-toggle="modal" data-target="#Modal" style="background-color: #fff"
      data-toggle="dropdown" id="dropdown08"><a href="#">'.$_SESSION['userck'].'</a></button>';
      echo '<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content"><div class="modal-body"><p align="right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button></p><p align="center">
        <button class="btn btn-outline-primary" style="width: 9em"><a href="login.php?log=out">로그아웃</a></button><br><br>
        <button class="btn btn-outline-dark" style="width: 9em"><a style="color: gray" href="user.php?mode=account">회원 정보 수정</a></button>
        </p></div></div></div></div>';
}else{
      echo '<a class="btn btn-outline-primary" style="background-color: #fff" href="login.php">로그인</a>';
}
?>
</nav>
  <main style="background-color: transparent" role="main">
    <div style="height: 40px; width: 100%"></div>
  <div class="container">