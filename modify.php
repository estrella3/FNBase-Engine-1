<?php
require 'up.php';

if($ban == 9){
echo '<h4>사이트 설정 수정</h4>';
echo '<p class="text-muted">사이트의 기본적인 설정 내용을 수정할 수 있습니다.</p><hr>';
    echo '
    <form action="modify_p.php" method="post">
    <div class="form-group row">
        <label for="url" class="col-sm-2 col-form-label">사이트 주소</label>
        <div class="col-sm-10">
        <input type="text" class="form-control" id="url" name="url" placeholder="예) https://fnbase.xyz" value="'.$fnSite.'">
        </div>
    </div>
    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">사이트 이름</label>
        <div class="col-sm-10">
        <input type="text" class="form-control" id="name" name="name" placeholder="예) FNBase" value="'.$fnSiteName.'">
        </div>
    </div>
    <div class="form-group row">
    <label for="desc" class="col-sm-2 col-form-label">사이트 설명</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="desc" name="desc" placeholder="검색엔진 등에 노출됩니다." value="'.$fnSiteDesc.'">
    </div>
    </div>
    <div class="form-group row">
    <label for="timezone" class="col-sm-2 col-form-label">사이트 시간대</label>
    <div class="col-sm-10">
    <input type="text" class="form-control" id="timezone" name="timezone" placeholder="예) Asia/Seoul" value="'.$fnSiteTimezone.'">
    </div>
    </div>
    <hr>
    <div class="form-group row">
    <label for="email" class="col-sm-2 col-form-label">연락처</label>
    <div class="col-sm-10">
    <input type="text" class="form-control" id="email" name="email" placeholder="사이트 관리자님의 이메일을 입력해주세요." value="'.$fnSiteEmail.'">
    </div>
    </div>
    <fieldset class="form-group">
        <div class="row">
        <legend class="col-form-label col-sm-2 pt-0">사이트 메인 페이지</legend>
        <div class="col-sm-10">
            <div class="form-check">
            <input class="form-check-input" type="radio" name="main" id="gridRadios1" value="recent" checked>
            <label class="form-check-label" for="gridRadios1">
                글 목록
            </label>
            </div>
            <div class="form-check">
            <input class="form-check-input" type="radio" name="main" id="gridRadios2" value="private">
            <label class="form-check-label" for="gridRadios2">
                전체 게시판 목록
            </label>
            </div>
            <div class="form-check disabled">
            <input class="form-check-input" type="radio" name="main" id="gridRadios3" value="url" disabled>
            <label class="form-check-label" for="gridRadios3">
                지정 경로
            </label>
            </div>
        </div>
        </div>
    </fieldset>
    <div class="form-group row">
        <div class="col-sm-2">공개 여부</div>
        <div class="col-sm-10">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="gridCheck1" checked disabled>
            <label class="form-check-label" for="gridCheck1">
            사이트를 공개합니다.
            </label>
        </div>
        </div>
    </div>
    <div class="form-group row">
    <label for="footer" class="col-sm-2 col-form-label">사이트 바닥글 (푸터)</label>
    <div class="col-sm-10">
        <textarea class="form-control" id="footer" name="footer">'.$fnSiteFooter.'</textarea>
        <small class="form-text text-muted">HTML 코드로 작성하셔도 됩니다.</small>
    </div>
    </div>
    <hr>
    <h5>세부 설정</h5>
    <div class="form-group row">
    <label for="fab" class="col-sm-2 col-form-label">파비콘 주소</label>
    <div class="col-sm-10">
    <input type="text" class="form-control-sm" id="fab" name="fab" placeholder="사이트의 파비콘이 저장된 주소를 입력해주세요. 예) /image/fab.png" value="'.$fnSiteFab.'">
    </div>
    </div>
    <div class="form-group row">
    <label for="suffix" class="col-sm-2 col-form-label">기본 게시판 이름</label>
    <div class="col-sm-10">
    <input type="text" class="form-control-sm" id="suffix" name="suffix" placeholder="예) 게시판" value="'.$fnSiteBoardName.'">
    </div>
    </div>
    <div class="form-group row">
    <label for="google" class="col-sm-2 col-form-label">구글 애널리틱스</label>
    <div class="col-sm-10">
    <textarea class="form-control" id="google" name="google" placeholder="없어도 무방합니다.">'.$fnSite_google.'</textarea>
    <small class="form-text text-muted">https://analytics.google.com 에서 제공하는 코드를 입력하세요. 없어도 괜찮습니다.</small>
    </div>
    </div>
    <hr>
    <h5>스킨 설정</h5>
    <h6 class="badge badge-primary">Primary</h6>
    <div class="form-group row">
    <label for="color" class="col-sm-2 col-form-label">주 색상 / 보조 색상</label>
    <div class="col-sm-5">
    <input type="text" class="form-control" id="color" name="color" placeholder="예) #5998d6" value="'.$fnSiteColor.'">
    </div>
    <div class="col-sm-5">
    <input type="text" class="form-control" id="subcolor" name="subcolor" placeholder="예) #ffffff" value="'.$fnSiteSubColor.'">
    </div>
    </div>

    <hr>
    <button type="submit" class="btn btn-primary" style="float:right;width:100%">저장</button><br><br>
    </form>
    ';
}else{
    echo '<h4>접근이 거부되었습니다.</h4>';
    echo '<p class="text-muted">사이트 소유주만 접근 가능합니다.</p>';
    exit;
}

include 'down.php';
?>