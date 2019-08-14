<?php
require 'up.php';
if(empty($_SESSION['userck'])){
    echo '<script>alert("로그인 후 이용 바랍니다.")</script>';
    exit;
}
$user = $_SESSION['userid'];
$userck = $_SESSION['userck'];
echo '<h3>'.$userck.'님, 반갑습니다.</h3>';

$sql = "SELECT * from `_account` WHERE `id` like '".$user."'";
$result = mysqli_query($conn, $sql);
if(1 > mysqli_num_rows($result)){
     echo '<script>alert("존재하지 않는 사용자입니다."); history.go(-1)</script>';
}
while($row = mysqli_fetch_array($result)){
    $user_id = $row['id'];
    $user_nickname = $row['name'];
    $user_email = $row['email'];
    $user_intro = $row['introduce'];
}

echo '<h5>정보 수정</h5>';
$m = 'title';
echo '<table class="table table-striped"><thead>
    <tr>
      <th scope="col">항목</th>
      <th scope="col">내용</th>
    </tr></thead><tbody>';
echo '<tr>';
echo '<td>아이디/닉네임</td><td>'.$user_id.' / '.$user_nickname.'</td>';
echo '</tr>';
echo '<tr>';
if($user_intro == ''){
	$user_intro = '<span style="color:gray">없음</span>';
}
echo '<td>본인 소개</td><td>'.$user_intro.'</td>';
echo '</tr>';
echo '<tr>';
echo '<td>가입한 이메일 주소</td><td>'.$user_email.'</td>';
echo '</tr>';
echo '<tr><td><button class="btn btn-light" onclick="activeForm()">회원 정보 수정</button></td></tr></tbody></table>';
echo '';
echo '<script>
function activeForm() {
    var a = document.getElementById("form");
    a.style = "";
}
</script>
<span id="form" style="display:none">
    <form method="post" action="tools.php?form=submit">';
    echo '<table class="table table-striped"><tr>';
    echo '<td>아이디/닉네임</td><td><input disabled class="form-control" value="'.$user.'"> / <input name="nickname" class="form-control" disabled value="'.$user_nickname.'"></td>';
    echo '</tr>';
    echo '<tr>';
    if($user_intro == ''){
        $user_intro = '<span style="color:gray">없음</span>';
    }
    echo '<td>본인 소개</td><td><textarea name="intro" class="form-control">'.$user_intro.'</textarea></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>가입한 이메일 주소</td><td><input name="email" class="form-control" disabled value="'.$user_email.'"></td>';
    echo '</tr><tr><td><button type="submit" class="btn btn-secondary">수정 완료</button></td></tr></span></table>';
echo '</form></span>';
if($_GET['form'] == 'submit'){
    echo '저장중...';
    $user_intro = FnFilter($user_intro);
    $intro = $_POST['intro'];
        $sql = "UPDATE `_account` set introduce = '{$intro}' where `id` like '{$user}'";
        $result = mysqli_query($conn, $sql);
        if($result === false){
        echo '데이터베이스에 저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
        } else {
        echo "<script>alert('수정 완료!'); history.go(-2)</script>";
        }
}

$sql = "SELECT * from `_userSetting` WHERE `id` like '".$user."'";
$result = mysqli_query($conn, $sql);
if(1 > mysqli_num_rows($result)){
    $sql = "INSERT INTO `_userSetting` (`id`, `showAlerts`) VALUES ('$user', '1');";
    $result = mysqli_query($conn, $sql);
    $sql = "SELECT * from `_userSetting` WHERE `id` like '".$user."'";
    $result = mysqli_query($conn, $sql);
}

while($row = mysqli_fetch_array($result)){
    $showAlerts = $row['showAlerts'];
}

echo '<h5>개인 설정</h5>';
echo '<form method="post" action="tools.php?form=set"> <table class="table table-striped"><thead>
    <tr>
      <th scope="col">항목</th>
      <th scope="col">내용</th>
    </tr></thead><tbody>';
echo '<tr>';
if($showAlerts === '1'){
    $showAlertsCheckBox = '예 : <input type="radio" class="form-control" name="SA" value="yes" checked>
    아니오 :<input type="radio" class="form-control" name="SA" value="no">';
}else{
    $showAlertsCheckBox = '예 : <input type="radio" class="form-control" name="SA" value="yes">
    아니오 : <input type="radio" class="form-control" name="SA" value="no" checked>';
}
echo '<td style="width:50%">알림 허용</td><td>'.$showAlertsCheckBox.'</td>';
echo '</tr>';
echo '<tr><td><button class="btn btn-secondary" type="submit">회원 설정 변경</button></td></tr></tbody></table></form>';

if($_GET['form'] == 'set'){
    if($_POST['SA'] == 'yes'){
        $sql = "UPDATE `_userSetting` set showAlerts = '1' where `id` like '{$user}'";
        $result = mysqli_query($conn, $sql);
    }elseif($_POST['SA'] == 'no'){
        $sql = "UPDATE `_userSetting` set showAlerts = '0' where `id` like '{$user}'";
        $result = mysqli_query($conn, $sql);
    }
    if($result === false){
        echo '데이터베이스에 저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
    } else {
        echo "<script>alert('수정 완료!'); history.go(-2)</script>";
    }
}

include 'down.php';
?>
