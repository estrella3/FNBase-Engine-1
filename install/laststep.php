<?php
$INSTALL_VERSION = '0.9.09';

$path = $_POST['path'];
$name = $_POST['name'];
$main = $_POST['main'];
$sub = $_POST['sub'];
$desc = $_POST['desc'];
$db = $_POST['db'];
$dbuser = $_POST['dbuser'];
$dbpw = $_POST['dbpw'];
$dbname = $_POST['dbname'];
$footer = $_POST['footer'];
$mail = $_POST['mail'];
$fab = $_POST['fab'];
$tz = $_POST['time'];
$aid = $_POST['adid'];
$apw = $_POST['adpw'];

if(empty($desc)){
    $desc = 'Site using FNBase Engine';
}
if(empty($aid)){
    $aid = 'admin';
}
if(empty($apw)){
    $apw = '123456';
}
if(empty($ack)){
    $ack = '관리자';
}

if(is_file('../setting.php')){
    echo '이미 설정 파일이 있습니다!';
    exit;
}
if(is_file('./setting.php')){
    echo '설정 파일이 잘못된 폴더에 있습니다.';
    exit;
}
if(is_file('/setting.php')){
    echo '이미 설정파일이 있습니다!';
    exit;
}

// 설정파일 생성
$localsetting = fopen("../setting.php", "w") or die("파일을 열 수 없습니다!");
$val = '<?php
# 이 파일은 FNBase.xyz에서 배포하는 게시글 관리 엔진의 설정 파일입니다.
# 자세한 설정에 관해서는 https://dev.fnbase.xyz/fnbe.html 을 참고하십시오.
$setVersion = "'.$INSTALL_VERSION.'"; #세팅 파일이 작성될 때의 버전입니다.

#데이터베이스 연결 설정입니다.
$fnSiteDB = "'.$db.'";
$fnSiteDBuser = "'.$dbuser.'";
$fnSiteDBpw = "'.$dbpw.'";
$fnSiteDBname = "'.$dbname.'";

$conn = mysqli_connect("$fnSiteDB", "$fnSiteDBuser", "$fnSiteDBpw", "$fnSiteDBname");

/* 이 아래는 일반적인 경우 수정하지 않으시는게 좋습니다. */
$fnMultiNum = 1;
$fnMultiDB_Suffix = FALSE;
$query = "SELECT * from `_Setting` WHERE `num` = $fnMultiNum";
$query_result = mysqli_query($conn, $query);
if($query_result !== FALSE){
    while($setting = mysqli_fetch_array($query_result)){
        $fnVersion = $setting["Version"];
        $fnSite = $setting["Site"];
        $fnSiteName = $setting["SiteName"];
        $fnSiteColor = $setting["SiteColor"];
        $fnSiteSubColor = $setting["SiteSubColor"];
        $fnSiteMode = $setting["SiteMode"];
        $fnSiteTitle = $fnSiteName;
        $fnSiteDesc = $setting["SiteDesc"];
        $fnSiteFab = $setting["SiteFab"];
        $fnSiteEmail = $setting["SiteEmail"];
        $fnSiteBoardList = $setting["SiteBoardList"];
        $fnSiteFooter = $setting["SiteFooter"];
        $fnSiteBoardName = $setting["SiteBoardSuffix"];
        $fnSite_Homepage = $setting["SiteHomepage"];
        $fnSite_HomepageName = $setting["SiteHomepageName"];
        $fnSite_google = $setting["Site_google"];
        $fnSite_isp = $setting["Site_isp"];
        $fnSiteTimezone = $setting["SiteTimezone"];
        $fnSiteDefaultSkin = $setting["DefaultSkin"];
        date_default_timezone_set($fnSiteTimezone);
    }
}else{
/* 데이터베이스 연결에 실패할 경우 메시지 출력 */
    if($isInstall === TRUE){
        $showError = FALSE; #PHP 에러 표시 여부.
        require "./error/db_fail.php";
    }
}
?>
';
fwrite($localsetting, $val);
fclose($localsetting);
 
// 연결 생성
$conn = mysqli_connect($db, $dbuser, $dbpw, $dbname);
 
