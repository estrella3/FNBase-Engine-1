<?php
$path = $_POST['path'];
$name = $_POST['name'];
$main = $_POST['main'];
$sub = $_POST['sub'];
$desc = $_POST['desc'];
$db = $_POST['db'];
$dbuser = $_POST['dbuser'];
$dbpw = $_POST['dbpw'];
$dbname = $_POST['dbname'];


$connect = new mysqli($db, $dbuser, $dbpw, $dbname);
 
if($connect->connect_errno){
    echo '데이터베이스 연결에 실패하였습니다.<br>이전 단계로 돌아가서 다시 입력해주세요.
    <button type="button" onclick="history.back()">뒤 로 가 기</button>';
    exit;
}else{
    echo '데이터베이스 연결 성공!'.'<br>';
}

$a = '<form method="post" action="laststep.php">
<h2>상세 설정</h2>
<label>사이트 바닥글 (푸터) : <textarea name="footer" required></textarea></label><br>
<input type="hidden" name="path" value="<?php echo $path;?>">
<input type="hidden" name="main" value="<?php echo $main;?>">
<input type="hidden" name="sub" value="<?php echo $sub;?>">
<input type="hidden" name="desc" value="<?php echo $desc;?>">
<input type="hidden" name="name" value="<?php echo $name;?>">
<input type="hidden" name="db" value="<?php echo $db;?>">
<input type="hidden" name="dbuser" value="<?php echo $dbuser;?>">
<input type="hidden" name="dbpw" value="<?php echo $dbpw;?>">
<input type="hidden" name="dbname" value="<?php echo $dbname;?>">
<input type="submit" value="다음 단계로">
</form>';
require './index.php';
?>