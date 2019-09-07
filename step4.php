<?php
require "up.php";

$code = Filt($_GET['code']);
$num = Filt($_GET['num']);

$sql = "SELECT * FROM `_mailAuth` WHERE `code` like '$code' and `num` like '$num'";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) == 1){
    echo '<h5>이메일 인증이 완료되었습니다.</h5><hr>';

while($row = mysqli_fetch_array($result)){
    $id = $row['id'];
    $ip = $row['ip'];
    $em = $row['email'];
}
?>

<h4>기본 정보 입력</h4>
<p class="text-muted">가입에 필요한 기본 정보를 입력해주세요.</p><hr>
<form action="step5.php" method="post">
<legend><h3>Step 4</h3></legend>
<fieldset>
<div class="form-group">
    <label>아이디</label>
    <input type="id" class="form-control" value="<?=$id?>" name="id" readonly>
  </div>
  <div class="form-group">
    <label>닉네임</label>
    <input type="text" class="form-control" placeholder="" name="name" required>
  </div>
  <div class="form-group">
    <label>이메일</label>
    <input type="email" class="form-control" value="<?=$em?>" name="email" readonly>
  </div>
  <div class="form-group">
    <label>비밀번호</label>
    <input type="password" class="form-control" placeholder="6자리 이상" minlength="6" name="pwd" required>
  </div>
  <div class="form-group">
    <label>비밀번호 확인</label>
    <input type="password" class="form-control" placeholder="6자리 이상" minlength="6" name="pwd_check" required>
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">소개글</label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="intro"></textarea>
  </div>
  <input type="hidden" name="ip" value="<?=$ip?>">
  <input type="submit" class="btn btn-primary" style="float:right" value="다음 단계로">
</fieldset>
</form>
<?php
}else{
    echo '인증에 실패하였습니다.';
}
        require "down.php";
?>