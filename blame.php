<?php
require 'up.php';
$b = Filt($_POST['from']);
$id = Filt($_POST['id']);
$repOpt = Filt($_POST['repOpt']);
$userid = $_SESSION['userid'];
$uip = $_SERVER['REMOTE_ADDR'];

$sql = "SELECT * from `_report` WHERE `ip` = '$uip' ORDER BY `time` DESC limit 1";
$result = mysqli_query($conn, $sql);
date_default_timezone_set('Asia/Seoul');
if($result === FALSE){
    $CaValue = 61;
}
while($row = mysqli_fetch_array($result)){
    $time = strtotime($row['time']);
    $CaValue = strtotime(date("Y-m-d H:i:s")) - $time;
}
if($CaValue < 60){
    $Value = 60 - $CaValue;
    echo '<script>alert("'.$Value.'초 뒤에 다시 시도해주세요."); history.back()</script>';
    exit;
}

if(empty($_SESSION['userid'])){
	echo '<script>alert("로그인 후 이용 바랍니다.");history.go(-1)</script>';
	exit;
}else{
  if(empty($_POST['id'])){
  $sql = "SELECT * FROM `_report` ORDER BY `num` DESC";
  $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)){
        if($row['reason'] == 1){
          $reason = '선정성·폭력성';
        }
        if($row['reason'] == 2){
          $reason = '광고';
        }
        if($row['reason'] == 3){
          $reason = '극단적';
        }
        if($row['reason'] == 4){
          $reason = '친목 조장';
        }
        if($row['reason'] == 5){
          $reason = '반사회성';
        }
        if($row['reason'] == 6){
          $reason = '명예 훼손 外 법 위반';
        }
        if($row['reason'] == 7){
          $reason = '기타 사유';
        }
        echo '<table class="table"><tr><td>'.$row['num'].'</td>';
        echo '<td><a href="'.$row['board'].'-'.$row['id'].'.base">'.$reason.'</a></td>';
        echo '<td>'.$row['time'].'</td><td>'.$row['userid'].'</td></tr></table>';
    }
  }else{
    $sql = "INSERT INTO `_report` (`reason`, `board`, `id`, `time`, `ip`, `userid`) VALUES ('$repOpt', '$b', '$id', NOW(), '$uip', '$userid');";
    $result = mysqli_query($conn, $sql);
    if($result === FALSE){
      echo '오류!';
    }else{
      echo '<script>history.go(-1)</script>';
    }
  }
}
require 'down.php';
?>