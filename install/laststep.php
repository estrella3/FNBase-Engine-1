<?php
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
if(empty($desc)){
    $desc = 'FNBase Engine';
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
$localsetting = fopen("../setting.php", "w") or die("Unable to open file!");
$gap = '
<?php
# 이 파일은 FNBase.xyz에서 배포하는 FNBase Engine의 설정 파일입니다.

$is_set = TRUE; #설정을 마치셨으면 이 변수의 값을 True로 바꿔주세요. 이 값이 False이면 작동하지 않습니다.

$fnSite = "'.$path.'"; #설치하신 웹 주소입니다.
$fnSiteName = "'.$name.'"; #운영하실 사이트의 이름입니다.
$fnSiteColor = "'.$main.'"; #사이트의 대표 색상입니다.
$fnSiteSubColor = "'.$sub.'"; #사이트의 보조 색상입니다.
$fnSiteMode = ""; #사이트의 동작 방식을 정의합니다. 현재는 변경하실 수 없습니다.

#데이터베이스 연결 설정입니다.
$fnSiteDB = "'.$db.'";
$fnSiteDBname = "'.$dbname.'";
$fnSiteDBuser = "'.$dbuser.'";
$fnSiteDBpw = "'.$dbpw.'";

$fnSiteTitle = $fnSiteName; #운영하실 사이트의 제목입니다. 검색엔진이나 브라우저 탭의 제목으로 표시됩니다. 기본적으로 사이트 이름과 같게 설정됩니다.
$fnSiteDesc = "'.$desc.'"; #운영하실 사이트에 대한 설명입니다.
$fnSiteNav1 = ""; #사이트의 상단 바 첫번째 메뉴입니다.
$fnSiteNav2  = ""; #사이트의 상단 바 두번째 메뉴입니다.
$fnSiteFab = ""; #운영하실 사이트의 파비콘입니다.
$fnSiteFooter = "'.$footer.'"; #사이트의 바닥글에 들어갈 내용입니다.
$fnSite_Pub = FALSE; #사이트의 공개 설정입니다. 현재는 변경하실 수 없습니다.
$fnSite_PubPW = ""; #사이트 비공개 설정 시 사용될 열람 비밀번호입니다.
$fnSite_google = ""; #구글 사이트 관리 스크립트 입력 가능합니다.
?>
';
fwrite($localsetting, $gap);
fclose($localsetting);
 
// 연결 생성
$conn = mysqli_connect($db, $dbuser, $dbpw, $dbname);
 
// 테이블 생성
$sql1 = 'CREATE TABLE `_board` (
    `id` bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `title` varchar(50) NOT NULL,
    `description` text,
    `from` char(5) NOT NULL,
    `to` char(5) NOT NULL,
    `created` datetime NOT NULL,
    `author_id` text NOT NULL,
    `name` char(12) NOT NULL,
    `stat` int(4) NOT NULL,
    `comment` smallint(6) NOT NULL,
    `view` int(11) NOT NULL,
    `UIP` tinytext NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
$sql2 = 'CREATE TABLE `_account` (
  `id` varchar(25) NOT NULL PRIMARY KEY COMMENT "회원 아이디",
  `pw` text NOT NULL COMMENT "회원 비밀번호",
  `name` text NOT NULL COMMENT "닉네임",
  `email` varchar(32) DEFAULT NULL COMMENT "회원 메일 주소",
  `at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cert` int(1) NOT NULL,
  `point` int(10) NOT NULL,
  `UIP` text NOT NULL,
  `ban` int(3) NOT NULL,
  `whyibanned` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
$sql3 = 'CREATE TABLE `_comment` (
  `board` text NOT NULL,
  `original` int(7) NOT NULL,
  `id` text NOT NULL,
  `num` bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` text NOT NULL,
  `content` text NOT NULL,
  `stat` int(1) NOT NULL,
  `created` datetime NOT NULL,
  `ip` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
$sql4 =  'CREATE TABLE `_log` (
  `id` int(11) NOT NULL,
  `ip` text NOT NULL,
  `b` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  ';
$sql5 = "INSERT INTO `_board` (`num`, `id`, `name`, `owner`, `text`, `notice`, `stat`) 
VALUES ('0', 'board', '방명록', '관리자', '설치를 환영합니다.', '준비중입니다.', '1')";
 
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
<title>FNBE</title></head>
<body><div class="center">
<table>
<tr class="h"><td>
<a href="https://fnbase.xyz"><img src="/install/FNBE.png" style="height: 50px; width: auto"></a><h1 class="p">FNBE Version 0.3.19</h1>
</td></tr>
</table>
<tr>';
$result = mysqli_query($conn, $sql1);
if($result === FALSE){
    $count = 1;
}else{
    echo "<td>게시판 데이터베이스 생성 완료</td>";
    $right = 1;
}
$result = mysqli_query($conn, $sql2);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<td>회원 데이터베이스 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql3);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<td>댓글 데이터베이스 생성 완료</td>";
    $right = $right + 1;
}
$result = mysqli_query($conn, $sql4);
if($result === FALSE){
    $count = $count + 1;
}else{
    echo "<td>추천 기록 데이터베이스 생성 완료</td>>";
    $right = $right + 1;
}
if($count == 4){
    echo '<td>데이터베이스 접속 실패</td></tr>';
}elseif($right == 4){
    echo '<td>데이터베이스 생성 완료.';
    echo '이제 당신의 커뮤니티에 접속할 수 있습니다.';
    echo '<a href="https://'.$path.'">바로가기</a></td></tr>';
}
echo '</table>
<table>
<tr class="v"><td>
2018 - 2019 FNBase Engine Team
</td></tr>
</table>';
mysqli_close($conn);
?>