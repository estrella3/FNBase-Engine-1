<?php
$path = $_POST['path'];
$name = $_POST['name'];
$main = $_POST['main'];
$sub = $_POST['sub'];
$desc = $_POST['desc'];

$a = '<form method="post" action="step3.php">
<h2>데이터베이스 연결 설정</h2>
<label>데이터베이스 서버 주소 : <input type="text" name="db" required></label><br><span style="color: gray">보통 localhost인 경우가 많습니다.</span><br>
<label>데이터베이스 이름 : <input type="text" name="dbname" required></label><br>
<label>데이터베이스 사용자 : <input type="text" name="dbuser" required></label><br>
<label>데이터베이스 사용자 비밀번호 : <input type="password" name="dbpw" required></label>
<input type="hidden" name="path" value="<?php echo $path;?>">
<input type="hidden" name="main" value="<?php echo $main;?>">
<input type="hidden" name="sub" value="<?php echo $sub;?>">
<input type="hidden" name="desc" value="<?php echo $desc;?>">
<input type="hidden" name="name" value="<?php echo $name;?>">
<p>값을 모를 경우 호스트 업체에 문의하거나 매뉴얼을 참고하세요.</p>
<input type="submit" value="다음 단계로">
</form>';
require './index.php';
?>