<?php
require "up.php";
?>
<p>&shy;</p>
</div>
<div class="container">
                <form method='post' action='find.php' onsubmit="alert('입력한 정보가 확실한가요?')">
                <legend><h3>Step 1</h3></legend>
                <fieldset>
                        <p>아이디: <input style="IME-MODE: disabled" maxlength="12" type="text" name="id" required>('_'문자를 제외한 특수문자를 사용하실 수 없습니다.)</p>
                                <button type="submit">중복 확인</button>
                                <span id="chkt" style="color: gray; font-size: 0.8em">아이디 중복 확인이 필요합니다.</span>
                </fieldset>
                </form>
        </div>
        </div>

<?php
        require "down.php";
?>