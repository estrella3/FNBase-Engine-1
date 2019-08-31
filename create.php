<?php
require 'up.php';
if(!$_SESSION['userid']){
    echo '<script>alert("회원가입 후 이용해주세요.");history.back();</script>';
    exit;
}
echo '<h4>게시판 생성</h4><hr>';
?>

<form action="/create_p.php" method="post">
  <div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label">이름</label>
    <div class="col-6">
      <input type="text" class="form-control" placeholder="게시판 이름" id="name" name="name" required="required">
    </div>
    <div class="col">
      <input type="text" class="form-control" placeholder="유형 (선택사항)" id="Suffix" name="suffix">
    </div>
  </div>
  <div class="form-group row">
    <label for="Exp" class="col-sm-2 col-form-label">설명</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="Exp" name="exp" placeholder="간략하게 게시판을 설명해주세요.">
    </div>
  </div>
  <div class="form-group">
    <label for="Exp" class="col-sm-2 col-form-label">주소</label>
    <input type="text" class="form-control" style="width:auto;display:inline" value="<?=$fnSite?>/b/" disabled>
    <input type="text" class="form-control" style="width:auto;display:inline" id="slug" name="slug" placeholder="게시판의 주소입니다." required="required">
  </div>
  <fieldset class="form-group">
    <div class="row">
      <legend class="col-form-label col-sm-2 pt-0">접근 권한</legend>
      <div class="col-sm-10">
        <div class="form-check">
          <input class="form-check-input" type="radio"  id="gridRadios1" name="priv" value="default" checked>
          <label class="form-check-label" for="gridRadios1">
            기본
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" id="gridRadios2" name="priv" value="option2" disabled>
          <label class="form-check-label" for="gridRadios2">
            로그인 한 사용자만
          </label>
        </div>
        <div class="form-check disabled">
          <input class="form-check-input" type="radio" id="gridRadios3" name="priv" value="option3" disabled>
          <label class="form-check-label" for="gridRadios3">
            소유주만
          </label>
        </div>
      </div>
    </div>
  </fieldset>
  <div class="form-group row">
    <div class="col-sm-2">약관 동의 여부</div>
    <div class="col-sm-10">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="gridCheck1" required="required">
        <label class="form-check-label" for="gridCheck1">
          <?=$fnSiteName?>의 <a href="bbs.html" target="blank">사설 게시판 약관</a>에 동의합니다.
          <iframe src="/bbs.html" style="width:100%;height:6em;border:1px solid gray">사설 게시판 이용 약관</iframe>
        </label>
      </div>
    </div>
  </div>
  <p align="center"><mark>
    개설 버튼을 누를 시 되돌릴 수 없으며, 1000 포인트가 소모됩니다.
  </mark></p>
    <button type="submit" class="btn btn-primary" style="float:right">개설</button>
</form>

<?php
include_once 'down.php';
?>