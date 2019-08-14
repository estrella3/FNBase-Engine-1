<!DOCTYPE html>
<?php
require 'up.php';
$id =  $_POST['id'];
$b = $_POST['b'];
if(empty($id)){
    exit;
}
$session = $_SESSION['userid'];
$sql = "SELECT * FROM `_article` WHERE `author_id` LIKE '$session' and `from` like '$b' and `id` like '$id'";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);

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
    while($row = mysqli_fetch_array($result)){
        $title = '<b>"'.$row['title'].'"</b>';
    }
echo '<div class="container"><form action="delete.php" method="post">
<h2>삭제하면 되돌릴 수 없습니다!</h2>
<p>'.$title.'을 정말 삭제하시겠습니까? 되돌릴 수 없습니다. <br><button type="button" style="float:right" class="btn-sm btn-light" onclick="history.back()">취소</button>
<button class="btn-sm btn-danger" style="float:right" type="submit">삭제</button></p>
<input type="hidden" value="TRUE" name="confilm">
<input type="hidden" value="'.$id.'" name="id">
<input type="hidden" value="'.$b.'" name="b">
</form></div>';
?>
<section>
<?php

require "down.php";
}}else{
    echo '본인 검증 오류';
}
?>
</body>
</html>