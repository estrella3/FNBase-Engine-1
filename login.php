<?php
require 'function.php';
if($_COOKIE['keeplogin'] == 'FNBase.xyz'){
  $id = 'value="'.$_COOKIE['keeplogin-id'].'"';
  $pw = 'value="'.$_COOKIE['keeplogin-password'].'"';
  $kl = 'kl';
}else{
  $id = ' ';
  $pw = $id;
}
if($_GET['from'] == 'keepgoing'){
  $a = '<input type="hidden" name="petCheetar" value="Jason">';
}
if($_GET['log'] == 'out'){
  unset($_COOKIE["keeplogin"]);
  unset($_COOKIE["keeplogin-id"]);
  unset($_COOKIE["keeplogin-password"]);
  setcookie("keeplogin", "", time() -1);
  setcookie("keeplogin-id", "", time() -1);
  setcookie("keeplogin-password", "", time() -1);
  session_start();
  if(empty($_SESSION['userid'])){
          print "<script>alert('로그인하지 않았습니다!'); history.back()</script>";
          exit;
  }
  $result = session_destroy();

  if($result) {
?>
  <script>
          alert("로그아웃 되었습니다.");
          history.back();
  </script>
<?php  }}elseif(!empty($_SESSION['userid'])){
?>
<script>
        alert("이미 로그인되어있습니다.");
        history.back();
</script>
<?php
}
?>
<!doctype html>
<html lang="ko">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?=$fnSiteFab?>" type="image/x-icon">
    <meta name="description" content="<?=$fnSiteDesc?>">
    <meta name="theme-color" content="<?=$fnSiteColor?>">
    <title>로그인 - <?=$fnSiteName?></title>

<link href="/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      body {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-align: center;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}

.form-signin {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: auto;
}
.form-signin .checkbox {
  font-weight: 400;
}
.form-signin .form-control {
  position: relative;
  box-sizing: border-box;
  height: auto;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="id"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
    </style>

  </head>
  <body class="text-center">
    <form class="form-signin" method='post' action='keeplogin.php'>
  <h1 class="h3 mb-3 font-weight-normal">로그인</h1>
  <label for="inputEmail" class="sr-only">아이디</label>
  <input type="text" <?php echo $id; ?> name="id" class="form-control" placeholder="아이디 입력.." required autofocus>
  <label for="inputPassword" class="sr-only">비밀번호</label>
  <input type="password" <?php echo $pw; ?> name="pw" class="form-control" placeholder="비밀번호" required>
  <div class="checkbox mb-3">
    <label onclick="ExpLog()">
    <!-- <input type="checkbox" name="autologin" value="remember-me"> 자동 로그인 -->
    <p id="explog"></p>
    </label>
  </div>
  <button class="btn btn-lg btn-primary btn-block" type="submit">로그인</button>
  <p><div style="height: 4px">&shy;</div><a href="signup.php"><button class="btn btn-lg btn-secondary btn-block" type="button">회원가입</button></a></p>
<?php echo $a;?>
</form>
</body>
</html>