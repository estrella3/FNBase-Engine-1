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
            <form method="post" action="step2.php">
                <h2>기본 설정</h2>
                <label>사이트가 설치될 주소 : <input type="text" name="path" required></label><br>
                <label>커뮤니티의 이름 : <input type="text" name="name" required></label><br>
                <label>테마 메인 색상 : <input type="text" name="main" required></label><br>
                <label>테마 서브 색상 : <input type="text" name="sub" required></label><br>
                <label>커뮤니티 설명 : <textarea name="desc"></textarea></label>
                <input type="submit" value="다음 단계로">
            </form>
        </tr></td>
    </table>
  </div>
</body>
</html>