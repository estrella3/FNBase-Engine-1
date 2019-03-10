<?php
include "up.php";
$id = $_POST['id'];
?>
<div style="height: 50px; width: 100%">
<p>&shy;</p>
</div>
<div class="container">
                <form method='post' action='keepgoing.php' onsubmit="alert('비밀번호나 아이디를 잊지는 않으셨는지 다시 한번 확인해주세요, 감사합니다.')">
                <legend><h3>Step 2</h3></legend>
                <fieldset>
                        <p>아이디: <input type="text" name="id" value="<?php echo $id;?>" readonly></p>
                        <p>닉네임: <input maxlength="12" type="text" name="nickname"></p>
                        <p>비밀번호: <input minlength="6" maxlength="20" type="password" name="pw"></p>
                        <span style="color: gray; font-size: 0.8em">비밀번호는 6자 이상, 20자 미만으로 작성해주세요.</span>
                        <p>전자우편 주소: <input type="email" name="email"></p>
                        <span style="color: gray; font-size: 0.8em">이메일은 중요하게 사용되오니 실제 사용하는 이메일을 입력하여주세요.<br></span>
                        <input type="hidden" name="UIP" value="<?php echo $_SERVER['REMOTE_ADDR']?>">
                        <button type="submit">작성 완료!</button>
                </fieldset>
                </form>
        </div>
</div>
<?php
        include "down.php";
?>