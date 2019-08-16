<!DOCTYPE html>
<html>
<?php require 'function.php';?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="<?=$fnSiteFab?>" type="image/x-icon">
  <meta name="description" content="<?=$fnSiteDesc?>">
  <title><?=$fnSiteTitle?></title>
  <?=$fnSite_google?>
</head>
<body>
<?php
?>
<?php
if(!empty($_POST['description'])){
  $desc = $_POST['description'];
  $conf = 1;
}else{
  $errormsg = "내용이 비어있습니다. ";
  $rconf = 1;
}
if(!empty($_POST['id'])){
  $id = $_POST['id'];
  $conf = $conf + 1;
}else{
  $errormsg = "세션에 문제가 생겼거나 잘못된 경로입니다. ";
  $rconf = $rconf + 1;
}

if ($rconf == 2){
  echo "댓글이 작성되지 않았습니다.";
}

$UIP = $_POST['ip'];

$desc = Filt($desc);
if(!isset($_POST['islogged'])){
  $id = "익명_".HTD($id);
  $pw = HTD($pw);
}else{
  session_start();
  $pw = $_SESSION['userpw'];
  $name = $_SESSION['userck'];
  $id = $_SESSION['userid'];
}

if($_POST['s'] == 'true'){ #비밀글
  $s = 1;
}else{
  $s = 0;
}

if ($conf == 2){
$board = $_POST['b'];
$origin = $_POST['origin'];

$sql = "
  INSERT INTO `_comment`
    (board, original, id, name, content, stat, created, blame, secret, reply)
    VALUES(
        '{$board}',
        '{$origin}',
        '{$id}',
        '{$name}',
        '{$desc}',
        '0',
        NOW(),
        '0',
        '{$s}',
        '0'
    )
";
$result = mysqli_query($conn, $sql);
if($result === false){
  echo '데이터베이스에 저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
  error_log(mysqli_error($conn));
} else {
  if(!empty($_SESSION['userid'])){
    $query = "select * from _account where id='".$_SESSION['userid']."'";
    $result = $conn->query($query);
    $row=mysqli_fetch_assoc($result);
    $pt = $row['point'] + 3;
    $sql = "UPDATE _account set point = '{$pt}' where id like '".$_SESSION['userid']."'";
    $result = mysqli_query($conn, $sql);
    if($result === false){
      echo '포인트 적립 실패';
    }
    }
        $rt = $_POST['title']; #멘션 만드는 부분
        $ru = $_POST['user'];
        if(empty($ru)){
          echo '오류 발생!';
          exit;
        }else{
          if($_SESSION['userid'] !== $ru){
        $linktxt = $fnSite.'/b/'.$board.'/1/'.$origin;
        $msgtxt = "[$rt]에 [$name]님이 댓글을 다셨습니다.";
        $sql = "INSERT INTO `_ment` (`name`, `to`, `read`, `msg`, `link`, `type`) VALUES ('$id', '$ru', '0', '$msgtxt', '$linktxt', 'comment')";
        $result = mysqli_query($conn, $sql);}
  $href = $_SERVER["HTTP_REFERER"];
  echo "<script>window.location.replace('$href');</script>";
        }
}
}elseif($rconf == 2){
  echo ' <a href="/write.php">뒤로가기</a>';
}else{
  echo $errormsg;
  echo '다시 확인해주세요.';
}

$sql = "SELECT * FROM `_article` WHERE `id` LIKE '{$origin}' and `from` like '{$board}'";
$result = mysqli_query($conn, $sql);
while($raw = mysqli_fetch_array($result)){
$comment = $raw['comment'];
}

$comment = $comment + 1;
$sql = "UPDATE `_article` set comment = '{$comment}' where `id` like '{$origin}' and `from` like '{$board}'";
$result = mysqli_query($conn, $sql);
if($result === false){
  echo '데이터베이스 연결 오류';
}

?>
  </body>
</html>