// 테이블 생성
$sql1 = "CREATE TABLE `_account` (
    `id` varchar(25) NOT NULL COMMENT '회원 아이디',
    `pw` text NOT NULL COMMENT '회원 비밀번호',
    `name` text NOT NULL COMMENT '닉네임',
    `email` text COMMENT '회원 메일 주소',
    `at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '회원 가입 시간',
    `cert` int(1) NOT NULL COMMENT '이메일 등 인증 여부 (아직 지원 안함)',
    `point` int(10) NOT NULL COMMENT '활동으로 얻은 포인트',
    `UIP` text NOT NULL COMMENT '가입시 사용한 아이피',
    `introduce` text COMMENT '유저가 작성한 소개글',
    `ext` int(3) NOT NULL COMMENT '계정 관련',
    `whyibanned` tinytext NOT NULL COMMENT '차단 이유',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$sql2 = "CREATE TABLE `_article` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '글 번호',
    `title` varchar(50) NOT NULL COMMENT '제목',
    `description` text COMMENT '글 내용',
    `from` char(20) NOT NULL COMMENT '작성된 게시판',
    `to` char(20) NOT NULL COMMENT '이동된 게시판 (아직 지원 안함)',
    `created` datetime NOT NULL COMMENT '작성 시간',
    `author_id` text NOT NULL COMMENT '작성자 아이디',
    `name` text NOT NULL COMMENT '작성자 닉네임',
    `stat` int(4) NOT NULL COMMENT '추천 수',
    `comment` int(6) NOT NULL COMMENT '댓글 수',
    `view` int(11) NOT NULL COMMENT '조회수',
    `UIP` tinytext NOT NULL COMMENT '작성 아이피',
    `issec` int(11) NOT NULL DEFAULT '0' COMMENT '열람 제한 여부. 0=전체공개 1=나만보기 2=특정유저',
    `issectxt` text COMMENT '열람이 허가될 id입니다.',
    `edited` int(1) DEFAULT NULL COMMENT '글 수정 여부',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$sql3 =  "CREATE TABLE `_board` (
    `num` int(7) NOT NULL AUTO_INCREMENT,
    `id` text NOT NULL COMMENT '고유 아이디',
    `name` text NOT NULL COMMENT '이름',
    `suffix` text NOT NULL COMMENT '접미사 ( ~게시판, ~채널 등)',
    `owner` text NOT NULL COMMENT '소유주',
    `keeper` text COMMENT '게시판 관리인',
    `volunteer` text COMMENT '게시판 운영 봉사자',
    `text` text NOT NULL COMMENT '소개',
    `hashtag` tinytext NOT NULL COMMENT '채널 설명 해시태그',
    `sub` int(11) NOT NULL DEFAULT '0' COMMENT '구독',
    `stat` int(1) NOT NULL COMMENT '종류 (공식, 사설 등)',
    PRIMARY KEY (`num`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$sql4 = "CREATE TABLE `_comment` (
    `board` text NOT NULL,
    `original` int(7) NOT NULL,
    `id` text NOT NULL,
    `num` bigint(20) NOT NULL AUTO_INCREMENT,
    `name` text NOT NULL,
    `content` text NOT NULL,
    `remarks` text NOT NULL,
    `stat` int(1) NOT NULL,
    `created` datetime NOT NULL,
    `blame` int(11) NOT NULL,
    `secret` int(1) NOT NULL DEFAULT '0',
    `reply` int(11) NOT NULL,
    `edited` int(11) DEFAULT NULL,
    `ment` int(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`num`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$sql5 = "CREATE TABLE `_edit` (
    `count` int(11) NOT NULL AUTO_INCREMENT,
    `author_id` text NOT NULL,
    `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `id` bigint(20) NOT NULL,
    PRIMARY KEY (`count`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$sql6 = "CREATE TABLE `_ipban` (
    `num` int(11) NOT NULL AUTO_INCREMENT COMMENT '순서',
    `ip` text NOT NULL COMMENT '전체 차단된 ip',
    `reason` text NOT NULL COMMENT '차단 이유',
    `who` text NOT NULL COMMENT '차단자',
    PRIMARY KEY (`num`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='(임시) ip 밴 리스트입니다.';";
$sql7 = "CREATE TABLE `_kicked` (
    `num` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '순번',
    `board_id` text NOT NULL COMMENT '추방당한 게시판 id',
    `user_id` text NOT NULL COMMENT '추방당한 사용자 id',
    `term` bigint(20) NOT NULL COMMENT '추방 기간',
    `by` text NOT NULL COMMENT '제재자',
    `reason` text NOT NULL COMMENT '제재 이유 (사용 안함)',
    `date` datetime NOT NULL COMMENT '추방 날짜',
    `type` int(11) NOT NULL DEFAULT '0' COMMENT '유형 (기본값 0)',
    PRIMARY KEY (`num`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='추방 기능';";
$sql8 = "CREATE TABLE `_log` (
    `num` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '순서',
    `ip` tinytext NOT NULL COMMENT '요청 ip',
    `id` tinytext NOT NULL COMMENT '사용된 id',
    `right` tinyint(1) NOT NULL COMMENT '로그인 성공 여부 (성공시 1, 실패시 0)',
    `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '로그인 타입 (아직 사용 안함, 기본값 1)',
    `at` datetime NOT NULL COMMENT '로그인 요청 시간',
    PRIMARY KEY (`num`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='로그인 기록';
  ";
$sql9 = "CREATE TABLE `_mailAuth` (
    `num` bigint(20) NOT NULL AUTO_INCREMENT,
    `id` text NOT NULL,
    `email` text NOT NULL,
    `ip` text NOT NULL,
    `at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `code` text NOT NULL,
    PRIMARY KEY (`num`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='이메일 인증을 기록합니다.';";
$sql10 = "CREATE TABLE `_ment` (
    `no` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '멘션 번호입니다',
    `name` text NOT NULL COMMENT '멘션한 사람의 아이디입니다.',
    `to` text NOT NULL COMMENT '멘션 받을 사람의 아이디입니다.',
    `read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '읽었는지 안 읽었는지 체크합니다. 읽었으면 1, 안 읽었으면 0입니다.',
    `msg` text NOT NULL COMMENT '알림의 내용입니다.',
    `link` text NOT NULL,
    `type` text NOT NULL COMMENT '알림의 타입입니다. 아직 사용하지 않습니다.',
    `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `go` text NOT NULL,
    PRIMARY KEY (`no`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$sql11 = "CREATE TABLE `_pinned` (
    `num` bigint(20) NOT NULL AUTO_INCREMENT,
    `board_id` text NOT NULL,
    `did_id` text NOT NULL,
    `article_id` bigint(20) NOT NULL,
    `position` char(6) NOT NULL DEFAULT 'top' COMMENT '공지 글이 노출될 위치 (미사용)',
    PRIMARY KEY (`num`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$sql12 = "CREATE TABLE `_push` (
    `num` bigint(20) NOT NULL AUTO_INCREMENT,
    `id` int(11) NOT NULL,
    `ip` text NOT NULL,
    `b` text NOT NULL,
    `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `type` tinytext NOT NULL,
    PRIMARY KEY (`num`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$sql13 = "CREATE TABLE `_reply` (
    `num` bigint(20) NOT NULL AUTO_INCREMENT,
    `resp` bigint(20) DEFAULT NULL,
    `id` text NOT NULL,
    `name` text NOT NULL,
    `original` bigint(11) NOT NULL,
    `content` text NOT NULL,
    `created` datetime NOT NULL,
    `ip` text NOT NULL,
    `step` int(2) NOT NULL,
    `email` text,
    `edited` int(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`num`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='대댓글';";
$sql14 = "CREATE TABLE `_report` (
    `num` int(11) NOT NULL AUTO_INCREMENT,
    `reason` text NOT NULL,
    `board` text NOT NULL,
    `id` int(11) NOT NULL,
    `time` datetime NOT NULL,
    `ip` text NOT NULL,
    `userid` text NOT NULL,
    PRIMARY KEY (`num`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$sql15 = "CREATE TABLE `_Setting` (
    `num` int(11) NOT NULL AUTO_INCREMENT,
    `Version` text NOT NULL COMMENT '엔진 버전입니다.',
    `Site` text NOT NULL COMMENT '사이트 주소입니다.',
    `SiteName` text NOT NULL COMMENT '사이트 이름입니다.',
    `SiteColor` text NOT NULL COMMENT '사이트의 메인 색상입니다.',
    `SiteSubColor` text NOT NULL COMMENT '사이트의 보조 색상입니다.',
    `SiteMode` text NOT NULL COMMENT '사이트의 동작 방식을 설정합니다 (미사용)',
    `SiteDesc` text NOT NULL COMMENT '사이트의 설명입니다.',
    `SiteFab` text NOT NULL COMMENT '사이트의 파비콘 주소입니다.',
    `SiteEmail` text NOT NULL COMMENT '사이트 관리자의 이메일입니다.',
    `SiteBoardList` text NOT NULL COMMENT '사이트 상단바에 들어갈 코드입니다.',
    `SiteFooter` text NOT NULL COMMENT '사이트 바닥글입니다.',
    `SiteBoardSuffix` text NOT NULL COMMENT '기본적으로 적용될 게시판 이름입니다.',
    `SiteHomepage` text NOT NULL COMMENT '사이트 메인 페이지의 동작을 설정합니다.',
    `SiteHomepageName` text NOT NULL COMMENT '사이트 메인 페이지의 주소입니다.',
    `Site_google` text NOT NULL COMMENT '구글 추적 코드를 입력할 수 있습니다.',
    `Site_isp` text NOT NULL COMMENT '공개 여부를 설정합니다.',
    `SiteTimezone` text NOT NULL COMMENT '사이트의 기본 시간대를 설정합니다.',
    `DefaultSkin` text NOT NULL COMMENT '사이트 기본 스킨입니다.',
    PRIMARY KEY (`num`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='사이트 기본 설정입니다.';";
$sql16 = "CREATE TABLE `_userRights` (
    `num` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '번호 (의미 없음)',
    `type` int(1) NOT NULL COMMENT '이용자 타입 ( exp 단락 참고 )',
    `exp` text NOT NULL COMMENT '설명',
    `read` int(11) NOT NULL COMMENT '글 읽기 권한',
    `write` int(11) NOT NULL COMMENT '글 쓰기 권한',
    `comment` int(11) NOT NULL COMMENT '댓글 쓰기 권한',
    `reply` int(11) NOT NULL COMMENT '답글 쓰기 권한',
    `edit` int(11) NOT NULL COMMENT '글 수정 권한',
    `delete` int(11) NOT NULL COMMENT '글 삭제 권한',
    `push` int(11) NOT NULL COMMENT '글 추천 권한',
    `report` int(11) NOT NULL COMMENT '글 신고 권한',
    `mention` int(11) NOT NULL COMMENT '이용자 호출 권한',
    `editUserInfo` int(11) NOT NULL COMMENT '자신의 회원 정보 수정 권한',
    `deleteAnother` int(11) NOT NULL COMMENT '다른 사람의 게시글 비공개 처리 & 댓글 삭제',
    `kickAnother` int(11) NOT NULL COMMENT '다른 사람을 일정기간 추방',
    `editBoardInfo` int(11) NOT NULL COMMENT '게시판 정보 및 설정 변경',
    `makeBoardNotice` int(11) NOT NULL COMMENT '게시판 공지 설정',
    `addBoardVolunteer` int(11) NOT NULL COMMENT '게시판 보조 관리인 임명',
    `disableBoard` int(11) NOT NULL COMMENT '게시판 비활성화 가능 여부',
    PRIMARY KEY (`num`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='사용자 권한';";
$sql17 = "CREATE TABLE `_userSetting` (
    `id` text NOT NULL COMMENT '사용자 아이디',
    `num` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '순번 (의미 없음 )',
    `showAlerts` int(1) NOT NULL COMMENT '댓글 등 알림 표시 여부, 1 = 참 0 = 거짓',
    `subList` text NOT NULL COMMENT '구독 게시판 목록입니다.',
    `evadeBoardList` text COMMENT '보고싶지 않은 채널 실시간에서 제외',
    `evadeUserList` text COMMENT '보고싶지 않은 사람 제외',
    `selectSkin` text NOT NULL COMMENT '사용자가 설정한 스킨입니다.',
    PRIMARY KEY (`num`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$sql18 = "INSERT INTO `_Setting` (`num`, `Version`, `Site`, `SiteName`, `SiteColor`, `SiteSubColor`, `SiteMode`, 
`SiteDesc`, `SiteFab`, `SiteEmail`, `SiteFooter`, `SiteBoardSuffix`, `SiteHomepage`, `SiteHomepageName`, `Site_google`, `Site_isp`, `SiteTimezone`, `DefaultSkin`) 
VALUES (NULL, '$INSTALL_VERSION', '$path', '$name', '$main',
 '$sub', 'board', '$desc', '$fab', '$mail', '$footer', '게시판', 'recent', 'rct', ' ', 'public', '$tz', 'primary')";
$sql19 = "INSERT INTO `_board` (`num`, `id`, `name`, `suffix`,
 `owner`, `keeper`, `volunteer`, `text`, `hashtag`, `sub`, `stat`) 
VALUES (NULL, 'rct', '종합', '글 목록', '$aid', NULL, NULL, '전체 게시글의 목록', '', '0', '8')";
$sql20 = "INSERT INTO `_board` (`num`, `id`, `name`, `suffix`,
`owner`, `keeper`, `volunteer`, `text`, `hashtag`, `sub`, `stat`) 
VALUES (NULL, 'trash', '휴지통', '', '$aid', NULL, NULL, '비공개 처리된 글들', '', '0', '8')";
$sql21 = "INSERT INTO `_board` (`num`, `id`, `name`, `suffix`,
`owner`, `keeper`, `volunteer`, `text`, `hashtag`, `sub`, `stat`) 
VALUES (NULL, 'recommend', '추천', '글 목록', '$aid', NULL, NULL, '추천을 많이 받은 글', '', '0', '8')";
$sql22 = "INSERT INTO `_board` (`num`, `id`, `name`, `suffix`,
`owner`, `keeper`, `volunteer`, `text`, `hashtag`, `sub`, `stat`) 
VALUES (NULL, 'default', '기본', '게시판', '$aid', NULL, NULL, '설치시 생성된 게시판', '', '0', '1')";

$pw = password_hash($apw, PASSWORD_BCRYPT);
$sql23 = "INSERT INTO `_account` (`id`, `pw`, `name`, `email`, `at`, `cert`,
 `point`, `UIP`, `introduce`, `ext`, `whyibanned`) 
VALUES ('$aid', '$pw', '$ack', '$mail', CURRENT_TIMESTAMP, '1', '1000', '127.0.0.1', NULL, '9', '')";
$sql23_1 = "INSERT INTO `_account` (`id`, `pw`, `name`, `email`, `at`, `cert`,
`point`, `UIP`, `introduce`, `ext`, `whyibanned`) 
VALUES ('Installer', 'Do_not_change_this', 'Installer', 'no_reply@fnbase.xyz', CURRENT_TIMESTAMP, '9', '0', '127.0.0.1', 'FNBE 설치 계정입니다.', '9', '')";

$sql24 = "INSERT INTO `_userRights` (`num`, `type`, `exp`, `read`, `write`, `comment`, `reply`, `edit`, `delete`, `push`, `report`, `mention`, `editUserInfo`, `deleteAnother`, `kickAnother`, `editBoardInfo`, `makeBoardNotice`, `addBoardVolunteer`, `disableBoard`) VALUES
(1, 0, '비로그인', 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 1, '로그인_일반', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0),
(3, 2, '로그인_게시판 운영 봉사자 = volunteer', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0),
(4, 3, '로그인_게시판 관리인 = keeper', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 0, 0),
(5, 4, '로그인_게시판 소유주 = owner', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);";

$sql25 = "INSERT INTO `_article` (`id`, `title`, `description`, `from`, `to`, `created`, `author_id`, `name`, 
`stat`, `comment`, `view`, `UIP`, `issec`, `issectxt`, `edited`)
 VALUES (NULL, 'FNBase Engine을 사용해주셔서 감사합니다!', '자세한 사용법을 알고싶으시다면 <a href=\'https://dev.fnbase.xyz\'>여기</a>를 방문해주세요.', 
 'default', 'default', '2019-09-09 00:03:02', 'Installer', 'Installer', '0', '0', '0', '127.0.0.1', '0', NULL, NULL)";


if (mysqli_connect_errno()) {
    printf("Connect failed", mysqli_connect_error());
    exit();
}
echo '
<style type="text/css">
body {background-color: #fff; color: #222; font-family: sans-serif;}
pre {margin: 0; font-family: monospace;}
a:link {color: #009; text-decoration: none; background-color: #fff;}
a:hover {text-decoration: underline;}
table {border-collapse: collapse; border: 0; width: 934px; box-shadow: 1px 2px 3px #ccc;}
.center {text-align: center;}
.center table {margin: 1em auto; text-align: left;}
.center th {text-align: center !important;}
td, th {border: 1px solid #666; font-size: 75%; vertical-align: baseline; padding: 4px 5px;}
h1 {font-size: 150%;}
h2 {font-size: 125%;}
.p {text-align: left;}
.e {background-color: royalblue; width: 300px; font-weight: bold; color: #fff;}
.h {background-color: #fff; font-weight: bold;}
.v {background-color: #eee; max-width: 300px; overflow-x: auto; word-wrap: break-word;}
.v i {color: #999;}
img {float: right; border: 0;}
hr {width: 934px; background-color: #ccc; border: 0; height: 1px;}
</style>
<title>FNBE Install</title></head>
<body><div class="center">
<table>
<tr class="h"><td>
<a href="https://dev.fnbase.xyz"><img src="/install/FNBE.png" style="height: 50px; width: auto"></a><h1 class="p">FNBE Version '.$INSTALL_VERSION.'</h1>
</td></tr>
</table>
<table>';
$result = mysqli_query($conn, $sql1);
if($result === FALSE){
    $count = 1;
}else{
    echo "<tr><td>회원 테이블 생성 완료</td>";
    $right = 1;
}
$result = mysqli_query($conn, $sql2);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>게시글 테이블 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql3);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>게시판 테이블 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql4);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>댓글 테이블 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql5);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>수정 기록 테이블 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql6);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>ip 차단 기록 테이블 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql7);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>추방 기록 테이블 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql8);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>로그인 기록 테이블 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql9);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>메일 인증 기록 테이블 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql10);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>알림 테이블 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql11);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>공지 테이블 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql12);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>추천 기록 테이블 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql13);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>답글 테이블 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql14);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>신고 기록 테이블 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql15);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>사이트 설정 테이블 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql16);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>사용자 권한 테이블 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql17);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>사용자 설정 테이블 생성 완료</td>";
    $right = $right + 1;
}


$result = mysqli_query($conn, $sql18);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>사용자 설정 테이블 기입 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql19);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>게시판 테이블 기입.. 1</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql20);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>게시판 테이블 기입.. 2</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql21);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>게시판 테이블 기입.. 3</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql22);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>게시판 테이블 기입.. 4</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql23);
$result = mysqli_query($conn, $sql23_1);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>관리자 계정 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql24);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>사용자 권한 테이블 기입</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql25);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<tr><td>도움말 생성</td>";
    $right = $right + 1;
}

if($count == 25){
    echo '<tr><td>데이터베이스 접속 실패</td></tr>';
}elseif($right == 25){
    echo '<tr style="background-color:#cdffe2"><td>데이터베이스 생성이 완료되었습니다.';
    echo '<tr><td>이제 당신의 커뮤니티에 접속할 수 있습니다.';
    echo '<tr><td><a href="'.$path.'">바로가기</a></td></tr>';
}else{
    echo '<tr style="background-color:#FF0000;color:#fff"><td></td></tr>';
}
echo '</table>
<table>
<tr class="v"><td>
2018 - 2019 FNBase Engine Team
</td></tr>
</table>';
mysqli_close($conn);
?>
