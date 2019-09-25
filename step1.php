<?php
require "up.php";
?>
<h4>기본 정보 입력</h4>
<p class="text-muted">가입에 필요한 기본 정보를 입력해주세요.</p><hr>
<div class="container">
                <form action="step2.php" method="post" id="form">
                <legend><h3>Step 1</h3></legend>
                <fieldset>
                        <p>아이디 : <input class="form-control-sm" maxlength="12" type="text" id="iiii" name="id" required>
                        <input type="hidden" id="chk" name="chk" value="false">
                        <button type="button" onclick="checkid()">중복 확인</button><br>
                        <span class="text-muted">영문 / 숫자 / 공백 문자 (_) 만 사용 가능합니다.</span></p><span id="res">(허용되지 않는 문자는 제거됩니다.)</span><br>
                        <button type="button" class="btn btn-primary" style="display:none" onclick="aaaa()" id="next">다음 단계로</button>
                </fieldset>
                </form>
        </div>
<script>
function aaaa(){
        var con = confirm("입력하신 정보가 확실한가요?");
        if(con == true){
        alert("이메일 인증 페이지로 이동합니다.");
        document.getElementById('form').submit();
        }
}
function checkid() {
        $.ajax({
                url: "idCheck.php",
                type: "post",
                data: $('form').serialize(),
        }).done(function(data) {
                res = document.getElementById('res');
                if(data == 1){
                        if(document.getElementById('iiii').value.length > 0){
                        res.innerHTML = ' 사용 가능한 아이디입니다.';
                        res.style = 'color:green';
                        document.getElementById('chk').value = 'yes';
                        document.getElementById('next').style = "width:100%";
                        }
                }else if(data == 0){
                        res.innerHTML = ' 이 아이디는 이미 등록되었거나 사용이 불가능합니다.';
                        res.style = 'color:red';
                }
        });
}
</script>
<?php
        require "down.php";
?>