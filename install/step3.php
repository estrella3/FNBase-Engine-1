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

$connect = new mysqli($db, $dbuser, $dbpw, $dbname);
if($connect->connect_errno){
    echo '데이터베이스 연결에 실패하였습니다.<br>이전 단계로 돌아가서 다시 입력해주세요. <button type="button" onclick="history.back()">뒤로가기</button>';
    exit;
}else{
    $msg = '데이터베이스 연결 성공!';
}
?>
<html lang="ko">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="../fab.png" type="image/x-icon">
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
    <title>FNBE Install</title>
</head>
<body>
    <script>
        alert('<?=$msg?>');
    </script>
  <div class="center">
    <table>
        <tr class="h"><td>
            <a href="https://dev.fnbase.xyz"><img src="/install/FNBE.png" style="height: 50px; width: auto"></a><h1 class="p">FNBE Installer</h1>
        </td></tr><tr><td>
            <form method="post" action="laststep.php">
            <h2>상세 설정</h2>
            <label>사이트 바닥글 (푸터) : <textarea name="footer" id="ft" required></textarea></label><br>
            <label>사이트 관리자 이메일 : <input type="text" name="mail" id="ml" required></label><br>
            <label>사이트 패비콘 주소 : <input type="text" name="fab" id="fb"></label><br>
            <label>기본 시간대 : <input type="text" name="time" value="Asia/Seoul" id="tz" required></label><br>
            <br>
            <label>관리자 아이디 : <input type="id" name="adid"></label><br>
            <label>관리자 비밀번호 : <input type="password" name="adpw"></label><br>
            <input type="hidden" name="path" value="<?php echo $path;?>">
            <input type="hidden" name="main" value="<?php echo $main;?>">
            <input type="hidden" name="sub" value="<?php echo $sub;?>">
            <input type="hidden" name="desc" value="<?php echo $desc;?>">
            <input type="hidden" name="name" value="<?php echo $name;?>">
            <input type="hidden" name="db" value="<?php echo $db;?>">
            <input type="hidden" name="dbuser" value="<?php echo $dbuser;?>">
            <input type="hidden" name="dbpw" value="<?php echo $dbpw;?>">
            <input type="hidden" name="dbname" value="<?php echo $dbname;?>">
            <input type="submit" value="입력 완료">
            </form>
        </td></tr>
    </table>
  </div>
</body>
</html>
