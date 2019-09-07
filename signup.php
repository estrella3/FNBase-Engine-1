<?php
require "up.php";
?>
<h4>약관 동의</h4>
<p class="text-muted">먼저, 사이트 이용약관에 동의해주세요.</p><hr>
<div class="container">
        <h5><?=$fnSiteName?> 이용약관</h5>
                <iframe src="./desc.html" width="100%" style="background-color: #fff; border: 1px solid gray" height="100%"
                >귀하의 브라우저가 Iframe을 지원하지 않아 내용을 표시할 수 없습니다. <a target="blank" href="./desc.html">새 창에서 보기</a></iframe>
        
        <h5>개인정보 처리 방침</h5>
                <iframe src="./pers.html" width="100%" style="background-color: #fff; border: 1px solid gray" height="100%"
                >귀하의 브라우저가 Iframe을 지원하지 않아 내용을 표시할 수 없습니다. <a target="blank" href="./pers.html">새 창에서 보기</a></iframe>
        <hr>
        <a class="btn btn-primary" style="width:100%" href="step1.php">약관의 내용을 모두 이해하였으며 이에 동의합니다.</a>
        <hr>
        <a class="btn btn-danger text-white" style="width:100%" onclick="alert('약관에 동의하지 않으면 가입이 불가능합니다.')">약관의 내용에 동의하지 않습니다.</a>
</div>

<?php
        include "down.php";
?>