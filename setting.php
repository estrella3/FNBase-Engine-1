<?php
# 이 파일은 FNBase.xyz에서 배포하는 게시글 관리 엔진의 설정 파일입니다.

/* 이 아래는 중요 설정이며, 반드시 기입하셔야 합니다. */
$fnSite = ''; #설치하신 주소입니다.
$fnSiteName = ''; #운영하실 사이트의 이름입니다.
$fnSiteColor = ''; #사이트의 대표 색상입니다.
$fnSiteSubColor = ''; #사이트의 보조 색상입니다.
$fnSiteMode = ''; #사이트의 동작 방식을 정의합니다. 현재는 변경하실 수 없습니다.

#데이터베이스 연결 설정입니다.
$fnSiteDB = '';
$fnSiteDBuser = '';
$fnSiteDBpw = '';
$fnSiteDBname = '';

/* 이 아래는 세부 설정이며, 기입하지 않으셔도 됩니다. */
$fnSiteTitle = $fnSiteName; #운영하실 사이트의 제목입니다. 검색엔진이나 브라우저 탭의 제목으로 표시됩니다.
$fnSiteNav1 = ''; #사이트 상단 첫번째 메뉴입니다.
$fnSiteNav2 = ''; #사이트 상단 두번째 메뉴입니다.
$fnSiteDesc = ''; #운영하실 사이트에 대한 설명입니다.
$fnSiteFab = ''; #운영하실 사이트의 파비콘입니다.
$fnSiteFooter = ''; #사이트의 바닥글에 들어갈 내용입니다.
$fnSiteBoardName = ''; #사이트의 게시판을 부르는 명칭입니다.
$fnSite_isp = FALSE; #사이트의 공개 설정입니다. 현재는 변경하실 수 없습니다.
$fnSite_google = ''; #구글 사이트 관리 스크립트 입력 가능합니다.
?>