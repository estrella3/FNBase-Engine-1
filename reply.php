<?php
require 'function.php';

$sql = "SELECT * from `_reply` WHERE `id` = '$u_id' ORDER BY `created` DESC limit 1";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) < 1){
    $CaValue = 6;
}
while($row = mysqli_fetch_array($result)){
    $time = strtotime($row['created']);
    $CaValue = strtotime(date("Y-m-d H:i:s")) - $time;
}
if($CaValue < 5){
    $Value = 5 - $CaValue;
    echo '<script>alert("'.$Value.'초 뒤에 다시 시도해주세요."); history.back()</script>';
    exit;
}

if(empty($_SESSION['userid'])){
  echo '로그인이 필요합니다.';
  exit;
}
if(empty($_POST['o'])){
    echo '원 댓글 값이 존재하지 않습니다.';
    exit;
}

$m = FnFilter($_POST['m']); //글 번호
$d = FnFilter($_POST['d']); //내용
$o = FnFilter($_POST['o']); //상위 댓글 번호
if($d == ''){
  echo '내용이 없습니다.';
  echo '<script>history.back()</script>';
  exit;
}

$query = "select * from _account where id='".$_SESSION['userid']."'";
  $res = $conn->query($query);
  $rist=mysqli_fetch_assoc($res);
  $e = $rist['email'];
  $n = $rist['name']; //답글 작성자 닉네임

$p = $_SERVER['REMOTE_ADDR']; //아이피
$b = FnFilter($_POST['b']); //상위 게시판
$t  = $_POST['title']; //원글 제목
$z = $_POST['to']; //원 댓글 작성자 닉네임
$p_id = $_POST['id']; //원 댓글 작성자 id

  if($_SESSION['userck'] !== $z){
$linktxt = $fnSite.'/b/'.$b.'/1/'.$m;
$msgtxt = "[$z]님이 [$t]에서 다신 댓글에 [$n]님이 답변하셨습니다.";
$sql = "INSERT INTO `_ment` (`name`, `to`, `read`, `msg`, `link`, `type`) VALUES ('$u_id', '$p_id', '0', '$msgtxt', '$linktxt', 'reply')";
$result = mysqli_query($conn, $sql);}

if(empty($_GET['step'])){
  $step = 1;
  $nal = 'original';
}else{
  $step = $_GET['step'] + 1;
  $nal = 'resp';
}

$sql = "
  INSERT INTO `_reply`
    (`id`, `name`, `$nal`, `content`, `created`, `ip`, `step`, `email`)
    VALUES(
        '{$u_id}',
        '{$n}',
        '{$o}',
        '{$d}',
        NOW(),
        '{$p}',
        '{$step}',
        '{$e}'
    )
";
$result = mysqli_query($conn, $sql);
if($result === false){
  echo 'XSS 스크립트가 포함되어있거나, 데이터베이스에 저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
  error_log(mysqli_error($conn));
} else {
  if(!empty($_SESSION['userid'])){
    $query = "select * from _account where id='".$_SESSION['userid']."'";
    $result = $conn->query($query);
    $row=mysqli_fetch_assoc($result);
    $pt = $row['point'] + 3;
    $sql = "UPDATE _account set point = '{$pt}' where id like '".$u_id."'";
    $result = mysqli_query($conn, $sql);
    if($result === false){
      echo '포인트 적립 실패';
    }
    }
  $sql = "SELECT * FROM `_article` WHERE `id` LIKE '{$m}' and `from` like '{$b}'";
  $result = mysqli_query($conn, $sql);
  while($raw = mysqli_fetch_array($result)){
  $comment = $raw['comment'];
  }
  
  $comment = $comment + 1;
  $sql = "UPDATE `_article` set comment = '{$comment}' where `id` like '{$m}' and `from` like '{$b}'";
  $result = mysqli_query($conn, $sql);
  if($result === false){
    echo '데이터베이스 연결 오류';
  }
  echo '<script>history.back()</script>';
  exit;
}
?>