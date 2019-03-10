<!DOCTYPE html>
<html>
<?php include 'setting.php'; ?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="<?=$fnSiteFab?>" type="image/x-icon">
  <meta name="description" content="<?=$fnSiteDesc?>">
  <title><?=$fnSiteTitle?></title>
  <link rel="stylesheet" href="layout.css">
  <?=$fnSite_google?>
</head>
<body>
<?php    
$conn = mysqli_connect("$fnSiteDB", "$fnSiteDBuser", "$fnSiteDBpw", "$fnSiteDBname");
?>
<nav>
        <p class="fntop">댓글 저장중...<br><br /></p>
</nav>
<section>
<div style="background-color: #fff">
<?php
if(!empty($_POST['description'])){
  $desc = $_POST['description'];
  $conf = 1;
}else{
  $errormsg = "내용이 없고, ";
  $rconf = 1;
}
if(!empty($_POST['id'])){
  $id = $_POST['id'];
  $conf = $conf + 1;
}else{
  $errormsg = "작성자 아이디가 없어요.&nbsp;";
  $rconf = $rconf + 1;
}

if ($rconf == 2){
  echo "댓글이 작성되지 않았습니다.";
}

$UIP = $_POST['ip'];

function HTD($argu){
  $argu = htmlspecialchars($argu);
  return $argu;
}

$desc = HTD($desc);
if(!isset($_POST['islogged'])){
  $id = "익명_".HTD($id);
  $pw = HTD($pw);
}else{
  session_start();
  $pw = $_SESSION['userpw'];
  $id = $_SESSION['userck'];
  $name = $_SESSION['userid'];
}

if ($conf == 2){
$board = $_POST['b'];
$origin = $_POST['origin'];

$desc = str_replace('"', '&quot;', $desc);
$desc = str_replace("'", '&#39;', $desc);
$desc = str_replace(';', '&#59;', $desc);
$desc = str_replace('`', '&#96;', $desc);
$desc = str_replace('(', '&#40;', $desc);
$desc = str_replace(')', '&#41;', $desc);
$desc = str_replace('<', '&lt;', $desc);
$desc = str_replace('s', '&#115;', $desc);
$desc = str_replace('o', '&#111;', $desc);
$desc = str_replace('e', '&#101;', $desc);
$desc = str_replace('t', '&#116;', $desc);
$desc = str_replace('S', '&#83;', $desc);
$desc = str_replace('O', '&#79;', $desc);
$desc = str_replace('E', '&#69;', $desc);
$desc = str_replace('T', '&#84;', $desc);

$sql = "
  INSERT INTO `_comment`
    (board, original, id, name, content, stat, created, ip)
    VALUES(
        '{$board}',
        '{$origin}',
        '{$name}',
        '{$id}',
        '{$desc}',
        '0',
        NOW(),
        '{$ip}'
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
  echo "<script>history.back()</script>";
}
}elseif($rconf == 2){
  echo ' <a href="write.php">뒤로가기</a>';
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
</div>
</section>
<div id="yeobaek"></div>
<footer>
        <p class="fnbottom">Contact : admin@fnbase.xyz</p>
</footer>
  <section id="scripts">
<script>


var agent = navigator.userAgent.toLowerCase();

function menu_click() {
	alert("준비중입니다.");
}

if ( (navigator.appName == 'Netscape' && navigator.userAgent.search('Trident') != -1) || (agent.indexOf("msie") != -1) ) {

alert("이 웹사이트는 일부 구형 브라우저에서는 오류가 발생할 수 있습니다. 최신 버전 Chrome이나 Firefox를 사용하세요.");

}
</script>
</section>
  </body>
</html>