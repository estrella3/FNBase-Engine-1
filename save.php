<!DOCTYPE html>
<?php include 'setting.php'; include 'function.php';?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="<?=$fnSiteFab?>" type="image/x-icon">
  <meta name="description" content="<?=$fnSiteDesc?>">
  <title><?=$fnSiteName?></title>
  <link rel="stylesheet" href="layout.css">
  <?=$fnSite_google?>
</head>
<body>
<?php
$board = "board";
		$getturn = $_POST['b'];
		if(isset($getturn)){
			$board = $getturn;
    }
    
$conn = mysqli_connect("$fnSiteDB", "$fnSiteDBuser", "$fnSiteDBpw", "$fnSiteDBname");
?>
<nav>
        <p class="fntop">글 저장중...<br><br /></p>
</nav>
<section>
<div style="background-color: #fff">
<?php
if(!empty($_POST['title'])){
  $jemok = $_POST['title'];
  $conf = 1;
}else{
  $errormsg = "제목이 없고, ";
  $rconf = 1;
}

if(!empty($_POST['description'])){
  $desc = $_POST['description'];
  $conf = $conf + 1;
}else{
  $errormsg = "내용이 없으며, ";
  $rconf = $rconf + 1;
}

if(!empty($_POST['id'])){
  $id = $_POST['id'];
  $conf = $conf + 1;
}else{
  $errormsg = "작성자 아이디가 없어요.&nbsp;";
  $rconf = $rconf + 1;
}

if(!empty($_POST['pw'])){
  $pw = $_POST['pw'];
  $conf = $conf + 1;
}else{
  $errormsg = "작성자 비밀번호가 없네요,";
  $rconf = $rconf + 1;
}

if ($rconf == 4){
  echo "글이 작성되지 않았습니다.";
}
$UIP = $_SERVER["REMOTE_ADDR"];

if(!isset($_POST['islogged'])){
  $author = "_anon";
}else{
  session_cache_expire(20160);
  session_start();
  $pw = $_SESSION['userpw'];
  $id = $_SESSION['userck'];
  $author = $_SESSION['userid'];
}

if ($conf == 4){
  $jemok = Filt($jemok);
  $desc = Filt($desc);
  $author = Filt($author);
  $id = Filt($id);
$sql = "
  INSERT INTO `_article`
    (`title`, `description`, `from`, `to`, `created`, `author_id`, `name`, `stat`, `view`, `UIP`)
    VALUES(
        '{$jemok}',
        '{$desc}',
        '{$board}',
        '{$board}',
        NOW(),
        '{$author}',
        '{$id}',
        '0',
        '0',
        '{$UIP}'
    )
";
$result = mysqli_query($conn, $sql);
if($result === false){
  echo 'XSS 스크립트가 포함되어있거나, 데이터베이스에 저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
  error_log(mysqli_error($conn));
} else {
  setcookie('writed', 'yes', time() + 50);
  if(!empty($_SESSION['userid'])){
  $query = "select * from _account where id='".$_SESSION['userid']."'";
  $result = $conn->query($query);
  $row=mysqli_fetch_assoc($result);
  $pt = $row['point'] + 10;
  $sql = "UPDATE _account set point = '{$pt}' where id like '".$_SESSION['userid']."'";
  $result = mysqli_query($conn, $sql);
  if($result === false){
    echo '포인트 적립 실패';
  }
  }
  echo '<script>location.replace("./index.php?page=1&b='.$board.'")</script>';
}
}elseif($rconf == 4){
  echo ' <a href="/write.php">뒤로가기</a>';
}else{
  echo $errormsg;
  echo '다시 확인해주세요.';
}

if($_COOKIE['writed'] == "yes"){
  setcookie('again', 'yes', time() + 80);
}

if($_COOKIE['again'] == "yes"){
  setcookie('worried', 'yes', time() + 80);
}

if($_COOKIE['worried'] == "yes"){
  setcookie('dont', 'yes', time() + 90);
}

?>
</div>
</section>
<footer>
        <p class="fnbottom">Contact : admin@fnbase.xyz</p>
</footer>
  </body>
</html>