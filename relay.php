<?php
include 'up.php';
$title = $_POST['title'];
$userid = $_SESSION['userid'];
if(empty($_SESSION['userid'])){
	echo '<script>alert("로그인 후 이용 바랍니다.");location.href("login.php")</script>';
}
echo "
<h3>한줄 게시판</h3>
익명으로 다른 사람과 짧은 대화를 나눠보세요.<br><br>
<span style=\"color: gray; font-size: 0.7em\">도배·불건전 행위 방지를 위해 남기신 분의 아이디가 저장되오니 부디 예의를 지켜주세요.</span>
<p>
<form method='post' action='relay.php'>
<input type='text' autocomplate=off name='reason' style=\"min-width:70%;max-width:90%\" required>
<input type='hidden' name='submit' value='yes'>
<input type='hidden' name='userid' value='$userid'>
<input type='submit' class='btn-sm btn-warning' style=\"min-width:3em;max-width:9%\" value='전송'></form><br></p>
";
if($_POST['submit'] == 'yes'){
  function htmstr($var){
    $var = htmlspecialchars($var);
    return $var;
  }
    $r = htmstr($_POST['reason']);
    $u = htmstr($_POST['userid']);
    $addr = $_SERVER['REMOTE_ADDR'];
      $sql = "
        INSERT INTO `_relay`
          (`reason`, `time`, `ip`, `userid`)
          VALUES(
              '{$r}',
              NOW(),
              '{$addr}',
              '{$u}'
          )
      ";
      $result = mysqli_query($conn, $sql);
      if($result === false){
        echo '데이터베이스에 저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
      }else{
        echo '<script>location.href("relay.php")</script>';
      }
}
echo "<table class='table table-striped'>";
echo '  <thead>
<tr>
  <th scope="col">#</th>
  <th scope="col">내용</th>
  <th scope="col">작성 시간</th>
</tr>
</thead>
<tbody>';
$i = 1;
$sql = "SELECT * FROM `_relay` ORDER BY `num` DESC";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)){
    echo '<tr><td>'.$row['num'].'</td>';
    echo '<td>'.$row['reason'].'</td>';
    echo '<td>'.$row['time'].'</td></tr>';
    $i++;
    if($i == 50){
    break;
    }
}
echo '</tbody></table>';
include 'down.php';
?>