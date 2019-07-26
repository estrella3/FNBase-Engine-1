<!DOCTYPE html>
<html>
<head>
<?php include 'function.php';?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="<?=$fnSiteFab?>" type="image/x-icon">
  <meta name="description" content="<?=$fnSiteDesc?>">
  <title><?=$fnSiteName?></title>
  <?=$fnSite_google?>
</head>
<body>

<nav>
        <p class="fntop">글 저장중...<br><br /></p>
</nav>
<section>
<div style="background-color: #fff">
<?php

$id = $_POST['id'];
$b = $_POST['b'];

$t = Filt($_POST['title']);
$n = Filt($_POST['description']);

$session = $_SESSION['userid'];
$sql = "SELECT * FROM `_article` WHERE `author_id` LIKE '$session' and `from` like '$b' and `id` like '$id'";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);
if($count === FALSE){
    exit;
}

$sql = "UPDATE `_article` set title = '$t', edited = 1, description = '$n' where `id` like '$id' and `from` like '$b'";
$result = mysqli_query($conn, $sql);
if($result === false){
  echo '데이터베이스에 저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
} else {
  echo "<script>alert('수정 완료!'); history.go(-2)</script>";
}

?>
</div>
</section>
  </body>
</html>