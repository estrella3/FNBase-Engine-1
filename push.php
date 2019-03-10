<?php
include 'setting.php';
$id = $_POST['id'];
$b = $_POST['b'];
$ip = $_POST['ip'];
$mode = $_GET['mode'];

if($mode == 'un'){
    $number = -1;
}else{
    $number = 1;
}

$conn = mysqli_connect("$fnSiteDB", "$fnSiteDBuser", "$fnSiteDBpw", "$fnSiteDBname");

if(!isset($id)){
    echo "잘못된 경로";
    echo '<script>history.back()</script>';
    exit;
}

if($id == 0){
    echo "<script>alert('게시판 이용 안내는 추천할 수 없습니다.'); history.go(-1)</script>";
    exit;
}

$sql = "SELECT * FROM _log WHERE `id` = '$id' AND `b` = '$b'";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result)){
if($row['ip'] == $ip){
    echo "<script>alert('추천 / 비추천은 한 게시글 당 한번만 가능합니다!'); history.go(-1)</script>";
    exit;
}
}

$sql = "SELECT * FROM `_article` WHERE `id` LIKE '$id' and `from` like '$b'";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result)){
$stat = $row['stat'];
}

$stat = $stat + $number;

$sql = "UPDATE `_article` set stat = $stat WHERE `id` LIKE '$id' and `from` like '$b'";
$result = mysqli_query($conn, $sql);
if($result === FALSE){
    echo "데이터베이스 접속 오류";
}else{
    $sql = "
  INSERT INTO _log
    (id, ip, b)
    VALUES(
        '{$id}',
        '{$ip}',
        '{$b}'
    )
";
$result = mysqli_query($conn, $sql);
if($result === FALSE){
    echo "데이터베이스 접속 오류";
}
print '<script>history.back()</script>';
}
?>