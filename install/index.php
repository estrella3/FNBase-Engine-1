<?php require '../setting.php';?>
<html lang="ko">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="../assets/images/fab.png" type="image/x-icon">
    <meta name="description" content="FNBase Engine 설치 페이지">
    <meta name="theme-color" content="red">
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
<a href="https://fnbase.xyz"><img src="/install/FNBE.png" style="height: 50px; width: auto"></a><h1 class="p">FNBE Version <?=$fnVersion?></h1>
</td></tr>
</table>
<td>
  <?php
  if($a == false){
    if(is_file('../setting.php')){
      echo '정상적으로 설치된 것으로 보입니다.
      <h3>FNBase Engine 안내</h3><hr>';
      $conn = mysqli_connect("$fnSiteDB", "$fnSiteDBuser", "$fnSiteDBpw", "$fnSiteDBname");
      echo '<table>
      <h4>버전 정보</h4>
      <tr><td class="e">PHP 버전</td><td class="v">'.phpversion().'</td></tr>
      <tr><td class="e">데이터베이스 버전</td><td class="v">'.mysqli_get_server_info($conn).'</td></tr>
      <tr><td class="e">엔진 버전</td><td class="v">'.$fnVersion.'</td></tr>
      </table>
      <table>
      <h4>기본 정보</h4>
      <tr><td class="e">사용중인 언어</td><td class="v">한국어</td></tr>
      <tr><td class="e">사용중인 시간대</td><td class="v">'.date_default_timezone_get().'</td></tr>
      </table>

      <table>
      <h4>부가 정보</h4>
      <tr><td class="e">사용중인 외형</td><td class="v">Primary</td></tr>
      </table><hr>';
      echo '<br><br><br>';
      echo '<h3>라이선스</h3><hr>
      <pre>이 커뮤니티는 FNBase Engine을 기반으로 작동합니다.
      FNBase Engine은 MIT 라이선스로 배포되며, 사용과 복제, 코드 변경, 재배포 등이 누구에게나 가능합니다.
      단, 원 저작권자와 라이선스 문서를 확실히 명시해야 합니다. 
      
      프로그램 개발자는 신체·정신·기계적 손상 등의 모든 사항에 있어 안전을 보증하거나 책임지지 않습니다.
      또한 영리 목적을 포함한 모든 사용법에서, 수익이나 적합함을 보장하지 않습니다.</pre><hr>';
      echo '<br><br><br>';
    }else{
      echo '<h3>Setting.php를 찾을 수 없습니다!</h3><hr><p>먼저 사이트를 <a href="step1.php">설정</a>해주세요.</p>';
      echo '<br><br><br><br><h4>도움이 필요하신가요?</h4><hr><p>게시판 엔진 유지·보수가 정상적으로 이뤄지고 있다면 <a href="https://fnbase.xyz/b/info">여기</a>
      에서 관련 도움말이나 지원을 받으실 수 있습니다.</p>';
    }}
    echo $a;
  ?>
</td>
<table>
<tr><td>
<a href="http://www.zend.com/"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/PHP-logo.svg/100px-PHP-logo.svg.png"></a>
<a href="http://www.mysql.com/"><img src="https://upload.wikimedia.org/wikipedia/en/thumb/6/62/MySQL.svg/100px-MySQL.svg.png"></a>
이 프로그램은 PHP를 사용하여 동작하며, PHP 7.2 버전에서 작동할 것을 염두에 두었습니다.<br>
데이터베이스로는 MySQL 또는 MariaDB 사용을 권장합니다.<br>
<span style="font-size:0.8em;color:gray"><br>이 프로그램을 우측에 언급된 개발사들이 보증한다는 의미가 아닙니다.</span><br>
<span style="font-size:0.7em;color:gray">해당 로고들은 좌측부터 각각 Oracle Corporation / Perforce Software, Inc.
(Zend the PHP Company) 의 저작물입니다.</span>
</tr>
</table>
<table>
<tr class="v"><td>
© 2018-2019 FNBase Engine Team. All rights reserved.
</td></tr>
</table>
<!-- PHP의 정보 페이지를 참고하였습니다. -->
</html>