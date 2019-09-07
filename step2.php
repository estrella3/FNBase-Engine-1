<?php
require "up.php";
if($_POST['chk'] == 'yes'){
    $id = $_POST['id'];
}
?>
<h4>기본 정보 입력</h4>
<p class="text-muted">가입에 필요한 기본 정보를 입력해주세요.</p><hr>
<div class="container">
                <form action="step3.php" method="post">
                <legend><h3>Step 2</h3></legend>
                <fieldset>
                        <p><label>아이디 : <input class="form-control-sm" maxlength="12" type="text" id="id" name="id" value="<?=$id?>" readonly></label>
                        <input type="hidden" id="chk" value="false"><br>
                        <label>이메일 : <input class="form-control-sm" type="email" name="email" required></label>
                        <button type="submit">인증하기</button><br>
                        <span class="text-muted">'인증하기' 버튼을 누르신 후, 귀하의 메일함에서 인증 메일의 링크를 클릭하세요.</span></p><br>
                </fieldset>
                </form>
        </div>
<?php
        require "down.php";
?>