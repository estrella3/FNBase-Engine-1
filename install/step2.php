<?php
$path = $_POST['path'];
$name = $_POST['name'];
$main = $_POST['main'];
$sub = $_POST['sub'];
$desc = $_POST['desc'];
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
  <div class="center">
    <table>
        <tr class="h"><td>
            <a href="https://dev.fnbase.xyz"><img src="/install/FNBE.png" style="height: 50px; width: auto"></a><h1 class="p">FNBE Installer</h1>
        </td></tr><tr><td>
            <form method="post" action="step3.php">
                <h2>데이터베이스 연결 설정</h2>
                <label>데이터베이스 서버 주소 : <input type="text" name="db" required></label><br><span style="color: gray">보통 localhost인 경우가 많습니다.</span><br>
                <label>데이터베이스 이름 : <input type="text" name="dbname" required></label><br>
                <label>데이터베이스 사용자 : <input type="text" name="dbuser" required></label><br>
                <label>데이터베이스 사용자 비밀번호 : <input type="password" name="dbpw" required></label>
                <input type="hidden" name="path" value="<?=$path?>">
                <input type="hidden" name="main" value="<?=$main?>">
                <input type="hidden" name="sub" value="<?=$sub?>">
                <input type="hidden" name="desc" value="<?=$desc?>">
                <input type="hidden" name="name" value="<?=$name?>">
                <p>값을 모를 경우 호스트 업체에 문의하거나 매뉴얼을 참고하세요.</p>
                <input type="submit" value="다음 단계로">
            </form>
        </tr></td>
    </table>
  </div>
</body>
</html>