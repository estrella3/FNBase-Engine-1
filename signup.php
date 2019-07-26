<?php
require "up.php";
?>
<script>
var next = '[<a class="links" href="./id.php">다음 단계로</a>]';
</script>
<div style="height: 50px; width: 100%">
<p>&shy;</p>
</div>
<div class="container">
                <h3><?=$fnSiteName?> 이용약관</h3>
<iframe src="./desc.html" width="100%" style="background-color: #fff" height="100%">귀하의 브라우저가 Iframe을 지원하지 않아 내용을 표시할 수 없습니다.</iframe>
<h3>개인정보 처리 방침</h3>
<iframe src="./pers.html" width="100%" style="background-color: #fff" height="100%">귀하의 브라우저가 Iframe을 지원하지 않아 내용을 표시할 수 없습니다.</iframe>
<p><label for="agree"><input id="agree" onclick="document.getElementById('loca').innerHTML = next;" type="checkbox">약관의 내용을 모두 이해하였으며 이에 동의합니다.</label> <span id="loca"></span></p>
</div>
</div>

<?php
        include "down.php";
?>