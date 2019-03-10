<!DOCTYPE html>
<?php
include 'up.php';
session_start();
$conn = mysqli_connect("$fnSiteDB", "$fnSiteDBuser", "$fnSiteDBpw", "$fnSiteDBname");
$id =  $_POST['id'];
$b = $_POST['b'];
$session = $_SESSION['userid'];
$sql = "SELECT * FROM `_article` WHERE `author_id` LIKE '$session' and `from` like '$b' and `id` like '$id'";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);
if($session == 'admin'){
    $count = 1;
}
if($count == 1){
if($_POST['confilm'] == "TRUE"){

$sql = "DELETE FROM `_article` WHERE `id` LIKE '$id' and `from` like '$b'";
$result = mysqli_query($conn, $sql);
if($result === FALSE){
    print '삭제 실패, 데이터베이스 수정 불가';
}else{
    echo '<script>history.go(-3)</script>';
}
}else{
    include 'up.php';


echo '<div class="container"><form action="delete.php" method="post">
<h2>삭제하면 되돌릴 수 없습니다!</h2>
<p>정말 삭제하시겠습니까?<button type="button" class="btn-sm btn-warning" onclick="history.back()">뒤로가기</button><button class="btn-sm btn-danger" type="submit">삭제하기</button></p>
<input type="hidden" value="TRUE" name="confilm">
<input type="hidden" value="'.$id.'" name="id">
<input type="hidden" value="'.$b.'" name="b">
</form></div>';
?>
<section>
<?php

include "down.php";
}}else{
    echo '본인 검증 오류';
}
?>
</body>
</html>