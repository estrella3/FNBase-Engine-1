<?php $a = '
<form method="post" action="step2.php">
<h2>기본 설정</h2>
<label>사이트가 설치될 주소 : <input type="text" name="path" required></label><br>
<label>커뮤니티의 이름 : <input type="text" name="name" required></label><br>
<label>테마 메인 색상 : <input type="text" name="main" required></label><br>
<label>테마 서브 색상 : <input type="text" name="sub" required></label><br>
<label>커뮤니티 설명 : <input type="text" name="desc"></label>
<input type="submit" value="다음 단계로">
</form>'; require './index.php'; ?>