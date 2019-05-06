<?php
include 'up.php';
$b = $_POST['from'];
$id = $_POST['id'];
$title = $_POST['title'];
$userid = $_SESSION['userid'];
if(empty($_SESSION['userid'])){
	echo '<script>alert("로그인 후 이용 바랍니다.");history.go(-1)</script>';
}else{
	
if(empty($_POST['submit'])){
echo "
<h3>게시글 신고</h3>
폭력적이거나 성적인 게시글, 모욕적인 게시글, 광고, 도배성 게시글 등을 신고하여 주세요.<br><br>
이 게시글을 신고하려는 이유가 무엇입니까?<p>
<form method='post' action='blame.php'>
<input type='text' name='reason' required>
<input type='hidden' name='b' value='$b'>
<input type='hidden' name='id' value='$id'>
<input type='hidden' name='title' value='$title'>
<input type='hidden' name='submit' value='yes'>
<input type='hidden' name='userid' value='$userid'>
<input type='submit' class='btn-sm btn-warning' value='접수'><br></p>
";
}elseif(empty($_POST['id'])){
echo '잘못된 접근입니다.';
}else{
  function htmstr($var){
    $var = htmlspecialchars($var);
    return $var;
  }
    $i = htmstr($_POST['id']);
    $b = htmstr($_POST['b']);
    $r = htmstr($_POST['reason']);
    $n = htmstr($_POST['title']);
    $u = htmstr($_POST['userid']);
    $addr = $_SERVER['REMOTE_ADDR'];
      $sql = "
        INSERT INTO `_report`
          (`reason`, `board`, `id`, `time`, `ip`, `userid`, `name`)
          VALUES(
              '{$r}',
              '{$b}',
              '{$i}',
              NOW(),
              '{$addr}',
              '{$u}',
              '{$n}'
          )
      ";
      $result = mysqli_query($conn, $sql);
      if($result === false){
        echo '데이터베이스에 저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
      }else{
        echo '이동중...<script>history.go(-2)</script>';
      }
}
echo "<h3>최근 신고 내역</h3><table class='table table-striped'>";
echo '  <thead>
<tr>
  <th scope="col">#</th>
  <th scope="col">글</th>
  <th scope="col">신고 사유</th>
  <th scope="col">접수 시간</th>
</tr>
</thead>
<tbody>';
$sql = "SELECT * FROM `_report` ORDER BY `num` DESC";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)){
    echo '<tr><td>'.$row['num'].'</td>';
    echo '<td><a href="'.$row['board'].'-'.$row['id'].'.base">'.$row['name'].'</a></td>';
    echo '<td>'.$row['reason'].'</td>';
    echo '<td>'.$row['time'].'</td></tr>';
}
echo '</tbody></table>';
}
include 'down.php';
?>