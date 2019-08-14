<?php
require 'function.php';
$id = $_POST['id'];
$b = $_POST['b'];
$ip = $_SERVER['REMOTE_ADDR'];
$mode = Filt($_GET['mode']);

if($mode == 'un'){
    $number = -1;
}else{
    $number = 1;
}

if($mode == 'go'){
    $sql = "SELECT * FROM `_article` WHERE `id` like '$id';";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result)){
        $author = $row['author_id'];
        $stat = $row['stat'];
    }
    if($stat > 7){
        if(mysqli_num_rows($result) == 1){
            $sql = "UPDATE `_article` SET `to`='recommend' WHERE `id` like '$id'";
            $result = mysqli_query($conn, $sql);
             if($result === FALSE){
                 echo '<script>alert("게시글이 이미 이동되었거나 데이터베이스 연결 오류입니다. -- goHead");history.back()</script>';
             }else{
                 echo '<script>alert("추천 글로 선정되셨습니다.");history.go(-2)</script>';
             }
        }
    }
}

if(!isset($id)){
    echo "잘못된 경로";
    echo '<script>history.back()</script>';
    exit;
}

if($id == 0){
    echo "<script>alert('추천할 수 없습니다.'); history.go(-1)</script>";
    exit;
}

$sql = "SELECT * FROM `_push` WHERE `id` = '$id' AND `b` = '$b' AND `type` = 'article'";
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
  INSERT INTO _push
    (id, ip, b, type)
    VALUES(
        '{$id}',
        '{$ip}',
        '{$b}',
        'article'
    )
";
$result = mysqli_query($conn, $sql);
if($result === FALSE){
    echo "데이터베이스 접속 오류";
}
print '<script>history.back()</script>';
}
?>