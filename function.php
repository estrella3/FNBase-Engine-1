<?php
function Filt($arg){
   $arg = str_replace("'", "\'", $arg);
   $arg = Fnfilter($arg);
   return $arg;
 }
function FnFilter($val) {
       $val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val); 
         
       $search = 'abcdefghijklmnopqrstuvwxyz'; 
       $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
       $search .= '1234567890!@#$%^&*()'; 
       $search .= '~`";:?+/={}[]-_|\'\\'; 
       for ($i = 0; $i < strlen($search); $i++) {
          $val = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val);
          $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val);
       } 
       $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 
    'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base64'); 
       $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'); 
       $ra = array_merge($ra1, $ra2); 
       $found = true;
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
             $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2);
             $val = preg_replace($pattern, $replacement, $val);
             if ($val_before == $val) {
                $found = false; 
             } 
          } 
       } 
       return $val; 
    }
function FnCommentTemp(){
   if(empty($_COOKIE['writecomment'])){
      setcookie("writecomment","once",time()+5);
    }
    if($_COOKIE['writecomment'] == 'once'){
      setcookie("writecomment","twice",time()+5);
    }
    if($_COOKIE['writecomment'] == 'twice'){
      setcookie("writecomment","third",time()+5);
    }
    if($_COOKIE['writecomment'] == 'third'){
      setcookie("writecomment","fourth",time()+10);
    }
    if($_COOKIE['writecomment'] == 'fourth'){
      setcookie("writecomment","fifth",time()+10);
      echo '<script>alert("댓글을 너무 짧은 시간 안에 많이 작성했습니다. 10초만 세고 시도하세요.")</script>';
    }
    if($_COOKIE['writecomment'] == 'fifth'){
      setcookie("writecomment","stop",time()+60);
      echo '<script>alert("도배가 목적이 아니라면 그만 둬 주시길 바랍니다. 속으로 60까지 세고 시도하세요.")</script>';
      exit;
    }
    if($_COOKIE['writecomment'] == 'stop'){
      setcookie("writecomment","once again",time()+50);
      echo '<script>alert("아직 60초가 지나지 않았어요. 50초 더 기다리세요.")</script>';
      exit;
    }
    if($_COOKIE['writecomment'] == 'once again'){
      setcookie("writecomment","twice again",time()+40);
      echo '<script>alert("넉넉잡아 2분 뒤에 다시 시도하시길 추천드려요.")</script>';
      exit;
    }
    if($_COOKIE['writecomment'] == 'twice again'){
      setcookie("writecomment","you should rest",time()+300);
      echo '<script>alert("아직 이릅니다. 휴식을 취하고 다시 시도하세요.")</script>';
      exit;
    }
    if($_COOKIE['writecomment'] == 'you should rest'){
      setcookie("writecomment","no more",time()+3600);
      echo '<script>alert("자바스크립트를 허용하지 않으셨네요. 앞으로 한 시간 동안 댓글을 다실 수 없어요.")</script>';
      exit;
    }
    if($_COOKIE['writecomment'] == 'you should rest'){
      echo '<script>history.back()</script>';
      exit;
    }
    if($_COOKIE['writecomment'] == 'no more'){
      echo '<script>history.back()</script>';
      exit;
    }
}
?>