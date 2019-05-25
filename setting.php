<?php
# 이 파일은 FNBase.xyz에서 배포하는 게시글 관리 엔진의 설정 파일입니다.

/* 이 아래는 중요 설정이며, 반드시 기입하셔야 합니다. */
$fnSite = 'https://fnbase.xyz'; #설치하신 주소입니다.
$fnSiteName = 'FNBase'; #운영하실 사이트의 이름입니다.
$fnSiteColor = '#5998d6'; #사이트의 대표 색상입니다.
/* $fnSiteSubColor = '#E8ECEF'; #사이트의 보조 색상입니다. */
$fnSiteSubColor = '#fff';
$fnSiteMode = 'board'; #사이트의 동작 방식을 정의합니다. 현재는 변경하실 수 없습니다.

#데이터베이스 연결 설정입니다.
$fnSiteDB = 'localhost';
$fnSiteDBuser = 'c180fnbase';
$fnSiteDBpw = 'gzwgeJVTTN#4';
$fnSiteDBname = 'c180nl';

/* 이 아래는 세부 설정이며, 기입하지 않으셔도 됩니다. */
$fnSiteTitle = $fnSiteName; #운영하실 사이트의 제목입니다. 검색엔진이나 브라우저 탭의 제목으로 표시됩니다.
$fnSiteDesc = 'Friendly Neighbors\' Basement'; #운영하실 사이트에 대한 설명입니다.
$fnSiteFab = 'assets/images/fab.png'; #운영하실 사이트의 파비콘입니다.
$fnSiteFooter = ' <div class="card">
<div class="card-header">
  이 사이트의 정보
</div>
<div class="card-body">
  <h5 class="card-title">약관과 정책</h5>
  <p class="card-text"><a href="/pers.html">개인정보 처리 방침</a> | <a href="/desc.html">이용 약관</a> | <a href="/anon.html">비회원 약관</a> (반드시 읽어주세요)</p>
  <h5 class="card-title">고객 문의 및 연락처</h5>
  <p class="card-text">사이트 동작 이상, 부적절한 게시글을 올리는 사용자 차단 요청, 기능 추가 건의 등 : <a href="/maint.fn">운영실에서 글 작성</a><br>
  사이트 접속 불가, 개발진 합류 요청, 광고 문의 등 : <a href="mailto:admin@fnbase.xyz">메일 보내기</a>
  </p>
  <h5 class="card-title">고마운 분들과 만든이</h5>
  <p class="card-text"><a href="https://getbootstrap.com">Bootstrap4</a>를 사용하였고, <a href="https://opentutorials.org/course/1">생활코딩</a>에서 공부하였으며, <a href="https://studyforus.com">StudyForUs</a>의 많은 분들께 도움을 받았습니다.
  만들게 된 계기를 제공해주신 UMANLE S.R.L. 임직원분께 감사드립니다.</p>
  <p class="card-text">현재까지 이 프로젝트에 참여한 사람은 Estrella3, hanam09이며, 이 게시판의 소스는 공개되어있습니다.
  <a href="https://github.com/estrella3/fnbase-engine" class="btn btn-dark float-right">Github</a><a href="https://piedots.xyz" class="btn btn-info float-right">개인 홈페이지</a></p>
</div>
</div>'; #사이트의 바닥글에 들어갈 내용입니다.
$fnSiteBoardName = '채널'; #사이트의 게시판을 부르는 명칭입니다.
$fnSite_isp = FALSE; #사이트의 공개 설정입니다. 현재는 변경하실 수 없습니다.
$fnSite_google = '<script async src="https://www.googletagmanager.com/gtag/js?id=UA-131479507-1"></script><script>  window.dataLayer = window.dataLayer || [];  function gtag(){dataLayer.push(arguments);}  gtag("js", new Date()); gtag("config", "UA-131479507-1"); </script>'; #구글 사이트 관리 스크립트 입력 가능합니다.
$fnsite_admincheck = "||";
?>