<!DOCTYPE html>
<?php include 'setting.php';?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="<?=$fnSiteFab?>" type="image/x-icon">
  <meta name="description" content="<?=$fnSiteDesc?>">
  <title><?=$fnSiteName?></title>
  <link rel="stylesheet" href="layout.css">
  <?=$fnSite_google?>
</head>
<body>
<?php
$board = "board";
		$getturn = $_POST['b'];
		if(isset($getturn)){
			$board = $getturn;
    }
    
$conn = mysqli_connect("$fnSiteDB", "$fnSiteDBuser", "$fnSiteDBpw", "$fnSiteDBname");
?>
<nav>
        <p class="fntop">글 저장중...<br><br /></p>
</nav>
<section>
<div style="background-color: #fff">
<?php
if(!empty($_POST['title'])){
  $jemok = $_POST['title'];
  $conf = 1;
}else{
  $errormsg = "제목이 없고, ";
  $rconf = 1;
}

if(!empty($_POST['description'])){
  $desc = $_POST['description'];
  $conf = $conf + 1;
}else{
  $errormsg = "내용이 없으며, ";
  $rconf = $rconf + 1;
}

if(!empty($_POST['id'])){
  $id = $_POST['id'];
  $conf = $conf + 1;
}else{
  $errormsg = "작성자 아이디가 없어요.&nbsp;";
  $rconf = $rconf + 1;
}

if(!empty($_POST['pw'])){
  $pw = $_POST['pw'];
  $conf = $conf + 1;
}else{
  $errormsg = "작성자 비밀번호가 없네요,";
  $rconf = $rconf + 1;
}

if ($rconf == 4){
  echo "글이 작성되지 않았습니다.";
}
$UIP = $_SERVER["REMOTE_ADDR"];

if(!isset($_POST['islogged'])){
  $author = "_anon";
}else{
  session_cache_expire(20160);
  session_start();
  $pw = $_SESSION['userpw'];
  $id = $_SESSION['userck'];
  $author = $_SESSION['userid'];
}

function jsengine_real_escape_string($val) {
// remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed 
   // this prevents some character re-spacing such as <java\0script> 
   // note that you have to handle splits with \n, \r, and \t later since they *are* 
   // allowed in some inputs 
   $val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val); 
    
   // straight replacements, the user should never need these since they're normal characters 
   // this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&
   // #X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29> 
   $search = 'abcdefghijklmnopqrstuvwxyz'; 
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
   $search .= '1234567890!@#$%^&*()'; 
   $search .= '~`";:?+/={}[]-_|\'\\'; 
   for ($i = 0; $i < strlen($search); $i++) { 
   // ;? matches the ;, which is optional 
   // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars 
    
   // &#x0040 @ search for the hex values 
      $val = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); 
      // with a ; 

      // @ @ 0{0,7} matches '0' zero to seven times 
      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ; 
   } 
    
   // now the only remaining whitespace attacks are \t, \n, and \r 
   $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 
'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base'); 
   $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'); 
   $ra = array_merge($ra1, $ra2); 
    
   $found = true; // keep replacing as long as the previous round replaced something 
   while ($found == true) { 
      $val_before = $val; 
      for ($i = 0; $i < sizeof($ra); $i++) { 
         $pattern = '/'; 
         for ($j = 0; $j < strlen($ra[$i]); $j++) { 
            if ($j > 0) { 
               $pattern .= '('; 
               $pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?'; 
               $pattern .= '|(&#0{0,8}([9][10][13]);?)?'; 
               $pattern .= ')?'; 
            } 
            $pattern .= $ra[$i][$j]; 
         } 
         $pattern .= '/i'; 
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag 
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags 
         if ($val_before == $val) { 
            // no replacements were made, so exit the loop 
            $found = false; 
         } 
      } 
   } 
   return $val; 
} 

if ($conf == 4){
  $jemok = jsengine_real_escape_string($jemok);
  $desc = jsengine_real_escape_string($desc);
  $author = jsengine_real_escape_string($author);
  $id = jsengine_real_escape_string($id);
$sql = "
  INSERT INTO `_article`
    (`title`, `description`, `from`, `to`, `created`, `author_id`, `name`, `stat`, `view`, `UIP`)
    VALUES(
        '{$jemok}',
        '{$desc}',
        '{$board}',
        '{$board}',
        NOW(),
        '{$author}',
        '{$id}',
        '0',
        '0',
        '{$UIP}'
    )
";
$result = mysqli_query($conn, $sql);
if($result === false){
  echo 'XSS 스크립트가 포함되어있거나, 데이터베이스에 저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
  error_log(mysqli_error($conn));
} else {
  setcookie('writed', 'yes', time() + 60);
  setcookie('again', 'yes', time() + 70);
  if(!empty($_SESSION['userid'])){
  $query = "select * from _account where id='".$_SESSION['userid']."'";
  $result = $conn->query($query);
  $row=mysqli_fetch_assoc($result);
  $pt = $row['point'] + 10;
  $sql = "UPDATE _account set point = '{$pt}' where id like '".$_SESSION['userid']."'";
  $result = mysqli_query($conn, $sql);
  if($result === false){
    echo '포인트 적립 실패';
  }
  }
  echo '<script>location.href = "index.php?b='.$board.'"</script>';
}
}elseif($rconf == 4){
  echo ' <a href="write.php">뒤로가기</a>';
}else{
  echo $errormsg;
  echo '다시 확인해주세요.';
}

if($_COOKIE['again'] == "yes"){
  setcookie('worried', 'yes', time() + 80);
}

if($_COOKIE['worried'] == "yes"){
  setcookie('dont', 'yes', time() + 90);
}

?>
</div>
</section>
<footer>
        <p class="fnbottom">Contact : admin@fnbase.xyz</p>
</footer>
  </body>
</html>