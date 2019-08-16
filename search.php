<?php
require 'up.php';

function jse($val) {
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

$mode = jse($_GET['mode']);
$query = jse($_GET['query']);
$board = jse($_GET['board']);

if($fnSite_Homepage == 'recent'){
  if($fnSite_HomepageName == $board){
    $fromboard = '';
  }
}else{
  $fromboard = "`from` like '".$board."' AND";
}

$i = 1;
echo '<h6>검색 결과입니다.</h6>';

if($mode == '1'){
$m = 'title';
echo '<table class="table table-striped"><thead>
    <tr>
      <th scope="col">글 제목</th>
      <th scope="col">작성자</th>
      <th scope="col">작성 시간</th>
    </tr></thead><tbody>';
$sql = "SELECT * from `_article` WHERE $fromboard `".$m."` like '%$query%' ORDER BY `created` DESC Limit 99";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)){
    echo '<tr><td><a href="'.$row['from'].'-'.$row['id'].'.base">'.$row['title'].'</a></td>';
    echo '<td>'.$row['name'].'</td>';
    echo '<td>'.$row['created'].'</td></tr>';
    $i++;
    if($i > 200){
      break;
    }
    }
if(1 > mysqli_num_rows($result)){
	 echo '<td>검색 결과가 없습니다.</td><td></td><td></td>';
}
if($result === FALSE){
echo '데이터베이스 연결 오류';
}
echo '</tbody></table>';
}elseif($mode == '2'){
$m = 'description';
$sql = "SELECT * from `_article` WHERE $fromboard `$m` like '%$query%' ORDER BY `created` DESC Limit 99";
echo '<table class="table table-striped"><thead>
    <tr>
      <th scope="col">글 제목</th>
      <th scope="col">작성자</th>
      <th scope="col">작성 시간</th>
    </tr></thead><tbody>';
    $result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)){
    echo '<td><a href="'.$row['from'].'-'.$row['id'].'.base">'.$row['title'].'</a></td>';
    echo '<td>'.$row['name'].'</td>';
    echo '<td>'.$row['created'].'</td></tr>';
    $i++;
    if($i > 200){
      break;
    }
}
if($result === FALSE){
echo '데이터베이스 연결 오류';
}
if(1 > mysqli_num_rows($result)){
	 echo '<td>검색 결과가 없습니다.</td><td></td><td></td>';
}
echo '</tbody></table>';
}elseif($mode == '3'){
$m = 'content';
$sql = "SELECT * from `_comment` WHERE $fromboard `$m` like '%$query%' ORDER BY `created` DESC Limit 99";
echo '<table class="table table-striped"><thead>
    <tr>
      <th scope="col">원 글</th>
      <th scope="col">댓글 작성자</th>
      <th scope="col">작성 시간</th>
    </tr></thead><tbody>';
    $result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)){
    echo '<td><a href="'.$row['board'].'-'.$row['original'].'.base">바로가기</a></td>';
    echo '<td>'.$row['name'].'</td>';
    echo '<td>'.$row['created'].'</td></tr>';
    $i++;
    if($i > 200){
      break;
    }
}
if(1 > mysqli_num_rows($result)){
	 echo '<td>검색 결과가 없습니다.</td><td></td><td></td>';
}
if($result === FALSE){
echo '데이터베이스 연결 오류';
}
echo '</tbody></table>';
}elseif($mode == '4'){
$m = 'name';
$sql = "SELECT * from `_article` WHERE $fromboard `$m` like '%$query%' ORDER BY `created` DESC Limit 99";
echo '<table class="table table-striped"><thead>
    <tr>
      <th scope="col">글 제목</th>
      <th scope="col">작성자</th>
      <th scope="col">작성 시간</th>
    </tr></thead><tbody>';
    $result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)){
    echo '<td><a href="'.$row['from'].'-'.$row['id'].'.base">'.$row['title'].'</a></td>';
    echo '<td>'.$row['name'].'</td>';
    echo '<td>'.$row['created'].'</td></tr>';
    $i++;
    if($i > 200){
      break;
    }
}
if(1 > mysqli_num_rows($result)){
	 echo '<td>검색 결과가 없습니다.</td><td></td><td></td>';
}
if($result === FALSE){
echo '데이터베이스 연결 오류';
}
echo '</tbody></table>';
}
if($i >= 200){
  echo '200번째 이후의 검색 결과는 생략됩니다.';
}
include 'down.php';
?>